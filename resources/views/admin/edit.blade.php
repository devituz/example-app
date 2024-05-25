<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin Profile</title>
</head>
<body>
<h1>Edit Admin Profile</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}">
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}">
    </div>

    <div>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
    </div>

    <div>
        <label for="password">Password (optional):</label>
        <input type="password" id="password" name="password">
    </div>

    <div>
        <label for="avatar">Avatar:</label>
        <input type="file" id="avatar" name="avatar">
        @if ($admin->avatar)
            <img src="{{ asset('storage/avatars/' . $admin->avatar) }}" alt="Avatar" width="100">
        @endif
    </div>

    <button type="submit">Update Profile</button>
</form>

<a href="{{ route('admin.index') }}">Back to Profile</a>

</body>
</html>
