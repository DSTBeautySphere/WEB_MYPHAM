
@extends('layout.index')
@section('title','Quản lý sản phẩm')
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
                {{-- <div class="col-sm-2">
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
                </div> --}}
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
                        <th>Trạng thái</th>
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
                            @if ($item->trang_thai == 1)
                                <span class="badge bg-success">Đang bán</span>
                            @else
                                <span class="badge bg-secondary">Ngừng bán</span>
                            @endif
                        </td>
                        <td>

                            <button class="btn btn-primary btn-sm trash" type="button" title="Xóa"
                                data-id="{{ $item->ma_san_pham }}"><i class="fas fa-trash-alt"></i>
                            </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" id="show-emp" data-id="{{ $item->ma_san_pham }}" data-toggle="modal"
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

<!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="updateForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>X
                    </button>
                </div>
                <div class="modal-body">
                    <!-- ID Sản phẩm -->
                    <input type="hidden" id="ma_san_pham" name="ma_san_pham">
                    
                    <div class="form-group">
                        <label for="ten_san_pham">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="ten_san_pham" name="ten_san_pham" required>
                    </div>
                    
                    {{-- <div class="form-group">
                        <label for="ma_loai_san_pham">Loại sản phẩm</label>
                        <select class="form-control" id="ma_loai_san_pham" name="ma_loai_san_pham">
                            <!-- Render danh sách loại sản phẩm -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="ma_nha_cung_cap">Nhà cung cấp</label>
                        <select class="form-control" id="ma_nha_cung_cap" name="ma_nha_cung_cap">
                            <!-- Render danh sách nhà cung cấp -->
                        </select>
                    </div>

                    

                    <div class="form-group">
                        <label for="anh_san_pham">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="anh_san_pham" name="anh_san_pham[]" multiple>
                        <!-- Hiển thị các ảnh đã có -->
                        <div id="anh_san_pham_container" class="mt-2">
                            <!-- Các ảnh sẽ được thêm vào đây -->
                        </div>
                    </div> --}}

                   
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
                        {{-- <input class="form-control" name="txt_tieude" id="txt_tieude" placeholder="Nhập Tiêu Đề"></input> --}}
                        {{-- <textarea class="form-control" name="txt_noidung" id="txt_noidung" placeholder="Nhập Nội Dung"></textarea> --}}
                        {{-- <button style="margin-top: 10px" class="btn btn-success" id="btn_themctmota" >Thêm Chi Tiết Mô Tả</button> --}}
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSave">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                   
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> --}}
<script>
    // $(document).on('click', '.edit', function () {
    //     const id = $(this).closest('tr').find('td:eq(1)').text(); // Lấy mã sản phẩm
    //     $.ajax({
    //         url: `/sanpham/${id}/edit`,
    //         method: 'GET',
    //         success: function (response) {
    //             $('#ma_san_pham').val(response.sanPham.ma_san_pham);
    //             $('#ten_san_pham').val(response.sanPham.ten_san_pham);
    //             $('#ma_loai_san_pham').val(response.sanPham.ma_loai_san_pham);
    //             $('#ma_nha_cung_cap').val(response.sanPham.ma_nha_cung_cap);
    //             $('#mo_ta').val(response.sanPham.mo_ta.ten_mo_ta);
    //             $('#chi_tiet_mo_ta').val(response.sanPham.mo_ta.chi_tiet_mo_ta.map(ct => ct.noi_dung).join('\n'));
                
    //             // Hiển thị modal
    //             $('#ModalUP').modal('show');
    //         },
    //         error: function () {
    //             alert('Không thể lấy dữ liệu sản phẩm!');
    //         }
    //     });
    // });
    let ds_mota = [];
    let ds_ctmota = [];
    $(document).on('click', '.edit', function () {
        const id = $(this).data('id'); // Lấy ID sản phẩm từ nút
        console.log("Mã sản phẩm được chọn:", id); // Log kiểm tra ID

        // Gửi AJAX request để lấy thông tin sản phẩm
        $.ajax({
            url: `/sanphamedit/${id}`, // Endpoint xử lý yêu cầu
            method: 'GET',
            success: function (response) {
                console.log("Dữ liệu trả về từ server:", response);
                if (response.sanPham) {
                    // Gán dữ liệu sản phẩm vào các trường
                    $('#ma_san_pham').val(response.sanPham.ma_san_pham);
                    $('#ten_san_pham').val(response.sanPham.ten_san_pham);

                    // Đổ danh sách loại sản phẩm vào combobox
                    const loaiSanPhamSelect = $('#ma_loai_san_pham');
                    loaiSanPhamSelect.empty(); // Xóa các lựa chọn cũ
                    response.danhSachLoaiSanPham.forEach(item => {
                        loaiSanPhamSelect.append(
                            `<option value="${item.ma_loai_san_pham}">${item.ten_loai_san_pham}</option>`
                        );
                    });
                    // Gán giá trị đã chọn
                    loaiSanPhamSelect.val(response.sanPham.loai_san_pham.ma_loai_san_pham);

                    // Đổ danh sách nhà cung cấp vào combobox
                    const nhaCungCapSelect = $('#ma_nha_cung_cap');
                    nhaCungCapSelect.empty(); // Xóa các lựa chọn cũ
                    response.danhSachNhaCungCap.forEach(item => {
                        nhaCungCapSelect.append(
                            `<option value="${item.ma_nha_cung_cap}">${item.ten_nha_cung_cap}</option>`
                        );
                    });
                    // Gán giá trị đã chọn
                    nhaCungCapSelect.val(response.sanPham.nha_cung_cap.ma_nha_cung_cap);
                    // Hiển thị ảnh sản phẩm đã có
                    const anhSanPhamContainer = $('#anh_san_pham_container');
                    anhSanPhamContainer.empty(); // Xóa các ảnh cũ
                    response.sanPham.anh_san_pham.forEach(anh => {
                        anhSanPhamContainer.append(
                            `<img src="${anh.url_anh}" alt="Ảnh sản phẩm" class="img-thumbnail" width="100">`
                        );
                    });
                   
                        // Hiển thị danh sách mô tả sản phẩm
                        const motaTable = $('#dgv_mota tbody');
                        motaTable.empty(); // Xóa dữ liệu cũ

                        response.danhSachMoTa.forEach(mota => {
                            ds_mota.push(mota);
                           
                           
                        });
                        Load_TableMoTa();
                        // Hiển thị chi tiết mô tả sản phẩm
                        const chitietTable = $('#dgv_chitietmota tbody');
                        chitietTable.empty(); // Xóa dữ liệu cũ

                        if (response.danhSachMoTa.length > 0) {
                            // Duyệt qua tất cả các mô tả
                            response.danhSachMoTa.forEach(mota => {
                                if (mota.chi_tiet_mo_ta && mota.chi_tiet_mo_ta.length > 0) {
                                    // Duyệt qua tất cả chi tiết mô tả trong mỗi mô tả và thêm vào ds_ctmota
                                    mota.chi_tiet_mo_ta.forEach(ct => {
                                        ds_ctmota.push(ct); // Thêm chi tiết mô tả vào danh sách ds_ctmota
                                    });
                                }
                            });
                        }

                        
                    // Hiển thị modal
                    $('#ModalUP').modal('show');
                } else {
                    alert('Không tìm thấy dữ liệu sản phẩm!');
                }
            },
            error: function (xhr) {
                console.error("Lỗi AJAX:", xhr);
                alert('Không thể lấy dữ liệu sản phẩm! Vui lòng thử lại.');
            }
        });
    });

    $('#ModalUP').on('hidden.bs.modal', function () {
        ds_mota = [];  // Làm rỗng mảng mô tả
        ds_ctmota = []; // Làm rỗng mảng chi tiết mô tả
    });
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
    // let editorInstance;
    // ClassicEditor
    //     .create(document.querySelector('#txt_noidung'))
    //     .then(editor => {
    //         editorInstance = editor;
    //     })
    //     .catch(error => {
    //         console.error(error);
    //     });
    // // Hàm thêm chi tiết mô tả
    // document.getElementById("btn_themctmota").addEventListener("click", function() {
    //     event.preventDefault(); 
    //     if (temp_mota !== null) {
            
    //         const tieuDe = document.getElementById('txt_tieude').value;

        
    //         const noiDung = editorInstance.getData();
    //         if (tieuDe && noiDung) {
    //             const chiTiet = { ma_mo_ta: temp_mota, tieu_de: tieuDe, noi_dung: noiDung };
    //             ds_ctmota.push(chiTiet);
    //             Load_TableMoTaChiTiet(temp_mota);
    //             document.getElementById("txt_tieude").value = ''; // Clear input
    //             document.getElementById("txt_noidung").value = ''; // Clear textarea
    //         } else {
    //             alert("Vui lòng nhập tiêu đề và nội dung chi tiết!");
    //         }
    //     } else {
    //         alert("Vui lòng chọn mô tả trước khi thêm chi tiết!");
    //     }
    // });

    // Load bảng mô tả
    function Load_TableMoTa() {
        const tbody = document.getElementById("dgv_mota").getElementsByTagName("tbody")[0];
        tbody.innerHTML = ''; // Clear previous data

        ds_mota.forEach(function(moTa) {
            const row = tbody.insertRow();
            
            row.insertCell(0).innerText = moTa.ten_mo_ta;
            
            const btnSelect = document.createElement("button");
            btnSelect.innerText = "Chọn";
            btnSelect.className = "btn btn-success btn-sm";

            btnSelect.addEventListener("click", function(event) {
                event.preventDefault();
                temp_mota = moTa.ma_mo_ta;

                console.log(`Selected ma_mo_ta: ${temp_mota}`);

                document.getElementById("lb_not").innerText = `Nhập Thông Tin Chi Tiết Cho [${moTa.ten_mo_ta}]`;

                // Gọi Load_TableMoTaChiTiet và truyền ma_mo_ta vào
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
                    Load_TableMoTa(); // Tải lại bảng mô tả
                }
            });

            // const btnDelete = document.createElement("button");
            // btnDelete.innerText = "Xóa";
            // btnDelete.classList.add("delete");
            // btnDelete.className = "btn btn-danger btn-sm";
            // btnDelete.addEventListener("click", function(event) {
            //     event.preventDefault();
            //     const index = ds_mota.indexOf(moTa);
            //     if (index > -1) {
            //         ds_mota.splice(index, 1);
            //         Load_TableMoTa(); // Tải lại bảng mô tả
            //     }
            // });

            const cellBtn = row.insertCell(1);
            cellBtn.appendChild(btnSelect);
            cellBtn.appendChild(btnEdit);
            // cellBtn.appendChild(btnDelete);
        });
    }
   
// Load bảng chi tiết mô tả khi nhấn "Chọn"

    function Load_TableMoTaChiTiet(ma_mo_ta) {
        const tbody = document.getElementById("dgv_chitietmota").getElementsByTagName("tbody")[0];
        if (!tbody) {
            console.error("Không tìm thấy bảng chi tiết mô tả!");
            return;
        }
        tbody.innerHTML = ''; // Clear previous data
    
        // Lọc danh sách chi tiết mô tả theo ma_mo_ta
        const chiTietList = ds_ctmota.filter(ct => ct.ma_mo_ta == ma_mo_ta);
        
        if (!chiTietList.length) {
            console.log(`Không tìm thấy chi tiết cho ma_mo_ta: ${ma_mo_ta}`);
            return;
        }

        // Duyệt qua các chi tiết mô tả và hiển thị trên bảng
        chiTietList.forEach(function(ct) {
            const row = tbody.insertRow();
            row.insertCell(0).innerText = ct.tieu_de;
            row.insertCell(1).innerText = ct.noi_dung;

            // Tạo nút "Sửa"
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
                    Load_TableMoTaChiTiet(ma_mo_ta); // Tải lại chi tiết mô tả
                }
            });

            // Tạo nút "Xóa"
            // const btnDelete = document.createElement("button");
            // btnDelete.innerText = "Xóa";
            // btnDelete.classList.add("delete");
            // btnDelete.className = "btn btn-danger btn-sm";
            // btnDelete.addEventListener("click", function(event) {
            //     event.preventDefault();
            //     const index = ds_ctmota.indexOf(ct);
            //     if (index > -1) {
            //         ds_ctmota.splice(index, 1);
            //         Load_TableMoTaChiTiet(ma_mo_ta); // Tải lại chi tiết mô tả
            //     }
            // });

            const cellBtn = row.insertCell(2);
            cellBtn.appendChild(btnEdit);
            // cellBtn.appendChild(btnDelete);
        });
    }

    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '#btnSave', function (e) {
        e.preventDefault(); // Ngừng hành động mặc định của nút submit

        // Lấy dữ liệu từ form
        const updatedProduct = {
            ma_san_pham: $('#ma_san_pham').val(), // ID sản phẩm
            ten_san_pham: $('#ten_san_pham').val(), // Tên sản phẩm
            // ma_loai_san_pham: $('#ma_loai_san_pham').val(), // Loại sản phẩm
            // ma_nha_cung_cap: $('#ma_nha_cung_cap').val(), // Nhà cung cấp
            // anh_san_pham: [], // Mảng chứa các ảnh sản phẩm (bao gồm cả ảnh cũ và ảnh mới)
            danhSachMoTa: [], // Giả sử có danh sách mô tả
            danhSachChiTietMoTa: [] // Giả sử có danh sách chi tiết mô tả
        };

        // Lấy các ảnh sản phẩm cũ đã có trong container
        // $('#anh_san_pham_container img').each(function () {
        //     updatedProduct.anh_san_pham.push({
        //         url_anh: $(this).attr('src') // Lấy URL ảnh cũ
        //     });
        // });

        // // Lấy các ảnh sản phẩm mới từ input file
        // const files = $('#anh_san_pham')[0].files;
        // if (files.length > 0) {
        //     for (let i = 0; i < files.length; i++) {
        //         updatedProduct.anh_san_pham.push({
        //             url_anh: URL.createObjectURL(files[i]) // Tạo URL cho ảnh tạm
        //         });
        //     }
        // }

        // Lấy các mô tả sản phẩm (giả sử bạn đã có danh sách mô tả trong ds_mota)
        updatedProduct.danhSachMoTa = getDanhSachMoTa(); // Ví dụ: gọi hàm getDanhSachMoTa để lấy danh sách mô tả
       
        // Lấy chi tiết mô tả (giả sử bạn đã có danh sách chi tiết mô tả trong ds_ctmota)
        updatedProduct.danhSachMoTa.forEach(function (mota) {
            mota.danhSachChiTietMoTa = getDanhSachChiTietMoTa(mota.ma_mo_ta);
        });
       
        // Gửi yêu cầu cập nhật đến server
        $.ajax({
            url: `/updatesanpham/${updatedProduct.ma_san_pham}`, // API endpoint cập nhật sản phẩm
            method: 'PUT', // Hoặc 'POST' nếu không có PUT
            data: JSON.stringify(updatedProduct),
            contentType: 'application/json', // Chỉ định dữ liệu gửi đi là JSON
            success: function (response) {
                console.log("Cập nhật thành công:", response);
                alert("Cập nhật sản phẩm thành công!");
                $('#ModalUP').modal('hide'); // Đóng modal
                // Cập nhật lại bảng hoặc làm mới danh sách sản phẩm
                window.location.href = response.redirect_url;
            },
            error: function (xhr) {
                console.error("Lỗi cập nhật:", xhr);
                alert('Cập nhật sản phẩm không thành công. Vui lòng thử lại.');
            }
        });
    });
   

    function getDanhSachMoTa() {
        return ds_mota.map(function(mota) {
            return {
                ma_mo_ta: mota.ma_mo_ta,
                ten_mota: mota.ten_mo_ta
            };
        });
    }
    function getDanhSachChiTietMoTa(ma_mo_ta) {
        // Lọc chi tiết mô tả dựa vào ma_mo_ta
        return ds_ctmota.filter(function(ct) {
            return ct.ma_mo_ta === ma_mo_ta;
        }).map(function(ct) {
            return {
                
                tieude: ct.tieu_de,
                noidung: ct.noi_dung
            };
        });
    }

    //Xóa sản phẩm
    document.querySelectorAll('.trash').forEach(button => {
    button.addEventListener('click', function () {
        const sanPhamId = this.dataset.id; // Lấy ID của sản phẩm từ dataset

        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
            fetch(`/xoaSP/${sanPhamId}`, {
                method: 'DELETE',
                // headers: {
                //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); // Tải lại trang sau khi xóa
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            });
        }
    });
});


</script>


@endsection