<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pet</title>
</head>
<body>
<h1>Add New Pet</h1>

<form action="{{ route('pets.store') }}" method="POST">
    @csrf

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div>
        <label for="category">Category Name:</label>
        <input type="text" id="category" name="category[name]" value="{{ old('category.name') }}" required>
    </div>

    <div>
        <label for="photoUrls">Photo URLs:</label>
        <input type="text" id="photoUrls" name="photoUrls[]" value="{{ old('photoUrls.0') }}" required>
    </div>

    <div>
        <label for="tags">Tags (comma-separated):</label>
        <input type="text" id="tags" name="tags[]" value="{{ old('tags.0.name') }}">
    </div>

    <div>
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
        </select>
    </div>

    <button type="submit">Add Pet</button>
</form>

<a href="{{ route('pets.index') }}">Back to list</a>
</body>
</html>
