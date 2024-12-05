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

                {{-- <div class="form-group">
                    <label for="price">Giá Nhập</label>
                    <input type="number" id="price" class="form-control" placeholder="Nhập giá nhập">
                </div> --}}

                <div class="form-group">
                    <label for="quantity">Số Lượng</label>
                    <input type="number" id="quantity" class="form-control" placeholder="Nhập số lượng">
                </div>

                <div class="form-group">
                    <label for="vat">VAT (%)</label>
                    <input type="number" id="vat" class="form-control" placeholder="Nhập VAT (%)">
                </div>

                <div class="form-group">
                    <label for="discount">Lãi (%)</label>
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
                            <th>Mã biến thể</th>
                            <th>Tên sản phẩm</th>
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

            <button style="margin-top: 5px" class="btn btn-primary btn-add-multiple" id="themNhieu">Chọn Nhiều Sản Phẩm</button>
            

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
                            <th>Lãi</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="tempProductBody">
                        <tr class="empty-row">
                            <td colspan="10">Chưa có sản phẩm nào được thêm.</td>
                        </tr>
                    </tbody>
                </table>
                <button id="btnAddPhieuNhap" class="btn btn-primary">Thêm Phiếu Nhập</button>
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

            // // Cập nhật danh sách ID sản phẩm
            // if (isChecked) {
            //     selectedProducts = $('.product-checkbox').map(function () {
            //         return $(this).data('id'); // Lấy ID từ thuộc tính data-id
            //     }).get();
            // } else {
            //     selectedProducts = [];
            // }
            // console.log('Danh sách sản phẩm đã chọn:', selectedProducts);
        });

        // Sự kiện thay đổi của từng checkbox sản phẩm
        $(document).on('change', '.product-checkbox', function () {
            // let productId = $(this).data('id');
            // if ($(this).is(':checked')) {
            //     if (!selectedProducts.includes(productId)) {
            //         selectedProducts.push(productId); // Thêm vào danh sách
            //     }
            // } else {
            //     selectedProducts = selectedProducts.filter(id => id !== productId); // Xóa khỏi danh sách
            // }

            // // Kiểm tra và cập nhật trạng thái của "Chọn tất cả"
            // let allChecked = $('.product-checkbox').length === $('.product-checkbox:checked').length;
            // $('#all_product').prop('checked', allChecked);

            // console.log('Danh sách sản phẩm đã chọn:', selectedProducts);
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
                                        <td>${parseFloat(item.gia_ban).toLocaleString()}</td>
                                       
                                        <td>
                                            <button class="btn btn-info btn-add-temp" data-id="${item.ma_bien_the}">Chọn</button>
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

          // Sự kiện khi nhấn nút "Chọn" sản phẩm
        $(document).on('click', '.btn-add-temp', function () {
            const row = $(this).closest('tr');
            const maBienThe = $(this).data('id');
            const tenSanPham = row.find('td:nth-child(3)').text();
            const mauSac = row.find('td:nth-child(4)').text();
            const loaiDa = row.find('td:nth-child(5)').text();
            const dungTich = row.find('td:nth-child(6)').text();
            const giaBan = row.find('td:nth-child(8)').text();
            
            const soLuong = $('#quantity').val();
            const vat = $('#vat').val();
            const discount = $('#discount').val();
            // Kiểm tra dữ liệu nhập vào
            if (!soLuong || !vat || !discount) {
                alert('Vui lòng nhập đầy đủ thông tin: Số lượng, VAT, Chiết khấu.');
                return;
            }

            // Kiểm tra sản phẩm đã tồn tại trong danh sách tạm hay chưa
            const exists = selectedProducts.find(product => product.maBienThe === maBienThe);
            if (exists) {
                alert('Sản phẩm này đã được thêm vào danh sách tạm.');
                return;
            }

            // Thêm vào mảng danh sách tạm
            selectedProducts.push({
                maBienThe,
                tenSanPham,
                mauSac,
                loaiDa,
                dungTich,
                soLuong,
                giaBan,
                vat,
                discount
            });

            // Cập nhật bảng "Danh Sách Sản Phẩm Tạm Thêm"
            updateTempProductTable();
        });
            // them nhieu san pham
        $(document).on('click', '.btn-add-multiple', function () {
            // Lặp qua các checkbox được chọn
            $('.product-checkbox:checked').each(function () {

                const row = $(this).closest('tr');
                // Kiểm tra nếu dòng này có số cột ít hơn số cần thiết, bỏ qua dòng này
                if (row.find('td').length < 8) {
                    console.warn('Dòng dữ liệu không hợp lệ:', row.html());
                    return; // Bỏ qua dòng này
                }
                const maBienThe = $(this).data('id');
                const tenSanPham = row.find('td:nth-child(3)').text();
                const mauSac = row.find('td:nth-child(4)').text();
                const loaiDa = row.find('td:nth-child(5)').text();
                const dungTich = row.find('td:nth-child(6)').text();
                const giaBan = row.find('td:nth-child(8)').text();

                const soLuong = $('#quantity').val() || 1; // Giá trị mặc định nếu không nhập
                const vat = $('#vat').val() || 0; // VAT mặc định nếu không nhập
                const discount = $('#discount').val() || 0; // Discount mặc định nếu không nhập
                // Kiểm tra nếu bất kỳ cột nào trả về `undefined` hoặc trống
                if (!maBienThe || !tenSanPham || !mauSac || !loaiDa || !dungTich || !giaBan) {
                    console.warn('Thông tin dòng không đầy đủ:', { maBienThe, tenSanPham, mauSac, loaiDa, dungTich, giaBan });
                    return; // Bỏ qua dòng này
                }
                // Kiểm tra sản phẩm đã tồn tại trong danh sách tạm hay chưa
                const exists = selectedProducts.find(product => product.maBienThe === maBienThe);
                if (exists) {
                    alert(`Sản phẩm ${tenSanPham} đã tồn tại trong danh sách tạm.`);
                    return;
                }
                console.log('Dòng được chọn:', row.html());
                // Thêm sản phẩm vào danh sách tạm
                selectedProducts.push({
                    maBienThe,
                    tenSanPham,
                    mauSac,
                    loaiDa,
                    dungTich,
                    soLuong,
                    giaBan,
                    vat,
                    discount
                });
            });

            // Cập nhật bảng "Danh Sách Sản Phẩm Tạm Thêm"
            updateTempProductTable();

            // Bỏ chọn tất cả checkbox sau khi thêm
            $('.product-checkbox:checked').prop('checked', false);
            $('#all_product').prop('checked', false);
        });


        // Hàm cập nhật bảng "Danh Sách Sản Phẩm Tạm Thêm"
        function updateTempProductTable() {
            const tbody = $('#tempProductBody');
            tbody.empty();

            if (selectedProducts.length === 0) {
                tbody.append('<tr class="empty-row"><td colspan="10">Chưa có sản phẩm nào được thêm.</td></tr>');
                return;
            }

            selectedProducts.forEach((product, index) => {
                const row = `
                    <tr>
                        
                        <td>${product.maBienThe}</td>
                        <td>${product.tenSanPham}</td>
                        <td>${product.mauSac}</td>
                        <td>${product.loaiDa}</td>
                        <td>${product.dungTich}</td>
                        <td><input type="number" class="form-control temp-soLuong" value="${product.soLuong}" data-index="${index}"></td>
                        <td><input type="float" class="form-control temp-giaNhap" value="" data-index="${index}"></td>
                        <td><input type="number" class="form-control temp-vat" value="${product.vat}" data-index="${index}"></td>
                        <td><input type="number" class="form-control temp-discount" value="${product.discount}" data-index="${index}"></td>
                        <td>
                            <button class="btn btn-danger btn-remove-temp" data-index="${index}">Xóa</button>
                            
                        </td>
                        
                    </tr>
                `;
                tbody.append(row);
            });
            // Thêm sự kiện khi người dùng chỉnh sửa dữ liệu
            tbody.find('.temp-soLuong').on('input', function () {
                const index = $(this).data('index');
                selectedProducts[index].soLuong = parseInt($(this).val(), 10) || 0; // Cập nhật số lượng
            });

            tbody.find('.temp-giaNhap').on('input', function () {
                const index = $(this).data('index');
                selectedProducts[index].giaNhap = parseFloat($(this).val()) || 0; // Cập nhật giá nhập
            });

            tbody.find('.temp-vat').on('input', function () {
                const index = $(this).data('index');
                selectedProducts[index].vat = parseInt($(this).val(), 10) || 0; // Cập nhật VAT
            });

            tbody.find('.temp-discount').on('input', function () {
                const index = $(this).data('index');
                selectedProducts[index].discount = parseInt($(this).val(), 10) || 0; // Cập nhật giảm giá
            });
        }

        // Sự kiện xóa sản phẩm khỏi danh sách tạm
        $(document).on('click', '.btn-remove-temp', function () {
            const index = $(this).data('index');
            selectedProducts.splice(index, 1);
            updateTempProductTable();
        });


        $('#btnAddPhieuNhap').on('click', function () {
            // Thu thập thông tin phiếu nhập
            const nhaCungCap = $('#supplier').val();
            const ghiChu = $('#note').val();
            const tongSoLuong = selectedProducts.reduce((total, product) => total + parseInt(product.soLuong || 0), 0);
            const tongGiaTri = selectedProducts.reduce((total, product) => {
                const giaNhap = parseFloat(product.giaNhap || 0);
                const soLuong = parseInt(product.soLuong || 0);
                return total + (giaNhap * soLuong);
            }, 0);

            // Kiểm tra dữ liệu hợp lệ
            if (!nhaCungCap || selectedProducts.length === 0) {
                alert('Vui lòng chọn nhà cung cấp và thêm sản phẩm.');
                return;
            }
             // Tính giá bán cho từng sản phẩm
            const vat = parseFloat($('#vat').val()) || 10; // VAT mặc định 10% nếu không nhập
            const lai = parseFloat($('#discount').val()) || 20; // Lãi mặc định 20% nếu không nhập
            selectedProducts.forEach((product) => {
                const giaNhap = parseFloat(product.giaNhap || 0);
                product.giaBan = giaNhap * (1 + lai / 100) * (1 + vat / 100);
            });

            // Tạo dữ liệu gửi đi
            const data = {
                ma_nha_cung_cap: nhaCungCap,
                ghi_chu: ghiChu || '',
                tong_so_luong: tongSoLuong,
                tong_gia_tri: tongGiaTri,
                chi_tiet_phieu_nhap: selectedProducts,
            };

            // Gửi dữ liệu qua AJAX
            $.ajax({
                url: '/themphieunhap', // Đường dẫn tới route xử lý thêm phiếu nhập
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    if (response.success) {
                        alert('Thêm phiếu nhập thành công!');
                        // Reset giao diện
                        $('#supplier').val('');
                        $('#note').val('');
                        selectedProducts = [];
                        updateTempProductTable();
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Lỗi khi thêm phiếu nhập:', error);
                    alert('Không thể thêm phiếu nhập.');
                },
            });
        });

        

    });



</script>

@endsection
