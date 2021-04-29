<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$voucher = new Voucher();
$vouchers = $voucher->index();
$categories = $voucher->getVoucherCategories();
$members = $voucher->getMembers();

$errors = [];

$name = '';
if (isset($_POST['create'])) {
    $voucher->create($_POST);
    $errors = $voucher->validate();
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Create Voucher</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <!-- Voucher Category -->
                <?php if ($categories) : ?>
                    <div class="form-group">
                        <label for="voucher_category_id">Voucher Category</label>
                        <select name="voucher_category_id" id="voucher_category_id" class="form-control" required>
                            <option value=""> Choose Voucher Category</option>
                            <?php foreach ($categories as $singleCategory) : ?>
                                <option value="<?php echo $singleCategory->id ?>"><?php echo $singleCategory->name ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                <?php endif ?>
                <!-- Members -->
                <?php if ($members) : ?>
                    <div class="form-group">
                        <label for="user_id">Member</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value=""> Choose Member</option>
                            <?php foreach ($members as $member) : ?>
                                <option value="<?php echo $member->id ?>">
                                    <?php echo $member->account_number . ' - ' . ucfirst($voucher->getUser($member->id)->firstname) . ' ' .
                                        ucfirst($voucher->getUser($member->id)->lastname) ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                <?php endif ?>
                <div class="form-group">
                    <label for="amount">Voucher Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" step=".01" required>
                </div>
                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="vouchers.php" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once '../app/includes/admin/footer.php';
ob_flush();
?>