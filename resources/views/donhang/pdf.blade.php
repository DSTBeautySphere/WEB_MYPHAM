<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hóa Đơn</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .invoice {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .invoice p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .invoice-details h3 {
            margin-top: 20px;
        }

        hr {
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Hóa Đơn</h1>

    @if($donDatList && count($donDatList) > 0)
    @foreach($donDatList as $donDat)
        <div class="invoice">
            <p><strong>Mã Đơn Đặt:</strong> {{ $donDat->ma_don_dat }}</p>
            <p><strong>Tên Khách Hàng:</strong> {{ $donDat->user->ho_ten ?? 'N/A' }}</p>
            <p><strong>Số Điện Thoại:</strong> {{ $donDat->so_dien_thoai ?? 'N/A' }}</p>
            <p><strong>Địa Chỉ Giao Hàng:</strong> {{ $donDat->dia_chi_giao_hang ?? 'N/A' }}</p>
            <p><strong>Phương Thức Thanh Toán:</strong> {{ $donDat->phuong_thuc_thanh_toan ?? 'N/A' }}</p>
            <p><strong>Tổng Số Tiền:</strong> {{ number_format($donDat->tong_tien_cuoi_cung, 2) }} VNĐ</p>
            <p><strong>Trạng Thái:</strong> {{ $donDat->trang_thai_don_dat ?? 'Chưa xác định' }}</p>

            <div class="invoice-details">
                <h3>Chi Tiết Đơn Đặt</h3>
                @if($donDat->chi_tiet_don_dat && count($donDat->chi_tiet_don_dat) > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá Đơn Vị</th>
                                <th>Số Lượng</th>
                                <th>Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donDat->chi_tiet_don_dat as $chiTiet)
                                <tr>
                                    <td>{{ $chiTiet->bien_the_san_pham->san_pham->ten_san_pham ?? 'N/A' }}</td>
                                    <td>{{ number_format($chiTiet->gia_ban, 0) }} VNĐ</td>
                                    <td>{{ $chiTiet->so_luong }}</td>
                                    <td>{{ number_format($chiTiet->gia_ban*$chiTiet->so_luong, 0) }} VNĐ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Không có chi tiết đơn đặt nào.</p>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p>Không có hóa đơn nào để hiển thị.</p>
@endif

</body>
</html>
