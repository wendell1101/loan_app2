<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$fixedDeposit = new AdminFixedDeposit();
$users = $fixedDeposit->getUsers();
$deposits = $fixedDeposit->index();
$errors = [];

$payment_by = $payment_amount = '';


if (isset($_POST['create'])) {
    $fixedDeposit->create($_POST);
    $errors = $fixedDeposit->validate();
    //get the data
    $data = $fixedDeposit->getData();
    $payment_by = sanitize($data['payment_by']);
    $amount = sanitize($data['amount']);
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
                    <label for="user_id">Select Account</label>
                    <select class="selectpicker form-control border " name="user_id" id="user_id" data-live-search="true" required>
                        <option value=""> Select account number</option>
                        <?php foreach ($users as $user) : ?>
                            <option data-tokens="
                        <?php echo $user->account_number . ' - ' . $user->firstname . ' ' . $user->lastname ?>" value="<?php echo $user->id ?>">
                                <?php echo $user->account_number . ' - ' . $user->firstname . ' ' . $user->lastname ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                    <label for="amount">Payment Amount</label>
                    <input type="number" name="amount" id="amount" min="1000" max="20000" class="form-control
                    <?php
                    if (!empty(($amount))) {
                        echo $errors['amount'] ? 'is-invalid' : 'is-valid';
                    } else {
                        if ($errors['amount']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $amount ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['amount'] ?? '' ?></small>
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