<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();



$adminLoan = new AdminLoan();


if (isset($_POST['id'])) {
    $id = sanitize($_POST['id']);
    $activeLoan = $adminLoan->getLoan($id);
}
if (isset($_POST['delete'])) {
    // $adminUser->deleteFromMembershipCommittee($_POST['id']);
    // $adminLoan->deleteFromFinancialCommittee($_POST['id']);
    $adminLoan->deleteLoanFromPresident($_POST['id']);
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <h1 style="font-size: 2rem">Are you sure you want to delete <?php echo $activeLoan->transaction_id ?>?</h1>
                    <div class="form-group d-flex justify-content-end align-items-center mt-2">
                        <input type="hidden" name="id" value="<?php echo $activeLoan->id ?>">
                        <a href="pending_president_loans.php" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once BASE . '/app/includes/admin/footer.php' ?>
<?php ob_flush(); ?>