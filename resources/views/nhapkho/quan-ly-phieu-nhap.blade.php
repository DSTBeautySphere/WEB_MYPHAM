@extends('layout.index')
@section('title','Quản Lý phiếu nhập')
@section('css')


@endsection

@section('content')
    <div class="container">
        <h1 class="my-4">Danh Sách Phiếu Nhập</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nhà Cung Cấp</th>
                    <th>Ngày Nhập</th>
                    <th>Tổng Số Lượng</th>
                    <th>Tổng Giá Trị</th>
                    {{-- <th>Ghi Chú</th> --}}
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($phieuNhaps as $phieuNhap)
                    <tr>
                        {{-- <td>{{ $loop->iteration }}</td> --}}
                        <td></td>
                        <td>{{ $phieuNhap->nha_cung_cap->ten_nha_cung_cap ?? 'N/A' }}</td>
                        <td>{{ $phieuNhap->ngay_nhap}}</td>
                        <td>{{ $phieuNhap->tong_so_luong }}</td>
                        <td>{{ number_format($phieuNhap->tong_gia_tri, 0, ',', '.') }} VND</td>
                        {{-- <td>{{ $phieuNhap->ghi_chu }}</td> --}}
                        <td>
                            @if ($phieuNhap->trang_thai == 1)
                                <span class="badge badge-info">Đang xử lý</span>
                            @else
                                <span class="badge badge-success">Hoàn thành</span>
                            @endif
                        </td>
                        <td>
                            {{-- <button class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#chiTietPhieuNhapModal" 
                                    data-ma_phieu_nhap="{{ $phieuNhap->ma_phieu_nhap }}">
                                Xem Chi Tiết
                            </button> --}}
                            <button class="btn btn-primary btn-sm btn-show-detail" data-id="{{ $phieuNhap->ma_phieu_nhap }}">Xem Chi Tiết</button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Chưa có phiếu nhập nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper d-flex justify-content-center">
            {{ $phieuNhaps->links('vendor.pagination.bootstrap-4') }}
        </div>
       
    </div>


    <!-- Modal -->
<div class="modal fade" id="chiTietPhieuNhapModal" tabindex="-1" role="dialog" aria-labelledby="chiTietPhieuNhapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chiTietPhieuNhapModalLabel">Chi Tiết Phiếu Nhập</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <h6>Thông Tin Phiếu Nhập</h6>
                <p><strong>Nhà Cung Cấp:</strong> <span id="nhaCungCap"></span></p>
                <p><strong>Ngày Nhập:</strong> <span id="ngayNhap"></span></p>
                <p><strong>Tổng Số Lượng:</strong> <span id="tongSoLuong"></span></p>
                <p><strong>Tổng Giá Trị:</strong> <span id="tongGiaTri"></span></p>
                <p><strong>Ghi Chú:</strong> <span id="ghiChu"></span></p>
                <hr>
                <h6>Chi Tiết Sản Phẩm</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá Nhập</th>
                        </tr>
                    </thead>
                    <tbody id="chiTietSanPham">
                        <!-- Sản phẩm sẽ được thêm vào đây -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // $('#chiTietPhieuNhapModal').on('show.bs.modal', function (event) {
    //     // Lấy button kích hoạt modal
    //     var button = $(event.relatedTarget);

    //     // Lấy giá trị từ thuộc tính data
    //     var maPhieuNhap = button.data('ma_phieu_nhap');

    //     // Gọi API để lấy chi tiết phiếu nhập
    //     fetch(`/chitietphieunhap/${maPhieuNhap}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 const phieuNhap = data.data;

    //                 // Điền thông tin phiếu nhập vào modal
    //                 $('#nhaCungCap').text(phieuNhap.nha_cung_cap.ten_nha_cung_cap || 'N/A');
    //                 $('#ngayNhap').text(phieuNhap.ngay_nhap);
    //                 $('#tongSoLuong').text(phieuNhap.tong_so_luong);
    //                 $('#tongGiaTri').text(
    //                     new Intl.NumberFormat('vi-VN').format(phieuNhap.tong_gia_tri) + ' VND'
    //                 );
    //                 $('#ghiChu').text(phieuNhap.ghi_chu || 'Không có ghi chú');

    //                 // Làm trống bảng chi tiết sản phẩm cũ
    //                 const chiTietTable = $('#chiTietSanPham');
    //                 chiTietTable.empty();

    //                 // Thêm các dòng mới vào bảng chi tiết sản phẩm
    //                 phieuNhap.chi_tiet_phieu_nhap.forEach((chiTiet, index) => {
    //                     const row = `
    //                         <tr>
    //                             <td>${index + 1}</td>
    //                             <td>${chiTiet.bien_the_san_pham.ten_san_pham || 'N/A'}</td>
    //                             <td>${chiTiet.so_luong}</td>
    //                             <td>${new Intl.NumberFormat('vi-VN').format(chiTiet.gia_nhap)} VND</td>
    //                         </tr>
    //                     `;
    //                     chiTietTable.append(row);
    //                 });
    //             } else {
    //                 alert('Không thể tải chi tiết phiếu nhập.');
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Lỗi khi tải chi tiết phiếu nhập:', error);
    //             alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
    //         });
    // });

    $(document).on('click', '.btn-show-detail', function () {
    var maPhieuNhap = $(this).data('id'); // Lấy mã phiếu nhập từ thuộc tính data-id
    if (!maPhieuNhap) {
        alert('Không tìm thấy mã phiếu nhập!');
        return;
    }

    fetch(`/chitietphieunhap/${maPhieuNhap}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const phieuNhap = data.data;

                // Điền thông tin vào modal
                $('#nhaCungCap').text(phieuNhap.nha_cung_cap?.ten_nha_cung_cap || 'N/A');
                $('#ngayNhap').text(phieuNhap.ngay_nhap);
                $('#tongSoLuong').text(phieuNhap.tong_so_luong);
                $('#tongGiaTri').text(new Intl.NumberFormat('vi-VN').format(phieuNhap.tong_gia_tri) + ' VND');
                $('#ghiChu').text(phieuNhap.ghi_chu || 'Không có ghi chú');

                // Xóa các dòng cũ và thêm mới
                const chiTietTable = $('#chiTietSanPham');
                chiTietTable.empty();

                phieuNhap.chi_tiet_phieu_nhap.forEach((chiTiet, index) => {
                    chiTietTable.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${chiTiet.bien_the_san_pham.san_pham?.ten_san_pham || 'N/A'}</td>
                            <td>${chiTiet.so_luong}</td>
                            <td>${new Intl.NumberFormat('vi-VN').format(chiTiet.gia_nhap)} VND</td>
                        </tr>
                    `);
                });

                // Hiển thị modal
                $('#chiTietPhieuNhapModal').modal('show');
            } else {
                alert('Không thể tải chi tiết phiếu nhập.');
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải chi tiết phiếu nhập:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
        });
});
</script>
    
@endsection
