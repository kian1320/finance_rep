@extends('layouts.usermaster')
@section('content')
@section('title', 'Change Password')



<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid px-4">
    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <h4>Change Password</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('password.change') }}">
                @csrf
                <div class="mb-3">
                    <input type="password" class="form-control" name="current_password" placeholder="Current Password"
                        required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="New Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="Confirm New Password" required>
                </div>

                <div class="mb-3">

                    <button type="submit" class="btn btn-success">Change Password</button>
                </div>

            </form>
        </div>
    </div>



</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#Itemstable').DataTable();
    });
</script>
@endsection
