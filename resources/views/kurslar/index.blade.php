@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kurslar</h1>
        <a href="{{ route('kurslar.create') }}" class="btn btn-primary mb-3">Yangi Kurs Qo'shish</a>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">O'qituvchi Ismi</th>
                <th scope="col">O'qituvchi Rasmi</th>
                <th scope="col">Kurslar Nomi</th>
                <th scope="col">Amallar</th>
            </tr>
            </thead>
            <tbody>
            @foreach($kurslar as $kurs)
                <tr>
                    <th scope="row">{{ $kurs->id }}</th>
                    <td>{{ $kurs->teachers_name }}</td>
                    <td><img src="{{ asset('storage/' . $kurs->teachers_img) }}" alt="{{ $kurs->teachers_name }}" width="50"></td>
                    <td>{{ $kurs->courses_name }}</td>
                    <td>
                        <a href="{{ route('kurslar.show', $kurs->id) }}" class="btn btn-info btn-sm">Ko'rish</a>
                        <a href="{{ route('kurslar.edit', $kurs->id) }}" class="btn btn-warning btn-sm">Tahrirlash</a>
                        <form action="{{ route('kurslar.destroy', $kurs->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">O'chirish</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
