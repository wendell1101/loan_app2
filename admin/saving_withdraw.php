<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$saving = new Savings();
$users = $saving->getUsers();
$errors = [];

$payment_by = $payment_amount = '';


if (isset($_POST['withdraw'])) {
    $saving->withdraw($_POST);
    $errors = $saving->validate();
    //get the data
    $data = $saving->getData();
    $payment_by = sanitize($data['payment_by']);
    $amount = sanitize($data['amount']);
}

if (!isset($_GET['user_id']) && !isset($_GET['saving_id'])) {
    redirect('savings.php');
}
$activeUser = $saving->getUser($_GET['user_id']);
$activeSaving = $saving->getSaving($_GET['saving_id']);


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
                <input type="hidden" name="user_id" value="<?php echo $activeUser->id ?>">
                <input type="hidden" name="saving_id" value="<?php echo $activeSaving->id ?>">
                <div class="form-group">
                    <label for="user_id">Account Number</label>
                    <input type="text" name="" id="" class="form-control" readonly value="<?php echo $activeUser->account_number
                                                                                                . ' - ' . ucfirst($activeUser->firstname) . ' ' . ucfirst($activeUser->lastname)  ?>">
                </div>


                <!-- <div class="form-group">
                    <select class="selectpicker form-control border " name="payment_by" id="payment_by" data-live-search="true" required>
                        <option value=""> Select Fullname</option>
                        <?php foreach ($users as $user) : ?>
                            <option data-tokens="
                        <?php echo $user->account_number . ' - ' . $user->firstname . ' ' . $user->lastname ?>" value="<?php echo $user->firstname . ' ' . $user->lastname ?>">
                                <?php echo $user->firstname . ' ' . $user->lastname ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div> -->
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