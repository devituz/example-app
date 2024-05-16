@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kategoriyani Tahrirlash</h1>
        <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="category_name">Kategoriya Nomi:</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}">
            </div>
            <div class="form-group">
                <label for="category_img">Kategoriya Rasmi:</label>
                <input type="file" class="form-control" id="category_img" name="category_img">
                <img src="{{ asset('storage/' . $category->category_img) }}" alt="{{ $category->category_name }}" width="50">
            </div>
            <div class="form-group">
                <label for="kurslar_id">Kurslar ID:</label>
                <select class="form-control" id="kurslar_id" name="kurslar_id">
                    @foreach($kurslar as $kurs)
                        <option value="{{ $kurs->id }}" {{ $category->kurslar_id == $kurs->id ? 'selected' : '' }}>{{ $kurs->courses_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </form>
    </div>
@endsection
