<?php
require_once 'path.php';
require_once 'core.php';
require_once 'app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

unset($_SESSION['active_amount']);
unset($_SESSION['active_loan']);
$loan = new Loan();
if (isset($_GET['id'])) {

    $activeLoan = $loan->getLoan($_GET['id']);
    $activeUser = $loan->getUser($activeLoan->user_id);
    // $interest_amount = $_SESSION['interest_amount_per_month'];
    $amount_per_month = $activeLoan->amount / $activeLoan->term;
    $amount_per_15th = $amount_per_month / 2;
    $comakers = $loan->getComakers($activeLoan->id);
} else {
    redirect('loan_create.php');
}
?>

<?php require_once 'app/includes/header.php' ?>

<div class="wrapper">
    <div class="container" style="margin-top:100px; margin-bottom:100px">
        <?php include 'app/includes/message.php' ?>
        <article class="card border">
            <div class="card-body">
                <h6>Transaction ID: <?php echo $activeLoan->transaction_id ?></h6>
                <article class="card">
                    <div class="card-body row">
                        <div class="col"> <strong>Loan By:</strong> <br><?php echo ucfirst($activeUser->firstname) . ' ' . ucfirst($activeUser->lastname) ?></div>
                        <div class="col"> <strong>Email:</strong> <br><?php echo $activeUser->email ?></div>
                        <div class="col text-uppercase"> <strong>Status:</strong> <br> <?php echo $activeLoan->status ?> </div>
                        <div class="col"> <strong>Loan #:</strong> <br> <?php echo $activeLoan->loan_number ?></div>
                    </div>
                </article>
                <div class="track">
                    <?php if ($activeLoan->status === 'pending') : ?>
                        <div class="step active"> <span class="icon"> <i class="far fa-clock"></i> </span> <span class="text">Pending</span> </div>
                        <div class="step"> <span class="icon"><i class="far fa-calendar-check"></i> </span> <span class="text"> Active</span> </div>
                        <div class="step"> <span class="icon"> <i class="fas fa-check"></i> </span> <span class="text"> Paid</span> </div>
                        <div class="step"> <span class="icon"> <i class="far fa-window-close"></i> </span> <span class="text">Cancelled</span> </div>
                    <?php elseif ($activeLoan->status === 'active') : ?>
                        <div class="step active"> <span class="icon"> <i class="far fa-clock"></i> </span> <span class="text">Pending</span> </div>
                        <div class="step active"> <span class="icon"> <i class="far fa-calendar-check"></i> </span> <span class="text"> Active</span> </div>
                        <div class="step"> <span class="icon"> <i class="fas fa-check"></i> </span> <span class="text"> Paid</span> </div>
                        <div class="step"> <span class="icon"> <i class="far fa-window-close"></i> </span> <span class="text">Cancelled</span> </div>
                    <?php elseif ($activeLoan->status === 'paid') : ?>
                        <div class="step active"> <span class="icon"> <i class="far fa-clock"></i> </span> <span class="text">Pending</span> </div>
                        <div class="step active"> <span class="icon"> <i class="far fa-calendar-check"></i> </span> <span class="text"> Active</span> </div>
                        <div class="step active"> <span class="icon"> <i class="fas fa-check"></i> </span> <span class="text"> Paid</span> </div>
                        <div class="step"> <span class="icon"> <i class="far fa-window-close"></i> </span> <span class="text">Cancelled</span> </div>
                    <?php elseif ($activeLoan->status == 'cancelled') : ?>
                        <div class="step active"> <span class="icon"> <i class="far fa-clock"></i> </span> <span class="text">Pending</span> </div>
                        <div class="step active"> <span class="icon"> <i class="far fa-calendar-check"></i> </span> <span class="text"> Active</span> </div>
                        <div class="step active"> <span class="icon"> <i class="fas fa-check"></i> </span> <span class="text"> Paid</span> </div>
                        <div class="step active"> <span class="icon"> <i class="far fa-window-close"></i> </span> <span class="text">Cancelled</span> </div>
                    <?php endif; ?>
                </div>
                <hr>


                <span style="font-weight:500">Date: </span>
                <span><?php echo shortDate($activeLoan->created_at) ?></span><br>
                <span style="font-weight:500">Amount: </span>PHP <?php echo formatDecimal($activeLoan->amount) ?><span></span><br>
                <span style="font-weight:500">Interest: </span><?php echo formatDecimal($loan->getInterest($activeLoan->loan_type_id)->interest) ?> %
                <span></span><br>
                <span style="font-weight:500">Expected Payment(per month):</span> PHP <?php echo formatDecimal($amount_per_month) ?><br>
                <span style="font-weight:500">Expected Payment(per kinsenas):</span> PHP <?php echo formatDecimal($amount_per_15th) ?></span><br>
                <!-- <span style="font-weight:500">Total Amount:</span> PHP <?php echo formatDecimal($activeLoan->total_amount) ?></span><br> -->
                <?php if ($comakers) : ?>
                    <?php foreach ($comakers as $comaker) : ?>
                        <span style="font-weight:200">Comaker: <?php echo ucfirst($comaker->firstname) . ' ' . ucfirst($comaker->lastname) ?></span><br>
                    <?php endforeach ?>
                <?php endif; ?>

                <hr>
                <a href="loans.php" class="btn btn-success" data-abc="true"> <i class="fa fa-chevron-left"></i> Back to loans</a>
            </div>
        </article>
    </div>
</div>
<?php include 'app/includes/footer.php' ?>