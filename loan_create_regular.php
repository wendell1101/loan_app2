<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();
$loan_type_id = $amount = $term = $department_id = '';
$departments = $loan->getDepartments();
$loan_type = $loan->getLoanByType('regular');
$comakers = $loan->getComakers();
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
        <p class="mt-5 text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto vitae quaerat perferendis at architecto tempora alias rem sit. Enim, eligendi!</p>

        <div class="row">
            <div class="col-md-9 mx-auto">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="shadow p-3">
                    <div class="form-group">
                        <label for="loan_type_id">Loan Type</label>
                        <select name="loan_type_id" id="loan_type_id" required class="form-control" read-only>
                            <option value="">Select loan type</option>
                            <option value="<?php echo $loan_type->id ?>" selected>
                                <?php echo $loan_type->name . ' - ' . $loan_type->interest ?>
                                % interest rate
                            </option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" max="<?php echo $loanable_amount ?>" required class="form-control
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
                        <small class="text-primary">Maximum Loanable Amount: PHP <?php echo formatDecimal($loanable_amount) ?></small>
                        <div class="text-danger">
                            <small><?php echo $errors['amount'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="term"> Term</label>
                        <select name="term" id="term" class="form-control
                        <?php
                        if (!empty(($term))) {
                            echo $errors['term'] ? 'is-invalid' : 'is-valid';
                        } else {
                            if ($errors['term']) {
                                echo 'is-invalid';
                            }
                        }
                        ?>

                        ">
                            <option value="null">Select term</option>
                            <option value="6">6 months</option>
                            <option value="12">12 months</option>
                            <option value="18">18 months</option>
                            <option value="24">24 months</option>
                        </select>
                        <div class="text-danger">
                            <small><?php echo $errors['term'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="position_id">Choose 2 Comaker</label>
                        <select name="position_id[]" id="position_id" class="form-control" multiple>
                            <?php foreach ($comakers as $comaker) : ?>
                                <option value="<?php echo $comaker->id ?>"><?php echo ucfirst($comaker->firstname) . ' ' . ucfirst($comaker->lastname) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <button type="submit" name="loan" class="btn btn-success">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>


<?php include 'app/includes/footer.php' ?>