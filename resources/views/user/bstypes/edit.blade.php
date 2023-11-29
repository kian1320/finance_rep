<!-- user/stypes/edit.blade.php -->
@extends('layouts.usermaster')
@section('content')
@section('title', 'items')

<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Edit Receipt Subtype</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="post" action="{{ url('user/update-bstypes/' . $bstype->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Enter text Here</label>
                    <input type="text" name="bsname" value="{{ $bstype->bsname }}" class="form-control">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>




        </div>
    </div>
</div>

@endsection
