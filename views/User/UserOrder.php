<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ease - Thời Trang Hiện Đại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- css main -->
    <style>
        main {
            padding: 40px 0;
            background-color: #f8f9fa;
        }

        .categories {
            text-align: center;
            margin-bottom: 40px;
        }

        .categories h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .category-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
        }

        .category-list li {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .category-list li:hover {
            transform: scale(1.05);
        }

        .category-list img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .featured-products {
            text-align: center;
            margin-bottom: 40px;
        }

        .featured-products h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 18px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-card button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .product-card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>


<body>
    <?php ;
    include('./views/components/header.php');
    ?>


    <main>
        <div class="container mt-5">
            <h2 class="mb-4">Đơn hàng của tôi</h2>
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Kích thước</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái đơn hàng</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order as $item): ?>
                        <tr>
                            <td>
                                <img src="<?php echo BASE_URL_ADMIN . $item['first_image']; ?>"
                                    alt="<?php echo $item['name']; ?>" class="img-thumbnail"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </td>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <span class="badge"
                                    style="background-color: <?php echo $item['color_code']; ?>; color: white;">
                                    <?php echo $item['color_name']; ?>
                                </span>
                            </td>
                            <td><?php echo $item['size_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php list($badgeClass, $icon, $label) = getStatusBadge($item['status']); ?>

                                <span class="badge <?= $badgeClass ?>">
                                    <?= $icon . ' ' . $label ?>
                                </span>
                            </td>
                            <td>
                                <!-- Hiển thị nút chi tiết đơn hàng cho tất cả trạng thái -->
                                <button class="btn btn-sm btn-outline-primary view-details"
                                    data-order-id="<?= $item['order_id'] ?>"
                                    data-recipient="<?php echo htmlspecialchars($item['receiver_name']); ?>"
                                    data-address="<?= $item['receiver_address'] ?>"
                                    data-phone="<?= $item['receiver_phone'] ?>" data-status="<?= $item['status'] ?>"
                                    data-bs-toggle="tooltip" title="Chi tiết đơn hàng">
                                    🧐
                                </button>
                                <?php
                                if ($item["status"] == "Pending" || $item["status"] == "Processing") {
                                    // Hủy đơn hàng với trạng thái Pending hoặc Processing
                                    ?>
                                    <a href="<?= BASE_URL . "?act=cancelled_order&order_id=" . $item["order_id"] ?>"
                                        class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Hủy đơn hàng">
                                        ❌
                                    </a>
                                    <?php
                                } elseif ($item["status"] == "Cancelled") {
                                    // Xóa đơn hàng với trạng thái Cancelled hoặc Delivered
                                    ?>
                                    <a href="<?= BASE_URL . "?act=delete_order&order_id=" . $item["order_id"] ?>"
                                        class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Xóa đơn hàng">
                                        🗑️
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Modal for showing order details -->
                    <div id="order-details-modal" class="modal fade" tabindex="-1"
                        aria-labelledby="orderDetailsModalLabel" aria-hidden="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Người nhận:</strong> <span id="recipient-name"></span></p>
                                    <p><strong>Địa chỉ:</strong> <span id="recipient-address"></span></p>
                                    <p><strong>Số điện thoại:</strong> <span id="recipient-phone"></span></p>
                                    <p><strong>Trạng thái:</strong> <span id="order-status"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </tbody>
            </table>
        </div>
    </main>
    <?php
    include('./views/components/footer.php');
    ?>

</body>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));


    // JavaScript to handle showing order details in a modal
    document.querySelectorAll('.view-details').forEach(function (button) {
        button.addEventListener('click', function () {
            // Get the order details from the data attributes
            var recipient = this.getAttribute('data-recipient');
            var address = this.getAttribute('data-address');
            var phone = this.getAttribute('data-phone');
            var status = this.getAttribute('data-status');

            // Set the modal content
            document.getElementById('recipient-name').textContent = recipient;
            document.getElementById('recipient-address').textContent = address;
            document.getElementById('recipient-phone').textContent = phone;
            document.getElementById('order-status').textContent = status;

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('order-details-modal'));
            myModal.show();
        });
    });
</script>

</html>