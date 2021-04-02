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
    $firstname = $activeUser->firstname;
    $lastname = $activeUser->lastname;
    $email = $activeUser->email;
    $id = $activeUser->id;
} else {
    redirect('admin_users.php');
}
if (isset($_POST['update'])) {
    $adminUser->update($_POST);
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
                <?php include '../app/includes/message.php' ?>
                <div class="row">
                    <!--firstname-->
                    <div class="col-6">
                        <label for="firstname">Firstname</label>
                        <input type="text" name="firstname" id="firstname" placeholder="First Name*" class="form-control

                        " value="<?php echo $firstname ?>" readonly>

                    </div>
                    <!--lastname-->
                    <div class="col-6">
                        <label for="lastname">Lastname</label>
                        <input type="text" name="lastname" id="lastname" class="form-control

                        " placeholder="Last Name*" value="<?php echo $lastname ?>" readonly>

                    </div>
                </div>
                <!--email-->
                <div class="form-group mt-2">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control

                    " placeholder="Enter email*" value="<?php echo $email ?>" readonly>

                </div>

                <div class="form-group">
                    <label for="role">Status</label>
                    <select name="active" id="active" class="form-control">
                        <option value="0" <?php echo (!$activeUser->active) ? "selected" : '' ?>>Pending</option>
                        <option value="1" <?php echo ($activeUser->active) ? "selected" : '' ?>>Approve</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role">Membership Fee</label>
                    <select name="paid_membership" id="paid_membership" class="form-control">
                        <option value="0" <?php echo (!$activeUser->paid_membership) ? "selected" : '' ?>>Not Paid</option>
                        <option value="1" <?php echo ($activeUser->paid_membership) ? "selected" : '' ?>>Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role">Position</label>
                    <select name="position_id" id="position_id" class="form-control">
                        <option value="1" <?php echo ($activeUser->position_id === 1) ? "selected" : '' ?>>Admin</option>
                        <option value="2" <?php echo ($activeUser->position_id === 2) ? "selected" : '' ?>>Customer</option>
                        <option value="3" <?php echo ($activeUser->position_id === 3) ? "selected" : '' ?>>President</option>
                        <option value="4" <?php echo ($activeUser->position_id === 4) ? "selected" : '' ?>>Treasurer</option>
                        <option value="5" <?php echo ($activeUser->position_id === 5) ? "selected" : '' ?>>Assistant Treasurer</option>
                        <option value="6" <?php echo ($activeUser->position_id === 6) ? "selected" : '' ?>>Membership Committee</option>
                        <option value="7" <?php echo ($activeUser->position_id === 7) ? "selected" : '' ?>>Financial Committee</option>
                        <option value="8" <?php echo ($activeUser->position_id === 8) ? "selected" : '' ?>>Comaker</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="hidden" name="password1" value="<?php echo $activeUser->password1 ?>">
                <input type="hidden" name="password2" value="<?php echo $activeUser->password2 ?>">
                <input type="hidden" name="gender" value="<?php echo $activeUser->gender ?>">
                <input type="hidden" name="contact_number" value="<?php echo $activeUser->contact_number ?>">
                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="admin_users.php" class="btn btn-secondary mr-2">Cancel</a>
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