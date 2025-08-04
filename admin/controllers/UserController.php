<?php
class UserController
{
    public $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        session_start();
    }
    public function get_user()
    {
        $users = $this->userModel->get_user();

        require_once './views/User/List.php';
    }
    public function delete_user()
    {
        $id = $_GET['id'];
        $this->userModel->delete_user($id);
        return $this->get_user();
    }
    public function update_user_status()
    {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $this->userModel->update_user_status($id, $status);
        echo "<script> 
                        alert('Update status Success');
                        setTimeout(function(){
                            window.location.href = '?act=get_user';
                        }, 1000); 
                      </script>";
    }
    public function update_user_role()
    {
        if (isset($_POST['user_id']) && isset($_POST['role'])) {
            $id = $_POST['user_id'];
            $role = $_POST['role'];

            $id = intval($id);
            $role = intval($role);

            $result = $this->userModel->update_user_role($id, $role);

            if ($result) {
                echo "<script> 
                        alert('Update role Success');
                        setTimeout(function(){
                            window.location.href = '?act=get_user';
                        }, 1000); 
                      </script>";
            } else {
                echo "<script> 
                        alert('Error updating role');
                        window.location.href = '?act=get_user';
                      </script>";
            }
        } else {
            echo "<script> 
                    alert('Invalid data');
                    window.location.href = '?act=get_user';
                  </script>";
        }
    }
    public function get_order()
    {
        $orders = $this->userModel->get_order();
        $groupedOrders = [];

        foreach ($orders as $order) {
            $orderId = $order['id']; // hoặc $order['order_id'] nếu bạn dùng tên khác
            usort($orders, function ($a, $b) {
                return strtotime($b['order_date']) <=> strtotime($a['order_date']);
            });
            // if (!isset($groupedOrders[$orderId])) {
            //     $groupedOrders[$orderId] = [];
            // }

            // $groupedOrders[$orderId][] = $order;
        }

        require_once "./views/User/UserOrder.php";
    }
    public function update_order()
    {
        if (isset($_POST["order_id"])) {
            $status = $_POST["status"];
            $order_id = $_POST["order_id"];

            // Lấy trạng thái hiện tại của đơn hàng
            $current_status = $this->userModel->get_order_status($order_id);

            // Kiểm tra điều kiện chuyển trạng thái hợp lệ
            if (
                ($current_status == "Pending" && $status == "Processing") ||
                ($current_status == "Processing" && $status == "Shipped") ||
                ($current_status == "Shipped" && $status == "Delivered")
            ) {

                // Cập nhật trạng thái nếu điều kiện hợp lệ
                $this->userModel->update_order($status, $order_id);
                echo "<script> 
                        alert('Update Success');
                        setTimeout(function(){
                            window.location.href = '?act=get_order';
                        }, 1000); 
                      </script>";
            } elseif ($current_status == "Cancelled" || $current_status == "Delivered") {
                // Nếu trạng thái hiện tại là Cancelled hoặc Delivered, không cho phép thay đổi
                echo "<script> 
                        alert('Không được thay đổi status nếu status là Cancelled hoặc Delivered.');
                        setTimeout(function(){
                            window.location.href = '?act=get_order';
                        }, 1000); 
                      </script>";
            } else {
                // Nếu trạng thái không hợp lệ (chẳng hạn như Pending -> Shipped mà không qua Processing)
                echo "<script> 
                        alert('Chuyển trạng thái không hợp lệ.');
                        setTimeout(function(){
                            window.location.href = '?act=get_order';
                        }, 1000); 
                      </script>";
            }
        }

    }
    public function __destruct()
    {
        $this->userModel = null;
    }
}