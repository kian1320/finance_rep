@extends('layouts.usermaster')

@section('content')
@section('title', 'Financial Report')

<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>Edit Budget</h4>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="card-body">
            <form action="{{ route('update-budget', $budget->id) }}" method="POST">
                @csrf
                @method('PUT')


                <div class="mb-3">
                    <label for="month"><strong>Month</strong></label>
                    <select name="month" class="form-control">
                        @for ($month = 1; $month <= 12; $month++)
                            @php
                                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                $isSelected = $month == date('n') ? 'selected' : '';
                            @endphp
                            <option value="{{ $month }}" {{ $month == $budget->month ? 'selected' : '' }}>
                                {{ $monthName }}</option>
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

                <div class="form-group">
                    <label for="btypes_id">Type</label>
                    <select name="btypes_id" id="btypes_id" class="form-control">
                        @foreach ($btypes as $btype)
                            <option value="{{ $btype->id }}"
                                {{ $budget->btypes_id == $btype->id ? 'selected' : '' }}>
                                {{ $btype->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="bstypes_id">SubType</label>
                    <select name="bstypes_id" id="bstypes_id" class="form-control">
                        @foreach ($bstypes as $bstype)
                            <option value="{{ $bstype->id }}"
                                {{ $budget->bstypes_id == $bstype->id ? 'selected' : '' }}>
                                {{ $bstype->bsname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" name="others" id="amount" class="form-control"
                        value="{{ $budget->others }}">
                </div>


                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control"
                        value="{{ $budget->amount }}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Budget</button>
                </div>
            </form>
        </div>
    </div>
</div>


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection
