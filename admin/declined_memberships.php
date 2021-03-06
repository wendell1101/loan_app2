<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
// require_once '../app/middlewares/AdminUsers.php';
$auth = new Auth();
$auth->restrict();



$adminUser = new AdminUser();


$users = $adminUser->getDeclinedMemberships();
// dump($users);
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
            <!-- <a href="admin_user_create.php" class="btn btn-success ml-auto">Create User<i class="ml-2 fas fa-plus text-light"></i></a> -->
            <!-- <a href="admin_user_create.php" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a> -->
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
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Position</th>
                                <th scope="col">Approved</th>
                                <th scope="col">Fee</th>
                                <th scope="col">Receipt</th>
                                <th scope="col">Formal Request</th>
                                <th scope="col">Reason for Declining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include BASE . '/app/includes/message.php' ?>

                            <?php foreach ($users as $key => $singleUser) : ?>
                                <tr class="align-items-center">
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><a class="text-info" href="admin_user_detail.php?id=<?php echo $singleUser->id ?>"><?php echo ucfirst($singleUser->firstname) . ' ' . ucfirst($singleUser->lastname) ?></a></td>
                                    <td><?php echo $singleUser->email ?></td>
                                    <td><?php echo $singleUser->gender ?></td>

                                    <td>
                                        <span class="<?php echo ($adminUser->getPosition($singleUser->position_id) != 'customer') ? 'text-success' : 'text-secondary' ?> ">
                                            <?php echo $adminUser->getPosition($singleUser->position_id) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($adminUser->checkIfActiveMembershipCommitteeHasApproved($singleUser->id) != 0) : ?>
                                            <i class="fas fa-check text-success"></i>
                                        <?php else : ?>
                                            <i class="fas fa-times text-danger"></i>
                                        <?php endif ?>
                                    </td>
                                    <td><?php echo ($singleUser->paid_membership) ? 'paid' : 'not paid' ?></td>
                                    <td><a href="membership_receipt.php?id=<?php echo $singleUser->id ?>" class="text-info">Receipt</a></td>
                                    <td><a href="membership_request.php?id=<?php echo $singleUser->id ?>" class="text-info">View</a></td>
                                    <td>
                                        <?php echo $singleUser->reason_for_decline ?>
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