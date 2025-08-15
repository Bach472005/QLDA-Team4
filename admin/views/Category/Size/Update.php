<?php $count = 1 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<?php include './views/components/header.php'; ?>
<?php include './views/components/sidebar.php' ?>
<div class="container-fluid">
        <!-- Main Content (BÊN PHẢI) -->
        <main class="col-md-8 p-4 main-content order-md-1" style="width: 75%; margin-left: 400px;">
            <h1>Update category</h1>
            <form action="<?php echo BASE_URL_ADMIN . '?act=update_category&id=' . $category["id"] ?>" method="post" class="form">
                <div class="mb-3">
                <label for="" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="category_name" placeholder="" value="<?php echo $category["category_name"] ?>">
                </div>

                

                <button type="submit" class="btn btn-primary form-control">Update product</button>
            </form>
        </main>
</div>

<?php include './views/components/footer.php'; ?>
</body>
</html>
