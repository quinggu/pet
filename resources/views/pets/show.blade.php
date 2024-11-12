<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title>
</head>
<body>
<h1>Pet Details</h1>

<div>
    <h3>Name: {{ $pet['name'] }}</h3>

    <p>Category: {{ $pet['category']['name'] ?? 'N/A' }}</p>

    <p>Status: {{ ucfirst($pet['status']) }}</p>

    @if(isset($pet['tags']) && count($pet['tags']) > 0)
        <p>Tags:
            @foreach ($pet['tags'] as $tag)
                <span>{{ $tag['name'] }}</span>
            @endforeach
        </p>
    @else
        <p>Tags: N/A</p>
    @endif

    @if(isset($pet['photoUrls']) && count($pet['photoUrls']) > 0)
        <p>Photos:</p>
        @foreach ($pet['photoUrls'] as $url)
            <img src="{{ $url }}" alt="Pet photo" style="max-width: 200px; margin-right: 10px;">
        @endforeach
    @else
        <p>No photos available.</p>
    @endif
</div>

<a href="{{ route('pets.index') }}">Back to list</a>
</body>
</html>
