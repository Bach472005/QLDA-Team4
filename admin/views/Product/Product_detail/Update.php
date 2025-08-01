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
            <h1>Thêm biến thể sản phẩm</h1>
            <!-- <pre><?php var_dump($product) ?></pre> -->
            <form action="<?php echo BASE_URL_ADMIN . '?act=update_product_detail&id=' . $product[0]["id"] . '&product_id=' . $product["0"]["product_id"] ?>" method="post" class="form">
                
                <div class="mb-3">
                <label for="" class="form-label">Size Name</label>

                <select name="size_id" id="" class="form-control">
                    <?php foreach($sizes as $size){
                        $selected_size = ($size["id"] === $product[0]["size_id"]) ? "selected" : "";    
                    ?>

                        <option value="<?php echo $size["id"] ?>" <?php echo $selected_size ?> ><?php echo $size["size_name"] ?></option>

                    <?php } ?>
                </select>
                </div>

                <div class="mb-3">
                <label for="" class="form-label">Color Name</label>
                <select name="color_id" id="" class="form-control">
                    <?php foreach($colors as $color){ 
                        $selected_color = ($color["id"] === $product[0]["color_id"]) ? "selected" : "";
                    ?>
                        
                        <option value="<?php echo $color["id"] ?>" <?php echo $selected_color ?>><?php echo $color["color_name"] ?></option>
                    
                    <?php } ?>
                </select>
                </div>

                <div class="mb-3">
                <label for="" class="form-label">Stock</label>
                <input type="number" class="form-control" id="exampleFormControlInput1" name="stock" placeholder="" min="0" value="<?php echo $product[0]["stock"] ?>">
                </div>


                <button type="submit" class="btn btn-primary form-control">Add product</button>
            </form>
        </main>
</div>

<?php include './views/components/footer.php'; ?>
</body>
</html>
