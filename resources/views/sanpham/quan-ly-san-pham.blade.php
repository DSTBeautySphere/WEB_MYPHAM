
@extends('layout.index')
@section('title','Báo Cáo Doanh Thu')
@section('css')

@endsection
<style>
 .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

</style>
@section('content')
<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
  
                  <a class="btn btn-add btn-sm" href="form-add-san-pham.html" title="Thêm"><i class="fas fa-plus"></i>
                    Tạo mới sản phẩm</a>
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
            <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                    <tr>
                        <th width="10"><input type="checkbox" id="all"></th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Tình trạng</th>
                        <th>Giá tiền</th>
                        <th>Danh mục</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>  
                    @foreach ($sanPham as $item)
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>{{ $item->ma_san_pham }}</td>
                        <td>{{ $item->ten_san_pham }}</td>
                        <td><img src="/img-sanpham/tu.jpg" alt="" width="50px;"></td>
                        <td>0</td>
                        <td><span class="badge bg-danger">Hét hàng</span></td>
                        <td>2.450.000 đ</td>
                        <td>Tủ</td>
                        <td><button class="btn btn-primary btn-sm trash" type="button" title="Xóa"
                                onclick="myFunction(this)"><i class="fas fa-trash-alt"></i>
                            </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" id="show-emp" data-toggle="modal"
                                data-target="#ModalUP"><i class="fas fa-edit"></i></button>
                          
                        </td>
                    </tr>
                    @endforeach            
                 
              
                </tbody>
            </table>
           
            <div style="text-align: center" class="pagination-wrapper">
                {{ $sanPham->links('vendor.pagination.bootstrap-4') }}
            </div>
            
        </div>
        
    </div>
</div>
@endsection

@section('js')




@endsection