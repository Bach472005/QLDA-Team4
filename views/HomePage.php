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
            grid-template-columns: repeat(4, 1fr);
            /* Chỉ hiển thị 4 sản phẩm một dòng */
            gap: 20px;
            justify-content: center;
            max-width: 1200px;
            /* Giới hạn độ rộng để căn giữa */
            margin: 0 auto;
            /* Căn giữa */
        }

        .product-card {
            background: linear-gradient(145deg, #ffffff, #f1f1f1);
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            /* hoặc bạn có thể chỉnh thành 220px, 250px tuỳ thiết kế */
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
            background-color: #f0f0f0;
            /* Màu nền dự phòng nếu ảnh chưa load */
            transition: transform 0.3s ease;
        }


        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .product-card p {
            font-size: 18px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-card .description {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .product-card button {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .product-card button:hover {
            background: linear-gradient(to right, #0056b3, #003e80);
            transform: scale(1.05);
        }


        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>


<body>
    <?php ;
    include('./views/components/header.php');
    include('./views/components/banner.php');
    // include('./views/components/navbar.php');
    ?>


    <main>
        <section class="categories">
            <h2>Danh Mục Sản Phẩm</h2>
            <ul class="category-list">
                <li>
                    <a href="<?php echo BASE_URL . "?act=category" ?>">
                        <img src="public/images/váy/anh11.jpg" alt="Váy" />

                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL . "?act=category" ?>">
                        <img src="public/images/áo phong/z6427049469666_5642f2b40d4f30e919ae96313d6b646b.jpg"
                            alt="Áo Phông" />

                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL . "?act=category" ?>">
                        <img src="public/images/quần/z6427058916226_812197864e55a002aa64164fa47ce000.jpg" alt="Quần" />

                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL . "?act=category" ?>">
                        <img src="public/images/ao khoac/z6427065417228_c4d35643131b5ce9de14e98e3e4b8886.jpg"
                            alt="Áo Khoác" />

                    </a>
                </li>
            </ul>
        </section>
        <section class="featured-products">
            <h2>Sản Phẩm Nổi Bật</h2>
            <div class="product-grid">
                <?php
                    foreach ($_SESSION["products"] as $product) {
                ?>
                    <div class="product-card">
                        <a href="<?= BASE_URL . "?act=pd&product_id=" . $product["id"] ?>"><img
                                src="<?= BASE_URL_ADMIN . $product['first_image'] ?>" alt="<?= $product["name"] ?>" /></a>
                        <h3><?= $product["category_name"] ?></h3>
                        <p><?= number_format($product["price"], 0, ".", ".") ?> VNĐ</p>

                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
    </main>


    <!-- <section class="order-check">
    <h1>Kiểm Tra Đơn Hàng Của Bạn</h1>
    <form action="#" method="post">
      <label for="order-id">Mã Đơn Hàng:</label>
      <input type="text" id="order-id" name="order-id" placeholder="Nhập mã đơn hàng" required />

      <label for="phone">Số Điện Thoại:</label>
      <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" required />

      <button type="submit">Kiểm Tra Đơn Hàng</button>
    </form>
  </section> -->
    <?php
    include('./views/components/footer.php');
    ?>



</body>

</html>