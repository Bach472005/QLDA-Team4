<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .cart-container {
            max-width: 900px;
            margin: auto;
        }

        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            display: flex;
            align-items: center;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
            border-radius: 5px;
        }

        .cart-item .info {
            flex-grow: 1;
        }

        .cart-item .actions {
            display: flex;
            align-items: center;
        }

        .cart-item .actions input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
        }

        .cart-summary {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include('./views/components/header.php'); ?>

    <form action="<?= BASE_URL . "?act=order" ?>" method="POST">
        <div class="container my-4 cart-container">
            <h2 class="mb-4">Giỏ hàng của bạn</h2>

            <?php $total = 0; ?>

            <?php foreach ($carts as $cart): ?>
                <?php
                $subtotal = $cart['quantity'] * $cart['price'];
                $total += $subtotal;
                ?>
                <div class="row border p-3 mb-3 align-items-center cart-item">
                    <!-- Checkbox chọn sản phẩm -->
                    <div class="col-md-1 text-center">
                        <input type="checkbox" class="form-check-input cart-checkbox" name="selected_cart_ids[]"
                            value="<?= $cart['id'] ?>" data-subtotal="<?= $subtotal ?>">
                    </div>

                    <!-- Hình ảnh sản phẩm -->
                    <div class="col-md-2">
                        <img src="<?= BASE_URL_ADMIN . $cart["first_image"] ?>" class="img-fluid" alt="Sản phẩm">
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-5">
                        <h5><?= $cart['product_name'] ?></h5>
                        <p><?= $cart['description'] ?></p>
                        <p class="d-flex align-items-center">
                            <strong>Màu sắc:</strong>
                            <span class="ms-1"><?= $cart['color_name'] ?></span>
                            <span class="border rounded-circle d-inline-block ms-2"
                                style="background: <?= $cart['color_code'] ?>; width: 20px; height: 20px; border: 1px solid #000;">
                            </span>
                        </p>
                        <p><strong>Size:</strong> <?= $cart['size_name'] ?></p>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="col-md-2">
                        <h5>Giá: <?= number_format($cart['price'], 0, ',', '.') ?> VNĐ</h5>
                    </div>

                    <!-- Số lượng -->
                    <div class="col-md-1">
                        <input type="number" class="form-control text-center" value="<?= $cart['quantity'] ?>" min="1"
                            readonly>
                    </div>

                    <!-- Xóa sản phẩm -->
                    <div class="col-md-1">
                        <a href="<?= BASE_URL . "?act=delete_cart&cart_detail_id=" . $cart['id'] ?>"
                            class="btn btn-danger">Xóa</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Tổng tiền -->
            <div class="row">
                <div class="col-md-12 text-end">
                    <h4><strong>Tổng tiền đã chọn: <span id="selected-total">0</span> VNĐ</strong></h4>
                </div>
            </div>

            <!-- Nút Thanh toán -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-secondary">Tiếp tục mua sắm</a>
                <button type="submit" class="btn btn-success">Thanh toán sản phẩm đã chọn</button>
            </div>
        </div>
    </form>

    <?php include('./views/components/footer.php'); ?>
</body>
<script>
    const checkboxes = document.querySelectorAll('.cart-checkbox');
    const totalDisplay = document.getElementById('selected-total');

    function updateTotal() {
        let total = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.subtotal);
            }
        });
        totalDisplay.textContent = total.toLocaleString('vi-VN');
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateTotal);
    });

    // Cập nhật ban đầu nếu có checkbox nào được mặc định chọn
    updateTotal();
</script>

</html>