<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminUser = new AdminUser();


$users = $adminUser->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Users</h4>
            <a href="admin_user_create.php" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($users) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Position</th>
                                <th scope="col">Status</th>
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
                                    <td><?php echo $singleUser->firstname . ' ' . $singleUser->lastname ?></td>
                                    <td><?php echo $singleUser->email ?></td>
                                    <td><?php echo $singleUser->gender ?></td>
                                    <td><?php echo $singleUser->contact_number ?></td>
                                    <td>
                                        <span class="<?php echo ($adminUser->getPosition($singleUser->position_id) != 'customer') ? 'text-success' : 'text-secondary' ?> ">
                                            <?php echo $adminUser->getPosition($singleUser->position_id) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($singleUser->active) : ?>
                                            <span class="text-success">active</span>
                                        <?php else : ?>
                                            <span class="text-danger">inactive</span>
                                        <?php endif; ?>
                                    </td>
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

<?php require_once BASE . '/app/includes/admin/footer.php' ?>
<?php ob_flush() ?>