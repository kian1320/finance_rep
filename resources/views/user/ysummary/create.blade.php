@extends('layouts.usermaster')
@section('content')
@section('title', 'items')



<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Add Yearly Budget</h4>
        </div>
        <div class="cardbody">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('ysummary.store') }}" method="POST">
                @csrf


                <div class="mb-3">
                    <label for="year">Year</label>
                    <select name="year" id="year" class="form-control">
                        @php
                            $currentYear = date('Y');
                            $startYear = $currentYear - 5; // You can adjust the range of years as needed
                            $endYear = $currentYear + 10;
                        @endphp
                        @for ($year = $startYear; $year <= $endYear; $year++)
                            <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>





            </form>

        </div>

    </div>

</div>




@endsection
