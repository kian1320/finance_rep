@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')



<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Add Monthly Receipt</h4>
        </div>
        <div class="cardbody">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('budget.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="month"><strong>Month</strong></label>
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
                    <label for="year"><strong>Year</strong></label>
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
                    <label for="type"><strong>Money type</strong></label>
                    <select name="type" id="type" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                    </select>
                </div>



                <div class="mb-3">
                    <label for="btype"><strong>Select Receipt Type:</strong></label>
                    <select id="btype" name="btypes_id" class="form-control">
                        <option value="">Select</option>
                        @foreach ($btypes as $btype)
                            <option value="{{ $btype->id }}">{{ $btype->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bstype"><strong>Select Sub Receipt Type:</strong></label>
                    <select id="bstype" name="bstypes_id" class="form-control">
                        <option value="">Select</option>
                        @foreach ($bstypes as $bstype)
                            <option value="{{ $bstype->id }}">{{ $bstype->bsname }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label for="amount"><strong>if others pls specify:</strong></label>
                    <input type="text" name="others" class="form-control" placeholder="Enter Text">
                </div>



                <div class="mb-3">
                    <label for="amount"><strong>Amount:</strong></label>
                    <input type="text" name="amount" class="form-control" placeholder="Enter Amount">
                </div>



                <div class="mb-3">
                    <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                </div>





            </form>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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


<script>
    $(document).ready(function() {
        $('#btype').on('change', function() {
            var btypeID = $(this).val(); // Get the selected BType ID

            // Clear the existing "bstype" options
            $('#bstype').empty().append('<option value="">Select</option>');

            // If a BType is selected, make an AJAX request to get the associated BSTypes
            if (btypeID) {
                $.ajax({
                    url: '{{ route('get-bstypes') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        btype_id: btypeID
                    },
                    success: function(response) {
                        // If BSTypes are found, populate the "bstype" dropdown
                        if (response.bstypes && response.bstypes.length) {
                            $.each(response.bstypes, function(index, bstype) {
                                $('#bstype').append('<option value="' + bstype.id +
                                    '">' + bstype.bsname + '</option>');
                            });
                        }
                    }
                });
            }
        });

        // Trigger the change event initially in case a BType is preselected
        $('#btype').trigger('change');
    });
</script>




@endsection
