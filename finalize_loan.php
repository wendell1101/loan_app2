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
    $comakers = $_SESSION['comakers'];
    $amount = $activeLoan['amount'];
    $total_amount = $_SESSION['total_amount'];
    $interest_amount = $_SESSION['interest_amount_per_month'];
    $amount_per_month = $amount / $activeLoan['term'];
    $amount_per_15th = $amount_per_month / 2;

    $interest = $loan->getInterest($activeLoan['loan_type_id'])->interest;
    $loan_type = $loan->getInterest($activeLoan['loan_type_id'])->name;
    $activeUser = $user->getUser();
} else {
    redirect('select_loan.php');
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
                    <?php echo formatDecimal($interest) ?> % (PHP <?php echo formatDecimal($interest_amount) ?>) per month
                </p>
                <p>
                    <small>Term:</small>
                    <?php echo $activeLoan['term'] ?> months
                </p>

                <p>
                    <small>Amount:</small>
                    PHP <?php echo formatDecimal($amount) ?>
                </p>
                <p>
                    <small>Total Amount:</small>
                    PHP <?php echo formatDecimal($total_amount) ?>
                </p>
                <p>
                    <small>Expected Payment(per month):</small>
                    PHP <?php echo formatDecimal($amount_per_month) ?>
                </p>
                <p>
                    <small>Expected Payment(per kinsenas):</small>
                    PHP <?php echo formatDecimal($amount_per_15th) ?>
                </p>
                <p class="">
                    <small>Comaker:</small><br>
                    <?php foreach ($comakers as $comaker) : ?>
                        <?php $selectedComaker = $loan->getUser($comaker) ?>
                        <small><?php echo ucfirst($selectedComaker->firstname) . ' ' . ucfirst($selectedComaker->lastname) . ',' ?></small>
                    <?php endforeach; ?>
                </p>

                <div class="d-flex">
                    <a href="select_loan.php" class="btn btn-secondary mr-2">Cancel</a>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <button type="submit" name="finalize_loan" class="btn btn-success">Finalize Loan</a>
                    </form>

                </div>

            </div>
        </div>
    </div>


</div>

<?php include 'app/includes/footer.php' ?>