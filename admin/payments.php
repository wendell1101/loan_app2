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

$penalty_amount = 0;
$service_charge = 0;
$membership_fee = 0;
$total = 0;


?>


<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <div class="row">
                <div>
                    <h4>Payments</h4>
                </div>
                <div class="ml-auto">
                    <?php if ($loans) : ?>
                        <a href="<?php echo 'payment_create_loan.php' ?>" class="btn btn-primary ml-auto mr-2">Pay for Loan<i class="ml-2 fas fa-plus text-light"></i></a>
                    <?php endif ?>
                    <a href="<?php echo 'payment_create.php' ?>" class="btn btn-primary ml-auto mr-2">Deposit<i class="ml-2 fas fa-plus text-light"></i></a>


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
                                <th scope="col">Penalty Amount </th>
                                <th scope="col">Service Charge</th>
                                <th scope="col">Membership Fee</th>
                                <th scope="col">Total</th>
                                <th scope="col">Generate Receipt</th>
                                <th scope="col">Penalty Receipt</th>
                                <th scope="col">Add Penalty</th>
                                <th scope="col">Paid at</th>
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

                                    <?php if ($payment->has_penalty) : ?>
                                        <?php $penalty_amount = $activePayment->getPenalty($payment->loan_id, $payment->id)->amount ?>
                                        <td>PHP <?php echo formatDecimal($penalty_amount) ?></td>
                                    <?php else : ?>
                                        <td>PHP 0.00</td>
                                    <?php endif; ?>
                                    <!-- service fee -->
                                    <?php if ($payment->has_penalty) : ?>
                                        <?php $service_charge = $activePayment->getPenalty($payment->loan_id, $payment->id)->service_fee ?>
                                        <td>PHP <?php echo formatDecimal($activePayment->getPenalty($payment->loan_id, $payment->id)->service_fee) ?></td>
                                    <?php else : ?>
                                        <td>PHP 0.00</td>
                                    <?php endif; ?>
                                    <!-- membership fee -->
                                    <td>PHP 0.00</td>
                                    <?php
                                    $total  = $payment->payment_amount + $payment->payment_fixed_deposit + $payment->payment_saving
                                        + $penalty_amount + $service_charge + $membership_fee;
                                    ?>
                                    <td>PHP <?php echo formatDecimal($total) ?></td>




                                    <?php if (!is_null($payment->payment_saving) || !is_null($payment->payment_fixed_deposit)) : ?>
                                        <td><a href="deposit_receipt.php?id=<?php echo $payment->id ?>" class="text-info">Deposit Receipt</a></td>
                                    <?php elseif (!is_null($payment->payment_amount)) : ?>
                                        <td><a href="loan_payment_receipt.php?id=<?php echo $payment->id ?>" class="text-info">Loan Receipt</a></td>
                                    <?php endif ?>

                                    <?php if (!is_null($payment->loan_id)) : ?>
                                        <?php if (!$payment->has_penalty) : ?>
                                            <td><span class="text-muted">none</span></td>
                                        <?php else : ?>
                                            <td><a href="penalty_receipt.php?id=<?php echo $payment->id ?>" class="text-info">Penalty Receipt</a></td>
                                        <?php endif; ?>

                                    <?php else : ?>
                                        <td><span class="text-muted">N/A</span></td>
                                    <?php endif ?>

                                    <?php if (!is_null($payment->loan_id)) : ?>
                                        <?php if (!$payment->has_penalty) : ?>
                                            <td>
                                                <a href="penalty_create.php?loan_id=<?php echo $payment->loan_id ?>&payment_id=<?php echo $payment->id ?>" class="btn btn-danger btn-sm">add</a>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <span class="text-muted text-center">has penalty</span>
                                            </td>

                                        <?php endif; ?>
                                    <?php else : ?>
                                        <td><span class="text-muted">N/A</span></td>
                                    <?php endif ?>

                                    <td> <?php echo formatDate($payment->paid_at) ?></td>
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