@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid px-4">
    <br>
    <div class="card">
        <div class="card-header">
            <h4> Add Subtype to
                @if ($subtypes->isNotEmpty())
                    {{ strtoupper($subtypes->first()->types->name) }}
                @elseif ($types)
                    {{ strtoupper($types->name) }}
                @endif
                <a href="{{ URL::to('/') }}/user/types" class="btn btn-primary btn-sm float-end">View types</a>
            </h4>

        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <div class="table-responsive"> <!-- Add the responsive wrapper here -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Add Subtype</th>
                            <th style="width:50%">Subtypes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            <form method="post" action="{{ URL::to('/') }}/user/add-stypes">
                                <input type="hidden" name="types_id" value="{{ $types_id }}">

                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Enter text
                                        Here</label>
                                    <textarea class="form-control" name="sname" rows="7"></textarea>
                                    <br>
                                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <table id="StypesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subtypes</th>
                                        <th>Date Added</th>
                                        <th>Edit</th>
                                        <th>delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subtypes as $subtype)
                                        <tr>
                                            <td>{{ $subtype->sname }}</td>
                                            <td>{{ $subtype->created_at->format('m-d-Y') }}</td>
                                            <td>
                                                <a href="{{ url('user/edit-stypes/' . $subtype->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                            <td>
                                                <form
                                                    action="{{ url('user/delete-stypes/' . $types_id . '/' . $subtype->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#StypesTable').DataTable();
    });
</script>
@endsection
