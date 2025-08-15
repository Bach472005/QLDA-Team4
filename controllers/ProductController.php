<?php
    class ProductController{
        public $userModel;

        public $productModel;
        public $commentModel;

        public function __construct(){
            $this->productModel = new ProductModel();
            $this->userModel = new UserModel();
            $this->commentModel = new CommentModel();

            session_start();
        }

        public function home_view(){
            $_SESSION["products"] = $this->productModel->get_product();
            if(isset($_SESSION["user"]["id"])){
                $user_id = $_SESSION["user"]["id"];
                $_SESSION["user"]["cart"] = $this->productModel->get_quantity_product_cart($user_id);
            }
            require './views/HomePage.php';
        }
        public function product_view(){
            $category = $this->productModel->get_table("categories");
            require_once './views/Category.php';
        }

        public function product_detail_view(){
            if(isset($_GET["product_id"])){
                $product_detail = $this->productModel->get_product_detail($_GET["product_id"]);
                $comments = $this->commentModel->get_comments($_GET["product_id"]);
                require_once './views/ProductDetail.php';
            }
        }
        public function post_comment(){
            if(isset($_GET["product_id"]) || isset($_SESSION["user"]["id"])){
                $product_id = $_GET["product_id"];
                $user_id = $_SESSION["user"]["id"];
                $rating = $_POST["rating"];
                $comment = $_POST["comment"];
                $this->commentModel->post_comment($product_id, $user_id, $rating, $comment);

                echo "<script>
                    alert('Đăng bài thành công!');
                    window.location.href = '" . BASE_URL . "?act=pd&product_id=". $product_id ."';
                  </script>";
            }
        }
        public function delete_comment(){
            if(isset($_GET["comment_id"])){
                $this->commentModel->delete_comment($_GET["comment_id"]);
                echo "<script>
                            alert('Xóa comment thành công!');
                            window.location.href = '" . BASE_URL . "?act=pd&product_id=" . $_GET["product_id"] . "';
                        </script>";
            }
        }
        public function __destruct(){
            $this->userModel = null;
            $this->productModel = null;
            $this->commentModel = null;
        }
    }