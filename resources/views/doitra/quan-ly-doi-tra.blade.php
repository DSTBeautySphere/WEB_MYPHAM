@extends('layout.index')

@section('title', 'Quản lý Đổi Trả')
<head>
    <!-- Các phần tử head khác -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('css')
<!-- Thêm CSS nếu cần -->
{{-- <style>
    .status-pending {
        color: orange;
    }
    .status-accepted {
        color: green;
    }
    .status-rejected {
        color: red;
    }
</style> --}}
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Quản lý Đổi Trả</h1>
   
    <table class="table table-bordered table-hover mt-4" id="exchangeTable">
        <thead >
            <tr>
                <th>#</th>
                {{-- <th>Mã Đổi Trả</th> --}}
                <th>Mã Đơn Đặt</th>
                <th>Sản Phẩm</th>
                <th>Lý Do Đổi Trả</th>
                <th>Ngày Yêu Cầu</th>
                <th>Hình Ảnh</th>
                <th>Trạng Thái</th>
                <th>Chức Năng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doi_tra as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                {{-- <td>{{ $item->ma_doi_tra }}</td> --}}
                <td>{{ $item->ma_don_dat }}</td>
                <td>{{ $item->bien_the_san_pham->san_pham->ten_san_pham ?? 'N/A' }}</td>
                <td>{{ $item->ly_do_doi_tra }}</td>
                <td>{{ \Carbon\Carbon::parse($item->ngay_yeu_cau)->format('d/m/Y') }}</td>
                {{-- <td>
                    <div class="d-flex flex-wrap justify-content-start">
                        @if($item->anh1)
                            <img src="{{ asset($item->anh1) }}" alt="Ảnh 1" class="img-thumbnail" 
                                style="width: 50px; height: 50px; margin: 2px;">
                        @endif
                        @if($item->anh2)
                            <img src="{{ asset($item->anh2) }}" alt="Ảnh 2" class="img-thumbnail" 
                                style="width: 50px; height: 50px; margin: 2px;">
                        @endif
                        @if($item->anh3)
                            <img src="{{ asset($item->anh3) }}" alt="Ảnh 3" class="img-thumbnail" 
                                style="width: 50px; height: 50px; margin: 2px;">
                        @endif
                    </div>
                </td> --}}
                <td>
                    <div class="d-flex flex-wrap justify-content-start">
                        @if($item->anh1)
                            <img src="{{ asset($item->anh1) }}" alt="Ảnh 1" class="img-thumbnail preview-image" 
                                style="width: 50px; height: 50px; margin: 2px;" data-bs-toggle="modal" data-bs-target="#imageModal">
                        @endif
                        @if($item->anh2)
                            <img src="{{ asset($item->anh2) }}" alt="Ảnh 2" class="img-thumbnail preview-image" 
                                style="width: 50px; height: 50px; margin: 2px;" data-bs-toggle="modal" data-bs-target="#imageModal">
                        @endif
                        @if($item->anh3)
                            <img src="{{ asset($item->anh3) }}" alt="Ảnh 3" class="img-thumbnail preview-image" 
                                style="width: 50px; height: 50px; margin: 2px;" data-bs-toggle="modal" data-bs-target="#imageModal">
                        @endif
                    </div>
                </td>
                <td>
                    {{-- <select name="trang_thai"  class="form-control status-dropdown" data-id="{{ $item->ma_doi_tra }}">
                        <option value="Chờ xử lý" {{ $item->trang_thai === 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="Đã chấp nhận" {{ $item->trang_thai === 'Đã chấp nhận' ? 'selected' : '' }}>Đã chấp nhận</option>
                        <option value="Từ chối" {{ $item->trang_thai === 'Từ chối' ? 'selected' : '' }}>Từ chối</option>
                    </select> --}}
                    {{-- {{$item->trang_thai}} --}}
                    <span 
                        class="badge {{ ($item->trang_thai === 'Yêu cầu đổi trả' || $item->trang_thai === 'Đã chấp nhận') ? 'bg-success' : 'bg-danger' }}" 
                        data-status="{{ ($item->trang_thai === 'Yêu cầu đổi trả' || $item->trang_thai === 'Đã chấp nhận') ? 'active' : 'inactive' }}"
                        style="cursor: pointer;">
                        {{ $item->trang_thai }}
                    </span>
                </td>
                <td>
                    {{-- <button class="btn btn-info btn-sm view-details" data-id="{{ $item->ma_doi_tra }}">Xem Chi Tiết</button> --}}
                    @if($item->trang_thai === 'Yêu cầu đổi trả')
                        <button class="btn btn-success btn-sm accept-request" data-id="{{ $item->ma_doi_tra }}">Chấp Nhận</button>
                        <button class="btn btn-danger btn-sm reject-request" data-id="{{ $item->ma_doi_tra }}">Từ Chối</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper d-flex justify-content-center">
        {{ $doi_tra->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Xem Ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Preview" class="img-fluid" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Thêm JavaScript nếu cần -->
<script>
document.querySelectorAll('.accept-request').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id'); // Lấy mã đổi trả

        // Gửi yêu cầu AJAX đến server
        fetch(`/chapnhandoitra/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Yêu cầu đổi trả đã được chấp nhận và đơn đặt hàng mới đã được tạo!');
                location.reload(); // Tải lại trang để cập nhật giao diện
            } else {
                alert('Cập nhật thất bại. Vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi. Vui lòng thử lại sau!');
        });
    });
});

$('.reject-request').on('click', function() {
    var id = $(this).data('id'); // Lấy id yêu cầu đổi trả
    
    if (confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?')) {
        // Gửi request tới controller để cập nhật trạng thái
        $.ajax({
            url: '/tuchoidoitra/' + id, // API để từ chối yêu cầu đổi trả
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Thêm CSRF token để bảo mật
                id: id
            },
            success: function(response) {
                if (response.success) {
                    alert('Yêu cầu đã được từ chối!');
                    // Cập nhật giao diện nếu cần (ví dụ, thay đổi trạng thái hiển thị)
                    location.reload(); // Reload lại trang để cập nhật trạng thái
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    }
});

// Lắng nghe sự kiện click trên ảnh
document.querySelectorAll('.preview-image').forEach(image => {
    image.addEventListener('click', function() {
        // Lấy URL của ảnh được click
        const imageUrl = this.src;
        // Đặt URL vào modal
        document.getElementById('modalImage').src = imageUrl;
    });
});

</script>
@endsection
