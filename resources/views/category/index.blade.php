@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Kategoriyalar</h1>
        <a href="{{ route('category.create') }}" class="btn btn-primary mb-3">Yangi Kategoriya Qo'shish</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kategoriya Nomi</th>
                <th>Kategoriya Rasmi</th>
                <th>Kurslar Nomi</th>
                <th>Amallar</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td><img src="{{ asset('storage/' . $category->category_img) }}" alt="{{ $category->category_name }}" width="50"></td>
                    <td>{{ $category->kurslar->courses_name }}</td> <!-- Updated cell content -->
                    <td>
                        <a href="{{ route('category.show', $category->id) }}" class="btn btn-info btn-sm">Ko'rish</a>
                        <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">Tahrirlash</a>
                        <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
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


