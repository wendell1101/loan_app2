<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();
$comakers = $loan->getOtherUsers();
$loan_type_id = $amount = $term = $department_id = '';
$departments = $loan->getDepartments();
$loan_type = $loan->getLoanByType('character');
$checkIfHasFixedDeposit = $loan->checkIfHasFixedDeposit($_SESSION['id']);
$loanable_amount = $loan->getLoanableAmount($_SESSION['id']);

if (!$checkIfHasFixedDeposit) {
    message('danger', 'You must have a fixed deposit first to request a loan');
    redirect('index.php');
}

if (isset($_POST['loan'])) {
    $loan->create($_POST);
    $errors = $loan->validate();

    //get the data
    $data = $loan->getData();
    $loan_type_id = sanitize($data['loan_type_id']);
    $amount = sanitize($data['amount']);
    $term = sanitize($data['term']);
}


?>

<?php include 'app/includes/header.php' ?>

<div class="wrapper">
    <div class="container">
        <h1 class="mt-5 text-center">Fill up the required information</h1>


        <div class="row">
            <div class="col-md-9 mx-auto">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="shadow p-3 loan-form">
                    <div class="form-group">
                        <label for="loan_type_id">Loan Type</label>
                        <select name="loan_type_id" id="loan_type_id" required class="form-control" read-only>
                            <option value="<?php echo $loan_type->id ?>" selected>
                                <?php echo $loan_type->name . ' - ' . $loan_type->interest ?>
                                % interest rate
                            </option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" min="5000" max="5000" value="5000" read-only required class="form-control
                        <?php
                        if (!empty(($amount))) {
                            echo $errors['amount'] ? 'is-invalid' : '';
                        } else {
                            if ($errors['amount']) {
                                echo 'is-invalid';
                            }
                        }
                        ?>
                        " value="<?php echo $amount ?>">
                        <small class="text-primary">Fixed Loanable Amount: PHP <?php echo 5000 ?></small>
                        <div class="text-danger">
                            <small><?php echo $errors['amount'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="term"> Term</label>
                        <select name="term" id="term" class="form-control" required>
                            <option value="5" selected>5 months</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comaker_id">Choose 2 Comaker</label>
                        <select name="comaker_id[]" id="comaker_id" class="form-control selecte" id="selecte" multiple>
                            <?php foreach ($comakers as $comaker) : ?>
                                <option value="<?php echo $comaker->id ?>"><?php echo ucfirst($comaker->firstname) . ' ' . ucfirst($comaker->lastname) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <a href="select_loan.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="loan" class="btn btn-success">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>


<?php include 'app/includes/footer.php' ?>