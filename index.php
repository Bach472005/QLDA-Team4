<?php

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// FILE hỗ trợ cho momo
require_once './config/momo_config.php'; // Hàm hỗ trợ


// Require toàn bộ file Controllers
require_once "./controllers/HomeController.php";
require_once "./controllers/UserController.php";
require_once "./controllers/ProductController.php";
require_once "./controllers/PaymentController.php";

// Require toàn bộ file Models
require_once "./models/UserModel.php";
require_once "./models/ProductModel.php";
require_once "./models/CommentModel.php";
require_once "./models/MoMoPayment.php";


// include('./views/components/header.php');

// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match




match ($act) {
    // Trang chủ
    '/' => (new ProductController())->home_view(),
    'contact' => (new HomeController()) -> contact_view(),
    // Product
    'pd' => (new ProductController())->product_detail_view(),
    'category' => (new ProductController())->product_view(),
    // USER
    'login' => (new UserController())->login(),
    'login_view' => (new UserController())->login_view(),
    'register' => (new UserController())->register(),
    'register_view' => (new UserController())->register_view(),
    'logout' => (new UserController())->log_out(),
    'profile' => (new UserController()) ->profile(),
    'change_password' => (new UserController()) -> change_password(),

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

    // COMMENT
    'post_comment' => (new ProductController())->post_comment(),


    // MOMO
    'order_success' => (new UserController()) -> order_success(),
    'momo' => (new PaymentController())->momo_payment(),
    'momo_return' => (new PaymentController())->momo_return(),
    'momo_notify' => (new PaymentController())->momo_notify(),
    default => require_once './views/components/404.php', // Trang lỗi 404
};

// include('./views/components/footer.php');