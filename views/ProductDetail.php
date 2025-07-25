<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS product -->
    <style>
        main.container {
            max-width: 1140px;
            margin: 0 auto;
        }

        /* --- Bố cục chi tiết sản phẩm --- */
        .product-detail {
            display: flex;
            gap: 40px;
            align-items: flex-start;
            justify-content: space-between;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* --- Ảnh sản phẩm bên trái --- */
        .product-detail .col-md-5 {
            flex: 1;
            text-align: center;
        }

        .product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        /* --- Thông tin sản phẩm bên phải --- */
        .product-detail .col-md-7 {
            flex: 1;
        }

        .product-detail h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        #product-info {
            margin: 15px 0;
        }

        /* Màu sắc - nút tròn */
        .color-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid #ddd;
            margin-right: 8px;
            cursor: pointer;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .color-btn:hover {
            transform: scale(1.1);
        }

        /* Nút size */
        .size-btn {
            width: 36px;
            height: 36px;
            line-height: 36px;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid #ccc;
            font-weight: bold;
            cursor: pointer;
            margin-right: 6px;
            transition: background-color 0.2s;
            color: black;
        }

        .size-btn:hover {
            background-color: #f0f0f0;
        }

        /* Input số lượng */
        input[name="quantity"] {
            width: 60px;
            height: 38px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        /* Nút Thêm vào Giỏ Hàng */
        button.add-to-cart {
            margin-top: 12px;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.2s;
        }

        button.add-to-cart:hover {
            background-color: #0056b3;
        }

        /* Giá sản phẩm */
        .text-danger {
            font-size: 28px;
            font-weight: 700;
            margin-top: 15px;
        }

        /* --- Responsive Mobile --- */
        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
                text-align: center;
            }

            .product-detail .col-md-5,
            .product-detail .col-md-7 {
                width: 100%;
            }

            .product-detail h2 {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <?php ;
    include('./views/components/header.php');
    // include('./views/components/navbar.php');
    ?>
    <main class="container mt-4">
        <h1 class="text-center mb-4">Trang chi tiết sản phẩm</h1>

        <div class="row product-detail align-items-center mb-5">
            <!-- Cột chứa ảnh sản phẩm -->
            <div class="col-md-5 text-center">
                <img src="<?= BASE_URL_ADMIN . $product_detail[0]['first_image'] ?>"
                    alt="<?= $product_detail[0]['name'] ?>" class="img-fluid rounded shadow product-image">
            </div>

            <!-- Cột chứa thông tin sản phẩm -->
            <div class="col-md-7">
                <h2 class="mb-3"><?= $product_detail[0]['name'] ?></h2>
                <p class="text-muted">
                    Màu sắc:
                    <?php
                    // Lấy danh sách màu duy nhất
                    $colors = [];
                    foreach ($product_detail as $item) {
                        $colors[$item['color_code']] = $item['color_name'];
                    }

                    foreach ($colors as $code => $name) {
                        ?>
                        <button class="color-btn" data-color="<?= $code ?>" title="<?= $name ?>" style="
                            background-color: <?= $code ?>;
                            width: 24px;
                            height: 24px;
                            border-radius: 50%;
                            border: 1px solid #ccc;
                            margin-right: 6px;
                            cursor: pointer;
                            padding: 0;
                            display: inline-block;
                            vertical-align: middle;
                        "></button>
                    <?php } ?>
                </p>

                <!-- Thông tin sẽ hiện ở đây -->
                <div id="product-info">
                </div>
                <h3 class="text-danger"><?= number_format($product_detail[0]['price'], 0, ".", ".") ?> VNĐ</h3>
                <p class="mt-3"><?= $product_detail[0]['description'] ?></p>

            </div>
        </div>
        <!-- Phần hiển thị đánh giá và bình luận -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h4 class="mb-4">Đánh giá & Bình luận</h4>

                <!-- Danh sách bình luận -->
                <div id="comment-list">
                    <?php if (empty($comments)): ?>
                        <div class="text-center text-muted fst-italic my-4">
                            😶 Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ cảm nhận của bạn!
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($comment['user_name']) ?></strong>
                                        <div>
                                            <small
                                                class="text-muted"><?= date('d/m/Y H:i', strtotime($comment['date'])) ?></small>
                                            <?php if (isset($_SESSION["user"]["id"]) && $comment['user_id'] == $_SESSION["user"]["id"]): ?>
                                                <form method="post" action="delete_comment.php" class="d-inline-block ms-2"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này không?');">
                                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Xóa Comment">🗑️</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span style="color: <?= $i <= $comment['rating'] ? '#ffc107' : '#e4e5e9' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>



                <!-- Form thêm bình luận -->
                <form method="POST"
                    action="<?= BASE_URL . '?act=post_comment&product_id=' . $product_detail[0]['product_id'] ?>"
                    class="mt-4">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Đánh giá:</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="">Chọn sao đánh giá</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?> ★</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Bình luận:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"
                            placeholder="Viết cảm nhận của bạn..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form> <br> <br> <br>
            </div>
        </div>
    </main>



    </main>
    <?php
    include('./views/components/footer.php');
    ?>
</body>
<script>
    const productDetail = <?= json_encode($product_detail) ?>;

    const colorButtons = document.querySelectorAll('.color-btn');
    const infoDiv = document.getElementById('product-info');
    let selectedSizeBtn = null;
    let selectedColorCode = null;

    function renderSizes(filteredItems, colorName) {
        let html = `
        <p>Màu: <strong>${colorName}</strong></p>
        <div class="mb-3 d-flex flex-wrap gap-2">
    `;

        filteredItems.forEach(item => {
            html += `
            <button class="size-btn" data-id="${item.product_detail_id}" 
                    data-price="${item.price}" 
                    data-stock="${item.stock}" 
                    style="
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 1px solid #ccc;
                cursor: pointer;
                padding: 0;
                font-weight: bold;
            ">${item.size_name}</button>
        `;
        });

        html += `
        </div>
        <div id="size-info" class="mt-2"></div>
        <div id="add-to-cart-container" class="mt-3"></div>
    `;
        infoDiv.innerHTML = html;

        const sizeButtons = document.querySelectorAll('.size-btn');
        const cartContainer = document.getElementById('add-to-cart-container');

        sizeButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                if (selectedSizeBtn) selectedSizeBtn.style.outline = '';
                this.style.outline = '2px solid #007bff';
                selectedSizeBtn = this;

                const detailId = this.dataset.id;
                const price = Number(this.dataset.price);
                const stock = Number(this.dataset.stock);

                document.getElementById('size-info').innerHTML = `
                Kho: <strong>${stock > 0 ? stock + ' sản phẩm' : 'Hết hàng'}</strong>
            `;

                if (stock > 0) {
                    cartContainer.innerHTML = `
                    <form action="<?= BASE_URL . '?act=add_to_cart' ?>" method="POST">
                        <input type="hidden" name="product_detail_id" value="${detailId}">
                        <input type="hidden" name="price" value="${price}">
                        <div class="d-flex align-items-center gap-2">
                            <label for="quantity" class="mb-0">Số lượng:</label>
                            <input type="number" name="quantity" value="1" min="1"  style="width: 80px;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg mt-2">Thêm vào giỏ hàng</button>
                    </form>
                `;
                } else {
                    cartContainer.innerHTML = `
                    <button class="btn btn-secondary btn-lg mt-2" disabled>Hết hàng</button>
                `;
                }
            });
        });

        // Auto chọn size đầu tiên
        if (sizeButtons.length > 0) {
            sizeButtons[0].click();
        }
    }


    colorButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const colorCode = this.dataset.color;
            selectedColorCode = colorCode;
            const colorName = this.title;

            const filtered = productDetail.filter(item => item.color_code === colorCode);
            if (filtered.length === 0) {
                infoDiv.innerHTML = '<p>Không có thông tin sản phẩm cho màu này.</p>';
                return;
            }

            // Highlight màu đang chọn
            colorButtons.forEach(b => b.style.outline = '');
            this.style.outline = '2px solid black';

            // Hiển thị danh sách size
            renderSizes(filtered, colorName);
        });
    });

    // 👉 Tự động click màu đầu tiên khi trang load
    if (colorButtons.length > 0) {
        colorButtons[0].click();
    }

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

</script>


</html>