<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ease - Đăng Ký / Đăng Nhập</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f9f9f9;
      font-family: Arial, sans-serif;
    }
    .form-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    form {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      width: 100%;
    }
    h2 {
      margin-bottom: 20px;
      align-self: center;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-bottom: 15px;
    }
    label {
      margin-bottom: 5px;
      font-weight: bold;
      text-align: left;
    }
    input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: calc(100% - 20px);
      margin: 0;
    }
    button {
      background-color: #e60023;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 5px;
      width: 100%;
    }
    button:hover {
      background-color: #c5001e;
    }
    a {
      color: #e60023;
      cursor: pointer;
    }
    p {
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Form Đăng Ký / Đăng Nhập -->
  <div class="form-container">

    <form action="<?php echo BASE_URL . '?act=login' ?>" method="POST">
      <h2>Đăng Nhập</h2>
      <div class="form-group">
        <label for="login-username">Email</label>
        <input type="email" name="email" required />
      </div>

      <div class="form-group">
        <label for="login-password">Mật Khẩu</label>
        <input type="password" name="password" required />
      </div>

      <button type="submit">Đăng Nhập</button>
      <p>Bạn chưa có tài khoản? <a href="<?php echo BASE_URL . '?act=register_view' ?>" id="switch-to-register">Đăng Ký</a></p>
    </form>
  </div>
</body>
</html>