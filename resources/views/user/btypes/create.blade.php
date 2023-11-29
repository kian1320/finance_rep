@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')


<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="">Add Receipt Type</h4>
        </div>
        <div class="cardbody">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif


            <form action="{{ 'add-btypes' }}" method="POST">
                @csrf



                <div class="mb-3">
                    <label for="">Budget Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>





            </form>

        </div>

    </div>

</div>




@endsection
