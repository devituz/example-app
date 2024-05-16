@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kurs Ma'lumoti</h1>
        <p><strong>O'qituvchi Ismi:</strong> {{ $kurs->teachers_name }}</p>
        <p><strong>Kurslar Nomi:</strong> {{ $kurs->courses_name }}</p>
        <img src="{{ asset('storage/' . $kurs->teachers_img) }}" alt="{{ $kurs->teachers_name }}" width="100">
        <a href="{{ route('kurslar.index') }}" class="btn btn-primary">Orqaga Qaytish</a>
    </div>
@endsection
