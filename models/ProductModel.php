<?php
class ProductModel extends Connect
{
    public function get_product()
    {
        $sql = "SELECT 
                        p.id, 
                        p.name, 
                        p.description, 
                        p.price, 
                        p.quantity, 
                        c.category_name,
                        MIN(i.image_url) AS first_image
                    FROM products AS p
                    JOIN categories AS c ON c.id = p.category_id
                    LEFT JOIN images AS i ON p.id = i.product_id
                    GROUP BY p.id, c.category_name";
        $data = $this->conn->prepare($sql);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_product_detail($product_id)
    {
        $sql = "SELECT 
                        pd.id AS product_detail_id,
                        pd.product_id,
                        pd.stock,
                        c.color_name,
                        c.color_code,
                        s.size_name,
                        p.name,
                        p.description,
                        p.price,
                        (SELECT i.image_url 
                        FROM images i 
                        WHERE i.product_id = p.id 
                        ORDER BY i.id ASC 
                        LIMIT 1) AS first_image
                    FROM product_detail pd
                    JOIN colors c ON pd.color_id = c.id
                    JOIN sizes s ON pd.size_id = s.id
                    JOIN products p ON pd.product_id = p.id
                    WHERE pd.product_id = :product_id";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":product_id", $product_id);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_quantity_product_cart($user_id)
    {
        $sql = "
            SELECT COUNT(*) AS total_products
            FROM cart c
            JOIN cart_details cd ON c.id = cd.cart_id
            WHERE c.user_id = :user_id
        ";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":user_id", $user_id);
        $data->execute();
        return $data->fetch(PDO::FETCH_ASSOC)['total_products'] ?? 0;
    }

}