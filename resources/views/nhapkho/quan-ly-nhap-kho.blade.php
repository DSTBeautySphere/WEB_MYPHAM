@extends('layout.index')
@section('title','Quản Lý Nhập Kho')
@section('css')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .form-inputs {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-inputs .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-inputs .form-group label {
        font-weight: bold;
    }

    .form-inputs .form-group input,
    .form-inputs .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    #sampleTable {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd; /* Viền ngoài bảng */
    min-height: 200px; /* Chiều cao tối thiểu của bảng */
}

#sampleTable th, #sampleTable td {
    border: 1px solid #ddd; /* Viền cho từng ô */
    text-align: center; /* Căn giữa nội dung */
    padding: 8px;
}

#sampleTable tbody tr.empty-row td {
    text-align: center;
    color: #aaa; /* Màu sắc cho dòng thông báo */
    font-style: italic; /* Kiểu chữ nghiêng */
}
.table-responsive {
    max-height: 400px; /* Chiều cao tối đa của bảng */
    overflow-y: auto; /* Thêm thanh cuộn dọc */
    border: 1px solid #ddd; /* Đường viền cho khu vực cuộn */
    position: relative; /* Đảm bảo tiêu đề có thể dính */
}

#sampleTable thead {
    position: sticky; /* Tiêu đề cố định */
    top: 0; /* Dính ở phía trên */
    background-color: #f8f9fa; /* Màu nền cho tiêu đề để rõ ràng */
    z-index: 1; /* Đảm bảo tiêu đề nằm trên các nội dung khác */
    border-bottom: 1px solid #ddd; /* Viền dưới cho tiêu đề */
}


</style>
@endsection

@section('content')

<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
       
            <div class="form-inputs">
                <div class="form-group">
                    <label for="supplier">Nhà Cung Cấp</label>
                    <select id="supplier" class="form-control">
                        <option value="" selected>-- Chọn nhà cung cấp --</option>
                       
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Giá Nhập</label>
                    <input type="number" id="price" class="form-control" placeholder="Nhập giá nhập">
                </div>

                <div class="form-group">
                    <label for="quantity">Số Lượng</label>
                    <input type="number" id="quantity" class="form-control" placeholder="Nhập số lượng">
                </div>

                <div class="form-group">
                    <label for="vat">VAT (%)</label>
                    <input type="number" id="vat" class="form-control" placeholder="Nhập VAT (%)">
                </div>

                <div class="form-group">
                    <label for="discount">Chiết Khấu (%)</label>
                    <input type="number" id="discount" class="form-control" placeholder="Nhập chiết khấu (%)">
                </div>

                {{-- <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="handleAdd()">Thêm Vào Danh Sách</button>
                </div> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th width="10"><input type="checkbox" id="all_product"></th>
                            <th>Mã Phiếu Nhập</th>
                            <th>Nhà Cung Cấp</th>
                            <th>Màu Sắc</th>
                            <th>Loại Da</th>
                            <th>Dung Tích</th>
                            <th>Số Lượng Tồn</th>
                            <th>Giá</th>
                            <th>Func</th>
                        </tr>
                    </thead>
                    <tbody id="product-body">
                        <tr class="empty-row">
                            <td colspan="9">Không có dữ liệu</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button style="margin-top: 5px" class="btn btn-primary">Chọn Nhiều Sản Phẩm</button>
            

            <h4>Danh Sách Sản Phẩm Tạm Thêm</h4>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tempProductTable">
                    <thead>
                        <tr>
                            <th>Mã Sản Phẩm</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Màu Sắc</th>
                            <th>Loại Da</th>
                            <th>Dung Tích</th>
                            <th>Số Lượng</th>
                            <th>Giá Nhập</th>
                            <th>VAT</th>
                            <th>Chiết Khấu</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="tempProductBody">
                        <tr class="empty-row">
                            <td colspan="10">Chưa có sản phẩm nào được thêm.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            
        </div>
        <div class="tile-body">
            
        </div>
    </div>
</div>

@endsection

@section('js')

<script>
    $(document).ready(function () {
        // Danh sách các sản phẩm đã chọn
        let selectedProducts = [];

        // Sự kiện thay đổi của checkbox "Chọn tất cả"
        $('#all_product').change(function () {
            let isChecked = $(this).is(':checked');
            $('.product-checkbox').prop('checked', isChecked);

            // Cập nhật danh sách ID sản phẩm
            if (isChecked) {
                selectedProducts = $('.product-checkbox').map(function () {
                    return $(this).data('id'); // Lấy ID từ thuộc tính data-id
                }).get();
            } else {
                selectedProducts = [];
            }
            console.log('Danh sách sản phẩm đã chọn:', selectedProducts);
        });

        // Sự kiện thay đổi của từng checkbox sản phẩm
        $(document).on('change', '.product-checkbox', function () {
            let productId = $(this).data('id');
            if ($(this).is(':checked')) {
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId); // Thêm vào danh sách
                }
            } else {
                selectedProducts = selectedProducts.filter(id => id !== productId); // Xóa khỏi danh sách
            }

            // Kiểm tra và cập nhật trạng thái của "Chọn tất cả"
            let allChecked = $('.product-checkbox').length === $('.product-checkbox:checked').length;
            $('#all_product').prop('checked', allChecked);

            console.log('Danh sách sản phẩm đã chọn:', selectedProducts);
        });

        // Tải nhà cung cấp
        $.ajax({
            url: '/laynhacungcap',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#supplier').find('option:not(:first)').remove();
                if (response && Array.isArray(response)) {
                    response.forEach(function (supplier) {
                        $('#supplier').append(
                            `<option value="${supplier.ma_nha_cung_cap}">${supplier.ten_nha_cung_cap}</option>`
                        );
                    });
                } else {
                    alert('Không có dữ liệu nhà cung cấp!');
                }
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi tải nhà cung cấp:', error);
                alert('Không thể tải danh sách nhà cung cấp.');
            },
        });

        // Tải sản phẩm theo nhà cung cấp
        $('#supplier').change(function () {
            var supplierId = $(this).val();

            if (supplierId) {
                $.ajax({
                    url: '/laysanphamtheonhacungcap',
                    method: 'GET',
                    data: { ma_nha_cung_cap: supplierId },
                    success: function (response) {
                        $('#product-body').empty();
                        if (response.length > 0) {
                            response.forEach(function (item) {
                                var row = `
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" data-id="${item.ma_bien_the}"></td>
                                        <td>${item.ma_bien_the}</td>
                                        <td>${item.ten_san_pham}</td>
                                        <td>${item.mau_sac}</td>
                                        <td>${item.loai_da}</td>
                                        <td>${item.dung_tich}</td>
                                        <td>${item.so_luong_ton_kho}</td>
                                        <td>${item.gia_ban}</td>
                                        <td>
                                            <button class="btn btn-info">Chọn</button>
                                        </td>
                                    </tr>
                                `;
                                $('#product-body').append(row);
                            });
                        } else {
                            $('#product-body').append('<tr class="empty-row"><td colspan="9">Không có dữ liệu</td></tr>');
                        }
                    },
                    error: function () {
                        alert('Đã xảy ra lỗi khi lấy dữ liệu.');
                    }
                });
            } else {
                $('#product-body').empty();
                $('#product-body').append('<tr class="empty-row"><td colspan="9">Không có dữ liệu</td></tr>');
            }
        });
    });



</script>

@endsection
