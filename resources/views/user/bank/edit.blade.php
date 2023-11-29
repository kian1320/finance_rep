@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('update-bank', ['bank_id' => $bank->id]) }}" method="POST">
    @csrf
    @method('PUT') <!-- Use the PUT method for updating -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Bank Account</h4>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="date_issued">Date</label>
                    <input type="date" name="date" id="date" class="form-control"
                        value="{{ $bank->date }}">
                </div>
                <div class="col-sm-2">
                    <label for="bname">Bank Name</label>
                    <input type="text" name="bname" id="bname" class="form-control"
                        value="{{ $bank->bname }}">
                </div>

                <div class="col-sm-2">
                    <label for="accnum">Account Number</label>
                    <input type="text" name="accnum" id="accnum" class="form-control"
                        value="{{ $bank->accnum }}">
                </div>

                <div class="col-sm-2">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control"
                        value="{{ $bank->amount }}">
                </div>

                <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
