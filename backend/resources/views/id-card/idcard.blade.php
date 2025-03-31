<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .id-card { width: 300px; padding: 20px; border: 2px solid black; }
        .photo { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .details { margin-top: 10px; }
    </style>
</head>
<body>
<div class="id-card">
    @if($user->image)
        <img src="{{ public_path('storage/' . $user->image) }}" class="photo" alt="User">
    @else
        <div class="photo-placeholder">No Photo</div>
    @endif

    <h2>{{ $user->name }}</h2>
    <p><strong>ID:</strong> {{ $user->university_id }}</p>
    <p><strong>Department:</strong> {{ $user->department?->name ?? 'N/A' }}</p>
    <p><strong>Role:</strong> {{ $user->roles->first()?->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
</div>
</body>
</html>
