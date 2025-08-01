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
            <h1>Danh sách Sản phẩm</h1>
            <table class="table">
                <tr>
                    <td colspan="6"></td>
                    <td colspan="5"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=add_product_view" ?>">Add Product</a></td>
                </tr>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Price</th>
                    <!-- <th>Images</th> -->
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Components</th>
                    <th class="text-center">Delete</th>
                    <!-- <th class="text-center">Update</th> -->
                   
                </tr>

                <?php foreach($products as $product){ ?>
                <tr>
                    <td class="text-center"><?php echo $count; ?></td>
                    <td class="text-center"><?php echo $product["name"]; ?></td>
                    
                    <td>
                    <?php foreach (explode(", ", $product["images"]) as $img) : ?>
                        <img src="<?= $img ?>" alt="Product Image" width="80">
                    <?php endforeach; ?>
                    </td>
                    <td class="text-center"><?php echo $product["description"]; ?></td>
                    <td class="text-center"><?php echo number_format($product["price"], 0, ',', '.') . ' VND' ?></td>
                    
                    <td class="text-center"><?php echo $product["quantity"]; ?></td>
                    <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=product_detail_view&id=" . $product["id"] ?>">Extend</a></td>
                    <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=get_product_id&id=" . $product["id"] ?>">Update</a></td>
                    <!-- <td class="text-center"><a class="btn btn-danger" href="javascript:void(0);" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</a></td> -->
                </tr>
                <?php $count++; }?>
            </table>
        </main>
</div>

<?php include './views/components/footer.php'; ?>
</body>
</html>

<script>
function deleteProduct(id) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "<?= BASE_URL_ADMIN ?>?act=delete_product&id=" + id;
        alert("Delete success!!!");

    } else{
        return null;
    }
}

</script>