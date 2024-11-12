{{-- <form action="{{ route('uploadImage') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="image">Chọn ảnh sản phẩm:</label>
        <input type="file" name="image" id="image" required>
    </div>
    <button type="submit">Tải ảnh lên</button>
</form>
@if(isset($success))
    <div>
        @if(strpos($success, 'Lỗi') === false)
            <span style="color: green;">{{ $success }}</span>
        @else
            <span style="color: red;">{{ $success }}</span>
        @endif
    </div>
@endif

@if(isset($imageUrl))
    <div>
        <h3>Ảnh đã được tải lên:</h3>
        <img src="{{ $imageUrl }}" alt="Uploaded Image" style="max-width: 300px;">
    </div>
@endif
 --}}

 <!-- resources/views/test.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>
<body>
    <h1>Upload Image</h1>
    @if (isset($success))
        <p>{{ $success }}</p>
    @endif

    @if (isset($imageUrl))
        <p>Uploaded Image:</p>
        <img src="{{ $imageUrl }}" alt="Uploaded Image" width="200">
    @endif

    <form action="/upload-image" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Choose image to upload:</label>
        <input type="file" name="images[]" id="image" multiple>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
