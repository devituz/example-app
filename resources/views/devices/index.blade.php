@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Devices</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('devices.create') }}" class="btn btn-primary mb-3">Add Device</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Android ID</th>
                <th>Windows ID</th>
                <th>Courses</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($devices as $device)
                <tr>
                    <td>{{ $device->id }}</td>
                    <td>{{ $device->androidId }}</td>
                    <td>{{ $device->windowsId }}</td>
                    <td>
                        @if ($device->kurslar)
                            {{ $device->kurslar->courses_name }}
                        @else
                            No Course Assigned
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('devices.destroy', $device->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this device?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
