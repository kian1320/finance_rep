@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')


<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Yearly summary <a href="{{ 'add-summary' }}" class="btn btn-primary btn-sm float-end">Add sum</a>
            </h4>
            <button onclick="window.print()">Print this page</button>
        </div>

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
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif



        <div class="container-fluid">
            <table class="table">
                <tbody>

                    <tr>
                        @foreach ($summary as $item)
                        @endforeach
                        <!-- Add a column for the total -->
                    </tr>

                    <!-- Starting Balance Row -->
                    <tr>
                        <th scope="row"></th>

                        <td><strong>Actual</strong>
                        </td>
                        <td><strong>Budget</strong></td>




                    </tr>

                    <tr>
                        <th scope="row">Starting balance:</th>
                        @php
                            $totalStartingBalance = 0; // Initialize the total starting balance
                        @endphp
                        @foreach ($summary as $item)
                            @if ($item->created_by === $user->id && $item->year === $currentYear)
                                @php
                                    $totalStartingBalance += $item->beginbal ?? 0;
                                @endphp
                            @endif
                        @endforeach

                        <td>
                            <p>PHP: {{ number_format($totalStartingBalance, 0, '.', ',') }}</p>
                        </td>
                        <td> </td>
                    </tr>



                    <!-- Second Container -->
                    <!-- Second Container -->
                    <div class="row">
                        @foreach ($btypes as $btype)
                            <tr>
                                <th colspan="{{ count($months) + 2 }}">{{ $btype->name }}</th>
                            </tr>
                            @foreach ($btype->bstypes as $bstype)
                                <tr>
                                    <td>{{ $bstype->bsname }}</td>
                                    @foreach ($years as $year)
                                        <td><strong>
                                                @if (isset($budgetDataByYear[$year][$btype->name][$bstype->bsname]))
                                                    {{ number_format($budgetDataByYear[$year][$btype->name][$bstype->bsname], 2) }}
                                                @else
                                                    -
                                                @endif
                                            </strong></td>
                                    @endforeach
                                    <!-- Display ybudgets data immediately after budget columns -->
                                    <td>
                                        @php
                                            $ybData = $ybudgetsData
                                                ->where('btypes_id', $btype->id)
                                                ->where('bstypes_id', $bstype->id)
                                                ->first();
                                        @endphp
                                        @if ($ybData)
                                            {{ number_format($ybData->amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($incomeCategories[$bstype->bsname]))
                                            @foreach ($incomeCategories[$bstype->bsname] as $category)
                                                <h2>{{ $category['name'] }}</h2>
                                                <ul>
                                                    <li>{{ $category['amount'] }}</li>
                                                </ul>
                                            @endforeach
                                        @else
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </div>





                    <!-- Now you can display the total starting sum in the table -->
                    <tr>
                        <th scope="row">Total Starting</th>
                        <td colspan="{{ count($summary) }}"><strong> PHP:
                                {{ number_format($totalBudgetAmount, 2) }}</strong></td>



                        <td></td>
                    </tr>
                    <br>

                    <tr>
                        <th scope="row">Expenses</th>
                    </tr>

                    <!-- Third Container -->




                    @foreach ($types as $type)
                        <tr>
                            <th scope="row">{{ $type->name }}</th>
                        </tr>
                        @foreach ($type->stypes as $stype)
                            <tr>
                                <td scope="row">{{ $stype->sname }}</td>
                                <td colspan="{{ count($months) }}">
                                    <!-- Use colspan to span across all month columns -->
                                    @php
                                        $subtypeYearlyTotal = 0; // Initialize with a default value
                                        if (isset($totalExpensesByStypeAndType[$stype->id][$type->id])) {
                                            $subtypeYearlyTotal = array_sum($totalExpensesByStypeAndType[$stype->id][$type->id]);
                                        }
                                    @endphp
                                    <strong>{{ number_format($subtypeYearlyTotal, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach







                    <tr>
                        <th scope="row">Total Expenses</th>
                        @foreach ($years as $year)
                            <td>

                                <p id="totalDisplay"> <strong>PHP:
                                        {{ number_format($total, 0, '.', ',') }}.00</strong></p>
                            </td>
                        @endforeach
                    </tr>









                    <!-- Now you can display the ending balance sum in the table -->
                    <tr>
                        <th scope="row">Ending Balance</th>
                        <td colspan="{{ count($summary) }}"><strong> PHP:

                                {{ number_format($remainingBudget, 2) }}
                            </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <script>
            // When the year filter dropdown changes
            $('#yearFilter').change(function() {
                // Get the selected year
                var selectedYear = $(this).val();

                // Redirect to the URL with the selected year as a query parameter
                var url = '{{ route('ysummary.index') }}'; // Update the route name
                if (selectedYear) {
                    url += '?year=' + selectedYear;
                }
                window.location.href = url;
            });
        </script>
    @endsection
