@extends('layouts.master')
@section('content')
@section('title', 'View Users')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid px-4">
    <br>


    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif





    <div class="card">
        <div class="card-header">
            <h4>Reset Password for {{ $user->name }}</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('admin.reset-password.update', $user) }}">
                @csrf
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" required placeholder="New Password">
                </div>
                <div class="mb-3">

                    <input type="password" class="form-control" name="password_confirmation" required
                        placeholder="Confirm Password">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Reset Password</button>
                </div>
            </form>

        </div>
    </div>



@endsection

</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#Itemstable').DataTable();
    });
</script>
