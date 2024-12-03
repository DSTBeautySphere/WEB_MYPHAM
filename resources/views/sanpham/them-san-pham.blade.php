@extends('layout.index')
@section('title','Báo Cáo Doanh Thu')
@section('css')
<style>
    .Choicefile {
        display: block;
        background: #14142B;
        border: 1px solid #fff;
        color: #fff;
        width: 150px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        padding: 5px 0px;
        border-radius: 5px;
        font-weight: 500;
        align-items: center;
        justify-content: center;
    }

    .Choicefile:hover {
        text-decoration: none;
        color: white;
    }

    #uploadfile,
    .removeimg {
        display: none;
    }


    #thumbbox {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; 
    }

    .thumb-container {
        position: relative;
        display: inline-block;
    }

    .thumb-container img {
        height: 100px;
        width: 100px;
        object-fit: cover; 
    }

    .removeimg {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
        padding: 2px 5px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
    }


    .removeimg::before {
        box-sizing: border-box;
        content: '';
        border: 1px solid red;
        background: red;
        text-align: center;
        display: block;
        margin-top: 11px;
        transform: rotate(45deg);
    }

    .removeimg::after {
        content: '';
        background: red;
        border: 1px solid red;
        text-align: center;
        display: block;
        transform: rotate(-45deg);
        margin-top: -2px;
    }

    .group-box {
        border: 1px solid #ccc;
        padding: 10px;
       
        min-width: 200px;
        margin-bottom: 15px; 
    }

    .group-box h4 {
        margin-bottom: 10px;
    }

    .group-box .form-group {
        margin-bottom: 15px;
    }
    .group-mau label {
        display: inline-block;
        margin-right: 15px;
        width: 80px; 
        text-overflow: ellipsis; 
        overflow: hidden;
        white-space: nowrap; 
    }
    #group-mau
    {
        opacity: 0.5;
        pointer-events: none;
    }
    #group-loaida
    {
        opacity: 0.5;
        pointer-events: none;
    }
    #group-dungtich
    {
        opacity: 0.5;
        pointer-events: none;
    }
    /* css modal */

    .modal-header .close {
        color: #000;  /* Màu đen cho dấu X */
        opacity: 1;   /* Đảm bảo không bị mờ */
        font-size: 1.5rem; /* Điều chỉnh kích thước dấu X */
        z-index: 1050; /* Đảm bảo nút thoát hiển thị trên cùng */
    }

    .modal-header .close:hover {
        color: #dc3545;  /* Màu đỏ khi hover */
        opacity: 0.8;    /* Đổi độ mờ khi hover */
    }

    .modal-header {
        position: relative;  /* Đảm bảo modal header có thể chứa nút đóng */
    }

    .scrollable-table {
    max-height: 400px; /* Chiều cao tối đa cho bảng */
    overflow-y: auto; /* Bật thanh cuộn dọc */
    overflow-x: auto; /* Bật thanh cuộn ngang nếu cần */
    border: 1px solid #ddd; /* Tùy chọn: thêm viền */
    }

    .table {
        margin-bottom: 0; /* Loại bỏ khoảng cách dưới bảng nếu cần */
    }


</style>
@endsection

@section('content')
<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title">Tạo mới sản phẩm</h3>
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-folder-plus"></i> Thêm nhà cung cấp</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" data-toggle="modal" data-target="#modalLoaiSanPham"><i class="fas fa-folder-plus"></i> Thêm loại sản phẩm</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" data-toggle="modal" data-target="#addTuyChon"><i class="fas fa-folder-plus"></i> Thêm tùy chọn</a>
                </div>
                
            </div>
            <form class="row" action="/themsanpham" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-md-4">
                    <label class="control-label">Tên Sản Phẩm</label>
                    <input class="form-control" type="text" placeholder="" name="ten_san_pham">
                </div>
            
                <div class="form-group col-md-4">
                    <label for="danhmuc" class="control-label">Loại Sản Phẩm</label>
                    <select class="form-control" id="danhmuc" name="ma_loai_san_pham">
                        <option>--Chọn Loại Sản Phẩm--</option>
                        @foreach ($loaiSanPham as $item)
                        <option value="{{ $item->ma_loai_san_pham }}">{{ $item->ten_loai_san_pham }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="nhacungcap" class="control-label">Nhà Cung Cấp</label>
                    <select class="form-control" id="nhacungcap" name="ma_nha_cung_cap">
                        <option>--Chọn Nhà Cung Cấp--</option>
                        @foreach ($nhaCungCap as $item)
                        <option value="{{ $item->ma_nha_cung_cap }}">{{ $item->ten_nha_cung_cap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <h5 class="control-label">Màu Sắc</h5>
                    <div id="group-mau">
                        @foreach($tuyChon as $item)
                            @if($item->ma_nhom_tuy_chon == 1)
                                <label class="control-label" style="width: 90px; display: inline-block; text-overflow: ellipsis; overflow: hidden; white-space:nowrap">
                                    <input type="checkbox" name="mau[]" value="{{ $item->ten_tuy_chon }}" 
                                    style="background-color: {{ $item->ma_tuy_chon }};">
                                    {{ $item->ten_tuy_chon }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <h5 class="control-label">Dung Tích</h5>
                    <div id="group-dungtich">
                        @foreach($tuyChon as $item)
                            @if($item->ma_nhom_tuy_chon == 2)
                                <label class="control-label" style="width: 90px; display: inline-block; text-overflow: ellipsis; overflow: hidden; white-space:nowrap">
                                    <input type="checkbox" name="dungtich[]" value="{{ $item->ten_tuy_chon }}" 
                                    style="background-color: {{ $item->ma_tuy_chon }};">
                                    {{ $item->ten_tuy_chon }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <h5 class="control-label">Loại Da</h5>
                    <div id="group-loaida" @disabled(false)>
                        @foreach($tuyChon as $item)
                            @if($item->ma_nhom_tuy_chon == 3)
                                <label class="control-label" style="width: 90px; display: inline-block; text-overflow: ellipsis; overflow: hidden; white-space:nowrap">
                                    <input type="checkbox" name="loaida[]" value="{{ $item->ten_tuy_chon }}" 
                                    style="background-color: {{ $item->ma_tuy_chon }};">
                                    {{ $item->ten_tuy_chon }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group col-md-12" id="sanPhamMau">
                    <!-- HTML -->
                    <table id="sanPhamMauGrid" class="table">
                        <thead>
                            <tr>
                                <th>Màu Sắc</th>
                                <th>Loại Da</th>
                                <th>Dung Tích</th>
                               
                                <th>Số Lượng Tồn Kho</th>
                                <th>Giá Bán</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Các dòng sản phẩm mẫu sẽ được thêm vào đây -->
                    
                        </tbody>
                    </table>
    
                    </div>
                    <div style="margin-top: -20px" class="form-group col-md-12"><button class="btn btn-info" type="button" id="layMau">Tạo Mẫu</button></div>
                    
                <div class="form-group col-md-12">
                    <label class="control-label">Ảnh sản phẩm</label>
                    <div id="myfileupload">
                        <input type="file" id="uploadfile" name="images[]"  multiple/>
                        {{-- onchange="readURL(this);" --}}
                    </div>
                    <div id="thumbbox">
                        <img height="450" width="400" alt="Thumb image" id="thumbimage" style="display: none" />
                        <a class="removeimg" href="javascript:"></a>
                    </div>
                    <div id="boxchoice">
                        <a href="javascript:" class="Choicefile"><i class="fas fa-cloud-upload-alt"></i> Chọn ảnh</a>
                        <p style="clear:both"></p>
                    </div>
                </div>
                {{-- mô tả --}}
                <div class="form-group col-md-12">
                    <label class="control-label">Mô tả sản phẩm</label>
                    <table class="table" id="dgv_mota">
                        <thead>
                            <tr>
                                {{-- <th>Code</th> --}}
                                <th>Tên Mô Tả</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <input class="form-control" type="text" name="txt_tenmota" id="txt_tenmota" placeholder="Nhập Mô Tả">
                    <button style="margin-top: 10px" class="btn btn-success" id="btn_themmota">Thêm Mô Tả</button>
                </div>
                
                <div class="form-group col-md-12">
                    <label class="control-label">Mô tả sản phẩm</label>
                    <label class="control-label" id="lb_not">Vui Lòng Chọn Mô Tả Trước Khi Thêm Chi Tiết Mô Tả!</label>
                    <table class="table" id="dgv_chitietmota">
                        <thead>
                            <tr>
                                <th>Tiêu Đề</th>
                                <th>Nội Dung</th>
                                <th>Thao Tác</th>
                            </tr>
                            <tbody>
                                
                            </tbody>
                        </thead>
                    </table>
                    <input class="form-control" name="txt_tieude" id="txt_tieude" placeholder="Nhập Tiêu Đề"></input>
                    <textarea class="form-control" name="txt_noidung" id="txt_noidung" placeholder="Nhập Nội Dung"></textarea>
                    <button style="margin-top: 10px" class="btn btn-success" id="btn_themctmota" >Thêm Chi Tiết Mô Tả</button>
                </div>

                <div class="form-group col-md-12 d-flex justify-content-center" style="align-items: center;">
                    <button style="width: 200px;" class="btn btn-save mx-2 px-4 py-2" type="submit" id="themSP">Thêm</button>
                    <a style="width: 200px;" class="btn btn-cancel mx-2 px-4 py-2" href="table-data-product.html">Hủy bỏ</a>
                </div>
                
                
            </form>
        </div>
    </div>
</div>

{{-- Modal 1 mẫu đây có dì coi thamm khảo --}}
{{-- nha cung cap --}}
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Quản lý Nhà Cung Cấp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form id="formNhaCungCap" method="POST" action="/themnhacungcap">
                    <div class="form-group col-md-12">
                        <label class="control-label">Nhập tên nhà cung cấp mới</label>
                        <input class="form-control" type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Nhập địa chỉ</label>
                        <input class="form-control" type="text" id="dia_chi" name="dia_chi" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Nhập số điện thoại</label>
                        <input class="form-control" type="text" id="so_dien_thoai" name="so_dien_thoai" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Nhập email</label>
                        <input class="form-control" type="email" id="email" name="email" required>
                    </div>
                    <br>
                    <button class="btn btn-save" type="button" id="saveNhaCungCap">Lưu lại</button>
                    <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
                    <br><br>
                </form>

                <h5>Danh sách nhà cung cấp</h5>
                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên nhà cung cấp</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nhaCungCap as $item)
                                <tr>
                                    <td>{{ $item->ma_nha_cung_cap }}</td>
                                    <td>{{ $item->ten_nha_cung_cap }}</td>
                                    <td>{{ $item->dia_chi }}</td>
                                    <td>{{ $item->so_dien_thoai }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" 
                                                    onclick="openEditModal(this)"
                                                    data-ma-nha-cung-cap="{{ $item->ma_nha_cung_cap }}"
                                                    data-ten-nha-cung-cap="{{ $item->ten_nha_cung_cap }}"
                                                    data-dia-chi="{{ $item->dia_chi }}"
                                                    data-so-dien-thoai="{{ $item->so_dien_thoai }}"
                                                    data-email="{{ $item->email }}">
                                                Sửa
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteNhaCungCap({{ $item->ma_nha_cung_cap }})">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditNCC" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Sửa Nhà Cung Cấp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form id="formEditNhaCungCap" method="POST" action="/suanhacungcap">
                    <input type="hidden" id="edit_ma_nha_cung_cap" name="ma_nha_cung_cap">
                    <div class="form-group">
                        <label for="edit_ten_nha_cung_cap">Tên nhà cung cấp</label>
                        <input class="form-control" type="text" id="edit_ten_nha_cung_cap" name="ten_nha_cung_cap" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_dia_chi">Địa chỉ</label>
                        <input class="form-control" type="text" id="edit_dia_chi" name="dia_chi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_so_dien_thoai">Số điện thoại</label>
                        <input class="form-control" type="text" id="edit_so_dien_thoai" name="so_dien_thoai" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input class="form-control" type="email" id="edit_email" name="email" required>
                    </div>
                    <br>
                    <button class="btn btn-save" type="submit">Lưu lại</button>
                    <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- tuy chon --}}

<div class="modal fade" id="addTuyChon" tabindex="-1" role="dialog" aria-labelledby="addTuyChonLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTuyChonLabel">Thêm Tùy Chọn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTuyChon" method="POST" action="/themtuychon">
                    @csrf 
                    <div class="form-group">
                        <label for="ten_tuy_chon">Tên Tùy Chọn</label>
                        <input type="text" class="form-control" id="ten_tuy_chon" name="ten_tuy_chon" required>
                    </div>
                    <div class="form-group">
                        <label for="ma_nhom_tuy_chon">Mã Nhóm Tùy Chọn</label>
                        <select class="form-control" id="ma_nhom_tuy_chon" name="ma_nhom_tuy_chon" required>
                            <option value="">Chọn Nhóm Tùy Chọn</option>
                            @foreach ($nhomTuyChon as $nhom)
                                <option value="{{ $nhom->ma_nhom_tuy_chon }}">{{ $nhom->ten_nhom_tuy_chon }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hiển thị danh sách tùy chọn dưới dạng bảng -->
                    <div class="form-group">
                        <label for="danh_sach_tuy_chon">Danh Sách Tùy Chọn</label>
                        <table class="table table-bordered" id="tableTuyChon">
                            <thead>
                                <tr>
                                   
                                    <th>Mã nhóm tùy chọn</th>
                                    <th>Tên Tùy Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Các tùy chọn sẽ được thêm vào đây qua AJAX -->
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="saveTuyChon">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- loai san pham --}}
<div class="modal fade" id="modalLoaiSanPham" tabindex="-1" role="dialog" aria-labelledby="modalLoaiSanPhamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLoaiSanPhamLabel">Quản Lý Loại Sản Phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Danh sách loại sản phẩm dưới dạng bảng -->
                <div class="table-responsive scrollable-table">
                    <table class="table table-bordered" id="tableLoaiSanPham">
                        <thead>
                            <tr>
                                <th>Mã Loại Sản Phẩm</th>
                                <th>Tên Loại Sản Phẩm</th>
                                <th>Mô Tả</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loaiSanPham as $loai)
                                <tr>
                                    <td>{{ $loai->ma_loai_san_pham }}</td>
                                    <td>{{ $loai->ten_loai_san_pham }}</td>
                                    <td>{{ $loai->mo_ta }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm btn-edit" 
                                                data-id="{{ $loai->ma_loai_san_pham }}" 
                                                data-ten="{{ $loai->ten_loai_san_pham }}" 
                                                data-mo-ta="{{ $loai->mo_ta }}" 
                                                data-dong-san-pham="{{ $loai->ma_dong_san_pham }}"
                                                data-nhom-tuy-chon="{{ json_encode($loai->nhom_tuy_chon->map(fn($nhom) => $nhom->ma_nhom_tuy_chon)) }}">
                                            Sửa
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $loai->ma_loai_san_pham }}">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Form Thêm/Sửa Loại Sản Phẩm -->
                <form id="formLoaiSanPham" method="POST" action="/themloaisanpham">
                    @csrf
                    <input type="hidden" id="ma_loai_san_pham" name="ma_loai_san_pham">
                    <div class="form-group">
                        <label for="ten_loai_san_pham">Tên Loại Sản Phẩm</label>
                        <input type="text" class="form-control" id="ten_loai_san_pham" name="ten_loai_san_pham" required>
                    </div>
                    <div class="form-group">
                        <label for="mo_ta">Mô Tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ma_dong_san_pham">Dòng Sản Phẩm</label>
                        <select class="form-control" id="ma_dong_san_pham" name="ma_dong_san_pham" required>
                            <option value="">Chọn Dòng Sản Phẩm</option>
                            @foreach ($dongSanPham as $dong)
                                <option value="{{ $dong->ma_dong_san_pham }}">{{ $dong->ten_dong_san_pham }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Chọn Nhóm Tùy Chọn</label>
                        <div class="checkbox-group">
                            @foreach ($nhomTuyChon as $nhom)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="nhom_tuy_chon[]" value="{{ $nhom->ma_nhom_tuy_chon }}" id="nhomTuyChon{{ $nhom->ma_nhom_tuy_chon }}">
                                    <label class="form-check-label" for="nhomTuyChon{{ $nhom->ma_nhom_tuy_chon }}">{{ $nhom->ten_nhom_tuy_chon }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveLoaiSanPham">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>



{{-- -- --}}
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // CKEDITOR.replace('txt_tenmota', {
    //     toolbar: [
    //         { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
    //         { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
    //         { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
    //         { name: 'colors', items: ['TextColor', 'BGColor'] },
    //         { name: 'insert', items: ['Image', 'Table'] }
    //     ],
    //     height: 70
    // });
    // CKEDITOR.replace('txt_tieude', {
    //     toolbar: [
    //         { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
    //         { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
    //         { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
    //         { name: 'colors', items: ['TextColor', 'BGColor'] },
    //         { name: 'insert', items: ['Image', 'Table'] }
    //     ],
    //     height: 70
    // });
    CKEDITOR.replace('txt_noidung', {
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'insert', items: ['Image', 'Table'] }
        ],
        height: 300
    });
</script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>


let sanPhamMau = [];
    $('#danhmuc').change(function() {
        $('input[name="mau[]"]').prop("checked", false);
        $('input[name="dungtich[]"]').prop("checked", false);
        $('input[name="loaida[]"]').prop("checked", false);
        var maLoaiSanPham = $(this).val();

        $.ajax({
            url: '/laynhomtuychontheoloai',
            type: 'GET',
            data: {
                ma_loai_san_pham: maLoaiSanPham,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                var hasMauSac = false;
                var hasDungTich = false;
                var hasLoaiDa = false;

                response.forEach(function(nhom) {
                    if (nhom.ma_nhom_tuy_chon == 1) hasMauSac = true;
                    if (nhom.ma_nhom_tuy_chon == 2) hasDungTich = true;
                    if (nhom.ma_nhom_tuy_chon == 3) hasLoaiDa = true;

                    var groupName = nhom.ten_nhom_tuy_chon;
                    var groupHTML = '<h4>' + groupName + '</h4>';

                    if (nhom.tuy_chon.length === 0) {
                        // Ẩn nhóm khỏi DOM nếu không có tùy chọn nào
                        $('#group-' + groupName.toLowerCase().replace(" ", "-")).hide();
                    } else {
                        // Nếu có tùy chọn, hiển thị bình thường
                        $('#group-' + groupName.toLowerCase().replace(" ", "-")).show();
                        nhom.tuy_chon.forEach(function(tuyChon) {
                            groupHTML += `
                                <label>
                                    <input type="checkbox" name="tuy_chon[]" value="${tuyChon.ma_tuy_chon}" 
                                        class="tuy-chon" data-nhom="${groupName}" 
                                        ${tuyChon.is_choosen ? '' : 'disabled'}> 
                                    ${tuyChon.ten_tuy_chon}
                                </label><br>
                            `;
                        });
                    }

                    $('#group-' + groupName.toLowerCase().replace(" ", "-")).html(groupHTML);
                });
                if (!hasMauSac) {
                    $('#group-mau').css({'opacity': '0.5', 'pointer-events': 'none'}); // Làm mờ nhóm màu sắc
                } else {
                    $('#group-mau').css({'opacity': '1', 'pointer-events': 'auto'}); // Hiển thị nhóm màu sắc
                }

                if (!hasDungTich) {
                    $('#group-dungtich').css({'opacity': '0.5', 'pointer-events': 'none'}); // Làm mờ nhóm dung tích
                } else {
                    $('#group-dungtich').css({'opacity': '1', 'pointer-events': 'auto'}); // Hiển thị nhóm dung tích
                }

                if (!hasLoaiDa) {
                    $('#group-loaida').css({'opacity': '0.5', 'pointer-events': 'none'}); // Làm mờ nhóm loại da
                } else {
                    $('#group-loaida').css({'opacity': '1', 'pointer-events': 'auto'}); // Hiển thị nhóm loại da
                }
                // Ẩn hoặc hiển thị các nhóm dựa trên biến kiểm tra
                $('#group-mau').toggle(hasMauSac);
                $('#group-dungtich').toggle(hasDungTich);
                $('#group-loaida').toggle(hasLoaiDa);
            }
        });
    });

$('#layMau').click(function() {
    var mau = [];
    var dungtich = [];
    var loaida = [];

    sanPhamMau = [];

    $('input[name="mau[]"]:checked').each(function() {
        mau.push($(this).val());
    });

  
    $('input[name="dungtich[]"]:checked').each(function() {
        dungtich.push($(this).val());
    });


    $('input[name="loaida[]"]:checked').each(function() {
        loaida.push($(this).val());
    });


    if (mau.length > 0 || dungtich.length > 0 || loaida.length > 0) {
        
        if (mau.length > 0 && dungtich.length > 0 && loaida.length > 0) {
            $.each(mau, function(i, mauItem) {
                $.each(dungtich, function(j, dungTichItem) {
                    $.each(loaida, function(k, loaiDaItem) {
                        sanPhamMau.push({
                            'mau_sac': mauItem || "",
                            'loai_da': loaiDaItem ||"",
                            'dung_tich': dungTichItem || "",  // Đảm bảo dung_tich là chuỗi hoặc null
                            'so_luong_ton_kho': 1,
                            'gia_ban': 10000
                        });
                    });
                });
            });
        }
        
        else if (mau.length > 0 && dungtich.length > 0 && loaida.length === 0) {
            $.each(mau, function(i, mauItem) {
                $.each(dungtich, function(j, dungTichItem) {
                    sanPhamMau.push({
                        'mau_sac': mauItem,
                        'loai_da': "",
                        'dung_tich': dungTichItem,
                       
                        'so_luong_ton_kho': 1,
                        'gia_ban': 10000
                    });
                });
            });
        }
       
        else if (mau.length > 0 && dungtich.length === 0 && loaida.length > 0) {
            $.each(mau, function(i, mauItem) {
                $.each(loaida, function(j, loaiDaItem) {
                    sanPhamMau.push({
                        'mau_sac': mauItem,
                        'loai_da': loaiDaItem,
                        'dung_tich': "",
                       
                        'so_luong_ton_kho': 1,
                        'gia_ban': 100000
                    });
                });
            });
        }
        
        else if (mau.length === 0 && dungtich.length > 0 && loaida.length > 0) {
            $.each(dungtich, function(i, dungTichItem) {
                $.each(loaida, function(j, loaiDaItem) {
                    sanPhamMau.push({
                        'mau_sac': "",
                        'loai_da': loaiDaItem,
                        'dung_tich': dungTichItem,
                       
                        'so_luong_ton_kho': 1,
                        'gia_ban': 100000
                    });
                });
            });
        }
      
        else if (mau.length > 0 && dungtich.length === 0 && loaida.length === 0) {
            $.each(mau, function(i, mauItem) {
                sanPhamMau.push({
                    'mau_sac': mauItem,
                    'loai_da': "",
                    'dung_tich': "",                
                    'so_luong_ton_kho': 1,
                    'gia_ban': 10000
                });
            });
        }
   
        else if (mau.length === 0 && dungtich.length > 0 && loaida.length === 0) {
            $.each(dungtich, function(i, dungTichItem) {
                sanPhamMau.push({
                    'mau_sac': "",
                    'loai_da': "",
                    'dung_tich': dungTichItem,
                    
                    'so_luong_ton_kho': 1,
                    'gia_ban': 10000
                });
            });
        }
     
        else if (mau.length === 0 && dungtich.length === 0 && loaida.length > 0) {
            $.each(loaida, function(i, loaiDaItem) {
                sanPhamMau.push({
                    'mau_sac': "",
                    'loai_da': loaiDaItem,
                    'dung_tich': "",
                    
                    'so_luong_ton_kho': 1,
                    'gia_ban': 10000
                });
            });
        }
    }

    var sanPhamMauHTML = '';
    sanPhamMau.forEach(function(sp) {
        sanPhamMauHTML += '<tr>' +
            '<td contenteditable="true">' + sp.mau_sac + '</td>' +
            '<td contenteditable="true">' + sp.loai_da + '</td>' +
            '<td contenteditable="true">' + sp.dung_tich + '</td>' +
            
            '<td contenteditable="true">' + sp.so_luong_ton_kho + '</td>' +
            '<td contenteditable="true">' + sp.gia_ban + '</td>' +
            '<td><button class="delete-row btn btn-danger">Xóa</button></td>' +
        '</tr>';
    });

    $('#sanPhamMauGrid tbody').prepend(sanPhamMauHTML);

    var lastRow = $('#sanPhamMauGrid tbody tr:last');
    var lastRowCells = lastRow.find('td');

    if (lastRowCells.text().trim() === "") {
        return;
    }

    var newRow = '<tr>' +
        '<td contenteditable="true"></td>' +
        '<td contenteditable="true"></td>' +
        '<td contenteditable="true"></td>' +
        '<td contenteditable="true"></td>' +
        '<td contenteditable="true"></td>' +
        '<td><button class="delete-row btn btn-danger">Xóa</button></td>' +
    '</tr>';
    $('#sanPhamMauGrid tbody').append(newRow);

    $(document).on('click', '.delete-row', function() {
        var rowIndex = $(this).closest('tr').data('index');
        
        $(this).closest('tr').remove();
        
        sanPhamMau.splice(rowIndex, 1);
      
        $('#sanPhamMauGrid tbody tr').each(function(index) {
            $(this).attr('data-index', index);
        });
    });

   
    $(document).on('blur', '#sanPhamMauGrid tbody td[contenteditable="true"]', function() {
    var rowIndex = $(this).closest('tr').index();
    var rowCells = $(this).closest('tr').find('td');

    // Lấy giá trị các ô
    var mauSac = rowCells.eq(0).text().trim();  
    var loaiDa = rowCells.eq(1).text().trim(); 
    var dungTich = rowCells.eq(2).text().trim();  // Cho phép Dung Tích để trống

    var soLuongTonKho = rowCells.eq(3).text().trim(); 
    var giaBan = rowCells.eq(4).text().trim();

    // Cập nhật mảng sanPhamMau
    if (sanPhamMau[rowIndex]) {
        sanPhamMau[rowIndex] = {
            'mau_sac': mauSac || null, // Để trống nếu không có giá trị
            'loai_da': loaiDa || null,  // Để trống nếu không có giá trị
            'dung_tich': dungTich || null, // Cho phép để trống
            'so_luong_ton_kho': soLuongTonKho || 0,  // Để trống nếu không có giá trị
            'gia_ban': giaBan || 0  // Để trống nếu không có giá trị
        };
    }

    console.log(sanPhamMau); 
});


$(document).on('blur', '#sanPhamMauGrid tbody td[contenteditable="true"]', function() 
{

    var lastRow = $('#sanPhamMauGrid tbody tr:last');
    var lastRowCells = lastRow.find('td');

    
    var isRowEmpty = true;
    lastRowCells.each(function() {
        if ($(this).text().trim() !== '' && !$(this).hasClass('delete-row')) {
            isRowEmpty = false;
            return false; 
        }
    });

    
    var priceCell = lastRowCells.eq(4);  
    var quantityCell = lastRowCells.eq(3);  
    var firstThreeCellsFilled = false;
    lastRowCells.slice(0, 3).each(function() {
        if ($(this).text().trim() !== '') {
            firstThreeCellsFilled = true;
            return false;
        }
    });

    
    if (isRowEmpty || (priceCell.text().trim() !== '' && quantityCell.text().trim() !== '' && firstThreeCellsFilled)) {
        var newRow = '<tr>' +
            '<td contenteditable="true"></td>' + 
            '<td contenteditable="true"></td>' +  
            '<td contenteditable="true"></td>' + 
            '<td contenteditable="true"></td>' + 
            '<td contenteditable="true"></td>' + 
            '<td><button class="delete-row btn btn-danger">Xóa</button></td>' +  
        '</tr>';
        $('#sanPhamMauGrid tbody').append(newRow);
    }
});


});

$(document).ready(function() {
    $('#uploadfile').on('change', function(e) {
        $('#thumbbox').empty(); // Xóa các ảnh đã hiển thị trước đó

        var files = e.target.files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                var file = files[i];

                if (file.type.startsWith("image/")) {
                    var reader = new FileReader();
                    reader.onload = (function(file) {
                        return function(e) {
                            var thumbContainer = $('<div class="thumb-container"></div>'); // Tạo container cho từng ảnh
                            var thumbImage = $('<img>', {
                                src: e.target.result,
                                alt: "Thumb image",
                                class: "thumbimage",
                                height: 100,
                                width: 100
                            });
                            var removeBtn = $('<a href="javascript:" class="removeimg">Xóa</a>');

                            removeBtn.on('click', function() {
                                thumbContainer.remove(); // Xóa container của ảnh
                            });

                            thumbContainer.append(thumbImage).append(removeBtn);
                            $('#thumbbox').append(thumbContainer); // Thêm container ảnh vào `thumbbox`
                        };
                    })(file);

                    reader.readAsDataURL(file); // Đọc và hiển thị ảnh
                }
            }
        }
    });
});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#thumbimage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('click', '.Choicefile', function() {
    $('#uploadfile').click();
});



let ds_mota = [];
// Mảng lưu chi tiết mô tả
let ds_ctmota = [];
let temp_mota = null;

// Hàm thêm mô tả
document.getElementById("btn_themmota").addEventListener("click", function(event) {
    event.preventDefault();  // Đảm bảo không bị reload trang
    console.log("Bắt đầu lấy giá trị...");
    
    const tenMoTa = document.getElementById('txt_tenmota').value;
    console.log("Giá trị tenMoTa:", tenMoTa);

    if (tenMoTa) {
        const maMoTa = Math.floor(Math.random() * 100000);  // Tạo mã mô tả ngẫu nhiên
        const mota = { ma_mo_ta: maMoTa, ten_mo_ta: tenMoTa };
        ds_mota.push(mota);
        Load_TableMoTa();
        document.getElementById("txt_tenmota").value = '';  // Clear input
    } else {
        alert("Vui lòng nhập tên mô tả!");
    }
});


// Hàm thêm chi tiết mô tả
document.getElementById("btn_themctmota").addEventListener("click", function() {
    event.preventDefault(); 
    if (temp_mota !== null) {
        
        const tieuDe = document.getElementById('txt_tieude').value;

       
        const noiDung = CKEDITOR.instances['txt_noidung'].getData();
        if (tieuDe && noiDung) {
            const chiTiet = { ma_mo_ta: temp_mota, tieu_de: tieuDe, noi_dung: noiDung };
            ds_ctmota.push(chiTiet);
            Load_TableMoTaChiTiet(temp_mota);
            document.getElementById("txt_tieude").value = ''; // Clear input
            document.getElementById("txt_noidung").value = ''; // Clear textarea
        } else {
            alert("Vui lòng nhập tiêu đề và nội dung chi tiết!");
        }
    } else {
        alert("Vui lòng chọn mô tả trước khi thêm chi tiết!");
    }
});

// Load bảng mô tả
function Load_TableMoTa() {
    const tbody = document.getElementById("dgv_mota").getElementsByTagName("tbody")[0];
    tbody.innerHTML = ''; // Clear previous data
    
    ds_mota.forEach(function(moTa) {
        const row = tbody.insertRow();
        // row.insertCell(0).innerText = moTa.ma_mo_ta;
        row.insertCell(0).innerText = moTa.ten_mo_ta;
        
        const btnSelect = document.createElement("button");
        btnSelect.innerText = "Chọn";
        btnSelect.className = "btn btn-success btn-sm";

        btnSelect.addEventListener("click", function(event) {
            event.preventDefault();
            temp_mota = moTa.ma_mo_ta;
            
            document.getElementById("lb_not").innerText = `Nhập Thông Tin Chi Tiết Cho [${moTa.ten_mo_ta}]`;
            Load_TableMoTaChiTiet(temp_mota);
        });

        const btnEdit = document.createElement("button");
        btnEdit.innerText = "Sửa";
        btnEdit.className = "btn btn-warning btn-sm";

        btnEdit.addEventListener("click", function(event) {
            event.preventDefault();
            const newName = prompt("Nhập tên mới cho mô tả:", moTa.ten_mo_ta);
            if (newName) {
                moTa.ten_mo_ta = newName;
                Load_TableMoTa();
            }
        });

        const btnDelete = document.createElement("button");
        btnDelete.innerText = "Xóa";
        btnDelete.classList.add("delete");
        btnDelete.className = "btn btn-danger btn-sm";
        btnDelete.addEventListener("click", function(event) {
            event.preventDefault();
            const index = ds_mota.indexOf(moTa);
            if (index > -1) {
                ds_mota.splice(index, 1);
                Load_TableMoTa();
            }
        });

        const cellBtn = row.insertCell(1);
        cellBtn.appendChild(btnSelect);
        cellBtn.appendChild(btnEdit);
        cellBtn.appendChild(btnDelete);
    });
}

// Load bảng chi tiết mô tả
function Load_TableMoTaChiTiet(ma_mo_ta) {
    const tbody = document.getElementById("dgv_chitietmota").getElementsByTagName("tbody")[0];
    tbody.innerHTML = ''; // Clear previous data
    
    const chiTietList = ds_ctmota.filter(ct => ct.ma_mo_ta === ma_mo_ta);
    chiTietList.forEach(function(ct) {
        const row = tbody.insertRow();
        row.insertCell(0).innerText = ct.tieu_de;
        row.insertCell(1).innerText = ct.noi_dung;
        
        const btnEdit = document.createElement("button");
        btnEdit.innerText = "Sửa";
        btnEdit.className = "btn btn-warning btn-sm";
        btnEdit.addEventListener("click", function(event) {
            event.preventDefault();
            const newTieuDe = prompt("Nhập tiêu đề mới:", ct.tieu_de);
            const newNoiDung = prompt("Nhập nội dung mới:", ct.noi_dung);
            if (newTieuDe && newNoiDung) {
                ct.tieu_de = newTieuDe;
                ct.noi_dung = newNoiDung;
                Load_TableMoTaChiTiet(ma_mo_ta);
            }
        });

        const btnDelete = document.createElement("button");
        btnDelete.innerText = "Xóa";
        btnDelete.classList.add("delete");
        btnDelete.className = "btn btn-danger btn-sm";
        btnDelete.addEventListener("click", function(event) {
            event.preventDefault();
            const index = ds_ctmota.indexOf(ct);
            if (index > -1) {
                ds_ctmota.splice(index, 1);
                Load_TableMoTaChiTiet(ma_mo_ta);
            }
        });

        const cellBtn = row.insertCell(2);
        cellBtn.appendChild(btnEdit);
        cellBtn.appendChild(btnDelete);
    });
}

$('#themSP').on('click', function(event) {
    event.preventDefault();

    let tenSanPham = $('input[name="ten_san_pham"]').val();
    let maLoaiSanPham = $('#danhmuc').val();
    let maNhaCungCap = $('#nhacungcap').val();
    sanPhamMau = sanPhamMau.map(item => ({
        ...item,
        dung_tich: String(item.dung_tich)  // Chuyển dung_tich thành chuỗi
    }));

    console.log("SanPhamMau:", sanPhamMau);

    if (sanPhamMau.length === 0) {
        alert("Vui lòng chọn đầy đủ thông tin về Màu sắc, Loại da và Dung tích.");
        return;
    }

    var files = $('#uploadfile')[0].files;
    var formData = new FormData();

    for (var i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }

    formData.append('ten_san_pham', tenSanPham);
    formData.append('ma_loai_san_pham', maLoaiSanPham);
    formData.append('ma_nha_cung_cap', maNhaCungCap);
    formData.append('bien_the', JSON.stringify(sanPhamMau));
    formData.append('mo_ta', JSON.stringify(ds_mota));
    formData.append('chi_tiet_mo_ta', JSON.stringify(ds_ctmota));
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: 'themsanpham', // Đảm bảo URL này trỏ đúng đến controller xử lý
        type: 'POST',
        data: formData,
        contentType: false, // Không set content-type vì FormData sẽ tự động set
        processData: false, // Không chuyển dữ liệu thành một chuỗi query
        success: function(response) {
            alert(response.message);
            window.location.href = response.redirect_url;
        },
        error: function(xhr, status, error) {
            var statusCode = xhr.status;
            var errorMessage = xhr.responseText || "Không có thông báo lỗi";
            alert('Có lỗi xảy ra: \nMã trạng thái: ' + statusCode + '\nThông báo lỗi: ' + errorMessage);
        }
    });
});



</script>

<script>
    $('#btnAddNhaCungCap').click(function() {
        $('#exampleModalCenter').modal('show');
    });

 
</script>


<script>
    // Thêm nhà cung cấp mới
 $('#saveNhaCungCap').click(function() {
     const ten_nha_cung_cap = $('#ten_nha_cung_cap').val();
     const dia_chi = $('#dia_chi').val();
     const so_dien_thoai = $('#so_dien_thoai').val();
     const email = $('#email').val();
 
     // Kiểm tra xem tên nhà cung cấp và email có được nhập không
     if (!ten_nha_cung_cap || !email) {
         alert('Vui lòng nhập tên nhà cung cấp và email');
         return;
     }
 
     $.ajax({
         url: 'themnhacungcap',
         type: 'POST',
         data: {
             "_token": "{{ csrf_token() }}",
             ten_nha_cung_cap,
             dia_chi,
             so_dien_thoai,
             email
         },
         success: function(response) {
             alert(response.message);
             location.reload(); // Tải lại trang để cập nhật danh sách nhà cung cấp
         },
         error: function(xhr) {
             // Hiển thị thông báo lỗi chi tiết từ phía máy chủ
             if (xhr.status === 422) {
                 const errors = xhr.responseJSON.errors;
                 let errorMessage = 'Lỗi khi thêm nhà cung cấp:\n';
                 for (const field in errors) {
                     errorMessage += `${errors[field][0]}\n`;
                 }
                 alert(errorMessage);
             } else {
                 alert('Thêm nhà cung cấp thất bại');
             }
         }
     });
 });
 
 
     // Xóa nhà cung cấp
     // function deleteNhaCungCap(id) {
     //     if (confirm('Bạn có chắc muốn xóa nhà cung cấp này?')) {
     //         $.ajax({
     //             url: `/xoa-nha-cung-cap/${id}`,
     //             type: 'DELETE',
     //             data: {
     //                 "_token": "{{ csrf_token() }}",
     //             },
     //             success: function(response) {
     //                 alert(response.message);
     //                 $(`#ncc_${id}`).remove(); // Xóa dòng tương ứng trong bảng
     //             },
     //             error: function(xhr) {
     //                 alert('Xóa nhà cung cấp thất bại');
     //             }
     //         });
     //     }
     // }
 
     // // Sửa nhà cung cấp
    // Mở modal và điền dữ liệu nhà cung cấp vào form sửa
    function openEditModal(button) {
        // Lấy dữ liệu từ các data-* attributes
        var ma_nha_cung_cap = $(button).data('ma-nha-cung-cap');
        var ten_nha_cung_cap = $(button).data('ten-nha-cung-cap');
        var dia_chi = $(button).data('dia-chi');
        var so_dien_thoai = $(button).data('so-dien-thoai');
        var email = $(button).data('email');
        
        // Gán giá trị vào các input trong form sửa
        $('#edit_ma_nha_cung_cap').val(ma_nha_cung_cap);
        $('#edit_ten_nha_cung_cap').val(ten_nha_cung_cap);
        $('#edit_dia_chi').val(dia_chi);
        $('#edit_so_dien_thoai').val(so_dien_thoai);
        $('#edit_email').val(email);
        
        // Mở modal sửa
        $('#modalEditNCC').modal('show');
    }

// Lưu thay đổi thông tin nhà cung cấp
$('#saveEditNhaCungCap').click(function() {
    const ma_nha_cung_cap = $('#edit_ma_nha_cung_cap').val();
    const ten_nha_cung_cap = $('#edit_ten_nha_cung_cap').val();
    const dia_chi = $('#edit_dia_chi').val();
    const so_dien_thoai = $('#edit_so_dien_thoai').val();
    const email = $('#edit_email').val();

    $.ajax({
        url: '/suanhacungcap', // Đường dẫn route sửa nhà cung cấp
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            ma_nha_cung_cap,
            ten_nha_cung_cap,
            dia_chi,
            so_dien_thoai,
            email
        },
        success: function(response) {
            alert(response.message);
            $('#modalEditNCC').modal('hide'); // Đóng modal
            location.reload(); // Tải lại trang
            //window.location.href = '/showthemsanpham'; 
        },
        error: function(xhr) {
            alert('Có lỗi xảy ra khi sửa nhà cung cấp');
        }
    });
});

 </script>



<script>
    $(document).ready(function() {
        // Khi chọn nhóm tùy chọn
        $('#ma_nhom_tuy_chon').on('change', function() {
            var maNhomTuyChon = $(this).val();
            
            // Kiểm tra nếu có giá trị nhóm tùy chọn
            if(maNhomTuyChon) {
                // Gửi yêu cầu AJAX để lấy các tùy chọn tương ứng
                $.ajax({
                    url: '/laytuychon',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ma_nhom_tuy_chon: maNhomTuyChon
                    },
                    success: function(response) {
                        // Xóa tất cả các dòng cũ trong bảng
                        $('#tableTuyChon tbody').empty();
                        
                        // Duyệt qua các tùy chọn và thêm vào bảng
                        response.forEach(function(item) {
                            var row = `<tr>
                                          
                                            <td>${item.ma_nhom_tuy_chon}</td>
                                            <td>${item.ten_tuy_chon}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="${item.ma_tuy_chon}" data-name="${item.ten_tuy_chon}" data-ma-nhom="${item.ma_nhom_tuy_chon}">
                                                    Sửa
                                                </button>
                                            </td>
                                        </tr>`;
                            $('#tableTuyChon tbody').append(row);
                        });
                    },
                    error: function() {
                        alert('Không thể tải các tùy chọn');
                    }
                });
            } else {
                // Nếu không có nhóm, xóa các tùy chọn cũ
                $('#tableTuyChon tbody').empty();
            }
            $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var maNhom = $(this).data('ma-nhom');
            
            // Chỉnh sửa thông tin trong form (ví dụ: điền tên và mã nhóm vào input)
            $('#ten_tuy_chon').val(name);
            $('#ma_nhom_tuy_chon').val(maNhom);
            
            // Cập nhật hành động của form để thực hiện cập nhật thay vì thêm mới
            $('#formTuyChon').attr('action', '/suatuychon');
            $('#formTuyChon').append('<input type="hidden" name="ma_tuy_chon" value="' + id + '">');
            $('#saveTuyChon').text('Cập nhật');
        });
        });
    });


//sửa loại sản phẩm
    $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    const ten = $(this).data('ten');
    const moTa = $(this).data('mo-ta');
    const dongSanPham = $(this).data('dong-san-pham');
    const nhomTuyChon = $(this).data('nhom-tuy-chon');

    // Gán giá trị vào form
    $('#ma_loai_san_pham').val(id); // Ẩn input hidden
    $('#ten_loai_san_pham').val(ten);
    $('#mo_ta').val(moTa);
    $('#ma_dong_san_pham').val(dongSanPham);

    // Bỏ chọn checkbox trước
    $('input[name="nhom_tuy_chon[]"]').prop('checked', false);

    // Chọn lại checkbox theo nhóm tùy chọn của sản phẩm
    if (Array.isArray(nhomTuyChon)) {
        nhomTuyChon.forEach(function (nhomId) {
            $(`#nhomTuyChon${nhomId}`).prop('checked', true);
        });
    }
    $('#formLoaiSanPham').attr('action', '/sualoaisanpham');
    $('#formLoaiSanPham').append('<input type="hidden" name="ma_loai_san_pham" value="' + id + '">');
    $('#modalLoaiSanPhamLabel').text('Chỉnh sửa Loại Sản Phẩm');
    $('#saveLoaiSanPham').text('Cập nhật');
    // Hiển thị modal
    $('#modalLoaiSanPham').modal('show');
});

</script>
{{-- Mô tả --}}
<script>

</script>

@endsection