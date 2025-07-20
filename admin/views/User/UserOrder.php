<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cách đơn giản: thêm link CDN vào trong <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: #f8f9fa;
        }

        .content {
            margin-left: 420px;
        }
    </style>
</head>

<body>

    <?php include './views/components/header.php';
    ?>

    <div class="d-flex">
        <?php include './views/components/sidebar.php' ?>

        <div class="content container-fluid mt-4">
            <h2 class="mb-4">Quản Lý Đơn Hàng</h2>

            <!-- Tìm kiếm và lọc theo trạng thái -->
            <div class="mb-4">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="searchName" class="form-control"
                                placeholder="Tìm theo tên khách hàng">
                        </div>
                        <button type="button" class="col-md-3 btn btn-secondary" id="clearFilter">Xóa bộ lọc</button>
                    </div>
                </form>
            </div>

            <!-- Bảng đơn hàng -->
            <table class="table table-bordered text-center" id="orderTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID Đơn Hàng</th>
                        <th>Khách Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="order-item" data-status="<?php echo strtolower($order['status']); ?>"
                            data-name="<?php echo strtolower($order['customer_name']); ?>">
                            <td>#<?php echo $order['user_id']; ?></td>
                            <td><?php echo $order['customer_name']; ?></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($order['order_date'])); ?></td>
                            <td><?php echo number_format($order['price'] * $order["quantity"], 0, ',', '.'); ?>đ</td>
                            <td>
                                <span class="badge bg-<?php echo
                                    ($order['status'] == 'Pending') ? 'warning' :
                                    ($order['status'] == 'Processing' ? 'info' :
                                        ($order['status'] == 'Shipped' ? 'primary' :
                                            ($order['status'] == 'Delivered' ? 'success' :
                                                'danger'))); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>

                            <td>
                                <!-- Nút xem đơn hàng -->
                                <button type="button" class="btn btn-info btn-sm"
                                    onclick="showOrderDetails(<?php echo $order['order_detail_id']; ?>)">Xem</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal hiển thị chi tiết đơn hàng -->
    <div id="order-details-modal" class="modal fade" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?= BASE_URL_ADMIN . '?act=update_order' ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- input ẩn để lưu order_detail_id -->
                        <input type="hidden" name="order_id" id="modal-order-id">

                        <p><strong>Người nhận:</strong> <span id="recipient-name"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="recipient-address"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="recipient-phone"></span></p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <select name="status" id="order-status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include './views/components/footer.php'; ?>

    <script>
        // Hiển thị chi tiết đơn hàng trong modal
        function showOrderDetails(orderDetailId) {
            var order = <?php echo json_encode($orders); ?>.find(o => o.order_detail_id == orderDetailId);

            if (order) {
                document.getElementById('recipient-name').textContent = order.customer_name;
                document.getElementById('recipient-address').textContent = order.receiver_address;
                document.getElementById('recipient-phone').textContent = order.receiver_phone;
                document.getElementById('modal-order-id').value = order.id;
                document.getElementById('order-status').value = order.status;

                var modal = new bootstrap.Modal(document.getElementById('order-details-modal'));
                modal.show();
            } else {
                console.error("Không tìm thấy order với ID:", orderDetailId);
            }
        }

        // Hàm lọc đơn hàng
        function filterOrders() {
            let status = $('#statusFilter').val().toLowerCase();
            let keyword = $('#searchName').val().toLowerCase();

            $('.order-item').each(function () {
                let orderStatus = $(this).data('status');
                let customerName = $(this).data('name');

                let matchStatus = status === '' || orderStatus === status;
                let matchName = customerName.includes(keyword);

                if (matchStatus && matchName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Gắn sự kiện lọc khi thay đổi
        $('#statusFilter').on('change', filterOrders);
        $('#searchName').on('input', filterOrders);

        // Nút xóa bộ lọc
        $('#clearFilter').on('click', function () {
            $('#statusFilter').val('');
            $('#searchName').val('');
            filterOrders();
        });

        // Gọi khi load trang để áp dụng sẵn (nếu có)
        $(document).ready(function () {
            filterOrders();
        });
    </script>

</body>

</html>