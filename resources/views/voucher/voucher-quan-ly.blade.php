@extends('layout.index')
@section('title', 'Quản lý Voucher')

@section('css')



@endsection

@section('content')

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Thêm Voucher</div>
        <div class="card-body">
            <form id="add-voucher-form" method="POST" action="/themvoucher">
                @csrf
                <div class="row">
                    <!-- code Voucher -->
                    <div class="col-md-6 mb-3">
                        <label for="ten_voucher" class="form-label">Code Voucher</label>
                        <input type="text" id="code_voucher" name="code_voucher" class="form-control">
                    </div>
                    <!-- Loại giảm giá -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="loai_giam_gia" class="form-label">Loại giảm giá</label>
                            <select id="loai_giam_gia" name="loai_giam_gia" class="form-control">
                                <option value="Phần trăm">Phần trăm (%)</option>
                                <option value="Số tiền">Số tiền cố định (VNĐ)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Mức giảm giá -->
                    <div class="col-md-4 mb-3">
                        <label for="muc_giam_gia" class="form-label" id="muc_giam_gia_label">Giá trị giảm (%)</label>
                        <input 
                            type="number" 
                            id="muc_giam_gia" 
                            name="muc_giam_gia" 
                            class="form-control" 
                            placeholder="Nhập giá trị giảm (%)" 
                            required
                        >
                    </div>
                    <!-- Giá trị điều kiện -->
                    <div class="col-md-4 mb-3">
                        <label for="gia_tri_dieu_kien" class="form-label">Giá trị điều kiện</label>
                        <input type="number" id="gia_tri_dieu_kien" name="gia_tri_dieu_kien" class="form-control" required>
                    </div>
                    <!-- Giảm giá tối đa -->
                    <div class="col-md-4 mb-3">
                        <label for="giam_gia_toi_da" class="form-label">Giảm giá tối đa</label>
                        <input type="number" id="giam_gia_toi_da" name="giam_gia_toi_da" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <!-- Ngày bắt đầu -->
                    <div class="col-md-6 mb-3">
                        <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" id="ngay_bat_dau" name="ngay_bat_dau" class="form-control" required>
                    </div>
                    <!-- Ngày kết thúc -->
                    <div class="col-md-6 mb-3">
                        <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" id="ngay_ket_thuc" name="ngay_ket_thuc" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <!-- Số lượng -->
                    <div class="col-md-6 mb-3">
                        <label for="so_luong" class="form-label">Số lượng</label>
                        <input type="number" id="so_luong" name="so_luong" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Thêm Voucher</button>
            </form>
            
            
        </div>
    </div>
   
    <div class="card mb-4">
        <div class="card-header">Danh sách Voucher</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code Voucher</th>
                        {{-- <th>Điều kiện áp dụng</th> --}}
                        <th>Mức giảm giá </th>
                        <th>Giá trị điều kiện</th>
                        <th>Giảm giá tối đa</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $index => $voucher)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $voucher->code_voucher }}</td>
                            {{-- <td>{{ $voucher->dieu_kien_ap_dung }}</td> --}}
                            <td>
                                @if ($voucher->loai_giam_gia == 'Phần trăm')
                                    {{ number_format($voucher->muc_giam_gia) }}%
                                @else
                                    {{ number_format($voucher->muc_giam_gia, 0, '.', ',') }} đ
                                @endif
                            </td>
                            <td>{{ number_format($voucher->gia_tri_dieu_kien, 0, '.', ',') }} đ</td>
                           
                            <td>{{ number_format($voucher->giam_gia_toi_da, 0, '.', ',') }} đ</td>
                            <td>{{ $voucher->ngay_bat_dau }}</td>
                            <td>{{ $voucher->ngay_ket_thuc }}</td>
                            <td>
                                @if ($voucher->trang_thai)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Ngừng</span>
                                @endif
                            </td>
                            <td>
                                <form action="/xoavoucher/{{ $voucher->ma_voucher }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                                {{-- <button class="btn btn-warning btn-sm">Sửa</button>
                                <button class="btn btn-danger btn-sm">Xóa</button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.getElementById('loai_giam_gia').addEventListener('change', function () {
        const loaiGiamGia = this.value;
        const mucGiamGiaLabel = document.getElementById('muc_giam_gia_label');
        const mucGiamGiaInput = document.getElementById('muc_giam_gia');

        if (loaiGiamGia === "Phần trăm") {
            mucGiamGiaLabel.textContent = "Giá trị giảm (%)";
            mucGiamGiaInput.placeholder = "Nhập giá trị giảm (%)";
        } else if (loaiGiamGia === "Số tiền") {
            mucGiamGiaLabel.textContent = "Giá trị giảm (VNĐ)";
            mucGiamGiaInput.placeholder = "Nhập giá trị giảm (VNĐ)";
        }
    });
</script>

<script>
   

    // Hàm sửa voucher
    function editVoucher(id) {
        alert(`Sửa voucher với ID: ${id}`);
        // Logic sửa voucher
    }

    // Hàm xóa voucher
    function deleteVoucher(id) {
        if (confirm('Bạn có chắc chắn muốn xóa voucher này?')) {
            alert(`Xóa voucher với ID: ${id}`);
            // Logic xóa voucher
        }
    }

    // Load dữ liệu khi trang sẵn sàng
    document.addEventListener('DOMContentLoaded', loadVouchers);
</script>
@endsection
