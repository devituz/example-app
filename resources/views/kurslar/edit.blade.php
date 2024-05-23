@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Yangi Kurs Tahrirlash</h1>
        <form action="{{ route('kurslar.update', $kurs->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="teachers_name">O'qituvchi Ismi:</label>
                <input type="text" class="form-control" id="teachers_name" name="teachers_name" value="{{ old('teachers_name', $kurs->teachers_name) }}" required>
            </div>
            <div class="form-group">
                <label for="teachers_img">O'qituvchi Rasmi:</label>
                <input type="file" class="form-control" id="teachers_img" name="teachers_img">
                @if($kurs->teachers_img)
                    <img src="{{ asset('storage/' . $kurs->teachers_img) }}" alt="{{ $kurs->teachers_name }}" class="img-thumbnail mt-2" width="150">
                @endif
            </div>
            <div class="form-group">
                <label for="courses_name">Kurslar Nomi:</label>
                <input type="text" class="form-control" id="courses_name" name="courses_name" value="{{ old('courses_name', $kurs->courses_name) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </form>
    </div>
@endsection
