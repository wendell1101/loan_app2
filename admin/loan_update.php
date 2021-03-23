<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminLoan = new AdminLoan();

$id = '';
if (isset($_POST['id'])) {
    $id = sanitize($_POST['id']);
    $activeLoan = $adminLoan->getLoan($id);
    $id = $activeLoan->id;
} else {
    redirect('loans.php');
}
if (isset($_POST['update'])) {
    $adminLoan->update($_POST);
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
                <div class="form-group">
                    <label for="loan_number">Loan Number</label>
                    <input type="text" name="loan_number" id="loan_number" class="form-control" value="<?php echo $activeLoan->loan_number ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" class="text-center" <?php echo ($activeLoan->status === 'pending') ? "selected" : '' ?>>Pending</option>
                        <option value="active" <?php echo ($activeLoan->status === 'active') ? "selected" : '' ?> class="text-center">Active</option>
                        <option value="paid" <?php echo ($activeLoan->status === 'paid') ? "selected" : '' ?> class="text-center">Paid</option>
                        <option value="cancelled" <?php echo ($activeLoan->status === 'cancelled') ? "selected" : '' ?> class="text-center">Cancelled</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $activeLoan->id ?>">
                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="loans.php" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
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