<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminUser = new AdminUser();


$id = '';
if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);
    $activeUser = $adminUser->getUser($id);
} else {
    redirect('pending_memberships.php');
}
if (isset($_POST['decline'])) {
    $adminUser->membershipDecline($_POST);
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Reason for Declining of Member</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $id ?>" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_GET['id'] ?>">
                <div class="form-group">
                    <label for="reason_for_decline">Reason</label>
                    <input type="text" name="reason_for_decline" id="reason_for_decline" class="form-control" required>
                </div>
                <div class="form-group my-3">
                    <?php if ($_SESSION['position_id'] == 6) : ?>
                        <a href="pending_memberships.php" class="btn btn-secondary">Cancel</a>>
                    <?php elseif ($_SESSION['position_id'] == 3) : ?>
                        <a href="pending_memberships_president.php" class="btn btn-secondary">Cancel</a>
                    <?php endif ?>

                    <button type="submit" name="decline" class="btn btn-success">Submit</button>
                </div>
            </form>


        </div>
    </div>
</div>
<!-- Modal -->
<!-- Button trigger modal -->

<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once BASE . '/app/includes/admin/footer.php';
ob_flush();
?>