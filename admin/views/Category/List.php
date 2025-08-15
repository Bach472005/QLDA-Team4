<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        #categorySelect {
            width: 200px;
            margin-bottom: 20px;
        }

        .category-table {
            display: none;
        }

        .table {
            width: 100%;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    
<?php include './views/components/header.php'; ?>
<?php include './views/components/sidebar.php' ?>
<div class="container-fluid">
        <!-- Main Content (BÊN PHẢI) -->
        <main class="col-md-8 p-4 main-content order-md-1" style="width: 75%; margin-left: 400px;">
            <!-- Dropdown to choose category -->
            <h3>Choose Category to View</h3>
            <select id="categorySelect" class="form-control" onchange="changeCategoryView()">
                <option value="category_list">Danh sách Danh Mục</option>
                <option value="size_list">Danh sách Sizes</option>
                <option value="color_list">Danh sách Colors</option>
            </select>

            <!-- Category Tables -->

            <!-- Danh Mục List -->
            <?php $count = 1 ?>
            <div id="category_list" class="category-table" style="display: block;">
                <h1>Danh sách Danh Mục</h1>
                <table class="table ">
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=add_category_view" ?>">Add Category</a></td>
                    </tr>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Category Name</th>
                        <th class="text-center" colspan="2">Action</th>
                    </tr>

                    <?php foreach($categories as $category){ ?>
                    <tr>
                        <td class="text-center"><?php echo $count; ?></td>
                        <td class="text-center"><?php echo $category["category_name"]; ?></td>
                        <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "/index.php?act=get_category_id&id=" . $category["id"] ?>">Update</a></td>
                        <!-- <td class="text-center"><a class="btn btn-danger" href="javascript:void(0);" onclick="deleteCategory(<?php echo $category['id']; ?>)">Delete</a></td> -->
                    </tr>
                    <?php $count++; }?>
                </table>
            </div>

            <!-- Sizes List -->
            <?php $count = 1 ?>
            <div id="size_list" class="category-table" style="display: none;">
                <h1>Danh sách Sizes</h1>
                <table class="table">
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=add_size_view" ?>">Add Size</a></td>
                    </tr>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Size Name</th>
                        <th class="text-center" colspan="2">Action</th>
                    </tr>

                    <?php foreach($sizes as $size){ ?>
                    <tr>
                        <td class="text-center"><?php echo $count; ?></td>
                        <td class="text-center"><?php echo $size["size_name"]; ?></td>
                        <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "/index.php?act=get_size_id&id=" . $size["id"] ?>">Update</a></td>
                        <!-- <td class="text-center"><a class="btn btn-danger" href="javascript:void(0);" onclick="deleteSize(<?php echo $size['id']; ?>)">Delete</a></td> -->
                    </tr>
                    <?php $count++; }?>
                </table>
            </div>  

            <!-- Colors List -->
            <?php $count = 1 ?>
            <div id="color_list" class="category-table" style="display: none;">
                <h1>Danh sách Colors</h1>
                <table class="table">
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "?act=add_color_view" ?>">Add Color</a></td>
                    </tr>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Color Name</th>
                        <th class="text-center" colspan="2">Action</th>
                    </tr>

                    <?php foreach($colors as $color){ ?>
                    <tr>
                        <td class="text-center"><?php echo $count; ?></td>
                        <td class="text-center"><?php echo $color["color_name"]; ?></td>
                        <td class="text-center"><a class="btn btn-primary" href="<?php echo BASE_URL_ADMIN . "/index.php?act=get_color_id&id=" . $color["id"] ?>">Update</a></td>
                        <!-- <td class="text-center"><a class="btn btn-danger" href="javascript:void(0);" onclick="deleteColor(<?php echo $color['id']; ?>)">Delete</a></td> -->
                    </tr>
                    <?php $count++; }?>
                </table>
            </div>

        </main>

</div>

<?php include './views/components/footer.php'; ?>
</body>
</html>

<script>
function deleteCategory(id) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "<?= BASE_URL_ADMIN ?>?act=delete_category&id=" + id;
        alert("Delete success!!!");

    } else{
        return null;
    }
}

function changeCategoryView() {
    var selectedCategory = document.getElementById("categorySelect").value;

    // Hide all category tables first
    var categoryTables = document.querySelectorAll(".category-table");
    categoryTables.forEach(function(table) {
        table.style.display = "none";
    });

    // Show the selected category table
    document.getElementById(selectedCategory).style.display = "block";
}


</script>