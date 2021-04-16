<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/AdminPayments.php';
$auth = new Auth();
$auth->restrict();


$activePayment = new Payment();
$payments = $activePayment->index();

$loans = $activePayment->getLoans();


?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Payments</h4>
            <?php if ($loans) : ?>
                <a href="<?php echo 'payment_create.php' ?>" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
            <?php endif ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($payments) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Reference number</th>
                                <th scope="col">Payment By</th>
                                <th scope="col">Amount Paid</th>
                                <th scope="col">Paid at</th>
                                <th scope="col">Generate Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($payments as $key => $payment) : ?>
                                <tr>
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td><?php echo $payment->reference_number ?></td>
                                    <td><?php echo $payment->payment_by ?></td>
                                    <td>PHP <?php echo formatDecimal($payment->payment_amount) ?></td>
                                    <td> <?php echo formatDate($payment->paid_at) ?></td>
                                    <td><a href="get_receipt.php?id=<?php echo $payment->id ?>" class="text-info">Get Receipt</a></td>


                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No Payment Yet</h2>
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
<?php ob_flush() ?>