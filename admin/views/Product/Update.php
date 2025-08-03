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
            <h1>Thêm sản phẩm</h1>
            <form action="<?php echo BASE_URL_ADMIN . '?act=update_product&id=' . $product["id"] ?>" method="post" class="form" enctype="multipart/form-data">
                <div class="mb-3">
                <label for="" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="name" placeholder="" value="<?php echo $product["name"] ?>">
                </div>

                <div class="mb-3">
                <label for="" class="form-label">Product Category</label>
                <select name="category_id" id="category_id" class="form-control">
                <?php 
                    for($i = 0; $i < count($categories); $i++){
                    $selected = ($categories[$i]["id"] == $product["category_id"]) ? "selected" : "";
                ?>
                    <option value="<?php echo $categories[$i]["id"] ?>" <?php echo $selected ?>><?php echo $categories[$i]["category_name"] ?></option>
                <?php
                    }
                ?>
                    
                </select>
                </div>
                
                <div class="mb-3">
                <label for="" class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image_url[]" multiple>
                </div>

                <div class="mb-3">
                <label for="" class="form-label">Product Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"><?php echo $product["description"] ?></textarea>
                </div>

                <div class="mb-3">
                <label for="" class="form-label">Product Price (VND)</label>
                <input type="number" class="form-control" id="exampleFormControlInput1" placeholder="" name="price" value="<?php echo $product["price"] ?>" >
                </div>

                <!-- <div class="mb-3">
                <label for="" class="form-label">Product Quantity</label>
                <input type="number" class="form-control" id="exampleFormControlInput1" placeholder="" name="quantity" value="<?php echo $product["quantity"] ?>" readonly>
                </div> -->

                

                <button type="submit" class="btn btn-primary form-control">Update product</button>
                
            </form>
        </main>
</div>

<?php include './views/components/footer.php'; ?>
</body>
</html>
