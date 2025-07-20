<?php $count = 1 ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>


<body>

    <?php include './views/components/header.php'; ?>
    <?php include './views/components/sidebar.php' ?>

    <div class="container-fluid">
        <main class="col-md-8 p-4 main-content order-md-1" style="width: 75%; margin-left: 400px;">
            <h1>Danh sách User</h1>

            <!-- Dropdown Filter -->
            <div class="mb-3">
                <label for="roleFilter">Lọc theo vai trò:</label>
                <select id="roleFilter" class="form-select w-25">
                    <option value="all">Tất cả</option>
                    <option value="0">Admin</option>
                    <option value="1">User</option>
                </select>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Role</th>

                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php foreach ($users as $user) { ?>
                        <tr data-role="<?= $user['role'] ?>">
                            <td class="text-center"><?= $count; ?></td>
                            <td class="text-center"><?= $user["name"]; ?></td>
                            <td class="text-center"><?= $user["email"]; ?></td>
                            <td class="text-center"><?= $user["phone"]; ?></td>
                            <td class="text-center"><?= $user["address"]; ?></td>
                            <td class="text-center">
                                <span
                                    class="<?= ($user['status'] == 'active') ? 'badge rounded-pill text-bg-success' : 'badge rounded-pill text-bg-danger' ?>"><?= $user["status"]; ?></span>
                            </td>
                            <td class="text-center">
                                <form method="POST" action="<?= BASE_URL_ADMIN . "?act=update_user_role" ?>" onchange="return confirmRoleChange(this);">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <select name="role" class="form-select form-select-sm w-auto d-inline"
                                        onchange="this.form.submit()">
                                        <option value="0" <?= $user["role"] == 0 ? 'selected' : '' ?>>User</option>
                                        <option value="1" <?= $user["role"] == 1 ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </form>
                            </td>



                            <td class="text-center">
                                <a class="<?= ($user['status'] == 'active') ? 'btn btn-warning' : 'btn btn-primary' ?> "
                                    href="javascript:void(0);"
                                    onclick="updateUserStatus(<?= $user['id'] ?>, '<?= $user['status'] ?>')">
                                    <?= ($user['status'] == 'active') ? 'Ban' : 'Unban' ?>
                                </a>
                            </td>

                            
                        </tr>
                        <?php $count++;
                    } ?>
                </tbody>
            </table>
        </main>
    </div>

    <?php include './views/components/footer.php'; ?>

    <script>
        function confirmRoleChange(form) {
            const select = form.querySelector('select[name="role"]');
            const selectedText = select.options[select.selectedIndex].text;

            return confirm(`Bạn có chắc chắn muốn đổi quyền người dùng thành "${selectedText}"?`);
        }
        function deleteUser(id) {
            if (confirm("Bạn có chắc chắn muốn xóa user này không?")) {
                window.location.href = "<?= BASE_URL_ADMIN ?>?act=delete_user&id=" + id;
            }
        }
        function updateUserStatus(id, status) {
            if (status == 'active' && confirm("Bạn có chắc chắn muốn ban user này không?")) {
                status = 'banned';

                window.location.href = "<?= BASE_URL_ADMIN ?>?act=update_user_status&id=" + id + "&status=" + status;
            } else if (status == 'banned' && confirm("Bạn có chắc chắn muốn unban user này không?")) {
                status = 'active';

                window.location.href = "<?= BASE_URL_ADMIN ?>?act=update_user_status&id=" + id + "&status=" + status;
            }
        }

        $(document).ready(function () {
            $("#roleFilter").change(function () {
                var selectedRole = $(this).val();

                $("#userTable tr").each(function () {
                    var rowRole = String($(this).data("role")); // Chuyển về kiểu chuỗi
                    if (selectedRole === "all" || rowRole === selectedRole) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

</body>

</html>