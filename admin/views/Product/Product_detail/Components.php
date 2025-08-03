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
            <!-- <h1>Welcome to Shop</h1> -->
            <h1>Danh sách Biến thể</h1>
            <table class="table">
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2" class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=add_product_detail_view&id=" . $id ?>">Add Product</a></td>
                </tr>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Size</th>
                    <th class="text-center">Color</th>
                    <th class="text-center">Example for color</th>
                    <th class="text-center">Stock</th>
                    <th colspan="2" class="text-center">Action</th>
                </tr>
                <?php foreach($products as $product){ ?> 
                <tr>

                    
                    <td class="text-center"><?php echo $count; ?></td>
                    <td class="text-center"><?php echo $product["size_name"] ?></td>
                    <td class="text-center"><?php echo $product["color_name"] ?></td>
                    <td class="text-center"><input type="color" value="<?php echo $product["color_code"]?>" disabled ></td>
                    <td class="text-center"><?php echo $product["stock"]; ?></td>

                    <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=get_product_detail_id&id=" . $product["product_detail_id"] ?>">Update</a></td>
                    <td class="text-center"><a class="btn btn-danger" href="javascript:void(0);" onclick="deleteProduct(<?php echo $product['product_detail_id'] ?>, <?php echo $id ?>, <?php echo $product['stock'] ?>)">Delete</a></td>
                    
                    
                    </tr>
                    <?php } ?>
                <?php $count++;?>
            </table>
        </main>
</div>

<?php include './views/components/footer.php'; ?>


</body>
</html>

<script>
function deleteProduct(id, product_id, stock) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "<?= BASE_URL_ADMIN ?>?act=delete_product_detail&id=" + id + "&product_id=" + product_id + "&stock=" + stock;
        alert("Delete success!!!");

    } else{
        return null;
    }
}

</script>