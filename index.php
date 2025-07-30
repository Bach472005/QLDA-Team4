<?php

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ



// Require toàn bộ file Controllers
require_once "./controllers/UserController.php";
require_once "./controllers/ProductController.php";

// Require toàn bộ file Models
require_once "./models/UserModel.php";
require_once "./models/ProductModel.php";

$act = $_GET['act'] ?? '/';




match ($act) {
    // Trang chủ
    '/' => (new HomeController())->home_view(),
    'login' => (new UserController())->login(),
    'login_view' => (new UserController())->login_view(),
    'logout' => (new UserController())->log_out(),
    'register' => (new UserController())->register(),
    'register_view' => (new UserController())->register_view(),

    'profile' => (new UserController()) ->profile(),
    'change_password' => (new UserController()) -> change_password(),

    // Product
    'pd' => (new ProductController())->product_detail_view(),
    'category' => (new ProductController())->product_view(),

    // CART
    'cart_view' => (new UserController())->cart_view(),
    'add_to_cart' => (new UserController())->add_to_cart(),
    'delete_cart' => (new UserController())->delete_cart(),

    // ORDER
    'order' => (new UserController())->order(),
    'order_id' => (new UserController())->order_id(),
    'add_orders' => (new UserController())->add_orders(),
    'cancelled_order' => (new UserController())->cancelled_order(),
    'delete_order' => (new UserController())->delete_order(),
    
    default => require_once './views/components/404.php', // Trang lỗi 404
};
