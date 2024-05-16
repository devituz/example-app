@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kategoriya Tafsilotlari</h1>
        <div class="card">
            <div class="card-header">
                Kategoriya ID: {{ $category->id }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Kategoriya Nomi: {{ $category->category_name }}</h5>
                <p class="card-text">
                    <strong>Kategoriya Rasmi:</strong>
                    <br>
                    <img src="{{ asset('storage/' . $category->category_img) }}" alt="{{ $category->category_name }}" width="100">
                </p>
                <p class="card-text">
                    <strong>Kurs Nomi:</strong> {{ $category->kurslar->courses_name ?? 'No Course' }}
                </p>
                <a href="{{ route('category.index') }}" class="btn btn-primary">Orqaga</a>
                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning">Tahrirlash</a>
                <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">O'chirish</button>
                </form>
            </div>
        </div>
    </div>
@endsection
