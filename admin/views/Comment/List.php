<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Main content container */
        .container {
            width: 70%; /* Make container full width */
            margin-top: 20px;
            margin-right: 0; /* Align to the left */
            margin-right: 30px; /* Slight margin on the left */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: left; /* Align header to the left */
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px 15px; /* Added some padding for a better look */
            text-align: left;
            border: 1px solid #ddd; /* Make sure there are borders around cells */
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            background-color: #ff5c5c;
            color: white;
            border-radius: 4px;
        }

        /* For better visibility of table rows */
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Add zebra-striping effect */
        }

        tr:hover {
            background-color: #e0e0e0; /* Highlight row when hovered */
        }
        .star-rating {
            color: #FFD700; /* Gold (Yellow) color */
        }
    </style>
</head>
<body>
    <?php include './views/components/header.php'; ?>
    <?php include './views/components/sidebar.php'; ?>

    <main>
        <div class="container">
            <h1>Quản Trị Bình Luận</h1>

            <!-- Table to Display Comments -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản Phẩm</th>
                        <th>Người Dùng</th>
                        <th>Đánh Giá</th>
                        <th>Bình Luận</th>
                        <th>Ngày Giờ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example of a Comment -->
                    <?php foreach($comments as $comment){ ?>
                        <tr>
                            <td><?= $comment["id"]; ?></td>
                            <td><?= $comment["product_name"]; ?></td>
                            <td><?= $comment["user_name"]; ?></td>
                            <td>
                                <!-- Render Rating Stars -->
                                <?php 
                                    $rating = $comment["rating"]; 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="fas fa-star star-rating"></i>';  // Filled star
                                        } else {
                                            echo '<i class="far fa-star star-rating"></i>';  // Empty star
                                        }
                                    }
                                ?>
                            </td>
                            <td><?= $comment["comment"]; ?></td>
                            <td><?= $comment["date"]; ?></td>
                            <td><button class="btn btn-danger"><a style="color:white; text-decoration:none" href="<?= BASE_URL_ADMIN . "?act=delete_comment&comment_id=" . $comment["id"] ?>">Xóa</a></button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
