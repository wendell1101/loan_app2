<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminUser = new AdminUser();
$adminLoan = new AdminLoan();


$id = '';
if (isset($_POST['id'])) {
    $id = sanitize($_POST['id']);
    $activeLoan = $adminUser->getLoan($id);
} else {
    redirect('pending_president_loans.php');
}
if (isset($_POST['update'])) {

    // $adminUser->updateByMembershipCommittee($_POST);
    $adminLoan->updateLoanByPresident($_POST);
    // $errors = $adminUser->validate();
}

?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Update Loan</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $activeLoan->id ?>">
                <h3>
                    Loan Number: <?php echo $activeLoan->loan_number ?><br><br>
                    Loan Amount: PHP <?php echo formatDecimal($activeLoan->amount) ?><br><br>
                </h3>
                <div class="from-group">
                    <label for="approved">Choose Action</label>
                    <select name="approved" id="approved" class="form-control" required>
                        <option value=""> Choose Action </option>
                        <option value="1" <?php if ($activeLoan->approved_by_president) : ?> selected <?php endif; ?>>Approve</option>
                        <option value="0" <?php if (!$activeLoan->approved_by_president) : ?> selected <?php endif; ?>>Disapprove</option>
                    </select>
                </div>
                <div class="form-group my-3">
                    <a href="pending_president_loans.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once BASE . '/app/includes/admin/footer.php';
ob_flush();
?>