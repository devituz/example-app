@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>{{ $lesson->title }}</h1>
        <p><strong>Description:</strong> {{ $lesson->description }}</p>
        <video width="320" height="240" controls>
            <source src="{{ asset('storage/' . $lesson->video) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p><strong>Category Name:</strong> {{ $lesson->category->category_name }}</p>
        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection
