<?php

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ



// Require toàn bộ file Controllers
require_once "./controllers/UserController.php";

// Require toàn bộ file Models
require_once "./models/UserModel.php";

$act = $_GET['act'] ?? '/';




match ($act) {
    // Trang chủ
    '/' => (new HomeController())->home_view(),
    'login' => (new UserController())->login(),
    'login_view' => (new UserController())->login_view(),
    'logout' => (new UserController())->log_out(),
    'register' => (new UserController())->register(),
    'register_view' => (new UserController())->register_view(),
    default => require_once './views/components/404.php', // Trang lỗi 404
};
