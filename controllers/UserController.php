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