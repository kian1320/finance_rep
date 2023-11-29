@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<div class="container-fluid px-4">
    <h1 class="mt-4">Add Late encashment</h1>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('add-lexpenses') }}" method="POST">
    @csrf

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Add Expenses</h4>
            </div>
            <div class="row">
                <div class="form-group mb-2">
                    <label for="date_issued">Date issued</label>
                    <input type="date" name="date_issued" id="date_issued" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label for="voucher">Voucher</label>
                    <input type="text" name="voucher" id="voucher" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label for="check">Money type</label>
                    <select name="check" id="check" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="encashment">Encashment</label>
                    <input type="date" name="encashment" id="encashment" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label for="type_id">Select Type</label>
                    <select name="type_id" id="type_id" class="form-control">
                        <option value="">Select Type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Second Dropdown (Stypes) -->
                <div class="form-group mb-2">
                    <label for="stype_id">Select Subtype</label>
                    <select name="stype_id" id="stype_id" class="form-control">
                        <option value="">Select Subtype</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="amount">if others pls specify:</label>
                    <input type="text" name="others" class="form-control" placeholder="Enter Text">
                </div>


                <div class="form-group mb-2">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control">
                </div>


                <div class="col-sm-1">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#Itemstable').DataTable();

        // Add an event listener to the type_id dropdown
        $('#type_id').on('change', function() {
            var typeID = $(this).val(); // Get the selected type ID

            // Clear the existing stypes options
            $('#stype_id').empty().append('<option value="">Select Stype</option>');

            // If a type is selected, make an AJAX request to get the stypes
            if (typeID) {
                $.ajax({
                    url: '{{ route('get-stypes') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type_id: typeID
                    },
                    success: function(response) {
                        // If stypes are found, populate the stype dropdown
                        if (response.stypes && response.stypes.length) {
                            $.each(response.stypes, function(index, stype) {
                                $('#stype_id').append('<option value="' + stype.id +
                                    '">' + stype.sname + '</option>');
                            });
                        }
                    }
                });
            }
        });

        // Trigger the change event initially in case a type is preselected
        $('#type_id').trigger('change');
    });
</script>

@endsection
