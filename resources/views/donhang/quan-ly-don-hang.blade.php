@extends('layout.index')
@section('title','Quản lý đơn hàng')
@section('css')
<style>
    .table-container {
        max-height: 400px;
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
       

        <!-- Các chức năng lọc và tìm kiếm -->
        <div class="row">
            <div class="col-md-4">
                <input type="date" class="form-control" id="filter-date">
            </div>

            <div class="col-md-4">
                <select class="form-control" id="filter-status">
                    <option value="">Chọn trạng thái</option>
                    <option value="all">Tất Cả</option>
                    <option value="chothanhtoan">Chờ Thanh Toán</option>
                    <option value="choxacnhan">Chờ Xác Nhận</option>
                    <option value="cholayhang">Chờ Lấy Hàng</option>
                    <option value="danggiahang">Đang Giao Hàng</option>
                    <option value="trahang">Trả Hàng</option>
                    <option value="duocgiao">Được Giao</option>
                    <option value="dahuy">Đã Hủy</option>
                </select>
            </div>

            <div class="col-md-4">
                <select class="form-control" id="filter-price">
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
                <input type="text" class="form-control" id="search-invoice" placeholder="Tìm kiếm mã đơn hàng">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" id="search-button">Tìm kiếm</button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" href="form-add-don-hang.html" title="Thêm"><i class="fas fa-plus"></i> Tạo mới đơn hàng</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm nhap-tu-file" type="button" title="Nhập" onclick="myFunction(this)"><i class="fas fa-file-upload"></i> Tải từ file</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i class="fas fa-print"></i> In dữ liệu</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file js-textareacopybtn" type="button" title="Sao chép"><i class="fas fa-copy"></i> Sao chép</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-excel btn-sm" href="" title="In"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm pdf-file" type="button" title="In" id="export-selected-pdf"><i class="fas fa-file-pdf"></i> Xuất PDF</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i class="fas fa-trash-alt"></i> Xóa tất cả </a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th width="10"><input type="checkbox" id="all"></th>
                            <th>Mã Đơn Đặt</th>
                            <th>Mã Voucher</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Phương Thức Thanh Toán</th>
                            <th>Số Tiền Cuối</th>
                            <th>Ngày Đặt Hàng</th>
                            <th>Trạng Thái Đơn Đặt</th>
                            {{-- <th>Trạng Thái Giao Hàng</th> --}}
                            <th>Chức Năng</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-body">
                        @foreach($donDat as $don)
                            <tr>
                                <td><input type="checkbox" class="checkbox" name='check1' value="{{ $don->ma_don_dat }}" /></td>
                                <td>{{ $don->ma_don_dat }}</td>
                                <td>{{ $don->ma_voucher ?? 'N/A' }}</td>
                                <td>{{ $don->so_dien_thoai }}</td>
                                <td>{{ $don->dia_chi_giao_hang }}</td>
                                <td>{{ $don->phuong_thuc_thanh_toan ?? 'N/A' }}</td>
                                <td>{{ number_format($don->tong_tien_cuoi_cung, 0, '.', ',') }} đ</td>
                                {{-- <td>{{ $don->trang_thai_don_dat }}</td> --}}
                                <td>{{\Carbon\Carbon::parse($don->ngay_dat)->format('d/m/Y') }}</td>
                                <td>
                                    <select class="form-control trang-thai-giao-hang" data-id="{{ $don->ma_don_dat }}">
                                        <option value="Chờ xác nhận" {{ $don->trang_thai_don_dat == 'Chờ xác nhận' ? 'selected' : '' }}>
                                            Chờ xác nhận
                                        </option>
                                        <option value="Chờ lấy hàng" {{ $don->trang_thai_don_dat == 'Chờ lấy hàng' ? 'selected' : '' }}>
                                            Chờ lấy hàng
                                        </option>
                                        <option value="Đang giao" {{ $don->trang_thai_don_dat == 'Đang giao' ? 'selected' : '' }}>
                                            Đang giao
                                        </option>
                                        <option value="Đã hoàn thành" {{ $don->trang_thai_don_dat == 'Đã hoàn thành' ? 'selected' : '' }}>
                                            Đã hoàn thành
                                        </option>
                                    </select>
                                </td>
                               
                                <td>
                                    <button class="btn btn-info show-details" 
                                            data-id="{{ $don->ma_don_dat }}">
                                        Chi Tiết
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Hóa Đơn -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1" role="dialog" aria-labelledby="invoiceDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceDetailModalLabel">Chi Tiết Hóa Đơn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Mã Đơn Hàng:</strong> <span id="invoice-id"></span></p>
                <p><strong>Mã Voucher:</strong> <span id="invoice-voucher-code"></span></p>
                <p><strong>Số Điện Thoại:</strong> <span id="invoice-phone"></span></p>
                <p><strong>Địa Chỉ:</strong> <span id="invoice-address"></span></p>
                <p><strong>Phương Thức Thanh Toán:</strong> <span id="invoice-payment-method"></span></p>
                <p><strong>Ngày Đặt Hàng:</strong> <span id="invoice-order-date"></span></p>
                <p><strong>Ngày Thanh Toán:</strong> <span id="invoice-payment-date"></span></p>
                <p><strong>Phí Vận Chuyển:</strong> <span id="invoice-delivery-fee"></span></p>
                <p><strong>Số Tiền Giảm:</strong> <span id="invoice-discount"></span></p>
                <p><strong>Số Tiền Cuối:</strong> <span id="invoice-final-amount"></span></p>
                <p><strong>Trạng Thái:</strong> <span id="invoice-status"></span></p>

                <table id="invoice-details-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã biến thể </th>
                            <th>Tên sản phẩm</th>
                            <th>Giá bán</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Chi tiết hóa đơn sẽ được load từ server -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="libs/jsPDF/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@section('js')




<script>



// $(document).ready(function() {
//     // Khi nhấn vào "Chi Tiết"
//     $('.show-details').on('click', function(e) {
//         e.preventDefault();

//         // Lấy mã đơn hàng từ data-id
//         const donDatId = $(this).data('id');

//         // Gửi yêu cầu AJAX để lấy dữ liệu chi tiết đơn hàng
//         $.ajax({
//             url: `/don-dat/${donDatId}`,  // Gửi yêu cầu đến API
//             method: 'GET',
//             success: function(data) {
//                 // Cập nhật thông tin vào modal
//                 $('#invoice-id').text(data.ma_don_dat);
//                 $('#invoice-voucher-code').text(data.hoa_don ? data.hoa_don.ma_voucher : 'Không có');
//                 $('#invoice-phone').text(data.so_dien_thoai);
//                 $('#invoice-address').text(data.dia_chi_giao_hang);
//                 $('#invoice-payment-method').text(data.hoa_don ? data.hoa_don.phuong_thuc_thanh_toan : 'N/A');
//                 $('#invoice-order-date').text(data.ngay_dat);
//                 $('#invoice-payment-date').text( data.hoa_don ? data.hoa_don.ngay_thanh_toan: 'N/A');
//                 $('#invoice-delivery-fee').text(data.hoa_don ? data.hoa_don.phi_van_chuyen: 'N/A');
//                 $('#invoice-discount').text(data.hoa_don ? data.hoa_don.so_tien_giam : '0');
//                 $('#invoice-final-amount').text(data.tong_tien);
//                 $('#invoice-status').text(data.trang_thai);

//                 // Cập nhật chi tiết sản phẩm vào bảng
//                 let detailsHtml = '';
//                 data.chi_tiet_don_dat.forEach(item => {
//                     detailsHtml += `
//                         <tr>
//                             <td>${item.ma_bien_the}</td>
//                             <td>${item.ten_san_pham}</td>
//                             <td>${item.chi_tiet_tuy_chon}</td>
//                             <td>${item.gia_ban}</td>
//                             <td>${item.so_luong}</td>
//                             <td>${(item.so_luong * item.gia_ban).toFixed(2)}</td>
//                         </tr>
//                     `;
//                 });
//                 $('#invoice-details-table tbody').html(detailsHtml);

//                 // Hiển thị modal
//                 $('#invoiceDetailModal').modal('show');
//             },
//             error: function() {
//                 alert('Lỗi khi tải dữ liệu chi tiết');
//             }
//         });
//     });
// });

//Chi Tiết
$(document).ready(function () {
    // Khi nhấn nút "Chi Tiết"
    $('.show-details').on('click', function (e) {
        e.preventDefault();

        // Lấy mã đơn đặt hàng từ data-id
        const donDatId = $(this).data('id');

        // Gửi yêu cầu AJAX để lấy dữ liệu chi tiết đơn hàng
        $.ajax({
            url: `/don-dat/${donDatId}`,
            method: 'GET',
            success: function (data) {
                // Điền thông tin vào modal
                $('#invoice-id').text(data.ma_don_dat);
                $('#invoice-voucher-code').text(data.ma_voucher || 'Không có');
                $('#invoice-phone').text(data.so_dien_thoai);
                $('#invoice-address').text(data.dia_chi_giao_hang);
                $('#invoice-payment-method').text(data.phuong_thuc_thanh_toan || 'N/A');
                $('#invoice-order-date').text(data.ngay_dat);
                $('#invoice-payment-date').text(data.ngay_thanh_toan || 'N/A');
                $('#invoice-delivery-fee').text(data.phi_van_chuyen || '0');
                $('#invoice-discount').text(data.giam_gia || '0');
                $('#invoice-final-amount').text(data.tong_tien_cuoi_cung || '0');
                $('#invoice-status').text(data.trang_thai_don_dat);

                // Cập nhật chi tiết đơn hàng
                let detailsHtml = '';
                data.chi_tiet_don_dat.forEach(item => {
                    detailsHtml += `
                        <tr>
                            <td>${item.ma_bien_the}</td>
                            <td>${item.bien_the_san_pham.san_pham.ten_san_pham}</td>
                            <td>${parseFloat(item.gia_ban).toLocaleString()} </td>
                            <td>${item.so_luong}</td>
                            <td>${parseFloat(item.gia_ban * item.so_luong).toLocaleString()} </td>
                        </tr>`;
                });
                $('#invoice-details-table tbody').html(detailsHtml);

                // Hiển thị modal
                $('#invoiceDetailModal').modal('show');
            },
            error: function () {
                alert('Không thể tải chi tiết đơn hàng. Vui lòng thử lại sau!');
            }
        });
    });
});

//lọc
$(document).ready(function () {
    // Xử lý lọc khi có thay đổi trong các bộ lọc
    $('#filter-date, #filter-status, #filter-price').on('change', function () {
        const filterDate = $('#filter-date').val();
        const filterStatus = $('#filter-status').val();
        const filterPrice = $('#filter-price').val();

        // Gửi yêu cầu AJAX để lọc dữ liệu
        $.ajax({
            url: '/locdulieuDH',
            method: 'GET',
            data: {
                date: filterDate,
                status: filterStatus,
                price: filterPrice
            },
            success: function (response) {
                // Cập nhật danh sách đơn đặt hàng
                let tableHtml = '';
                response.forEach(order => {
                    tableHtml += `
                        <tr>
                            <td><input type="checkbox" class="checkbox" name='check1' value="${order.ma_don_dat}"/></td>
                            <td>${order.ma_don_dat}</td>
                            <td>${order.ma_voucher || 'N/A'}</td>
                            <td>${order.so_dien_thoai}</td>
                            <td>${order.dia_chi_giao_hang}</td>
                            <td>${order.phuong_thuc_thanh_toan || 'N/A'}</td>
                            <td>${parseFloat(order.tong_tien_cuoi_cung).toLocaleString()} đ</td>
                            <td>${order.trang_thai_don_dat}</td>
                            <td>${order.ngay_dat}</td>
                            
                           <td>
                                <select class="form-control trang-thai-giao-hang" data-id="${order.ma_don_dat}">
                                    <option value="Chờ xác nhận" ${order.trang_thai_giao_hang === 'Chờ xác nhận' ? 'selected' : ''}>
                                        Chờ xác nhận
                                    </option>
                                    <option value="Chờ lấy hàng" ${order.trang_thai_giao_hang === 'Chờ lấy hàng' ? 'selected' : ''}>
                                        Chờ lấy hàng
                                    </option>
                                    <option value="Đang giao" ${order.trang_thai_giao_hang === 'Đang giao' ? 'selected' : ''}>
                                        Đang giao
                                    </option>
                                    <option value="Đã hoàn thành" ${order.trang_thai_giao_hang === 'Đã hoàn thành' ? 'selected' : ''}>
                                        Đã hoàn thành
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-info show-details" 
                                        data-id="${order.ma_don_dat}">
                                    Chi Tiết
                                </button>
                            </td>
                        </tr>`;
                });

                $('#invoice-body').html(tableHtml);

                // Rebind the "Chi Tiết" button click event
                $('.show-details').on('click', function (e) {
                    e.preventDefault();
                    const donDatId = $(this).data('id');
                    // Call the modal display logic here
                    $.ajax({
                        url: `/don-dat/${donDatId}`,
                        method: 'GET',
                        success: function (data) {
                            // Điền thông tin vào modal
                            $('#invoice-id').text(data.ma_don_dat);
                            $('#invoice-voucher-code').text(data.ma_voucher || 'Không có');
                            $('#invoice-phone').text(data.so_dien_thoai);
                            $('#invoice-address').text(data.dia_chi_giao_hang);
                            $('#invoice-payment-method').text(data.phuong_thuc_thanh_toan || 'N/A');
                            $('#invoice-order-date').text(data.ngay_dat);
                            $('#invoice-payment-date').text(data.ngay_thanh_toan || 'N/A');
                            $('#invoice-delivery-fee').text(data.phi_van_chuyen || '0');
                            $('#invoice-discount').text(data.giam_gia || '0');
                            $('#invoice-final-amount').text(data.tong_tien_cuoi_cung || '0');
                            $('#invoice-status').text(data.trang_thai_don_dat);

                            // Cập nhật chi tiết đơn hàng
                            let detailsHtml = '';
                            data.chi_tiet_don_dat.forEach(item => {
                                detailsHtml += `
                                    <tr>
                                        <td>${item.ma_bien_the}</td>
                                        <td>${item.ten_san_pham}</td>
                                        <td>${item.chi_tiet_tuy_chon || 'N/A'}</td>
                                        <td>${item.gia_ban}</td>
                                        <td>${item.so_luong}</td>
                                        <td>${(item.gia_ban * item.so_luong).toFixed(2)}</td>
                                    </tr>`;
                            });
                            $('#invoice-details-table tbody').html(detailsHtml);

                            // Hiển thị modal
                            $('#invoiceDetailModal').modal('show');
                        },
                        error: function () {
                            alert('Không thể tải chi tiết đơn hàng. Vui lòng thử lại sau!');
                        }
                    });
                });
                // Lắng nghe sự kiện thay đổi trên dropdown
                $('.trang-thai-giao-hang').on('change', function () {
                    let maDonDat = $(this).data('id'); // Lấy mã đơn đặt từ data-id
                    let trangThaiGiaoHang = $(this).val(); // Lấy giá trị trạng thái được chọn

                    // Gửi yêu cầu AJAX để cập nhật trạng thái
                    $.ajax({
                        url: '/capnhattrangthaiGH', // Đường dẫn xử lý trong controller
                        type: 'POST',
                        data: {
                            ma_don_dat: maDonDat,
                            trang_thai_giao_hang: trangThaiGiaoHang,
                            _token: $('meta[name="csrf-token"]').attr('content'), // Token CSRF
                        },
                        success: function (response) {
                            if (response.success) {
                                alert('Cập nhật trạng thái thành công!');
                            } else {
                                alert('Cập nhật trạng thái thất bại: ' + response.message);
                            }
                        },
                        error: function () {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        },
                    });
                });
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu. Vui lòng thử lại!');
            }
        });
    });
});

$(document).ready(function () {
    // Lắng nghe sự kiện thay đổi trên dropdown
    $('.trang-thai-giao-hang').on('change', function () {
        let maDonDat = $(this).data('id'); // Lấy mã đơn đặt từ data-id
        let trangThaiGiaoHang = $(this).val(); // Lấy giá trị trạng thái được chọn

        // Gửi yêu cầu AJAX để cập nhật trạng thái
        $.ajax({
            url: '/capnhattrangthaiGH', // Đường dẫn xử lý trong controller
            type: 'POST',
            data: {
                ma_don_dat: maDonDat,
                trang_thai_giao_hang: trangThaiGiaoHang,
                _token: $('meta[name="csrf-token"]').attr('content'), // Token CSRF
            },
            success: function (response) {
                if (response.success) {
                    alert('Cập nhật trạng thái thành công!');
                } else {
                    alert('Cập nhật trạng thái thất bại: ' + response.message);
                }
            },
            error: function () {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            },
        });
    });
});

document.getElementById("all").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".checkbox");
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

$('#export-selected-pdf').click(function() {
        var selectedInvoices = [];
        $('input[name="check1"]:checked').each(function() {
            var invoiceId = $(this).val();
            selectedInvoices.push(invoiceId);
        });

        if (selectedInvoices.length === 0) {
            alert('Vui lòng chọn ít nhất một hóa đơn!');
            return;
        }

        // Gửi danh sách hóa đơn dưới dạng mảng đến API
        $.ajax({
        url: '/export-pdf',
        method: 'GET',
        data: { don_dat: selectedInvoices },
        success: function(response) {
            // Nếu xuất PDF thành công, có thể chuyển hướng hoặc làm gì đó
            if (response.pdfPath) {
                    window.location.href = response.pdfPath;  // Chuyển hướng tới file PDF đã lưu
                }  // Chỉnh sửa theo đường dẫn thật của file PDF
        },
        error: function(xhr, status, error) {
            // Khi có lỗi xảy ra, ta sẽ log thông tin lỗi vào console và hiển thị thông báo lỗi
            console.log('Error: ' + error);  // Log thông báo lỗi ra console
            console.log('Status: ' + status);  // Log tình trạng của lỗi (ví dụ: timeout, error, v.v.)
            console.log('Response: ' + xhr.responseText);  // Log thông tin phản hồi từ server (nếu có)

            alert('Có lỗi xảy ra khi xuất PDF: ' + error);  // Hiển thị thông báo lỗi chi tiết cho người dùng
        }
    });

});
</script>


@endsection
