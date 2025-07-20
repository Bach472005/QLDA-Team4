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
}