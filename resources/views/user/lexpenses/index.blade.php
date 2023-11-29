@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Late Encashment <a href="{{ 'add-lexpenses' }}" class="btn btn-primary btn-sm float-end">Add
                    Late Expenses</a></h4>
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
                        <th>Others</th>
                        <th>Amount</th>
                        <th>Add to Expenses</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Lexpenses as $item)
                        <tr data-lexpensesid="{{ $item->id }}">
                            <td>{{ \Carbon\Carbon::parse($item->date_issued)->format('M j, Y') }}</td>
                            <td>{{ $item->voucher }}</td>
                            <td>{{ $item->check }}</td>
                            <td>
                                @if ($item->encashment)
                                    {{ \Carbon\Carbon::parse($item->encashment)->format('M j, Y') }}
                                @else
                                    {{-- Display a blank cell --}}
                                @endif
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ $item->stype->sname }}</td>
                            <td>{{ $item->others }}</td>
                            <td> PHP:{{ number_format($item->amount, 2, '.', ',') }}.00</td>

                            <td align="center">
                                @if ($item->is_added == 1)
                                    <span class="text-success">Added</span>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm"
                                        onclick="addToExpensesTable(this)"
                                        data-lexpensesid="{{ $item->id }}">Add</a>
                                @endif
                            </td>


                            <td align="center">
                                <a href="{{ url('user/edit-lexpenses/' . $item->id) }}"
                                    class="btn btn-secondary btn-sm">Edit</a>
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


{{-- 
// Function to check and update the button text based on isAdded value
    /*
        function updateButtonStatus(button, lexpensesId) {
            $.ajax({
                type: 'GET', // Use GET request to retrieve isAdded value
                url: 'check-is-added/' + lexpensesId, // Replace with your Laravel route
                success: function(response) {
                    if (response.isAdded) {
                        // Mark the button as "Added" to prevent duplication
                        $(button).html('<span class="text-white">Added</span>');
                        $(button).addClass('disabled').attr('disabled', true); // Disable the button
                    } else {
                        $(button).html('Add'); // Revert to "Add" if not added
                        $(button).removeClass('disabled').removeAttr('disabled'); // Enable the button
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error (you can add error handling here)
                    console.log('AJAX Error:', error);
                }
            });
        }

    */ --}}

<script>
    function addToExpensesTable(button) {
        // Get the row containing the "Add" button
        var row = $(button).closest('tr');

        var expenseData = {
            // Extract data from the row
            date_issued: row.find('td:eq(0)').text(),
            voucher: row.find('td:eq(1)').text(),
            check: row.find('td:eq(2)').text(),
            encashment: row.find('td:eq(3)').text(),
            description: row.find('td:eq(4)').text(),
            type: row.find('td:eq(5)').text(),
            stype: row.find('td:eq(6)').text(),
            amount: parseFloat(row.find('td:eq(7)').text().replace('PHP:', '').replace(',', '').trim())
        };

        // Get the lexpenses_id from the button's data attribute
        var lexpensesId = $(button).data('lexpensesid'); // Get the lexpensesId from the button

        if (lexpensesId !== undefined) {
            // Add CSRF token to data
            expenseData._token = $('meta[name="csrf-token"]').attr('content');

            // Construct the URL based on lexpensesId
            var urll = 'add-to-expenses/' + lexpensesId;


            var confirmation = confirm("Are you sure you want to add this expense to the table?");
            if (confirmation) {
                // Make an AJAX POST request to the constructed URL
                $.ajax({
                    type: 'POST',
                    url: urll, // Use the constructed URL
                    data: expenseData,
                    success: function(response) {
                        if (response.message == 'Expense added successfully') {
                            // Mark the original row as "Added" to prevent duplication
                            row.find('td:eq(8)').html('<span class="text-success">Added</span>');
                            // Disable the button after adding
                            $(button).addClass('disabled').attr('disabled', true);
                            // Optionally, remove the button click event handler
                            $(button).removeAttr('onclick');
                        } else {
                            // Handle the case where the expense could not be added
                            console.log('Expense not added:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.log('AJAX Error:', error);
                    }
                });
            }
        } else {
            console.error('Lexpenses ID is undefined.'); // Log an error if lexpensesId is undefined
        }
    } // Close addToExpensesTable function here


    // Trigger the initial button status check on page load
    $(document).ready(function() {
        // Loop through each "Add" button to check and update the status
        $('a[data-lexpensesid]').each(function() {
            var lexpensesId = $(this).data('lexpensesid');
            var button = this;

            // Check the is_added state from the server and update the button text accordingly
            // updateButtonStatus(button, lexpensesId);
        });

    });

    $(document).ready(function() {
        var table = $('#Itemstable').DataTable({

        });


    });
</script>


@endsection
