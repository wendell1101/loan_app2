<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/AdminUsers.php';
$auth = new Auth();
$auth->restrict();



$adminUser = new AdminUser();


$users = $adminUser->index('');
//sort by status
$active = '';
if (isset($_POST['active'])) {
    $active = sanitize($_POST['active']);
    $users = $adminUser->index($active);
}
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Users</h4>
            <a href="admin_user_create.php" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="ml-auto" id="sort-form">
                    <div class="form-group">
                        <label for="status">Sort users by status:</label>
                        <select name="active" id="active">
                            <option value="all">All</option>
                            <option value="1" <?php echo ($active == '1') ? 'selected' : '' ?>>Approved</option>
                            <option value="0" <?php echo ($active == '0') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </form>
                <?php if ($users) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Position</th>
                                <th scope="col">Status</th>
                                <th scope="col">Membership Fee</th>
                                <th scope="col">Membership Receipt</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include BASE . '/app/includes/message.php' ?>

                            <?php foreach ($users as $key => $singleUser) : ?>
                                <tr class="align-items-center">
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td>
                                        <img class="rounded-circle" width="35" src="https://ui-avatars.com/api/?name=<?php echo $singleUser->firstname . ' ' . $singleUser->lastname ?>" alt="image">
                                    </td>
                                    <td><a class="text-info" href="admin_user_detail.php?id=<?php echo $singleUser->id ?>"><?php echo ucfirst($singleUser->firstname) . ' ' . ucfirst($singleUser->lastname) ?></a></td>
                                    <td><?php echo $singleUser->email ?></td>
                                    <td><?php echo $singleUser->gender ?></td>

                                    <td>
                                        <span class="<?php echo ($adminUser->getPosition($singleUser->position_id) != 'customer') ? 'text-success' : 'text-secondary' ?> ">
                                            <?php echo $adminUser->getPosition($singleUser->position_id) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($singleUser->active) : ?>
                                            <span class="text-success">approved</span>
                                        <?php else : ?>
                                            <span class="text-danger">pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ($singleUser->paid_membership) ? 'paid' : 'not paid' ?></td>
                                    <td><a href="membership_receipt.php?id=<?php echo $singleUser->id ?>">Receipt</a></td>
                                    <td class="d-flex">
                                        <form action="admin_user_update.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleUser->id ?>">
                                            <button type="submit" class="text-warning mr-3" style="border:none; background:none">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </form>
                                        <form action="admin_user_delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleUser->id ?>">
                                            <button type="submit" style="border:none; background:none">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No User Yet</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    const sortForm = document.getElementById('sort-form');
    const status = document.getElementById('active');

    const searchForm = document.getElementById('search-form');
    const query = document.getElementById('q');
    const loader = document.getElementById('loader');
    const searchBtn = document.getElementById('search-btn');

    // sort
    status.addEventListener('change', (e) => {
        e.preventDefault();
        sortForm.submit();
    })

    // automatically search after 1.5secs
    query.addEventListener('keyup', () => {
        setTimeout(() => {
            searchBtn.innerHTML = `
            Searching ...
            <img src="../assets/img/loader.gif" id="loader" alt="" width="20">
            `;
            searchForm.submit();
        }, 1500);

    })
</script>

<?php require_once BASE . '/app/includes/admin/footer.php' ?>
<?php ob_flush() ?>