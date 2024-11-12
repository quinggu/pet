<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets</title>
</head>
<body>
<h1>Available Pets</h1>

@if(session('message'))
    <p>{{ session('message') }}</p>
@endif

<a href="{{ route('pets.create') }}">Add New Pet</a>

<ul>
    @forelse ($pets as $pet)
        <li>
            {{ $pet['name'] ?? 'Unknown' }}
            <a href="{{ route('pets.show', $pet['id']) }}">View</a>
            <a href="{{ route('pets.edit', $pet['id']) }}">Edit</a>

            <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @empty
        <li>No pets available</li>
    @endforelse
</ul>
</body>
</html>
