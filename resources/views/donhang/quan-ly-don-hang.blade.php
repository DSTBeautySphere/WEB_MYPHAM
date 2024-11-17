@extends('layout.index')
@section('title','Quản Lý Đơn Hàng')
@section('css')
<style>

    /* Đặt chiều cao cố định và cho phép cuộn trong phần bảng */
    .table-container {
        max-height: 400px; /* Điều chỉnh chiều cao của bảng */
        overflow-y: auto;
    }

    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection
@section('content')
<div class="col-md-12">
    <div class="container">
        <!-- Dòng trạng thái đơn hàng -->
        <div class="row mb-4 header-container">
            <div class="col-2 text-center border p-2 rounded">
                Tất Cả
            </div>
            <div class="col-2 text-center border p-2 rounded">
                Chờ Xác Nhận
            </div>
            <div class="col-2 text-center border p-2 rounded">
                Chờ Xử Lí
            </div>
            <div class="col-2 text-center border p-2 rounded">
                Đang Vận Chuyển
            </div>
            <div class="col-2 text-center border p-2 rounded">
                Đã Giao Hàng
            </div>
            <div class="col-2 text-center border p-2 rounded">
                Đã Hủy
            </div>
        </div>
        
        <!-- Các chức năng lọc và tìm kiếm -->
        <div class="row">
            
            
    
            <div class="col-md-4">
                <!-- Lọc theo ngày -->
                <input type="date" class="form-control">
            </div>
    
            <div class="col-md-4">
                <!-- Lọc theo trạng thái -->
                <select class="form-control">
                    <option value="">Chọn trạng thái</option>
                    <option value="all">Tất cả</option>
                    <option value="choxacnhan">Chờ Xác Nhận</option>
                    <option value="choxuli">Chờ Xử Lí</option>
                    <option value="dangvanchuyen">Đang Vận Chuyển</option>
                    <option value="dagiaohang">Đã Giao Hàng</option>
                    <option value="dahuy">Đã Hủy</option>
                </select>
            </div>
    
            <!-- Lọc theo giá lớn hơn -->
            <div class="col-md-4">
                <select class="form-control">
                    <option value="">Chọn giá lớn hơn</option>
                    <option value="100000">Lớn hơn 100.000 đ</option>
                    <option value="500000">Lớn hơn 500.000 đ</option>
                    <option value="1000000">Lớn hơn 1.000.000 đ</option>
                    <option value="5000000">Lớn hơn 5.000.000 đ</option>
                </select>
            </div>
            
            
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-8">
                <!-- Tìm kiếm theo mã đơn hàng -->
                <input type="text" class="form-control" placeholder="Tìm kiếm mã đơn hàng">
            </div>
            <div class="col-md-4">
                <!-- Nút tìm kiếm -->
                <button class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </div>
    
</div>

<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" href="form-add-don-hang.html" title="Thêm"><i class="fas fa-plus"></i>
                        Tạo mới đơn hàng</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm nhap-tu-file" type="button" title="Nhập" onclick="myFunction(this)"><i
                            class="fas fa-file-upload"></i> Tải từ file</a>
                </div>

                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                            class="fas fa-print"></i> In dữ liệu</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file js-textareacopybtn" type="button" title="Sao chép"><i
                            class="fas fa-copy"></i> Sao chép</a>
                </div>

                <div class="col-sm-2">
                    <a class="btn btn-excel btn-sm" href="" title="In"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm pdf-file" type="button" title="In" onclick="myFunction(this)"><i
                            class="fas fa-file-pdf"></i> Xuất PDF</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i
                            class="fas fa-trash-alt"></i> Xóa tất cả </a>
                </div>
            </div>

            <!-- Table nằm trong thẻ có thể cuộn -->
            <div class="table-container">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                    <tr>
                        <th width="10"><input type="checkbox" id="all"></th>
                        <th>ID đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Đơn hàng</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Tình trạng</th>
                        <th>Tính năng</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>QY8723</td>
                        <td>Ngô Thái An</td>
                        <td>Giường ngủ Kara 1.6x2m</td>
                        <td>1 </td>
                        <td>14.500.000 đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"><i class="fas fa-trash-alt"></i> </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection
