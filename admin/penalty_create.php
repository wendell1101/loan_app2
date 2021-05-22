<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$saving = new Savings();

$savings = $saving->index();
$deposits = $saving->getFixedDeposits();


$users = $saving->getUsers();
$payment = new Payment();
$payments = $payment->index();
$errors = [];
$activeLoan = $payment->getLoan($_GET['loan_id']);
// dump($activeLoan);
if (!isset($_GET['loan_id']) || !isset($_GET['payment_id'])) {
    redirect('payments.php');
}
$loans = $payment->getLoans();

if (isset($_POST['penalty'])) {

    $payment->createPenalty($_POST);
}



?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Create Penalty</h4>
        </div>

        <div class="card-body">

            <form action="<?php echo $_SERVER['PHP_SELF'] . '?loan_id=' . $_GET['loan_id'] ?>" method="POST">
                <h5 class="font-weight-bold">Penalty for: <?php echo $activeLoan->loan_number . ' - ' .
                                                                ucfirst($payment->getUser($activeLoan->user_id)->firstname) . ' ' .
                                                                ucfirst($payment->getUser($activeLoan->user_id)->lastname) . ' **Total Loan Balance: PHP ' . formatDecimal($activeLoan->total_amount)
                                                            ?>
                </h5><br>
                <input type="hidden" name="loan_id" value="<?php echo $_GET['loan_id'] ?>">
                <input type="hidden" name="payment_id" value="<?php echo $_GET['payment_id'] ?>">
                <div class="form-group">
                    <label for="reason">Reason for Penalty</label>
                    <textarea name="reason" id="reason" cols="30" rows="2" class="form-control" placeholder="example: Late payment" required></textarea>
                </div>
                <div class="form-group">
                    <label for="amount">Penalty Amount</label>
                    <input type="number" name="amount" id="amount" step=".01" class="form-control" required>
                </div>
                <input type="hidden" name="service_fee" value="0">
                <!-- <div class="form-group">
                    <label for="service_fee">Service Fee</label>
                    <input type="number" name="service_fee" id="service_fee" step=".01" class="form-control" required>
                </div> -->

                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="payments.php" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" name="penalty" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once '../app/includes/admin/footer.php';
ob_flush();
?>