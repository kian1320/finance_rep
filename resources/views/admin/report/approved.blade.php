@extends('layouts.master')
@section('content')
@section('title', 'View Approved Monthly Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .reverse-approval-btn {
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid px-4">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Approved Monthly Report</h4>
        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <table id="Itemstable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Months</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                        @if ($item->summaries->where('submitted', 2)->count() > 0)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @foreach ($item->summaries as $summary)
                                        @if ($summary->submitted == 2)
                                            <a
                                                href="{{ route('summary.show', ['user' => $item->id, 'year' => $summary->year, 'month' => $summary->month]) }}">
                                                {{ \Carbon\Carbon::createFromDate($summary->year, $summary->month, 1)->format('F Y') }}
                                            </a>
                                            <br>
                                        @endif
                                    @endforeach
                                </td>

                                <td>
                                    @foreach ($item->summaries as $summary)
                                        @if ($summary->submitted == 2)
                                            <button class="reverse-approval-btn btn btn-warning"
                                                data-summary-id="{{ $summary->id }}">Reverse Recieve</button>
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#Itemstable').DataTable();

        // Add click event for the "Reverse Approval" button
        $('.reverse-approval-btn').click(function() {
            var summaryId = $(this).data('summary-id');

            // Show a confirmation dialog
            var confirmation = confirm('Do you want to reverse the approval for this summary?');

            if (confirmation) {
                // If the user clicks "OK", make an AJAX request to reverse the approval in the database
                $.ajax({
                    type: 'POST',
                    url: 'reverse-approval/' + summaryId,
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);

                        // Reload the page
                        location.reload(
                            true); // Pass true to force a reload from the server

                        // Display a success message (you can also customize this part)
                        alert('Recieved Reversed');
                    },
                    error: function(error) {
                        console.error(error);
                        // Handle the error, display a message, etc.
                    },
                });
            } else {
                // If the user clicks "Cancel", do nothing or provide feedback
                console.log('Reverse approval canceled');
            }
        });
    });
</script>


@endsection
