<?php 

class DashboardController
{
    public $dashboard;

    public function __construct(){
        $this->dashboard = new DashboardModel;
        session_start();

        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 1) {
            // Nếu không phải admin, chuyển hướng người dùng ra trang đăng nhập hoặc trang khác
            echo "<script>
                    alert('Bạn phải là admin mới vào trang này được!');
                    window.location.href = '". BASE_URL ."';
                </script>";
        }
    }

    public function dashboard() {
        $total_users = $this->dashboard->totalUser();
        $today_revenue = $this->dashboard->todayRevenue();
        $new_orders = $this->dashboard->newOrder();
        $out_of_stock = $this->dashboard->outOfStock();
        $week_revenue = $this->dashboard->weekRevenue();
        
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('j/n', strtotime("-$i days"));
            $labels[] = $date;
            $data[] = $week_revenue[$date] ?? 0; // nếu không có thì 0
        }
        require_once './views/DashBoard/DashBoard.php';
    }
}