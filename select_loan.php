<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();
$loan_type_id = $amount = $term = $department_id = '';
$departments = $loan->getDepartments();
$loan_types = $loan->getLoanTypes();
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
        <h1 class="mt-5 text-center">Choose the best loan for you</h1>
        <p class="mt-5 text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto vitae quaerat perferendis at architecto tempora alias rem sit. Enim, eligendi!</p>
        <div class="row mt-5">
            <a href="loan_create_regular.php" class="col-md-5 m-auto bg-warning mb-2 d-flex border align-items-center justify-content-center rounded loan-type-box" style="min-height: 200px; margin-bottom: .5rem!important">
                <div class="text-center">
                    <h5 class="text-white">Regular</h5>
                    <p class="text-center text-white">1% Interest Rate</p>
                </div>
            </a>
            <a href="loan_create_character.php" class="col-md-5 m-auto bg-warning mb-2 d-flex border align-items-center justify-content-center rounded loan-type-box" style="min-height: 200px">
                <div class="text-center">
                    <h5 class="text-white">Character</h5>
                    <p class="text-center text-white">2% Interest Rate</p>
                </div>
            </a>

        </div>

    </div>


</div>


<?php include 'app/includes/footer.php' ?>