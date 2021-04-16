<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$saving = new Savings();
$savings = $saving->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Savings</h4>
            <a href="saving_create.php" class="btn btn-success ml-auto">Create Saving Deposit<i class="ml-2 fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($savings) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Reference Number</th>
                                <th scope="col">Payment By</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Receipt</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($savings as $key => $deposit) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><?php echo $deposit->reference_number ?></td>
                                    <td><?php echo ucwords($deposit->payment_by) ?></td>
                                    <td>PHP <?php echo formatDecimal($deposit->amount) ?></td>
                                    <td>
                                        <a href="savings_receipt.php?id=<?php echo $deposit->id ?>" class="text-info">
                                            Receipt
                                        </a>
                                    </td>

                                    <td><?php echo formatDate($deposit->created_at) ?></td>
                                    <td><a href="saving_withdraw.php?user_id=<?php echo $deposit->user_id ?>&saving_id=<?php echo $deposit->id ?>" class="text-info">Widthdraw</a></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No Savings Yet</h2>
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