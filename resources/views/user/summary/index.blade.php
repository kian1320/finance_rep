@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')


<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View summary <a href="{{ 'add-summary' }}" class="btn btn-primary btn-sm float-end">Add sum</a></h4>
            <button onclick="window.print()">Print this page</button>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif




        <div class="form-control">
            <label for="yearFilter">Select Year:</label>
            <select id="yearFilter">
                <option value="">All Years</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="container-fluid table-responsive">
            <table class="table">
                <tbody>
                    <!-- First Container -->
                    <tr>
                        <th scope="row">Month</th>
                        @foreach ($summary as $item)
                            <td>
                                {{ \Carbon\Carbon::createFromFormat('m', $item->month)->format('F') }}
                                {{ $item->year }}
                                <br>
                                <form method="post" action="{{ route('summary.submit', ['id' => $item->id]) }}"
                                    id="submitForm{{ $item->id }}">
                                    @csrf
                                    @method('POST')
                                    <button
                                        class="btn {{ $item->submitted == 0 ? 'btn-primary' : ($item->submitted == 1 ? 'btn-success' : 'btn-success') }}"
                                        onclick="submitSummary(event, {{ $item->id }}, {{ $item->submitted }})"
                                        {{ $item->submitted == 2 ? 'disabled' : '' }}>
                                        {{ $item->submitted == 0 ? 'Submit' : ($item->submitted == 1 ? 'Submitted' : 'Recieved') }}
                                    </button>
                                </form>
                            </td>
                        @endforeach

                    </tr>

                    <tr>
                        <th scope="row">Starting balance:</th>
                        @foreach ($summary as $item)
                            <td>
                                <p>PHP: {{ number_format($item->beginbal ?? 0, 2, '.', ',') }}</p>

                            </td>
                        @endforeach
                    </tr>

                    <!-- Second Container -->


                    @foreach ($btypes as $btype)
                        <tr>
                            <th scope="row">{{ $btype->name }}</th>
                        </tr>
                        @foreach ($btype->bstypes as $bstype)
                            <tr>
                                <td scope="row">{{ $bstype->bsname }}</td>
                                @foreach ($summary as $item)
                                    <?php $month = $item->month; ?>
                                    <td>
                                        @if (isset($totalBudgetAmountByMonth[$month][$btype->name][$bstype->bsname]))
                                            {{ number_format($totalBudgetAmountByMonth[$month][$btype->name][$bstype->bsname], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach






                    <tr>
                        <th scope="row">Total Starting</th>
                        @foreach ($summary as $item)
                            <td><strong> PHP: {{ number_format($item->totalstr, 2, '.', ',') }}</strong></td>
                        @endforeach
                    </tr>
                    <br>

                    <tr>
                        <th scope="row">
                            <h5 style="color:red;">Late Expenses</h5>
                        </th>
                    </tr>


                    @foreach ($types as $type)
                        <tr>
                            <th scope="row">{{ $type->name }}</th>
                        </tr>
                        @foreach ($type->stypes as $stype)
                            <tr>
                                <td>{{ $stype->sname }}</td>
                                @foreach ($months as $month)
                                    <td>
                                        {{ number_format($totalExpensesByStypeAndType1[$stype->id][$type->id][$month] ?? 0, 2) }}

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach



                    <tr>
                        <th scope="row">
                            <h5> <strong>Normal Expenses</strong></h5>
                        </th>
                    </tr>
                    @foreach ($types as $type)
                        <tr>
                            <th scope="row">{{ $type->name }}</th>
                        </tr>
                        @foreach ($type->stypes as $stype)
                            <tr>
                                <td scope="row">{{ $stype->sname }}</td>
                                @foreach ($months as $month)
                                    <td>
                                        {{ number_format($totalExpensesByStypeAndType[$stype->id][$type->id][$month] ?? 0, 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach









                    <tr>
                        <th scope="row">Total Expenses</th>
                        @foreach ($months as $month)
                            @php
                                $totalAmount = 0;
                                foreach ($totalExpensesByMonth as $expense) {
                                    if ($expense['month'] == $month) {
                                        $totalAmount = $expense['total_amount'];
                                        break;
                                    }
                                }
                            @endphp
                            <td>
                                PHP: {{ $totalAmount > 0 ? number_format($totalAmount, 2, '.', ',') : '-' }}
                            </td>
                        @endforeach
                    </tr>






                    <tr>
                        <th scope="row">Ending Balance</th>
                        @foreach ($summary as $item)
                            <td><strong> PHP: {{ number_format($item->aftexpenses, 2, '.', ',') }}.00</strong>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>





        <div class="container">
            @if ($lexpensesWithIsAdded0->isEmpty())
                <p>No matching records found.</p>
            @else
                <div class="table-responsive">
                    <H3 style="color:red;">Outstanding checks</H3>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">date issued</th>
                                <th scope="col">voucher</th>
                                <th scope="col">check</th>
                                <th scope="col">description</th>
                                <th scope="col">type</th>
                                <th scope="col">stype</th>
                                <th scope="col">amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAmount = 0; // Initialize the total amount
                            @endphp

                            @foreach ($lexpensesWithIsAdded0 as $lexpense)
                                <tr>
                                    <td>{{ $lexpense->date_issued }}</td>
                                    <td>{{ $lexpense->voucher }}</td>
                                    <td>{{ $lexpense->check }}</td>
                                    <td>{{ $lexpense->description }}</td>
                                    <td>{{ $lexpense->type->name }}</td>
                                    <td>{{ $lexpense->stype->sname }}</td>
                                    <td>{{ $lexpense->amount }}</td>
                                </tr>
                                @php
                                    // Add the current expense amount to the total amount
                                    $totalAmount += $lexpense->amount;
                                @endphp
                            @endforeach

                            <tr>
                                <th>Total Amount</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ number_format($totalAmount, 2) }}</th>
                            </tr>
                        </tbody>

                    </table>

                </div>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <script>
            function submitSummary(event, itemId, initialSubmittedState) {
                event.preventDefault(); // Prevent the form submission

                const form = document.getElementById(`submitForm${itemId}`);
                const button = form.querySelector('button');
                const csrfToken = form.querySelector('input[name="_token"]').value;

                // Check if the button state is stored in local storage
                const storedState = localStorage.getItem(`buttonState_${itemId}`);

                if (initialSubmittedState === 1 || (storedState === 'submitted' && initialSubmittedState !== 2)) {
                    // Button was previously submitted or is currently submitted
                    const unsubmitConfirmed = confirm('Do you want to unsubmit?');

                    if (unsubmitConfirmed) {
                        // Unsubmit the summary
                        $.ajax({
                            url: form.action,
                            type: 'POST',
                            data: {
                                _token: csrfToken,
                                action: 'unsubmit'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Summary is unsubmitted, update button appearance
                                    button.disabled = false;
                                    button.style.backgroundColor = 'blue'; // Change to the original color
                                    button.innerHTML = 'Submit';

                                    // Update the 'submitted' column in the database to '0'
                                    updateSubmittedStatusInDatabase(itemId, 0);

                                    // Remove the button state from local storage
                                    localStorage.removeItem(`buttonState_${itemId}`);
                                }
                            }
                        });
                    }
                } else if (initialSubmittedState === 2 || storedState === 'approved') {
                    // Button was previously approved or is currently approved
                    button.disabled = true;
                    button.style.backgroundColor = 'green'; // Set to green for "Approved"
                    button.innerHTML = 'Recieved';
                    return;
                } else {
                    // Ask for confirmation to submit
                    const submitConfirmed = confirm('Do you want to submit?');

                    if (submitConfirmed) {
                        // Make an AJAX request to submit the summary
                        $.ajax({
                            url: form.action,
                            type: 'POST',
                            data: {
                                _token: csrfToken,
                                action: 'submit'
                            },
                            success: function(response) {
                                if (response.success) {
                                    if (response.submitted === 1) {
                                        // Summary is submitted, update button appearance
                                        button.disabled = false;
                                        button.style.backgroundColor = 'green';
                                        button.innerHTML = 'Submitted';

                                        // Update the 'submitted' column in the database to '1'
                                        updateSubmittedStatusInDatabase(itemId, 1);

                                        // Store the button state in local storage
                                        localStorage.setItem(`buttonState_${itemId}`, 'submitted');
                                    } else if (response.submitted === 2) {
                                        // Summary is approved, update button appearance
                                        button.disabled = true;
                                        button.style.backgroundColor = 'green'; // Set to green for "Approved"
                                        button.innerHTML = 'Recieved';

                                        // Update the 'submitted' column in the database to '2'
                                        updateSubmittedStatusInDatabase(itemId, 2);

                                        // Store the button state in local storage
                                        localStorage.setItem(`buttonState_${itemId}`, 'approved');
                                    }
                                }
                            }
                        });
                    }
                }
            }
        </script>










        <script>
            // When the year filter dropdown changes
            $('#yearFilter').change(function() {
                // Get the selected year
                var selectedYear = $(this).val();

                // Redirect to the URL with the selected year as a query parameter
                var url = '{{ route('summary.index') }}'; // Update the route name
                if (selectedYear) {
                    url += '?year=' + selectedYear;
                }
                window.location.href = url;
            });
        </script>


    @endsection
