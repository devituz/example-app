<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatlar</title>
</head>
<body>
<h1>Chats</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Text</th>
        <th>Image</th>
        <th>Reply</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($chats as $chat)
        <tr>
            <td>{{ $chat->id }}</td>
            <td>{{ $chat->text }}</td>
            <td>
                @if ($chat->img)
                    <img src="{{ url('storage/' . $chat->img) }}" alt="Chat Image" width="100">
                @endif
            </td>
            <td>{{ $chat->reply }}</td>
            <td>
                <form action="{{ route('admin.chats.reply', $chat->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="reply" class="form-control" required>{{ old('reply', $chat->reply) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Reply</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
