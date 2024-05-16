@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Yangi Kategoriya Qo'shish</h1>
        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="category_name">Kategoriya Nomi:</label>
                <input type="text" class="form-control" id="category_name" name="category_name">
            </div>
            <div class="form-group">
                <label for="category_img">Kategoriya Rasmi:</label>
                <input type="file" class="form-control" id="category_img" name="category_img">
            </div>
            <div class="form-group">
                <label for="kurslar_id">Kurslar ID:</label>
                <select class="form-control" id="kurslar_id" name="kurslar_id">
                    @foreach($kurslar as $kurs)
                        <option value="{{ $kurs->id }}">{{ $kurs->courses_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </form>
    </div>
@endsection
