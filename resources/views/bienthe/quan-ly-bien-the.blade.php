@extends('layout.index')
@section('title', 'Quản lý Biến Thể Sản Phẩm')
@section('css')
<style>
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
</style>
@endsection

@section('content')
<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <button class="btn btn-add btn-sm" 
                        title="Thêm" 
                        data-toggle="modal" 
                        data-target="#addVariantModal"
                        data-ma_san_pham="{{ request()->get('ma_san_pham') }}">
                    <i class="fas fa-plus"></i> Tạo mới biến thể
                </button>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm nhap-tu-file" title="Nhập" onclick="myFunction(this)">
                        <i class="fas fa-file-upload"></i> Tải từ file
                    </a>
                </div>
            </div>

            <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                    <tr>
                        <th width="10"><input type="checkbox" id="all"></th>
                        <th>Mã biến thể</th>
                        <th>Mã sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Loại da</th>
                        <th>Dung tích</th>
                        <th>Số lượng tồn kho</th>
                        <th>Giá bán</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bienTheList as $bienThe)
                    <tr>
                        <td><input type="checkbox" name="check1" value="{{ $bienThe->ma_bien_the }}"></td>
                        <td>{{ $bienThe->ma_bien_the }}</td>
                        <td>{{ $bienThe->ma_san_pham }}</td>
                        <td>{{ $bienThe->mau_sac }}</td>
                        <td>{{ $bienThe->loai_da }}</td>
                        <td>{{ $bienThe->dung_tich }}</td>
                        <td>{{ $bienThe->so_luong_ton_kho }}</td>
                        <td>{{ number_format($bienThe->gia_ban, 0, ',', '.') }} đ</td>
                        <td>
                            <button class="btn btn-primary btn-sm trash" type="button" title="Xóa"
                                onclick="deleteBienThe({{ $bienThe->ma_bien_the }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button 
                                class="btn btn-primary btn-sm edit" 
                                type="button" 
                                title="Sửa" 
                                data-toggle="modal"
                                data-target="#editModal"
                                data-ma_san_pham="{{$bienThe->ma_san_pham}}"
                                data-ma_bien_the="{{ $bienThe->ma_bien_the }}"
                                data-mau_sac="{{ $bienThe->mau_sac }}"
                                data-loai_da="{{ $bienThe->loai_da }}"
                                data-dung_tich="{{ $bienThe->dung_tich }}"
                                data-so_luong_ton_kho="{{ $bienThe->so_luong_ton_kho }}"
                                data-gia_ban="{{ $bienThe->gia_ban }}"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @include('bienthe.sua-bien-the')
                    @endforeach
                    @include('bienthe.them-bien-the')
                </tbody>
            </table>
           
            {{-- <div style="text-align: center" class="pagination-wrapper">
                {{ $bienTheList->links('vendor.pagination.bootstrap-4') }}
            </div> --}}
        </div>
    </div>
</div>



@endsection

@section('js')
<script>





    
function deleteBienThe(maBienThe) {
    if (confirm('Bạn có chắc chắn muốn xóa biến thể này không?')) {
            $.ajax({
                url: '/xoabienthesanpham', // Đường dẫn đến route xóa
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token để bảo vệ
                    ma_bien_the: maBienThe, // Dữ liệu cần gửi
                },
                success: function(response) {
                    if (response.success) {
                        alert('Biến thể đã được xóa thành công.');
                        location.reload(); // Reload lại trang sau khi xóa thành công
                    } else {
                        alert('Có lỗi xảy ra khi xóa biến thể.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert('Đã xảy ra lỗi: ' + error);
                }
            });
        }
    }

   


    
   

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
     $('#addVariantModal').on('show.bs.modal', function(event) {
        // Lấy phần tử kích hoạt modal (button)
        var button = $(event.relatedTarget); 
        var maSanPham = button.data('ma_san_pham'); // Lấy giá trị từ data-ma_san_pham

        // Gán giá trị vào input
        if (maSanPham) {
            $('#ma_san_pham_modal').val(maSanPham);
        } else {
            console.error('Mã sản phẩm không tồn tại!');
        }
    });

</script>

<script>
    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Nút bấm mở modal
        var maBienThe = button.data('ma_bien_the');
        var mauSac = button.data('mau_sac');
        var loaiDa = button.data('loai_da');
        var dungTich = button.data('dung_tich');
        var soLuongTonKho = button.data('so_luong_ton_kho');
        var giaBan = button.data('gia_ban');

        // Gán dữ liệu vào các trường trong modal
        $('#ma_bien_the').val(maBienThe);
        $('#mau_sac').val(mauSac);
        $('#loai_da').val(loaiDa);
        $('#dung_tich').val(dungTich);
        $('#so_luong_ton_kho').val(soLuongTonKho);
        $('#gia_ban').val(giaBan);
    });
</script>
@endsection
