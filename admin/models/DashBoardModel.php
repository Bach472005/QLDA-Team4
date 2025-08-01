<?php

class DashboardModel extends Connect
{

    public function totalUser()
    {
        $sql = "SELECT COUNT(DISTINCT user_id) AS total_users FROM orders";

        $data = $this->conn->prepare($sql);
        $data->execute();

        $result = $data->fetch(PDO::FETCH_ASSOC); // Lấy dữ liệu theo kiểu mảng liên kết
        return $result ? $result['total_users'] : 0; // Trả về số lượng, nếu không có thì trả về 0
    }

    public function todayRevenue()
    {
        $sql = "SELECT SUM(od.quantity * od.price) AS today_revenue
                    FROM order_details od
                    JOIN orders o ON od.order_id = o.id
                    WHERE o.status = 'Delivered' 
                    AND DATE(o.completed_at) = CURDATE()";
        $data = $this->conn->prepare($sql);
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result["today_revenue"] ?? 0;
    }

    public function newOrder()
    {
        $sql = "SELECT COUNT(*) AS new_orders
               FROM orders
               WHERE DATE(created_at) = CURDATE()";
        $data = $this->conn->prepare($sql);
        $data->execute();
        $result = $data->fetch();
        return $result["new_orders"] ?? 0;
    }

    public function outOfStock()
    {
        $sql = "SELECT COUNT(*) AS out_of_stock
                    FROM products
                    WHERE quantity = 0";
        $data = $this->conn->prepare($sql);
        $data->execute();
        $result = $data->fetch();
        return $result["out_of_stock"] ?? 0;
    }

    public function weekRevenue()
    {
        $sql = "SELECT DATE(o.created_at) AS order_date,
                   SUM(od.quantity * od.price) AS revenue
            FROM order_details od
            JOIN orders o ON od.order_id = o.id
            WHERE o.status = 'Delivered' 
                AND o.created_at >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(o.created_at)
            ORDER BY DATE(o.created_at) ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $revenue_data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Format ngày giống với labels: "d/M" (vd: "14/4")
            $formattedDate = date('j/n', strtotime($row['order_date']));
            $revenue_data[$formattedDate] = (float) $row['revenue'];
        }

        return $revenue_data;
    }



}