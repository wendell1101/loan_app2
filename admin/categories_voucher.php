<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$activeVoucher = new VoucherCategory();
$categories = $activeVoucher->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Voucher Categories</h4>
            <a href="category_voucher_create.php" class="btn btn-success ml-auto">Create Category<i class="ml-2 fas fa-plus text-light"></i></a>
            <!-- <a href="<?php echo 'department_create.php' ?>" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($categories) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($categories as $key => $category) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><?php echo strtoupper($category->name) ?></td>

                                    <td class="d-flex">

                                        <form action="category_voucher_update.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $category->id ?>">
                                            <button type="submit" class="text-warning mr-3" style="border:none; background:none">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </form>
                                        <form action="category_voucher_delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $category->id ?>">
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
                    <h2 class="text-secondary text-center">No Voucher Category Yet</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once  '../app/includes/admin/footer.php' ?>
<?php echo ob_flush() ?>