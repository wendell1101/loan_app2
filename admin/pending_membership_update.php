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
if (isset($_POST['id'])) {
    $id = sanitize($_POST['id']);
    $activeUser = $adminUser->getUser($id);
} else {
    redirect('pending_memberships.php');
}
if (isset($_POST['update'])) {
    $adminUser->updateByMembershipCommittee($_POST);
    if ($_POST['approved'] == 0) {
        redirect("reason_for_decline.php?id=$id");
        // modal popup
    }
    // $errors = $adminUser->validate();
}

?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Update User</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $activeUser->id ?>">
                <h3>
                    User: <?php echo ucfirst($activeUser->firstname) . ' ' . ucfirst($activeUser->lastname) . ' - ' .
                                $activeUser->account_number
                            ?>
                </h3>
                <div class="from-group">
                    <label for="approved">Choose Action</label>
                    <select name="approved" id="approved" class="form-control" required>
                        <option value=""> Choose Action </option>
                        <option value="1" <?php if ($adminUser->checkIfActiveMembershipCommitteeHasApproved($activeUser->id) != 0) : ?> selected <?php endif; ?>>Approve</option>
                        <option value="0" <?php if ($adminUser->checkIfActiveMembershipCommitteeHasApproved($activeUser->id) == 0) : ?> selected <?php endif; ?>>Disapprove</option>
                    </select>
                </div>
                <div class="form-group my-3">
                    <a href="pending_memberships.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update" class="btn btn-success">Submit</button>
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