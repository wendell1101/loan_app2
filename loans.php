<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();

$loans = $loan->index();
$activeUser = $loan->getUser($_SESSION['id']);
// $payments = $loan->getPayments($loan->id);


?>
<?php require_once BASE . '/app/includes/header.php' ?>

<div class="wrapper">
    <div class="loans" style="margin-top: 100px">
        <div class="container">
            <?php include 'app/includes/message.php' ?>
            <div class="card border">
                <div class="card-header d-flex align-items-center">
                    <h4>Loans</h4>
                    <a href="select_loan.php" class="btn btn-success ml-auto">Request Loan<i class="ml-3 fas fa-plus text-light"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if ($loans) : ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Payment History</th>
                                        <th scope="col">Principal Amount</th>
                                        <th scope="col">Interest</th>
                                        <th scope="col">Total Amount</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include 'app/includes/message.php' ?>

                                    <?php foreach ($loans as $key => $activeLoan) : ?>
                                        <tr>
                                            <th scope="row"><?php echo $key + 1 ?></th>
                                            <td><a href="loan_detail.php?id=<?php echo $activeLoan->id ?>">
                                                    <?php echo $activeLoan->transaction_id ?></a></td>
                                            <td><?php echo $loan->getLoanType($activeLoan->loan_type_id) ?></td>
                                            <td><?php echo $activeLoan->status ?></td>
                                            <td><a href="payment_history.php?id=<?php echo $activeLoan->id ?>">View</a></td>
                                            <td>PHP <?php echo formatDecimal($activeLoan->amount) ?></td>
                                            <td>PHP <?php echo formatDecimal($loan->getInterest($activeLoan->loan_type_id)->interest) ?> %</td>
                                            <td>PHP <?php echo formatDecimal($activeLoan->total_amount) ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        <?php else : ?>
                            <h2 class="text-secondary text-center">No Loan Yet</h2>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'app/includes/footer.php' ?>