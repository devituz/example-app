@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Lesson</h1>
        <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $lesson->title }}">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $lesson->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="video">Video:</label>
                <input type="file" class="form-control" id="video" name="video">
                <video width="320" height="240" controls>
                    <source src="{{ asset('storage/' . $lesson->video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="form-group">
                <label for="category_id">Select Category:</label>
                <select class="form-control" id="category_id" name="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
