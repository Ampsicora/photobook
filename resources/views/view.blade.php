@extends('layouts.app')

@section('content')

<div class="jumbotron text-center">
    <div align="right">
        <a href="{{ route('photo.index') }}" class="btn btn-default">Back</a>
    </div>
    <br />
    <img src="https://storagephotobook.blob.core.windows.net/photos/{{ $photo->name }}" class="img-thumbnail" />
    <h3>Name - {{ $photo->name }} </h3>
    <h3>Description - {{ $photo->description }}</h3>
    <h4> Tags:
        @foreach($photo->tags as $tag)
            #{{ $tag->name }}
        @endforeach
    </h4>
</div>
@endsection