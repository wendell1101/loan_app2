<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$fixedDeposit = new FixedDeposit();
$errors = [];
$amount = '';

if (isset($_POST['deposit'])) {
    $fixedDeposit->create($_POST);
    $errors = $fixedDeposit->validate();
    $data = $fixedDeposit->getData();
    $amount = sanitize($data['amount']);
}



?>

<?php include 'app/includes/header.php' ?>

<div class="wrapper">
    <div class="container">
        <div class="text-center">
            <h1 class="mt-5 text-center">Initial Fixed Deposit</h1>
            <small class="text-danger mx-auto">Fixed deposit is required to loan</small>
            <p class="mt-5 text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto vitae quaerat perferendis at architecto tempora alias rem sit. Enim, eligendi!</p>
        </div>


        <div class="row">
            <div class="col-md-9 mx-auto">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="shadow p-3">
                    <div class="form-group">
                        <label for="amount">Amount to deposit</label>
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
                        " value="<?php echo $amount ?? '' ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['amount'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="deposit" class="btn btn-success">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

<?php include 'app/includes/footer.php' ?>