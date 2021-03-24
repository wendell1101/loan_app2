<?php
ob_start();
require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
require_once '../app/middlewares/SuperAdmin.php';
$auth = new Auth();
$auth->restrict();


$type = new Type();
$types = $type->index();
$errors = [];

$name = '';
$interest = '';
if (isset($_POST['create'])) {
    $type->create($_POST);
    $errors = $type->validate();



    //get the data
    $data = $type->getData();
    $name = sanitize($data['name']);
    $interest = sanitize($data['interest']);
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Loan Types</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control
                    <?php
                    if (!empty(($name))) {
                        echo $errors['name'] ? 'is-invalid' : 'is-valid';
                    } else {
                        if ($errors['name']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $name ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['name'] ?? '' ?></small>
                    </div>


                </div>
                <div class="form-group">
                    <label for="interest">Iterest Rate</label>
                    <input type="text" name="interest" id="interest" class="form-control
                    <?php
                    if (!empty(($interest))) {
                        echo $errors['interest'] ? 'is-invalid' : 'is-valid';
                    } else {
                        if ($errors['interest']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $interest ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['interest'] ?? '' ?></small>
                    </div>
                    <div class="form-group d-flex justify-content-end align-items-center mt-2">
                        <a href="types.php" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" name="create" class="btn btn-primary">Create</button>
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

<?php require_once '../app/includes/admin/footer.php';
ob_flush();
?>