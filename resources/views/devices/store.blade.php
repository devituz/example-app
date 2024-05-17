@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Device') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('devices.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="androidId">{{ __('Android ID') }}</label>
                                <input id="androidId" type="text" class="form-control @error('androidId') is-invalid @enderror" name="androidId" value="{{ old('androidId') }}" required autocomplete="androidId" autofocus>
                                @error('androidId')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="windowsId">{{ __('Windows ID') }}</label>
                                <input id="windowsId" type="text" class="form-control @error('windowsId') is-invalid @enderror" name="windowsId" value="{{ old('windowsId') }}" required autocomplete="windowsId">
                                @error('windowsId')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Select Courses') }}</label><br>
                                @foreach($courses as $course)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kurslar_ids[]" id="course{{ $course->id }}" value="{{ $course->id }}">
                                        <label class="form-check-label" for="course{{ $course->id }}">
                                            {{ $course->courses_name }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('kurslar_ids')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
