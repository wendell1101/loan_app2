<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();
// $activeUser = $loan->getUser($_SESSION['id']);

if (isset($_GET['id'])) {
    $payments = $loan->getPayments($_GET['id']);
} else {
    redirect('loans.php');
}
?>

<?php require_once BASE . '/app/includes/header.php' ?>

<div class="wrapper">
    <div class="loans" style="margin-top: 100px">
        <div class="container">
            <?php include 'app/includes/message.php' ?>
            <div class="card border">
                <div class="card-header d-flex align-items-center">
                    <h4>Payment History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if ($payments) : ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Reference Number</th>
                                        <th scope="col">Payment By</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Paid At</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include 'app/includes/message.php' ?>

                                    <?php foreach ($payments as $key => $activePayment) : ?>
                                        <tr>
                                            <th scope="row"><?php echo $key + 1 ?></th>
                                            <td> <?php echo $activePayment->reference_number ?></td>
                                            <td> <?php echo $activePayment->payment_by ?></td>
                                            <td>PHP <?php echo formatDecimal($activePayment->payment_amount) ?></td>
                                            <td><?php echo shortDate($activePayment->paid_at) ?></td>


                                        </tr>
                                    <?php endforeach; ?>
                                    Current Balance: PHP <?php echo formatDecimal($loan->getLoan($activePayment->loan_id)->total_amount) ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <h2 class="text-secondary text-center">No Payment Yet</h2>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'app/includes/footer.php' ?>