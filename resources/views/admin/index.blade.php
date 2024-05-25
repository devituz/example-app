<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
</head>
<body>
<h1>Admin Profile</h1>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<table>
    <tr>
        <th>Avatar:</th>
        <td>
            @if ($admin->avatar)
                <img src="{{ asset('storage/avatars/' . $admin->avatar) }}" alt="Avatar" width="100">
            @else
                <p>No avatar uploaded</p>
            @endif
        </td>
    </tr>
    <tr>
        <th>Name:</th>
        <td>{{ $admin->name }}</td>
    </tr>
    <tr>
        <th>Email:</th>
        <td>{{ $admin->email }}</td>
    </tr>
    <tr>
        <th>Phone:</th>
        <td>{{ $admin->phone }}</td>
    </tr>
    <tr>
        <th>Created At:</th>
        <td>{{ $admin->created_at }}</td>
    </tr>
    <tr>
        <th>Updated At:</th>
        <td>{{ $admin->updated_at }}</td>
    </tr>
</table>

<a href="{{ route('admin.edit') }}">Edit Profile</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>
