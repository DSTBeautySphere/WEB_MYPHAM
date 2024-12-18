@extends('layout.index')
@section('title', 'Quản lý khách hàng')

@section('css')
    <style>
        .form-floating .form-select {
            background-color: #f8f9fa; /* Màu nền nhẹ */
            border: 1px solid #ddd; /* Viền nhẹ */
            border-radius: 5px; /* Góc bo tròn */
            font-size: 14px;
            padding: 10px;
            transition: all 0.2s ease-in-out;
        }

        .form-floating .form-select:hover {
            border-color: #007bff; /* Đổi màu viền khi hover */
            background-color: #ffffff; /* Làm nổi bật */
        }

        

    </style>
    

@endsection

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="text-center">Danh sách người dùng</h1>
            </div>
        </div>

        {{-- Search and Filter --}}
        <div class="row mb-4">
            {{-- <div class="col-md-6">
                <form class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm người dùng..." id="searchUser" />
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}
           
            {{-- <div class="col-md-3">
                <div class="form-floating">
                    <select id="statusFilter" class="form-select" style="width: 80%;">
                        <option value=" ">Trạng thái</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Không hoạt động</option>
                    </select>
                </div>
            </div> --}}

            <div class="col-md-6">
                <form class="d-flex" method="GET" action="{{ route('timKiem') }}">
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control me-2" 
                        placeholder="Tìm kiếm người dùng..." 
                        value="{{ request('name') }}" 
                    />
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
    
            <!-- Form lọc trạng thái -->
            <div class="col-md-3">
                <form method="GET" action="{{ route('LocUser') }}">
                    <div class="form-floating">
                        <select name="status" id="statusFilter" class="form-select" style="width: 100%;" onchange="this.form.submit()">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Trạng thái</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                </form>
            </div>

        </div>

        {{-- User Table --}}
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover" id="userTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Giới tính</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Ngày sinh</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->ma_user }}</td>
                                <td>{{ $user->ten_dang_nhap }}</td>
                                <td>{{ $user->ho_ten }}</td>
                                <td>{{ $user->gioi_tinh }}</td>
                                <td>{{ $user->so_dien_thoai }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->ngay_sinh }}</td>
                                <td>
                                    <span 
                                        class="badge {{ $user->trang_thai ? 'bg-success' : 'bg-danger' }}" 
                                        data-id="{{ $user->ma_user }}" 
                                        data-status="{{ $user->trang_thai ? 'active' : 'inactive' }}"
                                        style="cursor: pointer;">
                                        {{ $user->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm" data-id="{{ $user->ma_user }}">Chi tiết</a>
                                   
                                </td>
                            </tr>
                        @endforeach --}}
                        @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->ma_user }}</td>
                            <td>{{ $user->ten_dang_nhap }}</td>
                            <td>{{ $user->ho_ten }}</td>
                            <td>{{ $user->gioi_tinh }}</td>
                            <td>{{ $user->so_dien_thoai }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->ngay_sinh }}</td>
                            <td>
                                <span class="badge {{ $user->trang_thai == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->trang_thai == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td><a href="#" class="btn btn-info btn-sm" data-id="{{ $user->ma_user }}">Chi tiết</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Không có người dùng nào tìm thấy.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="pagination-wrapper d-flex justify-content-center">
                    {{ $users->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>           
        </div>
       
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="userDetailModalLabel">Chi tiết người dùng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div id="userInfo">
                <!-- Thông tin người dùng sẽ được hiển thị ở đây -->
            </div>
            <h5>Đơn đặt hàng</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tình trạng</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody id="orderList">
                    <!-- Danh sách đơn đặt sẽ được hiển thị ở đây -->
                </tbody>
            </table>
           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
    //     $("#statusFilter").on("change", function () {
           
    //         const status = $("#statusFilter").val();

    //         console.log({  status }); // Kiểm tra giá trị

    //         $.ajax({
    //             url: "/locUser",
    //             method: "GET",
    //             data: {status: status || "" },
    //             success: function (data) {
    //                 console.log(data); // Kiểm tra phản hồi
    //                 //updateUserList(data);
    //             },
    //             error: function (err) {
    //                 console.error("Lỗi khi lọc dữ liệu:", err.responseText); // Kiểm tra lỗi
    //             },
    //         });
    //     });

    //     $("#searchUser").on("change", function () {
           
    //         const name = $(this).val();

    //        $.ajax({
    //            url: "/timKiem",
    //            method: "GET",
    //            data: {name },
    //            success: function (data) {
    //                console.log(data); // Kiểm tra phản hồi
    //                updateUserList(data);
    //            },
    //            error: function (err) {
    //                console.error("Lỗi khi lọc dữ liệu:", err.responseText); // Kiểm tra lỗi
    //            },
    //        });
    //    });

     
    });
    // $(document).ready(function () {
    //     // Lọc theo trạng thái
    //     $("#statusFilter").on("change", function () {
    //         const status = $("#statusFilter").val();
    //         fetchUsers({ status: status || "" }, "/locUser");
    //     });

    //     // Tìm kiếm theo tên
    //     $("#searchUser").on("change", function () {
    //         const name = $(this).val();
    //         fetchUsers({ name }, "/timKiem");
    //     });

    //     // Sự kiện phân trang
    //     $("#userTable").on("click", ".pagination a", function (e) {
    //         e.preventDefault();
    //         const url = $(this).attr("href"); // URL phân trang
    //         fetchUsers({}, url);
    //     });

    //     // Hàm gửi AJAX để lấy dữ liệu
    //     function fetchUsers(filters = {}, url = "/locUser") {
    //         $.ajax({
    //             url: url, // URL lọc hoặc tìm kiếm
    //             method: "GET",
    //             data: filters, // Truyền thêm tham số lọc hoặc tìm kiếm
    //             success: function (response) {
    //                 console.log(response); // Kiểm tra phản hồi
    //                 updateUserList(response); // Cập nhật bảng và phân trang
    //             },
    //             error: function (err) {
    //                 console.error("Lỗi khi tải dữ liệu:", err.responseText); // Kiểm tra lỗi
    //             },
    //         });
    //     }

    //     // Cập nhật nội dung bảng và phân trang
    //     function updateUserList(data) {
    //         const tableBody = $("#userTable tbody");
    //         const paginationWrapper = $(".pagination-wrapper");

    //         // Làm trống bảng và thêm dữ liệu mới
    //         tableBody.empty();
    //         data.data.forEach((user, index) => {
    //             const row = `
    //                 <tr>
    //                     <td>${user.ma_user}</td>
    //                     <td>${user.ten_dang_nhap}</td>
    //                     <td>${user.ho_ten}</td>
    //                     <td>${user.gioi_tinh}</td>
    //                     <td>${user.so_dien_thoai}</td>
    //                     <td>${user.email}</td>
    //                     <td>${user.ngay_sinh}</td>
    //                     <td>
    //                         <span 
    //                             class="badge ${user.trang_thai ? 'bg-success' : 'bg-danger'}" 
    //                             data-id="${user.ma_user}" 
    //                             data-status="${user.trang_thai ? 'active' : 'inactive'}">
    //                             ${user.trang_thai ? 'Hoạt động' : 'Không hoạt động'}
    //                         </span>
    //                     </td>
    //                     <td>
    //                         <a href="#" class="btn btn-info btn-sm" data-id="${user.ma_user}">Chi tiết</a>
    //                     </td>
    //                 </tr>
    //             `;
    //             tableBody.append(row);
    //         });

    //         // Cập nhật phân trang
    //         paginationWrapper.html(data.links);
    //     }
    // });

    // Lắng nghe sự kiện click trên nút "Chi tiết"
    $(document).on('click', '.btn-info', function(e) {
        e.preventDefault();

        // Lấy ID người dùng từ dữ liệu của dòng trong bảng
        var userId = $(this).data('id');

        // Gửi yêu cầu AJAX để lấy thông tin người dùng và đơn đặt hàng
        $.ajax({
            url: '/thongTinUser/' + userId,
            method: 'GET',
            success: function(response) {
                // Hiển thị thông tin người dùng
                $('#userInfo').html(`
                    <p><strong>Tên đăng nhập:</strong> ${response.ten_dang_nhap}</p>
                    <p><strong>Họ tên:</strong> ${response.ho_ten}</p>
                    <p><strong>Giới tính:</strong> ${response.gioi_tinh}</p>
                    <p><strong>Số điện thoại:</strong> ${response.so_dien_thoai}</p>
                    <p><strong>Email:</strong> ${response.email}</p>
                    <p><strong>Ngày sinh:</strong> ${response.ngay_sinh}</p>
                `);

                // Hiển thị các đơn đặt hàng
                var orderList = $('#orderList');
                orderList.empty();
                var totalAmount = 0; // Khởi tạo tổng tiền

            response.don_dat.forEach(function(order) {
                // Cộng dồn tổng tiền
                totalAmount += order.tong_tien; 

                // Hiển thị từng đơn đặt hàng
                orderList.append(`
                    <tr>
                        <td>${order.ma_don_dat}</td>
                        <td>${order.ngay_dat}</td>
                        <td>${order.trang_thai_don_dat}</td>
                     
                        <td>${parseFloat(order.tong_tien_cuoi_cung).toLocaleString()} đ</td>
                    </tr>
                `);
            });

            // Hiển thị tổng tiền cuối cùng
          


                // Hiển thị modal
                $('#userDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Lỗi khi tải thông tin người dùng.');
            }
        });
    });

  
    $(document).on('click', '.badge', function() {
        var badgeElement = $(this); // Lưu tham chiếu đến element .badge
        var userId = badgeElement.data('id'); // Lấy ID người dùng
        var currentStatus = badgeElement.data('status'); // Lấy trạng thái hiện tại (active/inactive)

        // Chuyển trạng thái (nếu là 'active' thì chuyển thành 'inactive', ngược lại)
        var newStatus = (currentStatus === 'active') ? 'inactive' : 'active';

        // Gửi yêu cầu AJAX để cập nhật trạng thái
        $.ajax({
            url: '/update-status/' + userId, // URL route để cập nhật trạng thái
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    // Cập nhật lại giao diện (thay đổi badge và trạng thái)
                    if (newStatus === 'active') {
                        badgeElement.removeClass('bg-danger').addClass('bg-success').text('Hoạt động');
                    } else {
                        badgeElement.removeClass('bg-success').addClass('bg-danger').text('Không hoạt động');
                    }

                    // Cập nhật lại thuộc tính 'data-status' của badge
                    badgeElement.data('status', newStatus);
                } else {
                    alert('Cập nhật trạng thái thất bại.');
                }
            },
            error: function(xhr, status, error) {
                alert('Lỗi khi cập nhật trạng thái người dùng.');
            }
        });
    });

</script>
    
@endsection