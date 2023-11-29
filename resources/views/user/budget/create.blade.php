@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')



<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Add Items</h4>
        </div>
        <div class="cardbody">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ 'add-summary' }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="">Month</label>
                    <select type="text" name="month" class="form-control">
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
                    <label for="">Year</label>
                    <input type="text" name="year" class="form-control">
                </div>
                <div class="mb-3">

                    <input type="hidden" name="totalstr" class="form-control">
                </div>
                <div class="mb-3">

                    <input type="hidden" name="aftexpenses" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="">Type Cash or Check</label>
                    <select type="text" name="type" class="form-control">
                        <option value="select">Select</option>
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>

                    </select>
                </div>

                <div class="mb-3">

                    <button type="submit" class="btn btn-success">Submit</button>
                </div>





            </form>

        </div>

    </div>

</div>




@endsection
