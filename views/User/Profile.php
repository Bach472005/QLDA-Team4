<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hồ Sơ Của Tôi</title>
  <style>
    .profile-wrapper {
      max-width: 1000px;
      margin: 40px auto;
      display: flex;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .profile-sidebar {
      width: 260px;
      border-right: 1px solid #eee;
      padding: 20px;
    }

    .profile-sidebar img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: block;
      margin: 0 auto 10px;
    }

    .username {
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      margin-top: 10px;
    }

    .menu {
      margin-top: 30px;
    }

    .menu-item {
      padding: 10px 15px;
      margin-bottom: 8px;
      border-radius: 4px;
      color: #444;
      cursor: pointer;
      transition: background 0.2s;
    }

    .menu-item:hover {
      background: #f0f0f0;
    }

    .menu-item.active {
      color: #ee4d2d;
      font-weight: bold;
      background: #fff5f0;
      border-left: 4px solid #ee4d2d;
    }

    .profile-content {
      flex: 1;
      padding: 20px;
    }

    .form-group {
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 6px;
      color: #555;
      font-weight: 500;
    }

    .form-group input {
      padding: 10px;
      width: 100%;
      max-width: 400px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .btn {
      padding: 10px 20px;
      background: #ee4d2d;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #d94423;
    }

    .hidden {
      display: none;
    }

    @media (max-width: 768px) {
      .profile-wrapper {
        flex-direction: column;
      }

      .profile-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #eee;
      }
    }
  </style>
</head>

<body>
  <?php include('./views/components/header.php'); ?>

  <div class="profile-wrapper">
    <div class="profile-sidebar">
      <img src="../assets/<?= htmlspecialchars($_SESSION['user']['avatar'] ?? 'img/default.png') ?>" alt="Avatar">
      <div class="username"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Người dùng') ?></div>
      <div class="menu">
        <div class="menu-item active" onclick="showTab('profile')">Hồ Sơ</div>
        <div class="menu-item" onclick="showTab('changepass')">Đổi Mật Khẩu</div>
      </div>
    </div>

    <div class="profile-content">
      <div id="tab-profile">
        <h2>Hồ Sơ Của Tôi</h2>
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="update_profile" value="1" />

          <div class="form-group">
            <label>Tên</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>" required />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" readonly />
          </div>
          <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>" />
          </div>
          <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="address" value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>" />
          </div>

          <div class="form-group">
            <label>Ảnh đại diện</label>
            <input type="file" name="avatar" accept=".png,.jpg,.jpeg">
          </div>

          <button class="btn btn-primary" type="submit">Lưu</button>
        </form>
      </div>

      <div id="tab-changepass" class="hidden">
        <h2>Đổi Mật Khẩu</h2>
        <form method="POST" action="<?= BASE_URL . '?act=change_password' ?>">
          <div class="form-group">
            <label>Mật khẩu cũ</label>
            <input type="password" name="current_password" required />
          </div>
          <div class="form-group">
            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" required />
          </div>
          <div class="form-group">
            <label>Xác nhận mật khẩu mới</label>
            <input type="password" name="confirm_password" required />
          </div>

          <button class="btn btn-primary" type="submit">Đổi mật khẩu</button>
        </form>
      </div>
    </div>
  </div>

  <?php include('./views/components/footer.php'); ?>

  <script>
    function showTab(tab) {
      document.getElementById('tab-profile').classList.add('hidden');
      document.getElementById('tab-changepass').classList.add('hidden');
      document.getElementById('tab-' + tab).classList.remove('hidden');

      const items = document.querySelectorAll('.menu-item');
      items.forEach(i => i.classList.remove('active'));
      items.forEach(i => {
        if (i.textContent.includes(tab === 'profile' ? 'Hồ Sơ' : 'Mật Khẩu')) {
          i.classList.add('active');
        }
      });
    }
  </script>
</body>

</html>