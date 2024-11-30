<!-- resources/views/emails/password_reset.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Mật khẩu mới của bạn</title>
</head>
<body>
    <p>Chào bạn,</p>
    <p>Đây là mật khẩu mới của bạn: <strong>{{ $newPassword }}</strong></p>
    <p>Vui lòng thay đổi mật khẩu của bạn sau khi đăng nhập.</p>
</body>
</html>
