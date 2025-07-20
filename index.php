<?php

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ



// Require toàn bộ file Controllers
// Require toàn bộ file Models
$act = $_GET['act'] ?? '/';




match ($act) {
    // Trang chủ
    '/' => (new HomeController())->home_view(),
    default => require_once './views/components/404.php', // Trang lỗi 404
};
