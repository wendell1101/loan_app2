<?php
require_once 'path.php';
require_once 'core.php';
if (!isset($_SESSION['id'])) {
    redirect('login.php');
}
// require_once 'app/middlewares/AuthMiddleware.php';

$loan = new Loan();

$user_id = $_SESSION['id'];

if (isset($_POST['comaker_approval'])) {
    $approve = $_POST['approve'];

    $loan_id = $_POST['loan_id'];
    $user_id = $_SESSION['id'];


    $activeLoan = $loan->getLoan($loan_id);
    if ($activeLoan->comaker1_id == $user_id) {
        if ($approve == 1) {
            $sql = "UPDATE loans SET approved_by_c1=1 WHERE id=$loan_id AND comaker1_id=$user_id";
            $stmt = $conn->query($sql);
            if ($stmt) {
                message('success', 'Comaker request updated');
                redirect('comakers.php');
            }
        } else if ($approve == 0) {
            redirect('comaker_reason_for_decline.php');
            $sql = "UPDATE loans SET comaker1_id=NULL WHERE id=$loan_id";
            $stmt = $conn->query($sql);
            if ($stmt) {
                message('success', 'Comaker request updated');
                redirect('comakers.php');
            }
        }
    } else if ($activeLoan->comaker2_id == $user_id) {
        if ($approve == 1) {
            $sql = "UPDATE loans SET approved_by_c2=1 WHERE id=$loan_id AND comaker2_id=$user_id";
            $stmt = $conn->query($sql);
            if ($stmt) {
                message('success', 'Comaker request updated');
                redirect('comakers.php');
            }
        } else if ($approve == 0) {
            $sql = "UPDATE loans SET comaker2_id=NULL WHERE id=$loan_id";
            $stmt = $conn->query($sql);
            if ($stmt) {
                message('success', 'Comaker request updated');
                redirect('comakers.php');
            }
        }
    }
}

$sql = "SELECT * FROM loans WHERE comaker1_id=$user_id AND approved_by_c1=0 OR comaker2_id=$user_id AND  approved_by_c2=0";
$stmt = $conn->query($sql);
$activeLoan = $stmt->fetch();

if ($activeLoan) {
    $activeUser = $loan->getUser($activeLoan->user_id);
}



?>
<?php require_once BASE . '/app/includes/header.php' ?>

<div class="wrapper" style="padding: 100px 0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-5">Comaker Request</h1>
                <?php if ($activeLoan) : ?>
                    <p>
                        <?php echo $activeLoan->loan_number . ' - by: ' . ucfirst($activeUser->firstname) . ' ' . ucfirst($activeUser->lastname) ?>
                    </p>
                    <p>
                        Amount : PHP <?php echo formatDecimal($activeLoan->amount) ?>
                    </p>


                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="loan_id" value="<?php echo $activeLoan->id ?>">
                            <input type="radio" name="approve" id="approve" value="1" checked> Agree
                            <input type="radio" name="approve" id="approve" value="0"> Disagree
                            <button type="submit" name="comaker_approval" class="btn btn-success btn-sm">Submit</button>
                        </div>

                    </form>

                <?php else : ?>
                    <p class="text-secondary">No comaker request yet</p>
                <?php endif ?>
            </div>

        </div>
    </div>
</div>


<?php include 'app/includes/footer.php' ?>