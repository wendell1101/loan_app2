<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$fixedDeposit = new AdminFixedDeposit();
$fixedDeposits = $fixedDeposit->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Fixed Deposits</h4>
            <a href="<?php echo 'fixed_deposit_create.php' ?>" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($fixedDeposits) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Reference Number</th>
                                <th scope="col">Payment By</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($fixedDeposits as $key => $deposit) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><?php echo $deposit->reference_number ?></td>
                                    <td><?php echo ucwords($deposit->payment_by) ?></td>
                                    <td>PHP <?php echo formatDecimal($deposit->amount) ?></td>

                                    <td><?php echo formatDate($deposit->created_at) ?></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No Deposit Yet</h2>
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