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

if (isset($_POST['create'])) {
    $payment->create($_POST);
    $errors = $payment->validate();



    //get the data
    $data = $payment->getData();
    $loan_id = sanitize($data['loan_id']);
    $payment_by = sanitize($data['payment_by']);
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
                        <?php echo $loan->transaction_id . ' - ' . $loan->loan_number . ' - PHP' . formatDecimal($loan->total_amount); ?>" value="<?php echo $loan->id ?>">
                                <?php echo $payment->getUser($loan->user_id)->firstname . ' ' . $payment->getUser($loan->user_id)->lastname . ' - ' . $loan->loan_number . ' - (PHP' . formatDecimal($loan->total_amount) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-danger">
                        <small><?php echo $errors['loan_id'] ?? '' ?></small>
                    </div>
                </div>

                <div class="form-group">
                    <select class="selectpicker form-control border " name="payment_by" id="payment_by" data-live-search="true" required>
                        <option value=""> Select Fullname</option>
                        <?php foreach ($users as $user) : ?>
                            <option data-tokens="
                        <?php echo $user->account_number . ' - ' . $user->firstname . ' ' . $user->lastname ?>" value="<?php echo $user->firstname . ' ' . $user->lastname ?>">
                                <?php echo $user->firstname . ' ' . $user->lastname ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_amount">Payment Amount</label>
                    <input type="number" name="payment_amount" id="payment_amount" class="form-control
                    <?php
                    if (!empty(($payment_amount))) {
                        echo $errors['payment_amount'] ? 'is-invalid' : 'is-valid';
                    } else {
                        if ($errors['payment_amount']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $payment_amount ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['payment_amount'] ?? '' ?></small>
                    </div>
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