<?php
    class ProductDetailModel extends Connect {
        public function get_product_detail($product_id){
            $sql = "SELECT 
                        pd.id AS product_detail_id,
                        pd.product_id,
                        pd.stock,
                        c.color_name,
                        c.color_code,
                        s.size_name
                    FROM product_detail pd
                    JOIN colors c ON pd.color_id = c.id
                    JOIN sizes s ON pd.size_id = s.id
                    WHERE pd.product_id = :product_id";
            $data = $this->conn->prepare($sql);
            $data->bindParam(":product_id", $product_id);
            $data->execute();
            return $data->fetchAll(PDO::FETCH_ASSOC);
        }
        public function add_product_detail($product_detail){
            $sqlInsert = "INSERT INTO `product_detail`
                                        (product_id, size_id, color_id, stock)
                                    VALUES (:product_id, :size_id, :color_id, :stock)";
                        $data = $this->conn->prepare($sqlInsert);
                        $data->bindParam(":product_id", $product_detail["product_id"]);
                        $data->bindParam(":size_id", $product_detail["size_id"]);
                        $data->bindParam(":color_id", $product_detail["color_id"]);
                        $data->bindParam(":stock", $product_detail["stock"], PDO::PARAM_INT);
                        $data->execute();

                        // Sửa lỗi ở đây: Sử dụng đúng biến `$sqlUpdate`
                        $sqlUpdate = "UPDATE `products` 
                                    SET quantity = quantity + :stock 
                                    WHERE id = :product_id";
                        $dataUpdate = $this->conn->prepare($sqlUpdate);
                        $dataUpdate->bindParam(":product_id", $product_detail["product_id"]);
                        $dataUpdate->bindParam(":stock", $product_detail["stock"], PDO::PARAM_INT);
                        $dataUpdate->execute();
        }
        public function delete_product_detail($id, $product_id, $stock){
            $sql = "DELETE from product_detail where id = :id";
            $data= $this->conn->prepare($sql);
            $data->bindParam(":id", $id);
            $data->execute();

            $sqlUpdate = "UPDATE `products` 
                                    SET quantity = quantity - :stock 
                                    WHERE id = :product_id";
                        $dataUpdate = $this->conn->prepare($sqlUpdate);
                        $dataUpdate->bindParam(":product_id", $product_id);
                        $dataUpdate->bindParam(":stock", $stock, PDO::PARAM_INT);
                        $dataUpdate->execute();
                        
        }
        public function update_product_detail($product_detail, $product_id, $product){
            try {
                $sql = "UPDATE product_detail set size_id = :size_id, color_id = :color_id, stock = :stock WHERE id = :id";

                $data = $this->conn->prepare($sql);
                $data->bindParam(":size_id", $product["size_id"]);
                $data->bindParam(":color_id", $product["color_id"]);
                $data->bindParam(":stock", $product["stock"]);
                $data->bindParam(":id", $product_detail);
                $data->execute();

                $sqlUpdate = "UPDATE products 
                        SET quantity = (
                            SELECT COALESCE(SUM(stock), 0) 
                            FROM product_detail 
                            WHERE product_id = :product_id
                        )
                        WHERE id = :product_id";
            
                $dataUpdate = $this->conn->prepare($sqlUpdate);
                $dataUpdate->bindParam(":product_id", $product_id);
                // $dataUpdate->bindParam(":product_id", $product_id);
                $dataUpdate->execute();
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

?>