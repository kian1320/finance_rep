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

<form action="{{ route('add-bank') }}" method="POST">
    @csrf
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Add Bank Account</h4>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="date_issued">Date</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label for="bname">Bank Name</label>
                    <input type="text" name="bname" id="bname" class="form-control">
                </div>


                <div class="col-sm-2">
                    <label for="accnum">Account Number</label>
                    <input type="text" name="accnum" id="accnum" class="form-control">
                </div>

                <div class="col-sm-2">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control">
                </div>


                <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>




@endsection
