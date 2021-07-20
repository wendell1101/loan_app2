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

$loan_id = $payment_by = $payment_amount = $saving_id = $fixed_deposit_id =
    $payment_saving = $payment_fixed_deposit = '';
$loans = $payment->getLoans();

if (isset($_POST['create'])) {
    // dump($_POST);
    $payment->createPayment($_POST);
    $errors = $payment->validate();



    //get the data
    $data = $payment->getData();
    $loan_id = sanitize($data['loan_id']);
    $payment_by = sanitize($data['payment_by']);
    $payment_amount = sanitize($data['payment_amount']);
    $payment_fixed_deposit = sanitize($data['payment_fixed_deposit']);
    $payment_saving = sanitize($data['payment_saving']);
}
// if (empty($loans)) {
//     redirect('payments.php');
// }


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


                <!-- saving amount -->
                <div class="form-group">
                    <h3 style="font-size: 1.5rem" class="mt-2 mb-2">Deposit</h3>
                </div>

                <div class="form-group">
                    <label for="loan_id">Select User</label>
                    <select class="selectpicker form-control border
                    <?php
                    if (!empty(($user_id))) {
                        echo $errors['user_id'] ? 'is-invalid' : '';
                    } else {
                        if ($errors['user_id']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " name="user_id" id="user_id" data-live-search="true" <?php echo $loans ? '' : 'required' ?> required>
                        <option value=""> Select User's Account Number</option>
                        <?php foreach ($users as $user) : ?>
                            <option data-tokens="
                            <?php echo $payment->getUser($user->id)->account_number . ' - ' . $payment->getUser($user->id)->firstname . ' ' . $payment->getUser($user->id)->lastname ?>" value="<?php echo $user->id ?>">
                                <?php echo $payment->getUser($user->id)->account_number . ' - ' . $payment->getUser($user->id)->firstname . ' ' . $payment->getUser($user->id)->lastname ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-danger">
                        <small><?php echo $errors['user_id'] ?? '' ?></small>
                    </div>
                </div>
                <!-- Fixed deposit amount-->

                <div class="form-group">
                    <label for="payment_fixed_deposit">Fixed Deposit Amount</label>
                    <input type="number" name="payment_fixed_deposit" id="payment_fixed_deposit" class="form-control
                    <?php
                    if (!empty(($payment_fixed_deposit))) {
                        echo $errors['payment_fixed_deposit'] ? 'is-invalid' : '';
                    } else {
                        if ($errors['payment_fixed_deposit']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $payment_fixed_deposit ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['payment_fixed_deposit'] ?? '' ?></small>
                    </div>

                </div>
                <div class="text-danger">
                    <small><?php echo $errors['payment_fixed_deposit'] ?? '' ?></small>
                </div>
                <div class="form-group">
                    <label for="payment_saving">Saving Amount</label>
                    <input type="number" name="payment_saving" id="payment_saving" class="form-control
                    <?php
                    if (!empty(($payment_saving))) {
                        echo $errors['payment_saving'] ? 'is-invalid' : '';
                    } else {
                        if ($errors['payment_saving']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $payment_saving ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['payment_saving'] ?? '' ?></small>
                    </div>
                </div>
                <div class="text-danger">
                    <small><?php echo $errors['payment_saving'] ?? '' ?></small>
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