<?php
class UserModel extends Connect
{
    public $conn;

    public function get_user()
    {
        $sql = "SELECT * FROM users";
        $data = $this->conn->prepare($sql);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete_user($id)
    {
        $sql = "DELETE from users where id = :id";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":id", $id);
        $data->execute();
    }
    public function get_order_status($order_id) {
        $sql = "SELECT status FROM orders WHERE id = :order_id";

        $data = $this->conn->prepare($sql);
        $data->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $data->execute();
        return $data->fetchColumn();
    }
    
    public function update_user_status($id, $status)
    {
        $sql = "UPDATE `users` SET status = :status where id = :id";
        $data = $this->conn->prepare($sql);

        $data->bindParam(":id", $id);
        $data->bindParam(":status", $status);
        $data->execute();
    }
    public function update_user_role($id, $role){
        $sql = "UPDATE `users` SET role = :role where id = :id";
        $data = $this->conn->prepare($sql);

        $data->bindParam(":id", $id);
        $data->bindParam(":role", $role);
        if ($data->execute()) {
            return true; // Return true if the update is successful
        } else {
            return false; // Return false if the execution fails
        }
        
    }
    public function get_order()
    {
        $sql = "SELECT
                        od.id as order_detail_id,
                        od.quantity,
                        od.price,
                        o.status,
                        o.user_id,
                        o.id,
                        o.receiver_name AS customer_name,
                        o.receiver_phone,
                        o.receiver_address,
                        o.receiver_note,
                        o.created_at AS order_date,
                        o.completed_at,
                        s.size_name,
                        cl.color_name,
                        cl.color_code,
                        p.name,
                        MIN(i.image_url) AS first_image
                    FROM orders o
                    JOIN order_details od ON od.order_id = o.id
                    JOIN product_detail pd ON od.product_detail_id = pd.id
                    JOIN sizes s ON pd.size_id = s.id
                    JOIN colors cl ON pd.color_id = cl.id
                    JOIN products p ON pd.product_id = p.id
                    LEFT JOIN images i ON i.product_id = p.id
                    GROUP BY order_detail_id, od.quantity, od.price, o.status, o.user_id, o.id, o.receiver_name, o.receiver_phone, o.receiver_address, o.receiver_note, o.created_at, o.completed_at, s.size_name, cl.color_name, cl.color_code, p.name";

        $data = $this->conn->prepare($sql);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update_order($status, $order_id)
    {
        $sql = "UPDATE `orders`
                SET status = :status,
                    completed_at = CASE
                        WHEN :status = 'Delivered' THEN NOW()
                        ELSE completed_at
                    END
                WHERE id = :id;
                ";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":status", $status);
        $data->bindParam(":id", $order_id);
        $data->execute();
    }
}