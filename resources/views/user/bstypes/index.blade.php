@extends('layouts.usermaster')
@section('content')
@section('title', 'Financial Report')

<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<div class="container-fluid px-4">
    <br>
    <div class="card">
        <div class="card-header">
            <h4> Add Subtype to
                @if ($bsubtypes->isNotEmpty())
                    {{ strtoupper($bsubtypes->first()->btypes->name) }}
                @elseif ($btypes)
                    {{ strtoupper($btypes->name) }}
                @endif
                <a href="{{ URL::to('/') }}/user/btypes" class="btn btn-primary btn-sm float-end">View budget sub
                    types</a>
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
                            <form method="post" action="{{ URL::to('/') }}/user/add-bstypes">
                                <input type="hidden" name="btypes_id" value="{{ $btypes_id }}">

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
                        </td>
                        <td>
                            <table id="StypesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subtypes</th>
                                        <th>Date Added</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bsubtypes as $bsubtype)
                                        <tr>
                                            <td>{{ $bsubtype->bsname }}</td>
                                            <td>{{ $bsubtype->created_at->format('m-d-Y') }}</td>
                                            <td>
                                                <a href="{{ url('user/edit-bstypes/' . $bsubtype->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>

                                            </td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ URL::to('/') }}/user/delete-bstypes/{{ $btypes_id }}/{{ $bsubtype->id }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>

                                        </tr>
                        </td>
                    @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

            </tbody>
            </table>
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
