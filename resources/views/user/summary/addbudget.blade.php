@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')



<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Add Additional Monthly Receipt</h4>
        </div>
        <div class="cardbody">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('budget.additional') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="month">Month</label>
                    <select name="month" class="form-control">
                        @for ($month = 1; $month <= 12; $month++)
                            @php
                                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                $isSelected = $month == date('n') ? 'selected' : '';
                            @endphp
                            <option value="{{ $month }}" {{ $isSelected }}>{{ $monthName }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label for="year">Year</label>
                    <select name="year" id="year" class="form-control">
                        @php
                            $currentYear = date('Y');
                            $startYear = 2020; // Change this to your desired start year
                            $endYear = $currentYear + 5; // Change this to your desired end year
                        @endphp
                        @for ($year = $startYear; $year <= $endYear; $year++)
                            <option value="{{ $year }}" @if ($year == $currentYear) selected @endif>
                                {{ $year }}</option>
                        @endfor
                    </select>
                </div>





                <div class="mb-3">
                    <label for="type">Money type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                    </select>
                </div>



                @foreach ($btypes as $btype)
                    <div class="mb-3">
                        <label><strong>{{ $btype->name }}</strong></label>

                        @foreach ($btype->bstypes as $bstype)
                            <div class="mb-2">
                                <label
                                    for="budgets[{{ $btype->id }}][{{ $bstype->id }}]">{{ $bstype->bsname }}</label>
                                <input type="text" name="budgets[{{ $btype->id }}][{{ $bstype->id }}]"
                                    class="form-control" placeholder="Input Amount for {{ $bstype->bsname }}">
                            </div>
                        @endforeach
                    </div>
                @endforeach






                <div class="mb-3">
                    <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                </div>





            </form>

        </div>

    </div>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var submitButton = document.getElementById("submitButton");
        var hasBeenClicked = false; // Track whether the button has been clicked

        submitButton.addEventListener("click", function(event) {
            if (hasBeenClicked) {
                event
                    .preventDefault(); // Prevent form submission if the button has already been clicked
                return;
            }

            var confirmed = confirm("Are you sure you want to submit?");

            if (confirmed) {
                hasBeenClicked = true; // Set the flag to indicate the button has been clicked

                // Proceed with your submission logic
            } else {
                event.preventDefault(); // Prevent form submission if the user canceled
            }
        });
    });
</script>


@endsection
