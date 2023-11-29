@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Late Encashment</h1>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


<form action="{{ url('user/update-lexpenses/' . $lexpenses->id) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Form fields and submit button -->



    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <label for="date_issued">Date issued</label>
                <input type="date" name="date_issued" value="{{ $lexpenses->date_issued }}" id="date_issued"
                    class="form-control">
            </div>
            <div class="col-sm-1">
                <label for="voucher">Voucher</label>
                <input type="text" name="voucher" id="voucher" value="{{ $lexpenses->voucher }}"
                    class="form-control">
            </div>
            <div class="col-sm-1">
                <label for="check">Cash/Check</label>
                <input type="text" name="check" id="check" value="{{ $lexpenses->check }}"
                    class="form-control">
            </div>
            <div class="col-sm-2">
                <label for="encashment">Encashment</label>
                <input type="date" name="encashment" value="{{ $lexpenses->encashment }}" id="encashment"
                    class="form-control">
            </div>
            <div class="col-sm-2">
                <label for="description">Description</label>
                <input type="text" name="description" value="{{ $lexpenses->description }}" id="description"
                    class="form-control">
            </div>
            <div class="col-sm-2">
                <label for="type_id">Type</label>
                <select name="type_id" id="type_id" class="form-control">
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" {{ $lexpenses->type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Second Dropdown (Stypes) -->
            <div class="col-sm-2">
                <label for="stype_id">Select Subtype</label>
                <select name="stype_id" id="stype_id" class="form-control">
                    <!-- Add an empty option as a placeholder -->
                    <option value="" disabled>Select a Type first</option>
                    @foreach ($stypes as $stype)
                        @if ($lexpenses->type_id == $stype->types_id || $lexpenses->stype_id == $stype->id)
                            <option value="{{ $stype->id }}"
                                {{ $lexpenses->stype_id == $stype->id ? 'selected' : '' }}>
                                {{ $stype->sname }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-sm-1">
                <label for="amount">Amount</label>
                <input type="text" name="amount" value="{{ $lexpenses->amount }}" id="amount"
                    class="form-control">
            </div>
            <div class="col-sm-1">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
        // Function to update stypes options based on the selected type
        function updateStypesOptions() {
            var selectedTypeId = $("#type_id").val();
            var stypesDropdown = $("#stype_id");

            // Clear the existing options
            stypesDropdown.empty();

            // Add an empty option as a placeholder
            stypesDropdown.append('<option value="" disabled>Select a Type first</option>');

            // Add the currently selected stype (if exists and related to the selected type)
            @if ($lexpenses->stype_id)
                var selectedStypeId = {{ $lexpenses->stype_id }};
                var selectedStypeName = "{{ $lexpenses->stype->sname }}";
                if (selectedTypeId == {{ $lexpenses->stype->types_id }}) {
                    stypesDropdown.append($('<option>', {
                        value: selectedStypeId,
                        text: selectedStypeName,
                        selected: true
                    }));
                }
            @endif

            // Add other stypes that match the selected type
            @foreach ($stypes as $stype)
                if ({{ $stype->types_id }} == selectedTypeId && {{ $lexpenses->stype_id }} !=
                    {{ $stype->id }}) {
                    stypesDropdown.append($('<option>', {
                        value: "{{ $stype->id }}",
                        text: "{{ $stype->sname }}"
                    }));
                }
            @endforeach
        }

        // Initial update when the page loads
        updateStypesOptions();

        // Update the stypes dropdown when the type selection changes
        $("#type_id").change(function() {
            updateStypesOptions();
        });

        // Update the hidden field with the selected stype value when the form is submitted
        $("form").submit(function() {
            var selectedStypeValue = $("#stype_id").val();
            $("#hidden_stype_id").val(selectedStypeValue);
        });
    });
</script>







@endsection
