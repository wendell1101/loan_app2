<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$loan = new Loan();
if (isset($_SESSION['active_loan']) && isset($_SESSION['total_amount'])) {
    // print_r($_GET['activeLoan']);
    $activeLoan = $_SESSION['active_loan'];
    $amount = $activeLoan['amount'];
    $total_amount = $_SESSION['total_amount'];
    $interest = $loan->getInterest($activeLoan['loan_type_id'])->interest;
    $loan_type = $loan->getInterest($activeLoan['loan_type_id'])->name;
    $activeUser = $user->getUser();
    $department = $loan->getDepartment($activeLoan['department_id']);
} else {
    redirect('loan_create.php');
}
if (isset($_POST['finalize_loan'])) {
    $loan->saveLoan();
}
?>


<?php include 'app/includes/header.php' ?>

<div class="wrapper">
    <div class="container">
        <h1 class="mt-5 text-center title text-success">Finalize your loan</h1>
        <p class="mt-5 text-center">Loan Summary</p>
        <div class="row justify-content-center">
            <div class="col-md-5 mr-2 p-2 border">
                <h3 class="title">Personal Information</h3>
                <p class="mt-3">
                    <small>Fullname: </small>
                    <?php echo $activeUser->firstname . ' ' . $activeUser->lastname ?>
                </p>
                <p>
                    <small>Email: </small>
                    <?php echo $activeUser->email ?>
                </p>
                <p>
                    <small>Contact Number:</small>
                    <?php echo $activeUser->contact_number ?>
                </p>
            </div>
            <div class="col-md-5 p-2 border">
                <h3 class="title">Loan Details</h3>
                <p class="mt-3">
                    <small>Loan Type:</small>
                    <?php echo $loan_type ?>
                </p>
                <p>
                    <small>Interest Rate:</small>
                    <?php echo formatDecimal($interest) ?> % per month
                </p>
                <p>
                    <small>Term:</small>
                    <?php echo $activeLoan['term'] ?> months
                </p>
                <p>
                    <small>Department:</small>
                    <?php echo $department->name ?>
                </p>
                <p>
                    <small>Amount:</small>
                    PHP <?php echo formatDecimal($amount) ?>
                </p>
                <p>
                    <small>Total Amount:</small>
                    PHP <?php echo formatDecimal($total_amount) ?>
                </p>
                <div class="d-flex">
                    <a href="loan.php" class="btn btn-secondary mr-2">Cancel</a>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <button type="submit" name="finalize_loan" class="btn btn-success">Finalize Loan</a>
                    </form>

                </div>

            </div>
        </div>
    </div>


</div>

<?php include 'app/includes/footer.php' ?>