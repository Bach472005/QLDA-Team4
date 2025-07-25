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

    // REGISTER
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
    
    // PROFILE
    public function profile()
    {
        require_once "./views/user/Profile.php";
    }
    public function change_password()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        // Kiểm tra khi form được gửi
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $userId = $_SESSION['user']['id'];

            $user = $_SESSION["user"];

            // Kiểm tra mật khẩu hiện tại
            if (!password_verify($current_password, $user['password'])) {
                $message = "Mật khẩu hiện tại không đúng.";
            } elseif ($new_password !== $confirm_password) {
                $message = "Mật khẩu mới và xác nhận không khớp.";
            } elseif (strlen($new_password) < 6) {
                $message = "Mật khẩu mới phải có ít nhất 6 ký tự.";
            } else {
                // Cập nhật mật khẩu mới
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $this->userModel->updatePassword($userId, $hashedPassword);

                $message = "Đổi mật khẩu thành công.";
            }
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
    }

    public function __destruct()
    {
        $this->userModel = null;
    }

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
}