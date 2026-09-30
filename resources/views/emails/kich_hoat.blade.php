<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }}!</h2>
    <p>Cảm ơn bạn đã đăng ký tài khoản tại Karate-Do Shop.</p>
    <p>Nhấn vào nút bên dưới để kích hoạt tài khoản:</p>
    <p>
        <a href="{{ url('/activate/' . $token) }}"
           style="display:inline-block;padding:10px 18px;background:#dc3545;color:#fff;text-decoration:none;border-radius:4px;">
            Kích hoạt tài khoản
        </a>
    </p>
    <p>Nếu bạn không thực hiện đăng ký, hãy bỏ qua email này.</p>
</body>
</html>
