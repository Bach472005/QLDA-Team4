<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Kết quả thanh toán từ MoMo</h2>

    <?php if (isset($_GET['resultCode']) && $_GET['resultCode'] == 0): ?>
        <div class="alert alert-success">
            <strong>✅ Giao dịch thành công!</strong> Cảm ơn bạn đã thanh toán.
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <strong>❌ Giao dịch thất bại!</strong> Vui lòng thử lại hoặc liên hệ hỗ trợ.
        </div>
    <?php endif; ?>

    <h5>Chi tiết giao dịch:</h5>
    <table class="table table-bordered table-hover">
        <tbody>
        <?php foreach ($_GET as $key => $value): ?>
            <tr>
                <th><?= htmlspecialchars($key) ?></th>
                <td><?= htmlspecialchars($value) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= BASE_URL ?>" class="btn btn-primary">Quay lại trang chủ</a>
</div>
</body>
</html>
