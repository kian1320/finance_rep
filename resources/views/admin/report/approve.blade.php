@extends('layouts.master')
@section('content')
@section('title', 'View Submitted Monthly Report')

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

    .approve-btn {
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid px-4">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Submitted Monthly Report</h4>
        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <table id="Itemstable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Month and Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                        @if ($item->summaries->where('submitted', 1)->count() > 0)
                            <tr>
                                <td>{{ $item->name }}</td>

                                <!-- Modify your Blade template to link directly to the summary page -->
                                <!-- Inside your Blade template -->
                                <td>
                                    @foreach ($item->summaries as $summary)
                                        @if ($summary->submitted == 1)
                                            <a
                                                href="{{ route('summary.show', ['user' => $item->id, 'year' => $summary->year, 'month' => $summary->month]) }} ">
                                                {{ \Carbon\Carbon::createFromDate($summary->year, $summary->month, 1)->format('F Y') }}
                                            </a>
                                            <br>
                                        @endif
                                    @endforeach
                                </td>

                                <td>
                                    @foreach ($item->summaries as $summary)
                                        @if ($summary->submitted == 1)
                                            <button class="approve-btn btn btn-success"
                                                data-summary-id="{{ $summary->id }}">Recieve</button>
                                        @endif
                                        <br>
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



{{-- <div class="container-fluid px-4">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Approved Monthly Report</h4>
        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <table id="Itemstable2">
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
                                            {{ \Carbon\Carbon::createFromDate($summary->year, $summary->month, 1)->format('F') }}
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->summaries as $summary)
                                        @if ($summary->submitted == 2)
                                            <button class="reverse-approval-btn btn btn-warning"
                                                data-summary-id="{{ $summary->id }}">Reverse Approval</button>
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
</div> --}}

<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<!-- Remove this script from your Blade template -->
<script>
    // When the document is ready
    $(document).ready(function() {
        // When a month link is clicked
        $('.month-link').click(function(e) {
            e.preventDefault(); // Prevent the default behavior (e.g., navigating to a new page)

            // Get the data attributes from the clicked link
            var userId = $(this).data('user-id');
            var year = $(this).data('year');
            var month = $(this).data('month');
            var summaryUrl = $(this).data('summary-url');

            // Use AJAX to fetch the summary data
            $.ajax({
                url: summaryUrl,
                method: 'GET',
                success: function(data) {
                    // Update the content of the modal with the fetched data
                    $('#summaryDetails').html(data);

                    // Show the modal
                    $('#summaryModal').modal('show');
                },
                error: function(error) {
                    console.error('Error fetching summary data:', error);
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#Itemstable').DataTable();

        // Add click event for the "Approve" button
        $('.approve-btn').click(function() {
            var summaryId = $(this).data('summary-id');

            // Show a confirmation dialog
            var confirmation = confirm('Do you want to receive this summary?');

            if (confirmation) {
                // If the user clicks "OK", make an AJAX request to update the database
                $.ajax({
                    type: 'POST',
                    url: 'approve-summary/' + summaryId,
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);

                        // Reload the page
                        location.reload(
                            true); // Pass true to force a reload from the server

                        // Display a success message (you can also customize this part)
                        alert('Summary Recieved');
                    },
                    error: function(error) {
                        console.error(error);
                        // Handle the error, display a message, etc.
                    },
                });
            } else {
                // If the user clicks "Cancel", do nothing or provide feedback
                console.log('Approval canceled');
            }
        });
    });
</script>



<script>
    $(document).ready(function() {
        $('#Itemstable2').DataTable();

        // Add click event for the "Reverse Approval" button
        $('.reverse-approval-btn').click(function() {
            var summaryId = $(this).data('summary-id');

            // Make an AJAX request to reverse the approval in the database
            $.ajax({
                type: 'POST',
                url: 'reverse-approval/' + summaryId,
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                    // Optionally, you can update the UI or display a success message
                },
                error: function(error) {
                    console.error(error);
                    // Handle the error, display a message, etc.
                },
            });
        });
    });
</script>
@endsection
