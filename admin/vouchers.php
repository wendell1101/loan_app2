<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$activeVoucher = new Voucher();
$vouchers = $activeVoucher->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Vouchers</h4>
            <a href="voucher_create.php" class="btn btn-success ml-auto">Create Voucher<i class="ml-2 fas fa-plus text-light"></i></a>
            <!-- <a href="<?php echo 'department_create.php' ?>" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($vouchers) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Voucher Number</th>
                                <th scope="col">Category</th>
                                <th scope="col">Member</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($vouchers as $key => $voucher) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><?php echo strtoupper($voucher->receipt_number) ?></td>
                                    <td><?php echo $activeVoucher->getVoucherCategory($voucher->voucher_category_id)->name ?></td>
                                    <td><?php echo ucfirst($activeVoucher->getUser($voucher->user_id)->firstname)
                                            . ' ' . ucfirst($activeVoucher->getUser($voucher->user_id)->lastname) ?>
                                    </td>
                                    <td>PHP <?php echo formatDecimal($voucher->amount) ?></td>
                                    <td>
                                        <a href="voucher_receipt.php?id=<?php echo $voucher->id ?>" class="text-info">Voucher Receipt</a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No Voucher Yet</h2>
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