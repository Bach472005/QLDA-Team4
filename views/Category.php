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
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
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
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .product-card.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
            position: absolute;
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

        /* Làm sticky cho bộ lọc */
        .filter-box {
            max-height: 80vh;
            /* Giới hạn chiều cao */
            overflow-y: auto;
            /* Cuộn nếu bộ lọc dài quá */
        }

        /* Optional: tùy biến scrollbar cho đẹp */
        .filter-box::-webkit-scrollbar {
            width: 6px;
        }

        .filter-box::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        /* Responsive: trên mobile, bộ lọc nên hiển thị trên đầu */
        @media (max-width: 991.98px) {
            .filter-box {
                position: static !important;
                max-height: none;
                overflow: visible;
            }
        }
    </style>
</head>


<body>
    <?php ;
    include('./views/components/header.php');
    ?>


    <main class="container my-5">
        <div class="row">
            <!-- BỘ LỌC - BÊN TRÁI VÀ STICKY -->
            <aside class="col-lg-3 mb-4">
                <div class="filter-box sticky-top p-3 bg-white shadow-sm rounded" style="top: 100px;">
                    <h5 class="mb-3">Bộ Lọc</h5>

                    <!-- Tên sản phẩm (lọc bằng nhập từ khoá) -->
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Tìm theo tên sản phẩm</label>
                        <input type="text" id="nameInput" class="form-control" placeholder="Nhập tên sản phẩm...">
                    </div>


                    <!-- Loại sản phẩm -->
                    <div class="mb-3">
                        <label for="categoryFilter" class="form-label">Loại sản phẩm</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="all">Tất cả</option>
                            <?php
                            $categories = array_unique(array_column($_SESSION["products"], "category_name"));
                            foreach ($categories as $category) {
                                echo "<option value='$category'>$category</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Giá -->
                    <div class="mb-3">
                        <label for="priceFilter" class="form-label">Lọc theo giá</label>
                        <select id="priceFilter" class="form-select">
                            <option value="all">Tất cả</option>
                            <option value="0-199999">Dưới 200.000đ</option>
                            <option value="200000-399999">200.000đ - 400.000đ</option>
                            <option value="400000-999999999">Trên 400.000đ</option>
                        </select>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="mb-3">
                        <label for="sortSelect" class="form-label">Sắp xếp</label>
                        <select id="sortSelect" class="form-select">
                            <option value="default">Mặc định</option>
                            <option value="price-asc">Giá tăng dần</option>
                            <option value="price-desc">Giá giảm dần</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- SẢN PHẨM -->
            <section class="col-lg-9">
                <h2 class="text-center mb-4">Sản Phẩm Nổi Bật</h2>
                <div class="product-grid">
                    <?php foreach ($_SESSION["products"] as $product): ?>
                        <div class="product-card" data-category="<?= $product["category_name"] ?>"
                            data-price="<?= $product["price"] ?>">
                            <a href="<?= BASE_URL . "?act=pd&product_id=" . $product["id"] ?>">
                                <img src="<?= BASE_URL_ADMIN . $product['first_image'] ?>" alt="<?= $product["name"] ?>" />
                            </a>
                            <h3><?= $product["name"] ?></h3>
                            <p><?= number_format($product["price"], 0, ".", ".") ?> VNĐ</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>




    <?php
    include('./views/components/footer.php');
    ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const categoryFilter = document.getElementById("categoryFilter");
        const priceFilter = document.getElementById("priceFilter");
        const productCards = document.querySelectorAll(".product-card");
        const nameInput = document.getElementById("nameInput");

        function filterProducts() {
            const keyword = nameInput.value.toLowerCase().trim();
            const selectedCategory = categoryFilter.value;
            const selectedPrice = priceFilter.value;

            productCards.forEach(card => {
                const name = card.querySelector("h3").textContent.toLowerCase().trim();
                const category = card.getAttribute("data-category");
                const price = parseInt(card.getAttribute("data-price"));

                let matchName = name.includes(keyword);
                let matchCategory = selectedCategory === "all" || category === selectedCategory;
                let matchPrice = true;

                if (selectedPrice !== "all") {
                    const [min, max] = selectedPrice.split("-").map(Number);
                    matchPrice = price >= min && price <= max;
                }

                if (matchName && matchCategory && matchPrice) {
                    card.classList.remove("hidden");
                } else {
                    card.classList.add("hidden");
                }
            });
        }

        categoryFilter.addEventListener("change", filterProducts);
        priceFilter.addEventListener("change", filterProducts);
        nameInput.addEventListener("input", filterProducts);

    });

    document.getElementById("sortSelect").addEventListener("change", function () {
        const value = this.value;
        const productsArray = Array.from(document.querySelectorAll(".product-card"));
        const container = document.querySelector(".product-grid");

        productsArray.sort((a, b) => {
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);

            if (value === "price-asc") return priceA - priceB;
            if (value === "price-desc") return priceB - priceA;
            return 0;
        });

        // Xóa cũ và thêm mới theo thứ tự
        container.innerHTML = "";
        productsArray.forEach(card => container.appendChild(card));
    });



</script>

</html>