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
$savings = $activePayment->getSavings();


?>


<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h4>Payments</h4>
                </div>
                <div class="col d-flex align-items-end">
                    <?php if ($loans) : ?>
                        <a href="<?php echo 'payment_create_loan.php' ?>" class="btn btn-primary ml-auto mr-2">Pay for Loan<i class="ml-2 fas fa-plus text-light"></i></a>
                    <?php endif ?>
                    <a href="<?php echo 'payment_create.php' ?>" class="btn btn-primary ml-auto mr-2">Cash in<i class="ml-2 fas fa-plus text-light"></i></a>

                    <?php if ($savings) : ?>
                        <a href="<?php echo 'payment_withdraw.php' ?>" class="btn btn-primary ml-auto mr-2">Cash out<i class="ml-2 fas fa-plus text-light"></i></a>
                    <?php endif ?>

                </div>
            </div>


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
                                <th scope="col">Loan Amount Paid</th>
                                <th scope="col">Fixed Deposit Amount Paid</th>
                                <th scope="col">Saving Amount Paid</th>
                                <th scope="col">Withdraw Amount Paid</th>
                                <th scope="col">Paid at</th>
                                <th scope="col">Generate Receipt</th>
                                <th scope="col">Loan Penalty</th>
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
                                    <td>PHP <?php echo formatDecimal($payment->payment_fixed_deposit) ?></td>
                                    <td>PHP <?php echo formatDecimal($payment->payment_saving) ?></td>
                                    <td>PHP <?php echo formatDecimal($payment->payment_saving_withdraw) ?></td>
                                    <td> <?php echo formatDate($payment->paid_at) ?></td>
                                    <td><a href="get_receipt.php?id=<?php echo $payment->id ?>" class="text-info">Get Receipt</a></td>
                                    <?php if (!is_null($payment->loan_id)) : ?>
                                        <td><a href="penalty_receipt.php?id=<?php echo $payment->id ?>" class="text-info">View</a></td>
                                    <?php else : ?>
                                        <td><span class="text-muted">None</span></td>
                                    <?php endif ?>


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