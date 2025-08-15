<?php
    class Connect{
        public $conn;
        public function __construct(){
            $this->conn = connect_db();
        }
        public function __destruct(){
            $this->conn = null;
        }
        public function get_table($table_name, $conditions = [], $columns = "*", $limit = null) {
            $sql = "SELECT $columns FROM $table_name";
            $params = [];
        
            if (!empty($conditions)) {
                $sql .= " WHERE ";
                $whereClauses = [];
                
                foreach ($conditions as $column => $value) {
                    $whereClauses[] = "$column = :$column";
                    $params[":$column"] = $value;
                }
                
                $sql .= implode(" AND ", $whereClauses);
            }
        
            if ($limit) {
                $sql .= " LIMIT :limit";
            }
        
            $data = $this->conn->prepare($sql);
            
            foreach ($params as $param => $value) {
                $data->bindValue($param, $value);
            }
        
            if ($limit) {
                $data->bindValue(":limit", $limit, PDO::PARAM_INT);
            }
        
            $data->execute();
            return $data->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }


    class ProductModel extends Connect{
        public $conn;
        // CRUD Product
        public function get_list(){
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.name, 
                        p.description, 
                        p.price, 
                        p.quantity, 
                        GROUP_CONCAT(i.image_url SEPARATOR ', ') AS images
                    FROM products AS p
                    LEFT JOIN images AS i ON p.id = i.product_id
                    GROUP BY p.id";
            $data = $this->conn->prepare($sql);
            $data->execute();
            return $data->fetchAll(PDO::FETCH_ASSOC);
        }

        public function add_product($product){
            $sql = "INSERT INTO products (category_id, name, description, price) VALUES (:category_id, :name, :description, :price)";

            $data = $this->conn->prepare($sql);
            $data->bindParam(":category_id", $product["category_id"]);
            $data->bindParam(":name", $product["name"]);
            $data->bindParam(":description", $product["description"]);
            $data->bindParam(":price", $product["price"]);
            $data->execute();
            
        }

        public function delete_product($id){
            $sql = "DELETE FROM products WHERE id = :id";
            $data= $this->conn->prepare($sql);
            $data->bindParam(":id", $id);
            return $data->execute();
        }

        public function get_product_id($id){
            if($this->conn){
                $sql = "SELECT 
                            p.id, 
                            p.category_id, 
                            p.name, 
                            p.description, 
                            p.price, 
                            p.quantity, 
                            GROUP_CONCAT(i.image_url SEPARATOR ', ') AS images
                        FROM products AS p
                        LEFT JOIN images AS i ON p.id = i.product_id
                        WHERE p.id = :id
                        GROUP BY p.id";
                $data = $this->conn->prepare($sql);
                $data->bindParam(":id", $id);
                $data->execute();
                return $data->fetch(PDO::FETCH_ASSOC);
            } else{
                return false;
            }
        }

        public function update_product($id, $new){
            if($this->conn){
                $sql = "UPDATE products SET id = :id, category_id = :category_id, name = :name, description = :description, price = :price WHERE id = :id";
                $data = $this->conn->prepare($sql);
                $data->bindParam(":category_id", $new["category_id"]);
                $data->bindParam(":name", $new["name"]);
                $data->bindParam(":description", $new["description"]);
                $data->bindParam(":price", $new["price"]);
                // $data->bindParam(":quantity", $new["quantity"]);
                $data->bindParam(":id", $id);

                $data->execute();
            } else{
                return false;
            }
            
        }

        // IMAGES
        public function add_image($Image, $product_id = null){
            try {
                if ($product_id === null) {
                    // Trường hợp thêm mới ảnh (sử dụng product_id từ mảng $Image)
                    if (!isset($Image["product_id"]) || !is_array($Image["image_url"])) {
                        throw new Exception("Dữ liệu không hợp lệ");
                    }
                    
                    $sql = "INSERT INTO images (`product_id`, `image_url`) VALUES (:product_id, :image_url)";
                    $stmt = $this->conn->prepare($sql);
        
                    foreach ($Image["image_url"] as $imgUrl) {
                        $stmt->bindValue(":product_id", $Image["product_id"], PDO::PARAM_INT);
                        $stmt->bindValue(":image_url", $imgUrl, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                } else {
                    // Cập nhật ảnh của sản phẩm (Xóa ảnh cũ rồi thêm ảnh mới)
                    $this->conn->beginTransaction(); // Bắt đầu transaction
        
                    // Xóa ảnh cũ
                    $sqlDelete = "DELETE FROM images WHERE product_id = :product_id";
                    $deleteStmt = $this->conn->prepare($sqlDelete);
                    $deleteStmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                    $deleteStmt->execute();
        
                    // Kiểm tra nếu có ảnh mới để thêm vào
                    if (isset($Image["image_url"]) && is_array($Image["image_url"])) {
                        $sqlInsert = "INSERT INTO images (product_id, image_url) VALUES (:product_id, :image_url)";
                        $insertStmt = $this->conn->prepare($sqlInsert);
        
                        foreach ($Image["image_url"] as $imgUrl) {
                            $insertStmt->bindValue(":product_id", $product_id, PDO::PARAM_INT);
                            $insertStmt->bindValue(":image_url", $imgUrl, PDO::PARAM_STR);
                            $insertStmt->execute();
                        }
                    }
        
                    $this->conn->commit(); // Lưu thay đổi nếu không có lỗi
                }
            } catch (Exception $e) {
                $this->conn->rollBack(); // Nếu lỗi, hoàn tác transaction
                echo "Lỗi: " . $e->getMessage();
            }
        }
        

        public function update_image($id, $Images){
            $sql = "DELETE images where product_id = :id;
                    INSERT INTO images ";
        }

    }
    class CategoryModel extends Connect{
        public function get_list(){
            $sql = "SELECT * FROM categories";
            $data = $this->conn->prepare($sql);
            $data->execute();
            return $data->fetchAll(PDO::FETCH_ASSOC);
        }
        public function add_categories($category){
            $sql = "INSERT INTO categories (category_name) VALUES (:category_name)";

            $data = $this->conn->prepare($sql);
            $data->bindParam(":category_name", $category["category_name"]);
            $data->execute();
        }
        public function delete_category($id){
            $sql = "DELETE FROM categories WHERE id = :id";
            $data= $this->conn->prepare($sql);
            $data->bindParam(":id", $id);
            return $data->execute();
        }

        public function get_category_id($id){
            if($this->conn){
                $sql = "SELECT * from categories WHERE id = :id";
                $data = $this->conn->prepare($sql);
                $data->bindParam(":id", $id);
                $data->execute();
                return $data->fetch(PDO::FETCH_ASSOC);
            } else{
                return false;
            }
        }

        public function update_category($id, $new){
            if($this->conn){
                $sql = "UPDATE categories SET category_name = :category_name WHERE id = :id";
                $data = $this->conn->prepare($sql);
                $data->bindParam(":category_name", $new["category_name"]);
                $data->bindParam(":id", $id);

                $data->execute();
            } else{
                return false;
            }
            
        }
        
    }