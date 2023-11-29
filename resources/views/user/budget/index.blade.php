@extends('layouts.usermaster')

@section('content')
@section('title', 'Financial Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Budgets <a href="{{ 'add-budget' }}" class="btn btn-primary btn-sm float-end">Add Budget</a>
            </h4>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="table-responsive"> <!-- Add the responsive wrapper here -->
            <table id="Budgettable" class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Month</th>
                        <th>year</th>
                        <th>Type</th>
                        <th>SubType</th>
                        <th>Others</th>
                        <th>Amount</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budgets as $item)
                        <tr>

                            <td>{{ \Carbon\Carbon::create()->month($item->month)->format('F') }}</td>
                            <td>{{ $item->year }}</td>
                            <td>{{ $item->btype->name }}</td>
                            <td>{{ $item->bstype ? $item->bstype->bsname : '' }}</td> <!-- Display the stype's name -->
                            <td>{{ $item->others }}</td>
                            <td>PHP: {{ number_format($item->amount, 2, '.', ',') }}</td>
                            <td align="center">
                                <a href="{{ url('user/edit-budget/' . $item->id) }}"
                                    class="btn btn-success btn-sm">Edit</a>

                            </td>
                            <td align="center">
                                <form action="{{ url('user/delete-budget/' . $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this budget?')">Delete</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>

            <button onclick="window.print()">Print this page</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#Budgettable').DataTable({
            // DataTable options...
            // ...
            "initComplete": function() {
                calculateSum();
            },
            "drawCallback": function() {
                calculateSum();
            }
        });

        function calculateSum() {
            if (table && table.rows) {
                var sum = 0; // Initialize the sum variable
                var filteredData = table.rows({
                    search: 'applied'
                }).data();

                table.rows({
                    search: 'applied'
                }).data().each(function(row) {
                    var amount = parseFloat(row['amount']);
                    console.log('Amount:', amount); // Add this line for debugging

                    if (!isNaN(amount)) {
                        sum += amount;
                    }
                });

                console.log('Sum:', sum); // Add this line for debugging

                $('#totalDisplay').text('Total: PHP ' + sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g,
                    "$1,"));
            }
        }
    });
</script>
@endsection
