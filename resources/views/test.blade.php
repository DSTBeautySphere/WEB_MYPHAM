<form action="{{ route('uploadImage') }}" method="POST" enctype="multipart/form-data">
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

