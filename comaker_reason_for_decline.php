<?php
require_once 'path.php';
require_once 'core.php';

$loan = new Loan();

if (!isset($_GET['loan_id']) || !isset($_GET['user_id'])) {
    header('location: comakers.php');
}
$activeLoan = $loan->getLoan($_GET['loan_id']);
$user_id = $_GET['user_id'];
$loan_id = $_GET['loan_id'];
if (isset($_POST['submit'])) {

    if ($activeLoan->comaker1_id == $user_id) {
        $comaker_reason_for_decline = $_POST['comaker_reason_for_decline'];

        $sql = "INSERT INTO declined_comaker_loans (comaker_reason_for_decline, loan_id, user_id) VALUES ('$comaker_reason_for_decline', '$loan_id', '$user_id')";
        $inserted = $conn->query($sql);
        if ($inserted) {
            $sql = "UPDATE users SET is_comaker=0 WHERE id=$user_id";
            $run1 = $conn->query($sql);
            $sql = "UPDATE loans SET comaker1_id=NULL WHERE id=$loan_id";
            $stmt = $conn->query($sql);
            if ($stmt) {
                message('success', 'Comaker request updated');
                redirect('comakers.php');
            }
        }
    } else if ($activeLoan->comaker2_id == $user_id) {

        if ($activeLoan->comaker2_id == $user_id) {
            $comaker_reason_for_decline = $_POST['comaker_reason_for_decline'];
            $user_id = $_GET['user_id'];
            $loan_id = $_GET['loan_id'];
            $sql = "INSERT INTO declined_comaker_loans (comaker_reason_for_decline, loan_id, user_id) VALUES ('$comaker_reason_for_decline', '$loan_id', '$user_id')";
            $inserted = $conn->query($sql);
            if ($inserted) {
                $sql = "UPDATE users SET is_comaker=0 WHERE id=$user_id";
                $run1 = $conn->query($sql);
                $sql = "UPDATE loans SET comaker2_id=NULL WHERE id=$loan_id";
                $stmt = $conn->query($sql);
                if ($stmt) {
                    message('success', 'Comaker request updated');
                    redirect('comakers.php');
                }
            }
        }
    }
}





?>
<?php require_once BASE . '/app/includes/header.php' ?>

<div class="wrapper" style="padding: 100px 0">
    <div class="container">
        <div class="row">

            <div class="col-md-8 mx-auto shadow">
                <h2>Comaker Reason for decline</h2>
                <form action="#" method="POST" class="mt-5">
                    <div class="form-group">
                        <label for="comaker_reason_for_decline">Reason for decline</label>
                        <input type="text" name="comaker_reason_for_decline" id="comaker_reason_for_decline" class="form-control">
                    </div>
                    <div class="form-group">
                        <a class="btn btn-secondary" href="comakers.php">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>


<?php include 'app/includes/footer.php' ?>