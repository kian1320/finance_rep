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

<div class="container-fluid">
    <br>
    <div class="card">
        <div class="card-header">
            <h4>View Bank Records <a href="{{ route('add-bank') }}" class="btn btn-primary btn-sm float-end">Add Bank</a>
            </h4>
        </div>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="table- responsive">
            <table id="bankTable" class="table table-bordered table-striped">
                <thead>

                    <tr>

                        <th class="">Total Cash on Hand:
                            @if ($latestSummary)
                                <strong> PHP: {{ number_format($latestSummary->aftexpenses, 2, '.', ',') }}.00</strong>
                            @else
                                <strong>No data available</strong>
                            @endif
                        </th>
                    </tr>

                </thead>

                <tbody>
                    <tr>
                        <td>
                            @if ($latestSummary)
                                {{ \Carbon\Carbon::createFromFormat('m', $latestSummary->month)->format('F') }}
                                {{ $latestSummary->year }}
                            @else
                                No data available
                            @endif
                        </td>

                    </tr>
                </tbody>
        </div>


        <div class="table-responsive">
            <table id="bankTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>Amount</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banks as $bank)
                        <tr>
                            <td>{{ $bank->date }}</td>
                            <td>{{ $bank->bname }}</td>
                            <td>{{ $bank->accnum }}</td>
                            <td> PHP: {{ number_format($bank->amount, 0, '.', ',') }}.00</td>
                            <td align="center">
                                <a href="{{ route('edit-bank', ['bank_id' => $bank->id]) }}"
                                    class="btn btn-outline-success">Edit</a>
                            </td>

                            <td align="center">
                                <a href="{{ route('delete-bank', ['bank_id' => $bank->id]) }}"
                                    class="btn btn-outline-danger"
                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete?')) { document.getElementById('delete-form-{{ $bank->id }}').submit(); }">Delete</a>
                                <form id="delete-form-{{ $bank->id }}"
                                    action="{{ route('delete-bank', ['bank_id' => $bank->id]) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table- responsive">
            <table id="bankTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <!-- Your existing code -->

                        {{-- <div>Total Bank Amount: {{ number_format($totalBankAmount, 2) }}</div> --}}
                        <div> <strong>Total Money: {{ number_format($totalAmountWithBank, 2) }}</strong>
                        </div>

                        <!-- Your existing code -->



                    </tr>

                </thead>

                <tbody>

                </tbody>
        </div>





        <div>
            <button onclick="window.print()">Print this page</button>
        </div>
    </div>
</div>

<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bankTable').DataTable();
    });
</script>

@endsection
