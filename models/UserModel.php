<?php
class Connect
{
    public $conn;
    public function __construct()
    {
        $this->conn = connect_db();
    }
    public function __destruct()
    {
        $this->conn = null;
    }
    public function get_table($table_name, $conditions = [], $columns = "*", $limit = null)
    {
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
class UserModel extends Connect
{
    public $conn;

    public function get_user_email($email)
    {
        $sql = "SELECT * FROM users where email = :email";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":email", $email);
        $data->execute();
        return $data->fetch(PDO::FETCH_ASSOC);
    }
    public function register($user)
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":name", $user["name"]);
        $data->bindParam(":email", $user["email"]);
        $data->bindParam(":password", $user["password"]);
        $data->execute();
    }
    public function updatePassword($userId, $newHashedPassword)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'password' => $newHashedPassword,
            'id' => $userId
        ]);
    }

    public function get_cart($id)
    {
        $sql = "SELECT 
                        cd.id, 
                        cd.cart_id, 
                        cd.product_detail_id, 
                        cd.quantity, 
                        cd.price, 
                        pd.stock,  
                        p.name AS product_name,
                        p.description,
                        p.category_id,
                        p.id AS product_id,
                        s.size_name,
                        cl.color_name,
                        cl.color_code,
                        MIN(i.image_url) AS first_image
                    FROM cart AS c 
                    JOIN cart_details AS cd ON c.id = cd.cart_id
                    JOIN product_detail AS pd ON pd.id = cd.product_detail_id  
                    JOIN products AS p ON pd.product_id = p.id
                    JOIN sizes AS s ON s.id = pd.size_id
                    JOIN colors AS cl ON cl.id = pd.color_id
                    LEFT JOIN images AS i ON p.id = i.product_id
                    WHERE c.user_id = :id
                    GROUP BY cd.id; 
                    ";

        $data = $this->conn->prepare($sql);
        $data->bindParam(":id", $id, PDO::PARAM_INT);
        $data->execute();

        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_cart_id($cart_id)
    {
        $sql = "SELECT
                        cd.id as cart_detail_id,
                        cd.quantity,
                        cd.price,
                        pd.id as product_detail_id,
                        s.size_name,
                        c.color_name,
                        c.color_code,
                        p.name,
                        MIN(i.image_url) AS first_image
                        from cart_details cd
                        JOIN product_detail pd ON pd.id = cd.product_detail_id
                        JOIN sizes s ON pd.size_id = s.id
                        JOIN colors c ON pd.color_id = c.id
                        JOIN products p ON p.id = pd.product_id
                        LEFT JOIN images i ON i.product_id = p.id
                        where cd.id = :id";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":id", $cart_id, PDO::PARAM_INT);
        $data->execute();

        return $data->fetch(PDO::FETCH_ASSOC);
    }
    public function get_cart_id_by_user($user_id)
    {
        $sql = "SELECT id FROM cart WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchColumn(); // chỉ trả về cart_id
    }
    // Check xem sản phẩm đã có trong giỏ hàng chưa
    public function get_cart_item($cart_id, $product_detail_id)
    {
        $sql = "SELECT * FROM cart_details 
                    WHERE cart_id = :cart_id AND product_detail_id = :product_detail_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':cart_id' => $cart_id,
            ':product_detail_id' => $product_detail_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về mảng nếu tìm thấy, hoặc false
    }

    // Check stock sản phẩm
    public function get_stock_quantity($product_detail_id)
    {
        $sql = "SELECT stock FROM product_detail WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $product_detail_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int) $result["stock"] : 0;
    }

    // Tạo mới giỏ hàng nếu chưa có
    public function create_cart($user_id)
    {
        $sql = "INSERT INTO cart (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Thêm sản phẩm vào chi tiết giỏ hàng
    public function add_cart_detail($cart_detail)
    {
        $sql = "INSERT INTO cart_details (cart_id, product_detail_id, quantity, price) VALUES (:cart_id, :product_detail_id, :quantity, :price)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":cart_id", $cart_detail["cart_id"]);
        $stmt->bindParam(":product_detail_id", $cart_detail["product_detail_id"]);
        $stmt->bindParam(":quantity", $cart_detail["quantity"]);
        $stmt->bindParam(":price", $cart_detail["price"]);
        $stmt->execute();
    }

    // Xóa 1 sản phẩm khỏi giỏ
    public function delete_cart_detail($cart_detail_id)
    {
        $sql = "DELETE FROM cart_details WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $cart_detail_id);
        $stmt->execute();
    }
    // UPDATE cart quantity
    public function update_cart_item_quantity($cart_id, $product_detail_id, $new_quantity)
    {
        $sql = "UPDATE cart_details 
                    SET quantity = :quantity 
                    WHERE cart_id = :cart_id AND product_detail_id = :product_detail_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':quantity' => $new_quantity,
            ':cart_id' => $cart_id,
            ':product_detail_id' => $product_detail_id
        ]);
    }
}