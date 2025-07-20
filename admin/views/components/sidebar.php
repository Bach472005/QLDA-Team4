<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL_ADMIN . "public/css/style.css" ?>">
    <title>Document</title>
    <style>
        .user-info {
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: 600;
            color: #333;
            background-color: #f9f9f9;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
        }

        .user-info i {
            margin-right: 10px;
            color: #007bff;
            /* Màu icon xanh dương */
            font-size: 22px;
        }
    </style>
</head>

<body>
    <aside class="col-md-3 bg-dark text-white p-4 sidebar order-md-2">
        <?php

        if (isset($_SESSION["user"]["name"])) {
            $fullName = trim($_SESSION["user"]["name"]);

            if (strpos($fullName, " ") !== false) {
                $nameParts = explode(" ", $fullName);
                $lastName = end($nameParts);
            } else {
                $lastName = $fullName; // Only one name
            }
            echo '<div class="user-info"><i class="fas fa-user"></i>Welcome ' . htmlspecialchars($lastName) . '</div>';
        } else {
            echo '<div class="user-info"><i class="fas fa-user"></i> Guest</div>';
        }
        ?>

        <ul>
            <li><i class="fas fa-tachometer-alt"></i> <a href="<?php echo BASE_URL_ADMIN ?>"
                    class="text-decoration-none text-white"> DashBoard</a></li>
            <li><i class="fas fa-shopping-cart"></i> <a href="<?= BASE_URL_ADMIN . "?act=get_order" ?>"
                    class="text-decoration-none text-white">Đơn hàng</a></li>
            <li><i class="fas fa-list"></i><a href="<?php echo BASE_URL_ADMIN . "?act=category_view" ?>"
                    class="text-decoration-none text-white"> Danh mục</a></li>

            <li><i class="fas fa-box"></i> <a href="<?php echo BASE_URL_ADMIN . "?act=get_product" ?>"
                    class="text-decoration-none text-white"> Sản phẩm</a></li>
            <!-- <li><i class="fas fa-newspaper"></i> Bài viết</li> -->
            <!-- <li><i class="fas fa-chart-bar"></i> Thống kê</li> -->
            <li><i class="fas fa-users"></i> <a href="<?php echo BASE_URL_ADMIN . "?act=get_user" ?>"
                    class="text-decoration-none text-white"> Thành viên</a></li>
            <li><i class="fas fa-comments"></i> <a href="<?php echo BASE_URL_ADMIN . "?act=get_comment" ?>"
                    class="text-decoration-none text-white"> Bình luận</a></li>
        </ul>
    </aside>
</body>

</html>