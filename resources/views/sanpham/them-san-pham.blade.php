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
        position: relative;
        width: 100%;
        margin-bottom: 20px;
    }

    .removeimg {
        height: 25px;
        position: absolute;
        background-repeat: no-repeat;
        top: 5px;
        left: 5px;
        background-size: 25px;
        width: 25px;
        border-radius: 50%;
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
            <form class="row">
                <div class="form-group col-md-4">
                    <label class="control-label">Tên Sản Phẩm</label>
                    <input class="form-control" type="text" placeholder="" name="ten_san_pham">
                </div>
            
                <div class="form-group col-md-4">
                    <label for="danhmuc" class="control-label">Loại Sản Phẩm</label>
                    <select class="form-control" id="danhmuc" name="ma_loai_san_pham">
                        @foreach ($loaiSanPham as $item)
                        <option value="{{ $item->ma_loai_san_pham }}">{{ $item->ten_loai_san_pham }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="nhacungcap" class="control-label">Nhà Cung Cấp</label>
                    <select class="form-control" id="nhacungcap" name="ma_nha_cung_cap">
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
                                    <input type="checkbox" name="mau[]" value="{{ $item->ma_tuy_chon }}" 
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
                                    <input type="checkbox" name="dungtich[]" value="{{ $item->ma_tuy_chon }}" 
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
                                    <input type="checkbox" name="loaida[]" value="{{ $item->ma_tuy_chon }}" 
                                    style="background-color: {{ $item->ma_tuy_chon }};">
                                    {{ $item->ten_tuy_chon }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div>Sản Phẩm Mẫu</div>
                <div class="form-group col-md-12" id="sanPhamMau">

                </div>
                <div class="form-group col-md-12">
                    <label class="control-label">Ảnh sản phẩm</label>
                    <div id="myfileupload">
                        <input type="file" id="uploadfile" name="ImageUpload" onchange="readURL(this);" />
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
                <button class="btn btn-save" type="button" id="layMau">Lưu lại</button>
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
 $('#danhmuc').change(function() {
    $('input[name="mau[]"]').prop("checked", false);
    $('input[name="dungtich[]"]').prop("checked", false);
    $('input[name="loaida[]"]').prop("checked", false);
    var maLoaiSanPham = $(this).val();

    // Gửi yêu cầu AJAX đến controller
    $.ajax({
        url: '/laynhomtuychontheoloai',
        type: 'GET',
        data: {
            ma_loai_san_pham: maLoaiSanPham,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // Biến để kiểm tra có nhóm nào có tùy chọn hay không
            var hasMauSac = false;
            var hasDungTich = false;
            var hasLoaiDa = false;

            // Duyệt qua các nhóm tùy chọn trả về từ controller
            response.forEach(function(nhom) {
                // Kiểm tra nhóm và gán các biến có giá trị nếu nhóm đó có tùy chọn
                if (nhom.ma_nhom_tuy_chon == 1) hasMauSac = true;
                if (nhom.ma_nhom_tuy_chon == 2) hasDungTich = true;
                if (nhom.ma_nhom_tuy_chon == 3) hasLoaiDa = true;

                // Cập nhật nội dung nhóm tùy chọn
                var groupName = nhom.ten_nhom_tuy_chon;  // Tên nhóm như "Màu", "Dung tích", v.v.
                var groupHTML = '<h4>' + groupName + '</h4>';  // Hiển thị tên nhóm

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

    // Lấy màu sắc đã chọn
    $('input[name="mau[]"]:checked').each(function() {
        mau.push($(this).val());
    });

    // Lấy dung tích đã chọn
    $('input[name="dungtich[]"]:checked').each(function() {
        dungtich.push($(this).val());
    });

    // Lấy loại da đã chọn
    $('input[name="loaida[]"]:checked').each(function() {
        loaida.push($(this).val());
    });

    var sanPhamMau = [];

    // Duyệt qua các tổ hợp của ba trường
    if (mau.length > 0 || dungtich.length > 0 || loaida.length > 0) {
        // Trường hợp có đầy đủ cả 3 trường
        if (mau.length > 0 && dungtich.length > 0 && loaida.length > 0) {
            $.each(mau, function(i, mauItem) {
                $.each(dungtich, function(j, dungTichItem) {
                    $.each(loaida, function(k, loaiDaItem) {
                        sanPhamMau.push({
                            'mau_sac': mauItem,
                            'dung_tich': dungTichItem,
                            'loai_da': loaiDaItem
                        });
                    });
                });
            });
        }
        // Trường hợp chỉ có màu sắc và dung tích
        else if (mau.length > 0 && dungtich.length > 0 && loaida.length === 0) {
            $.each(mau, function(i, mauItem) {
                $.each(dungtich, function(j, dungTichItem) {
                    sanPhamMau.push({
                        'mau_sac': mauItem,
                        'dung_tich': dungTichItem
                    });
                });
            });
        }
        // Trường hợp chỉ có màu sắc và loại da
        else if (mau.length > 0 && dungtich.length === 0 && loaida.length > 0) {
            $.each(mau, function(i, mauItem) {
                $.each(loaida, function(j, loaiDaItem) {
                    sanPhamMau.push({
                        'mau_sac': mauItem,
                        'loai_da': loaiDaItem
                    });
                });
            });
        }
        // Trường hợp chỉ có dung tích và loại da
        else if (mau.length === 0 && dungtich.length > 0 && loaida.length > 0) {
            $.each(dungtich, function(i, dungTichItem) {
                $.each(loaida, function(j, loaiDaItem) {
                    sanPhamMau.push({
                        'dung_tich': dungTichItem,
                        'loai_da': loaiDaItem
                    });
                });
            });
        }
        // Trường hợp chỉ có màu sắc
        else if (mau.length > 0 && dungtich.length === 0 && loaida.length === 0) {
            $.each(mau, function(i, mauItem) {
                sanPhamMau.push({
                    'mau_sac': mauItem
                });
            });
        }
        // Trường hợp chỉ có dung tích
        else if (mau.length === 0 && dungtich.length > 0 && loaida.length === 0) {
            $.each(dungtich, function(i, dungTichItem) {
                sanPhamMau.push({
                    'dung_tich': dungTichItem
                });
            });
        }
        // Trường hợp chỉ có loại da
        else if (mau.length === 0 && dungtich.length === 0 && loaida.length > 0) {
            $.each(loaida, function(i, loaiDaItem) {
                sanPhamMau.push({
                    'loai_da': loaiDaItem
                });
            });
        }
    }

    var sanPhamMauHTML = '';
sanPhamMau.forEach(function(sp) {
    // Khởi tạo chuỗi hiển thị cho từng sản phẩm
    var sanPhamHtml = '<div>Sản phẩm: ';
    
    // Thêm các thuộc tính vào cùng một dòng nếu chúng tồn tại
   sanPhamHtml += 'Màu sắc: ' + sp.mau_sac + '; ';
   sanPhamHtml += 'Dung tích: ' + sp.dung_tich + '; ';
   sanPhamHtml += 'Loại da: ' + sp.loai_da + '; ';
    
    // Kết thúc chuỗi và đóng thẻ div
    sanPhamHtml += '</div>';
    
    // Thêm vào HTML của danh sách sản phẩm mẫu
    sanPhamMauHTML += sanPhamHtml;
});

$('#sanPhamMau').html(sanPhamMauHTML);
});



</script>
<script>
    
</script>
@endsection
