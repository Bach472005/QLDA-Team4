<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>

    <style>
        .main-grid {
            display: grid;
            grid-template-columns: 55% 45%;
            gap: 30px;
            align-items: start;
        }

        .order-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-list {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .product-details {
            flex: 1;
            font-size: 14px;
        }

        .product-details p {
            margin: 4px 0;
        }

        .product-details strong {
            font-size: 16px;
        }

        .color-circle {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 1px solid #ccc;
            display: inline-block;
            vertical-align: middle;
            margin-top: 5px;
        }
    </style>

</head>

<body>
    <div id="container">
        <?php ;
        include('./views/components/header.php');
        ?>
        <div class="order-container">
            <h2>Thông Tin Đặt Hàng</h2>
            <div class="main-grid">

                <!-- BÊN TRÁI: FORM -->
                <form action="<?= BASE_URL . "?act=add_orders" ?>" method="POST">
                    <div class="mb-3">
                        <label>Họ và Tên</label>
                        <input type="text" class="form-control" name="receiver_name"
                            value="<?= $_SESSION["user"]["name"] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Số Điện Thoại</label>
                        <input type="text" class="form-control" name="receiver_phone"
                            value="<?= $_SESSION["user"]["phone"] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Địa Chỉ Nhận Hàng</label>
                        <input type="text" class="form-control" name="receiver_address"
                            value="<?= $_SESSION["user"]["address"] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>NOTE</label>
                        <input type="text" class="form-control" name="receiver_note">
                    </div>
                    <div class="mb-3">
                        <label>Phương Thức Thanh Toán</label>
                        <select class="form-select" name="payment_method" required>
                            <optgroup label="Thanh toán khi nhận hàng">
                                <option value="COD">COD (thu tiền tận nơi)</option>
                            </optgroup>
                            <optgroup label="Thanh toán Online">
                                <option value="PayPal">MOMO</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Bank Transfer">Chuyển khoản ngân hàng</option>
                            </optgroup>
                        </select>

                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đặt Hàng</button>
                </form>

                <!-- BÊN PHẢI: SẢN PHẨM -->
                <div class="product-list">
                    <h5>Sản Phẩm Của Bạn</h5>
                    <?php foreach ($_SESSION["cart_order"] as $item): ?>
                        <div class="product-item">
                            <img src="<?= BASE_URL_ADMIN . $item["first_image"] ?>" alt="Sản phẩm">
                            <div class="product-details">
                                <p><strong><?= $item["name"] ?></strong></p>
                                <p>Size: <?= $item["size_name"] ?></p>
                                <div class="color-circle" style="background-color: <?= $item["color_code"] ?>;"></div>
                                <p>Giá: <?= number_format($item["price"], 0, ".", ".") ?> VNĐ</p>
                                <p>Số lượng: <?= $item["quantity"] ?></p>
                                <p><strong>Tổng: <?= number_format($item["quantity"] * $item["price"], 0, ".", ".") ?>
                                        VNĐ</strong></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>




        <?php ;
        include('./views/components/footer.php');
        ?>

    </div>



</body>

</html>