@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-4">
        <form action="/search" method="get">
            <div class="input-group">
                <input type="search" name="search" class="form-control">
                <span class="input-group-prepend">
                    <button type="submit" class="btn btn-primary">Search</button>
                </span>
            </div>
        </form>
    </div>
</div>


<div align="right">
    <a href="{{ route('photo.create') }}" class="btn btn-success btn-sm">Add</a>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@elseif ($message = Session::get('errors'))
<div class="alert alert-danger">
    <p>{{ $message->first() }}</p>
</div>
@endif


<table class="table table-bordered table-striped">
    <tr>
        <th width="10%">Photo</th>
        <th width="35%">Description</th>
        <th width="30%">Action</th>
    </tr>
    @foreach($photos as $photo)
    <tr>
        <td><img src="https://storagephotobook.blob.core.windows.net/photos/{{ $photo->name }}" class="img-thumbnail" width="75" /></td>
        <td>{{ $photo->description }}</td>
        <td>
            <a href="{{ route('photo.show', $photo->id) }}" class="btn btn-success">Show</a>
            <a href="{{ route('photo.edit', $photo->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('photo.destroy', $photo->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            <div class="row">
                <div class="col-md-4">
                    <form action="/createToken/{{ $photo->id }}" method="post">
                        @csrf
                        <div class="input-group">
                            <input type="date" name="date" class="form-control">
                            <span class="input-group-prepend">
                                <button type="submit" class="btn btn-primary">Share</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</table>
@endsection