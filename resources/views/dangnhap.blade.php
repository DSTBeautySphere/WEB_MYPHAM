<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Đăng nhập quản trị | Website quản trị v2.0</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('images/team.jpg') }}" alt="IMG">
                </div>
                <!--=====TIÊU ĐỀ======-->
                <form class="login100-form validate-form">
                    <span class="login100-form-title">
                        <b>ĐĂNG NHẬP HỆ THỐNG POS</b>
                    </span>
                    <!--=====FORM INPUT TÀI KHOẢN VÀ PASSWORD======-->
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" placeholder="Tài khoản quản trị" name="email" id="email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-user'></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu" name="mat_khau" id="mat_khau">
                        <span class="bx fa-fw bx-hide field-icon click-eye" id="toggle-password"></span>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-key'></i>
                        </span>
                    </div>

                    <!--=====ĐĂNG NHẬP======-->
                    <div class="container-login100-form-btn">
                        <input type="button" value="Đăng nhập" id="submit"/>
                    </div>
                    <!--=====LINK TÌM MẬT KHẨU======-->
                    <div class="text-right p-t-12">
                        <a class="txt2" href="/forgot.html">
                            Bạn quên mật khẩu?
                        </a>
                    </div>

                    <!--=====FOOTER======-->
                    <div class="text-center p-t-70 txt2">
                        Phần mềm quản lý bán hàng <i class="far fa-copyright" aria-hidden="true"></i>
                        <script type="text/javascript">document.write(new Date().getFullYear());</script> 
                        <a class="txt2" href="#"> Code bởi Duy </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Javascript-->
    <!-- jQuery -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Boxicons -->
    <script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>

    <!-- Script tùy chỉnh -->
    <script src="/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Show - hide mật khẩu khi click vào icon
        $("#toggle-password").click(function () {
            console.log("Icon clicked"); // In ra console khi nhấn vào icon
            
            // Lấy trường input mật khẩu
            var passwordField = $("#password-field");
            
            // Đổi loại input giữa password và text
            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                $(this).removeClass("bx-hide").addClass("bx-show"); // Đổi icon
                console.log("Changed to text"); // Thông báo khi đổi thành text
            } else {
                passwordField.attr("type", "password");
                $(this).removeClass("bx-show").addClass("bx-hide"); // Đổi icon
                console.log("Changed to password"); // Thông báo khi đổi thành password
            }
        });
    </script>
    
<script>
$(document).ready(function() {
    // Gắn sự kiện click cho nút submit
    $('#submit').click(function(e) {
        e.preventDefault(); // Ngừng hành động mặc định nếu có
        console.log("Nút đã được nhấn"); // Kiểm tra khi bấm nút

        var email = $('#email').val(); // Lấy giá trị email
        var mat_khau = $('#mat_khau').val(); // Lấy giá trị mật khẩu

        console.log("Thông tin đăng nhập:");
        console.log("Email: " + email);  // In email ra console
        console.log("Mật khẩu: " + mat_khau); // In mật khẩu ra console

        // Gửi yêu cầu AJAX
        $.ajax({
            url: '/admin/login', // URL của API
            type: 'POST', // Phương thức POST
            dataType: 'json', // Dữ liệu trả về dạng JSON
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token
            },
            data: {
                email: email, // Dữ liệu email
                mat_khau: mat_khau, // Dữ liệu mật khẩu
            },
            success: function(response) {
                console.log("Phản hồi từ server:"); // In thông tin phản hồi từ server
                console.log(response);

                if (response.message) {
                    console.log("Thông báo thành công: " + response.message); // In thông báo thành công
                    alert("Thành công! "); // Sử dụng alert
                    window.location.href = response.redirect_url;
                }
            },
            error: function(xhr, status, error) {
                console.log("Lỗi yêu cầu AJAX:");
                console.log(xhr.responseText); // In lỗi trả về từ server
                alert("Lỗi! Email hoặc mật khẩu không đúng");
            }
        });
    });
});

</script>
</body>

</html>
