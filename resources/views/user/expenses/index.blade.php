@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Encashment <a href="{{ 'add-expenses' }}" class="btn btn-primary btn-sm float-end">Add Expenses</a>
            </h4>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <div class="table-responsive"> <!-- Add the responsive wrapper here -->
            <table id="Itemstable" class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Date_issued</th>
                        <th>Voucher</th>
                        <th>Cash/Check</th>
                        <th>Encashment</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>SubType</th>
                        <th>others</th>
                        <th>Late/Ontime</th>
                        <th>Amount</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Expenses as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->date_issued)->format('M j Y') }}</td>
                            <td>{{ $item->voucher }}</td>
                            <td>{{ $item->check }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->encashment)->format('M j Y') }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ $item->stype ? $item->stype->sname : '' }}</td> <!-- Display the stype's name -->
                            <td>{{ $item->others }}</td>
                            <td>{{ $item->late_encash == '1' ? 'late' : 'ontime' }}</td>
                            <td> PHP:{{ number_format($item->amount, 2, '.', ',') }}.00</td>

                            <td align="center">
                                <a href="{{ url('user/edit-expenses/' . $item->id) }}"
                                    class="btn btn-success btn-sm">Edit</a>
                            </td>
                            <td align="center">
                                <a href="{{ route('delete-expenses', ['expenses_id' => $item->id]) }}"
                                    class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this expense?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }">Delete</a>
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('delete-expenses', ['expenses_id' => $item->id]) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <p id="totalDisplay"> <strong> Total: PHP {{ number_format($total, 0, '.', ',') }}.00</strong></p>
            <button onclick="window.print()">Print this page</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>


<script>
    $(document).ready(function() {
        var table = $('#Itemstable').DataTable({
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

                $('#totalDisplay').text(sum.toFixed(
                    2)); // Replace #totalDisplay with the element where you want to display the sum
            }
        }
    });
</script>
@endsection
