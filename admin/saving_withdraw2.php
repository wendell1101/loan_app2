<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$saving = new Savings();
$users = $saving->getUsers();
$savings = $saving->index();
// dump($savings);
$errors = [];

$payment_by = $payment_amount = '';


if (isset($_POST['withdraw'])) {
    $saving->withdraw($_POST);
    $errors = $saving->validate();
    //get the data
    $data = $saving->getData();
    // $payment_by = sanitize($data['payment_by']);
    $amount = sanitize($data['amount']);
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Withdraw Saving</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                <div class="form-group">
                    <label for="">Select Saving</label>
                    <select class="selectpicker form-control border

                    " name="saving_id" id="saving_id" data-live-search="true" required>

                        <option value=""> Choose Saving</option>
                        <?php foreach ($savings as $singleSaving) : ?>
                            <option data-tokens="
                            <?php echo $singleSaving->reference_number . ' - ' . $singleSaving->amount . ' (' . $saving->getUser($singleSaving->user_id)->firstname . ' ' .
                                $saving->getUser($singleSaving->user_id)->lastname . ')' ?>" value="<?php echo $singleSaving->id ?>">
                                <?php echo $singleSaving->reference_number . ' - Saving Amount: PHP' . formatDecimal($singleSaving->amount) . ' - ' . ucfirst($saving->getUser($singleSaving->user_id)->firstname) . ' ' .
                                    ucfirst($saving->getUser($singleSaving->user_id)->lastname) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control
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
                    <a href="savings.php" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" name="withdraw" class="btn btn-primary">Withdraw</button>
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