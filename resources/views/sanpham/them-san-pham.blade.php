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
                    <a class="btn btn-add btn-sm" data-toggle="modal" data-target="#adddanhmuc"><i class="fas fa-folder-plus"></i> Thêm danh mục</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" data-toggle="modal" data-target="#addtinhtrang"><i class="fas fa-folder-plus"></i> Thêm tình trạng</a>
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
                    <button class="btn btn-save" type="button" id="layMau">Tạo Sản Phẩm</button>
                <div class="form-group col-md-12">
                    <label class="control-label">Ảnh sản phẩm</label>
                    <div id="myfileupload">
                        <input type="file" id="uploadfile" name="ImageUpload[]" onchange="readURL(this);" multiple/>
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
                <div class="form-group col-md-12">
                    <label class="control-label">Mô tả sản phẩm</label>
                    <textarea class="form-control" name="mota" id="mota"></textarea>
                </div>
                <button class="btn btn-save" type="button" id="themSP" >Thêm</button>
                <a class="btn btn-cancel" href="table-data-product.html">Hủy bỏ</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('mota', {
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

                // Kiểm tra xem nhóm có tùy chọn không
                if (nhom.tuy_chon.length === 0) {
                    // Nếu không có tùy chọn, thêm lớp CSS để làm mờ và vô hiệu hóa
                    groupHTML = ` 
                        <div class="group-readonly" style="opacity: 0.5; pointer-events: none;">
                            <h4>${groupName}</h4>
                            <p>Không có tùy chọn nào.</p>
                        </div>`;
                } else {
                    // Nếu có tùy chọn, hiển thị bình thường
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

                // Thêm nhóm vào đúng vị trí
                $('#group-' + groupName.toLowerCase().replace(" ", "-")).html(groupHTML);
            });

            // Kiểm tra và áp dụng hiệu ứng làm mờ cho các nhóm không có tùy chọn
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
                            'mau_sac': mauItem || null,
                            'loai_da': loaiDaItem || null,
                            'dung_tich': dungTichItem || null,  // Đảm bảo dung_tich là chuỗi hoặc null
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
                        'loai_da': 'null',
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
                        'dung_tich': 'null',
                       
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
                        'mau_sac': 'null',
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
                    'loai_da': 'null',
                    'dung_tich': 'null',                
                    'so_luong_ton_kho': 1,
                    'gia_ban': 10000
                });
            });
        }
   
        else if (mau.length === 0 && dungtich.length > 0 && loaida.length === 0) {
            $.each(dungtich, function(i, dungTichItem) {
                sanPhamMau.push({
                    'mau_sac': 'null',
                    'loai_da': 'null',
                    'dung_tich': dungTichItem,
                    
                    'so_luong_ton_kho': 1,
                    'gia_ban': 10000
                });
            });
        }
     
        else if (mau.length === 0 && dungtich.length === 0 && loaida.length > 0) {
            $.each(loaida, function(i, loaiDaItem) {
                sanPhamMau.push({
                    'mau_sac': 'null',
                    'loai_da': loaiDaItem,
                    'dung_tich': 'null',
                    
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
            '<td><button class="delete-row">Xóa</button></td>' +
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
        '<td><button class="delete-row">Xóa</button></td>' +
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
                '<td><button class="delete-row">Xóa</button></td>' +  
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

$('#themSP').on('click', function() {
    let tenSanPham = $('input[name="ten_san_pham"]').val();
    let maLoaiSanPham = $('#danhmuc').val();
    let maNhaCungCap = $('#nhacungcap').val();

    // Chuyển đổi tất cả giá trị của `dung_tich` trong `sanPhamMau` thành chuỗi
    sanPhamMau = sanPhamMau.map(item => ({
        ...item,
        dung_tich: String(item.dung_tich)  // Chuyển `dung_tich` thành chuỗi
    }));

    
    
    if (sanPhamMau.length === 0) {
        alert("Vui lòng chọn đầy đủ thông tin về Màu sắc, Loại da và Dung tích.");
        return;
    }

    var formData = new FormData();
    formData.append('ten_san_pham', tenSanPham);
    formData.append('ma_loai_san_pham', maLoaiSanPham);
    formData.append('ma_nha_cung_cap', maNhaCungCap);
    formData.append('bien_the[]', JSON.stringify(sanPhamMau)); // Chuyển sanPhamMau thành chuỗi JSON

    // Thêm các ảnh vào formData
    for (var i = 0; i < imageFiles.length; i++) {
        formData.append('anh_san_pham[]', imageFiles[i]);
    }
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: '/themsanpham',
        type: 'POST',
        data: formData,
        processData: false, // Không xử lý dữ liệu trước khi gửi
        contentType: false, // Không gửi content-type
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr, status, error) {
            var statusCode = xhr.status;
            var errorMessage = xhr.responseText || "Không có thông báo lỗi";
            alert('Có lỗi xảy ra: \nMã trạng thái: ' + statusCode + '\nThông báo lỗi: ' + errorMessage);
        }
    });
});




</script>

@endsection
