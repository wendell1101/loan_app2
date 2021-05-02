<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$saving = new Savings();
$users = $saving->getUsers();
$payment = new Payment();
$payments = $payment->index();
$errors = [];

$loan_id = $payment_by = $payment_amount = '';
$loans = $payment->getLoans();

// $amount_per_month = $amount / $activeLoan['term'];
// $amount_per_15th = $amount_per_month / 2;

if (isset($_POST['create'])) {
    // dump($_POST);
    $payment->create($_POST);
    $errors = $payment->validate();



    //get the data
    $data = $payment->getData();
    $loan_id = sanitize($data['loan_id']);
    $payment_amount = sanitize($data['payment_amount']);
}
if (empty($loans)) {
    redirect('payments.php');
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Create Payment</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <label for="loan_id">Select loan</label>
                    <select class="selectpicker form-control border
                    <?php
                    if (!empty(($loan_id))) {
                        echo $errors['loan_id'] ? 'is-invalid' : '';
                    } else {
                        if ($errors['loan_id']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " name="loan_id" id="loan_id" data-live-search="true">
                        <option value="null"> Select loan</option>
                        <?php foreach ($loans as $loan) : ?>
                            <option data-tokens="
                            <?php echo ucfirst($payment->getUser($loan->user_id)->firstname) . ' ' . ucfirst($payment->getUser($loan->user_id)->lastname) . ' - '
                                . strtoupper($payment->getLoanTypeName($loan->loan_type_id)) . ' - Amount: PHP' .  formatDecimal($loan->amount) .
                                ' ** Per Month: PHP ' . formatDecimal($loan->amount_per_month) . ' ** Per/Kinsenas: PHP ' . formatDecimal($loan->amount_per_kinsenas)
                                . '**Interest Amount : PHP ' . formatDecimal($loan->interest_amount) . '** Monthly Interest: PHP ' . formatDecimal($loan->interest_amount_per_month)
                                . '** Interest per kinsenas: PHP ' . formatDecimal($loan->interest_amount_per_kinsenas)
                            ?>
                            " value="<?php echo $loan->id ?>">
                                <?php echo ucfirst($payment->getUser($loan->user_id)->firstname) . ' ' . ucfirst($payment->getUser($loan->user_id)->lastname) . ' - '
                                    . 'Type: ' . strtoupper($payment->getLoanTypeName($loan->loan_type_id)) . ' - Amount: PHP' .  formatDecimal($loan->amount) .
                                    ' ** Per Month: PHP ' . formatDecimal($loan->amount_per_month) . ' ** Per/Kinsenas: PHP ' . formatDecimal($loan->amount_per_kinsenas)
                                    . '**Interest Amount : PHP ' . formatDecimal($loan->interest_amount) . '** Monthly Interest: PHP ' . formatDecimal($loan->interest_amount_per_month)
                                    . '** Interest per kinsenas: PHP ' . formatDecimal($loan->interest_amount_per_kinsenas)
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-danger">
                        <small><?php echo $errors['loan_id'] ?? '' ?></small>
                    </div>
                </div>



                <div class="form-group">
                    <label for="payment_amount">Loan Payment(per month/per kinsenas)</label>
                    <input type="number" name="payment_amount" id="payment_amount" step=".01" class="form-control">

                </div>

                <!-- interest amount -->
                <div class="form-group">
                    <label for="interest_amount">Interest Payment(per month/per kinsenas)</label>
                    <input type="number" name="interest_amount" id="interest_amount" step="0.1" class="form-control">
                </div>


                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="payments.php" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
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