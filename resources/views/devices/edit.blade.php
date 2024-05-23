@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Device Tahrirlash</h1>
        <form action="{{ route('devices.update', $device->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="category_name">Familya:</label>
                <input type="text" class="form-control" id="lastname" name="lastname"
                       value="{{ $device->lastname }}">
            </div>

            <div class="form-group">
                <label for="category_name">Ismi:</label>
                <input type="text" class="form-control" id="firstname" name="firstname"
                       value="{{ $device->firstname }}">
            </div>
            <div class="form-group">
                <label for="category_name">androidId:</label>
                <input type="text" class="form-control" id="androidId" name="androidId"
                       value="{{ $device->androidId }}">
            </div>
            <div class="form-group">
                <label for="category_name">windowsId:</label>
                <input type="text" class="form-control" id="windowsId" name="windowsId"
                       value="{{ $device->windowsId }}">
            </div>

            <div class="form-group">
                <label for="category_img">User Rasmi:</label>
                <input type="file" class="form-control" id="userimg" name="userimg">
                <img src="{{ asset('storage/' . $device->userimg) }}" alt="{{ $device->lastname }}"
                     width="50">
            </div>


            <div class="form-group">
                <label>{{ __('Select Courses') }}</label><br>
                @foreach($courses as $course)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kurslar_ids[]"
                               id="course{{ $course->id }}" value="{{ $course->id }}"
                               @if(in_array($course->id, $device->kurslars->pluck('id')->toArray())) checked @endif>
                        <label class="form-check-label" for="course{{ $course->id }}">
                            {{ $course->courses_name }}
                        </label>
                    </div>
                @endforeach
                @error('kurslar_ids')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </form>
    </div>
@endsection
