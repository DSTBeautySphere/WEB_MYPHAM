
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
  
                  <a class="btn btn-add btn-sm" href="{{ url('/showthemsanpham') }}" title="Thêm"><i class="fas fa-plus"></i>
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
                        <th>Loại sản phẩm</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>  
                    @foreach ($sanPham as $item)
                    <tr>
                        <td width="10"><input type="checkbox" name="check1" value="1"></td>
                        <td>{{ $item->ma_san_pham }}</td>
                        <td>{{ $item->ten_san_pham }}</td>
                        <td>
                            @if ($item->anh_san_pham->isNotEmpty())
                            <img src="{{$item->anh_san_pham->first()->url_anh }}" alt="Ảnh sản phẩm" width="50px;">
                        @else
                            <img src="#" alt="Không có ảnh" width="50px;">
                        @endif
                        </td>
                        <td>  {{ $item->bien_the_san_pham->sum('so_luong_ton_kho') }}</td>

                        <td>
                            @php
                                // Tính tổng số lượng tồn kho của tất cả biến thể sản phẩm
                                $totalQuantity = $item->bien_the_san_pham->sum('so_luong_ton_kho');
                            @endphp
                            @if ($totalQuantity > 0)
                                <span class="badge bg-success">Còn hàng </span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>  {{ number_format($item->bien_the_san_pham->first()->gia_ban, 0, ',', '.') }} đ</td>
                        <td>{{ $item->loai_san_pham->ten_loai_san_pham }}</td>
                        <td>

                            <button class="btn btn-primary btn-sm trash" type="button" title="Xóa"
                                onclick="myFunction(this)"><i class="fas fa-trash-alt"></i>
                            </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" id="show-emp" data-toggle="modal"
                                data-target="#ModalUP"><i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-info btn-sm variant" type="button" title="Biến thể"
                                onclick="window.location.href='{{ url('/danhsachbienthesanpham') }}?ma_san_pham={{ $item->ma_san_pham }}'">
                                <i class="fas fa-th"></i> Biến thể
                            </button>
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