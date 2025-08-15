<?php 

// Require file Common
require_once '../commons/env.php'; // Khai báo biến môi trường
require_once '../commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/DashboardController.php';
require_once './controllers/ProductDetailController.php';
require_once './controllers/ProductController.php';
require_once './controllers/UserController.php';
require_once './controllers/CommentController.php';

// Require toàn bộ file Models
require_once './models/ProductModel.php';
require_once './models/ProductDetailModel.php';
require_once './models/UserModel.php';
require_once './models/DashBoardModel.php';
require_once './models/CommentModel.php';

// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match
// Các Thầy.Cô có thể dùng Switch-Case
match ($act) {
    // Trang chủ
    '/' => (new DashboardController)->dashboard(),
    // Add product
    'get_product' => (new ProductController()) ->get_product(),
    'add_product_view' => (new ProductController()) -> add_product_view(),
    'add_product' => (new ProductController()) -> add_product(),
    // Delete product
    'delete_product' => (new ProductController()) -> deleteProduct(),
    // Update product
    'get_product_id' => (new ProductController()) -> get_product_id(),
    'update_product' => (new ProductController()) -> update_product_id(),

    
    //Component product
    'product_detail_view' => (new ProductDetailController()) -> product_detail_view(),
    'add_product_detail_view' => (new ProductDetailController()) -> add_product_detail_view(),
    'add_product_detail' => (new ProductDetailController()) -> add_product_detail(), 
    'delete_product_detail' => (new ProductDetailController()) -> delete_product_detail(), 
    'get_product_detail_id' => (new ProductDetailController()) ->update_product_detail_view(),
    'update_product_detail' => (new ProductDetailController()) ->update_product_detail(),

    // Category
    'category_view' => (new CategoryController()) ->get_category(),
    'add_category_view' => (new CategoryController()) ->add_category_view(),
    'add_category' => (new CategoryController()) ->add_category(),
    'delete_category' => (new CategoryController()) -> deleteCategory(),
    'get_category_id' => (new CategoryController()) -> get_category_id(),
    'update_category' => (new CategoryController()) -> update_category(),

    // USER
    'get_user' => (new UserController()) ->get_user(),
    'delete_user' => (new UserController()) -> delete_user(),
    'update_user_status' => (new UserController()) -> update_user_status(),
    'update_user_role' => (new UserController()) -> update_user_role(),

    // ORDER
    "get_order" => (new UserController()) -> get_order(),
    "update_order" => (new UserController()) -> update_order(),

    // Comment
    "get_comment" => (new CommentController()) -> get_comment(),
    "delete_comment" => (new CommentController()) -> delete_comment(),
    default => require_once './views/404.php', // Trang lỗi 404
};