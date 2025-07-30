<?php
class UserController
{
    public $userModel;

    public $productModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        session_start();
    }
    // Validate
    public function validatePassword($password)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }

    public function validateEmail($email)
    {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }

    public function validateName($name)
    {
        return preg_match('/^[A-Za-zÀ-ỹ\s]{2,50}$/u', $name);
    }

    public function register_view()
    {
        require_once './views/User/register.php';
    }
    public function register()
    {

        // echo "<script> console.log('". $_POST["name"] ."') </script>";

        if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["verify_password"])) {
            echo "<script> alert('❌ Không được để trống bất kỳ trường nào!'); </script>";
            return 0;
        }

        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        $verify_password = $_POST["verify_password"];

        // Kiểm tra mật khẩu nhập lại có khớp không
        if ($password !== $verify_password) {
            echo "<script> alert('❌ Mật khẩu xác nhận không trùng khớp!'); </script>";
            return;
        }

        // Kiểm tra email hợp lệ
        if (!$this->validateEmail($email)) {
            echo "<script> alert('❌ Email không hợp lệ!'); </script>";
            return;
        }

        // Kiểm tra tên hợp lệ
        if (!$this->validateName($name)) {
            echo "<script> alert('❌ Tên không hợp lệ!'); </script>";
            return;
        }

        // Kiểm tra mật khẩu hợp lệ
        if (!$this->validatePassword($password)) {
            echo "<script> alert('❌ Mật khẩu phải có ít nhất 8 ký tự, 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt!'); </script>";
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = [
            "name" => $name,
            "email" => $email,
            "password" => $hashedPassword
        ];
        try {
            $this->userModel->register($user);
            echo "<script>
                        alert('✅ Đăng ký thành công!');
                            window.location.href = '" . BASE_URL . "?act=login_view';
                    </script>";
            exit(); // Dừng script ngay sau khi chuyển hướng
        } catch (\Throwable $th) {
            echo "<script> alert('❌ Lỗi hệ thống: " . addslashes($th->getMessage()) . "'); </script>";
            return;
        }
    }
    // LOGIN
    public function login_view()
    {
        require_once './views/User/login.php';
    }
    public function login()
    {
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            echo "<script> alert('❌ Không được để trống bất kỳ trường nào!'); </script>";
            return $this->login_view();
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $this->userModel->get_user_email($email);

        if (!$user) {
            echo "<script> alert('❌ Email không tồn tại!'); </script>";
            return $this->login_view();
        }

        if ($user["status"] == 'banned') {
            echo "<script> alert('❌ Tài khoản này đã bị khóa!'); </script>";
            return $this->login_view();
        }

        if (!password_verify($password, $user["password"])) {
            echo "<script> alert('❌ Sai mật khẩu!'); </script>";
            return $this->login_view();
        }

        // Đăng nhập thành công, lưu session
        $_SESSION["user"] = $user;

        $redirectUrl = ($user["role"] == 0) ? '/Project_1' : '/Project_1/admin';
        echo "<script>alert('✅ Đăng nhập thành công!'); window.location.href='$redirectUrl';</script>";
        exit(); // Dừng script để tránh load lại trang không cần thiết
    }
    public function log_out()
    {
        unset($_SESSION["user"]);
        echo "<script>alert('✅ Đăng xuất thành công!')</script>";

        return $this->login_view();
 

        // Cart
    public function cart_view()
    {
        if (isset($_SESSION["user"]["id"])) {
            $carts = $this->userModel->get_cart($_SESSION["user"]["id"]);

            require_once "./views/User/Cart.php";
        } else {
            echo "<script>
                            alert('Bạn chưa đăng nhập nên không xem được giỏ hàng!');
                            window.location.href = '" . BASE_URL . "?act=login_view';
                        </script>";

        }

    }
    public function add_to_cart()
    {
        if (!isset($_SESSION["user"])) {
            echo "<script>alert('Bạn cần đăng nhập để thêm vào giỏ hàng!'); window.location.href='" . BASE_URL . "?act=login_view';</script>";
            return;
        }

        if (isset($_POST["product_detail_id"]) && isset($_POST["quantity"]) && isset($_POST["price"])) {
            $user_id = $_SESSION["user"]["id"];
            $product_detail_id = $_POST["product_detail_id"];
            $quantity = (int) $_POST["quantity"];
            $price = $_POST["price"];

            // Lấy số lượng tồn kho
            $stock_quantity = $this->userModel->get_stock_quantity($product_detail_id);
            if ($stock_quantity === null) {
                echo "<script>alert('Không tìm thấy thông tin sản phẩm!'); window.history.back();</script>";
                return;
            }

            // Kiểm tra giỏ hàng đã tồn tại chưa
            $cart_id = $this->userModel->get_cart_id_by_user($user_id);
            if (!$cart_id) {
                $cart_id = $this->userModel->create_cart($user_id);
            }

            // Kiểm tra sản phẩm đã có trong giỏ chưa
            $existing_item = $this->userModel->get_cart_item($cart_id, $product_detail_id);
            $current_quantity_in_cart = $existing_item ? $existing_item["quantity"] : 0;

            // Tổng số lượng sau khi thêm
            $total_quantity_after_add = $current_quantity_in_cart + $quantity;


            if ($total_quantity_after_add > $stock_quantity) {
                echo "<script>alert('❌ Số lượng bạn thêm vượt quá số lượng tồn kho!'); window.history.back();</script>";

                return;
            }

            // Nếu hợp lệ, tiến hành thêm hoặc cập nhật
            if ($existing_item) {
                $this->userModel->update_cart_item_quantity($cart_id, $product_detail_id, $total_quantity_after_add);
            } else {
                $this->userModel->add_cart_detail([
                    "cart_id" => $cart_id,
                    "product_detail_id" => $product_detail_id,
                    "quantity" => $quantity,
                    "price" => $price
                ]);
            }
            echo "<script>alert('✅ Đã thêm vào giỏ hàng!'); window.location.href = '" . BASE_URL . "?act=cart_view';</script>";
        } else {
            echo "<script>alert('❌ Thiếu thông tin sản phẩm để thêm vào giỏ hàng!'); window.history.back();</script>";
        }
    }
    public function delete_cart()
    {
        if (!isset($_SESSION["user"])) {
            echo "<script>alert('Bạn cần đăng nhập để thực hiện thao tác này!'); window.location.href='" . BASE_URL . "?act=login_view';</script>";
            return;
        }

        if (isset($_GET["cart_detail_id"])) {
            $cart_detail_id = $_GET["cart_detail_id"];
            $this->userModel->delete_cart_detail($cart_detail_id);
            echo "<script>alert('✅ Đã xóa sản phẩm khỏi giỏ hàng!'); window.location.href='" . BASE_URL . "?act=cart_view';</script>";
        } else {
            echo "<script>alert('❌ Không tìm thấy sản phẩm cần xóa!'); window.location.href='" . BASE_URL . "?act=cart_view';</script>";
        }
    }
    // ORDER
    public function order()
    {
        if (isset($_POST["selected_cart_ids"]) && is_array($_POST["selected_cart_ids"])) {
            $_SESSION["cart_order"] = [];

            foreach ($_POST["selected_cart_ids"] as $cart_detail_id) {
                $cart = $this->userModel->get_cart_id($cart_detail_id);
                if ($cart) {
                    $_SESSION["cart_order"][] = $cart;
                }
            }

            require_once "./views/User/Order.php";
        } else {
            echo "<script>alert('Vui lòng chọn sản phẩm để đặt hàng'); window.location.href='" . BASE_URL . "?act=cart_view';</script>";
        }
    }


    public function add_orders()
    {
        $payment_method = $_POST["payment_method"];

        // Lưu thông tin vào session tạm (chưa insert DB)
        $_SESSION["pending_order"] = [
            "order" => [
                "user_id" => $_SESSION["user"]["id"],
                "payment_method" => $payment_method,
                "receiver_name" => $_POST["receiver_name"],
                "receiver_phone" => $_POST["receiver_phone"],
                "receiver_address" => $_POST["receiver_address"],
                "receiver_note" => $_POST["receiver_note"],
            ],
            "details" => [],
            "total_amount" => 0
        ];

        $total = 0;
        foreach ($_SESSION["cart_order"] as $cart_order) {
            $total += $cart_order["price"] * $cart_order["quantity"];
            $_SESSION["pending_order"]["details"][] = [
                "product_detail_id" => $cart_order["product_detail_id"],
                "price" => $cart_order["price"],
                "quantity" => $cart_order["quantity"],
            ];
        }
        $_SESSION["pending_order"]["total_amount"] = $total;

        if ($payment_method === "PayPal") {
            // Call MoMo payment API (the specific MoMo sandbox or production API)

            $this->initiate_momo_payment($total);
            return;
        }

        // Nếu không dùng MOMO, tiến hành xử lý luôn
        $this->userModel->add_orders(
            $_SESSION["pending_order"]["order"],
            $_SESSION["pending_order"]["details"]
        );

        // xóa sản phẩm trong giỏ hàng đã mua
        foreach ($_SESSION["cart_order"] as $cart_order) {
            $this->userModel->delete_cart_detail($cart_order["cart_detail_id"]);
            $this->userModel->decrease_product_stock(
                $cart_order["product_detail_id"],
                $cart_order["quantity"]
            );
        }

        // Xoá các sản phẩm đã đặt khỏi session/cart nếu muốn
        unset($_SESSION["cart_order"]);

        echo "<script>
                     alert('Đặt hàng thành công!');
                     window.location.href = '" . BASE_URL . "?act=order_id';
                   </script>";
    }

    public function order_id()
    {
        if (isset($_SESSION["user"])) {
            $order = $this->userModel->get_order_by_user_id($_SESSION["user"]["id"]);
            usort($order, function ($a, $b) {
                // Ưu tiên đơn hàng không bị huỷ
                $isACancelled = strtolower($a['status']) === 'cancelled' ? 1 : 0;
                $isBCancelled = strtolower($b['status']) === 'cancelled' ? 1 : 0;

                if ($isACancelled !== $isBCancelled) {
                    return $isACancelled - $isBCancelled; // đơn bị hủy sẽ có giá trị lớn hơn => xuống dưới
                }

                // Nếu cả 2 cùng bị hủy hoặc cùng không bị hủy => so sánh created_at
                $aTime = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
                $bTime = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
                return $bTime <=> $aTime;
            });

            function getStatusBadge($status)
            {
                $status = strtolower($status);
                switch ($status) {
                    case 'pending':
                        return ['bg-warning-subtle text-dark', '⏳', 'Pending'];
                    case 'processing':
                        return ['bg-info-subtle text-dark', '🔧', 'Processing'];
                    case 'shipped':
                        return ['bg-primary-subtle text-dark', '📦', 'Shipped'];
                    case 'delivered':
                        return ['bg-success-subtle text-dark', '✅', 'Delivered'];
                    case 'cancelled':
                        return ['bg-danger-subtle text-dark', '❌', 'Cancelled'];
                    case 'deleted':
                        return ['bg-secondary text-white', '🗑️', 'Deleted'];
                    default:
                        return ['bg-secondary-subtle text-dark', '❔', ucfirst($status)];
                }
            }

            require_once "./views/User/UserOrder.php";
        }
    }
    public function cancelled_order()
    {
        if (isset($_GET["order_id"])) {
            $order_id = $_GET["order_id"];
            $status = $this->userModel->get_status_order_by_id($order_id);
            if ($status == "Pending" || $status == "Processing") {
                if ($this->userModel->cancelled_order($order_id)) {
                    echo "<script>
                    alert('Bạn đã hủy đơn hàng thành công!');
                    window.location.href = '" . BASE_URL . "?act=order_id';
                  </script>";
                } else {
                    echo "<script>
                    alert('Đã có lỗi xảy ra trong quá trình hủy đơn hàng!');
                    window.location.href = '" . BASE_URL . "?act=order_id';
                  </script>";
                }

            } else {
                if ($status == "Shipped" || $status == "Delivered") {
                    echo "<script>
                    alert('Bạn không thể hủy đơn hàng khi nó đang được ship!');
                    window.location.href = '" . BASE_URL . "?act=order_id';
                  </script>";
                }
            }
        }
    }
    public function delete_order()
    {
        if (isset($_GET["order_id"])) {
            $order_id = $_GET["order_id"];
            if ($this->userModel->delete_order($order_id)) {
                echo "<script>
                    alert('Bạn đã xóa đơn hàng thành công!');
                    window.location.href = '" . BASE_URL . "?act=order_id';
                  </script>";
            } else {
                echo "<script>
                    alert('Đã có lỗi xảy ra trong quá trình hủy đơn hàng!');
                    window.location.href = '" . BASE_URL . "?act=order_id';
                  </script>";
            }
        }
    }

    
    public function __destruct()
    {
        $this->userModel = null;
        $this->productModel = null;
=======
    public function __destruct()
    {
        $this->userModel = null;
    }
}