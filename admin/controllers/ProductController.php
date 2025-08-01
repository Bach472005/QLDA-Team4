<?php
    class ProductController{
        public $productModel;
        public function __construct(){
            $this->productModel = new ProductModel();
            session_start();
        }
        public function get_product(){
            $products = $this->productModel->get_list();

            require_once './views/Product/List.php';
        }

        // Add product
        public function add_product_view(){
            $categories = $this->productModel->get_table('categories');
            require_once './views/product/Add.php';
        }
        public function add_product(){
            $imgPaths = []; // Mảng lưu đường dẫn các file đã upload

            if (isset($_FILES['image_url'])) {
                $file_img = "./uploads/";
            
                // Lặp qua từng file
                foreach ($_FILES['image_url']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['image_url']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = time() . "_" . basename($_FILES["image_url"]["name"][$key]); // Đổi tên file để tránh trùng
                        $targetFile = $file_img . $fileName;
            
                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $imgPaths[] = $targetFile; // Lưu đường dẫn file thành công
                        } else {
                            echo "Error uploading file: " . $_FILES["image_url"]["name"][$key] . "<br>";
                        }
                    }
                }
            }
            

            $products = [
                "category_id" =>$_POST["category_id"],
                "name" => $_POST["name"], 
                "description" => $_POST["description"],
                "price" => $_POST["price"]
            ];
            if($products){
                $this->productModel->add_product($products);
                $product_id = $this->productModel->conn->lastInsertId();

                $Images = [
                    "product_id" => $product_id,
                    "image_url" => $imgPaths
                ];

                $this->productModel->add_image($Images);
                // echo "<script>alert('Add product success!!!') </script>";
                echo "<script> 
                        alert('Add Success');
                        window.location.href = '". BASE_URL ."/admin/?act=get_product';
                      </script>";
                // return $this->get_product();
            } else{
                echo "<script>alert('Add product fail!!!') </script>"; 
            }
        }

        // Delete product
        public function deleteProduct(){
            try {
                if(isset($_GET["id"])){
                    $id = $_GET["id"];
                    $this->productModel->delete_product($id);
                }
                echo "<script> 
                        alert('Delete Success');
                        window.location.href = '". BASE_URL ."/admin/?act=get_product';
                        </script>";
            } catch (\Throwable $th) {
                echo "<script> 
                        alert('". htmlspecialchars($th->getMessage()) ."'); 
                    </script>";
            }
            // return $this->get_product();   
        }

        // Update product 
        public function get_product_id(){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $product = $this->productModel->get_product_id($id);
                if($product["quantity"] == null){
                    $product["quantity"] = 0;
                }
                $categories = $this->productModel->get_table('categories');
                require "./views/Product/Update.php";
            } else{
                return false;
            }
        }

        public function update_product_id(){
            $imgPaths = []; // Mảng lưu đường dẫn các file đã upload
            $id = $_GET["id"];

            if (isset($_FILES['image_url'])) {
                $file_img = "./uploads/";
            
                // Lặp qua từng file
                foreach ($_FILES['image_url']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['image_url']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = time() . "_" . basename($_FILES["image_url"]["name"][$key]); // Đổi tên file để tránh trùng
                        $targetFile = $file_img . $fileName;
            
                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $imgPaths[] = $targetFile; // Lưu đường dẫn file thành công
                        } else {
                            echo "Error uploading file: " . $_FILES["image_url"]["name"][$key] . "<br>";
                        }
                    }
                }
            }
            $Images = [
                "image_url" => $imgPaths
            ];

            $new = [
                "category_id" => $_POST["category_id"],
                "name"=> $_POST["name"],
                "description" => $_POST["description"],
                "price" => $_POST["price"]
            ];
            if(isset($id, $new)){
                $this->productModel->update_product($id, $new);
                if($Images != null){
                    $this->productModel->add_image($Images, $id);
                }
                echo "<script> alert('Update product success!!!') </script>";
                return $this->get_product();
            } else{
                echo "<script> alert('Update product fail!!!') </script>";
                return null;
            }
                
        }
    } 
?>