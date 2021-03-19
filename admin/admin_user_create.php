<?php
ob_start();

require_once '../core.php';
require_once '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminUser = new AdminUser();

$errors = [];
$firstname = $lastname = $email = $password1 = $password2 = $gender = $contact_number = '';
$positions = $adminUser->getPositions();
if (isset($_POST['create'])) {
    $adminUser->create($_POST);



    $errors = $adminUser->validate();
    //get the data
    $data = $adminUser->getData();
    $firstname = sanitize($data['firstname']);
    $lastname = sanitize($data['lastname']);
    $email = sanitize($data['email']);
    $gender = sanitize($data['gender']);
    $contact_number = sanitize($data['contact_number']);
    $password1 = sanitize($data['password1']);
    $password2 = sanitize($data['password2']);
}


?>

<!-- Main content -->

<!-- Main content -->

<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Create User</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="row">
                    <!--firstname-->
                    <div class="col-6">
                        <input type="text" name="firstname" id="firstname" placeholder="First Name*" class="form-control
                            <?php
                            if (!empty(($firstname))) {
                                echo $errors['firstname'] ? 'is-invalid' : 'is-valid';
                            } else {
                                if ($errors['firstname']) {
                                    echo 'is-invalid';
                                }
                            }
                            ?>
                        " value="<?php echo $firstname ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['firstname'] ?? '' ?></small>
                        </div>
                    </div>
                    <!--lastname-->
                    <div class="col-6">
                        <input type="text" name="lastname" id="lastname" class="form-control
                            <?php
                            if (!empty(($lastname))) {
                                echo $errors['lastname'] ? 'is-invalid' : 'is-valid';
                            } else {
                                if ($errors['lastname']) {
                                    echo 'is-invalid';
                                }
                            }
                            ?>
                        " placeholder="Last Name*" value="<?php echo $lastname ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['lastname'] ?? '' ?></small>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="form-group">

                            <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number*" class="form-control
                            <?php
                            if (!empty(($contact_number))) {
                                echo $errors['contact_number'] ? 'is-invalid' : 'is-valid';
                            } else {
                                if ($errors['contact_number']) {
                                    echo 'is-invalid';
                                }
                            }
                            ?>
                            " value="<?php echo $contact_number ?>">
                            <div class="text-danger">
                                <small><?php echo $errors['contact_number'] ?? '' ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">

                            <select name="gender" id="gender" class="form-control
                            <?php
                            if (!empty(($gender))) {
                                echo $errors['gender'] ? 'is-invalid' : 'is-valid';
                            } else {
                                if ($errors['gender']) {
                                    echo 'is-invalid';
                                }
                            }
                            ?>
                            " value="<?php echo $gender ?>">
                                <option value="null">Select Gender</option>
                                <option value="male" <?php echo ($gender === 'male') ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?php echo ($gender === 'female') ? 'selected' : '' ?>>Female</option>
                            </select>
                            <div class="text-danger">
                                <small><?php echo $errors['gender'] ?? '' ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!--email-->
                <div class="form-group mt-2">
                    <input type="text" name="email" id="email" class="form-control
                        <?php
                        if (!empty(($email))) {
                            echo $errors['email'] ? 'is-invalid' : 'is-valid';
                        } else {
                            if ($errors['email']) {
                                echo 'is-invalid';
                            }
                        }
                        ?>
                    " placeholder="Enter email*" value="<?php echo $email ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['email'] ?? '' ?></small>
                    </div>
                </div>
                <!--position-->
                <div class="form-group">
                    <select name="position_id" id="position_id" class="form-control">
                        <?php foreach ($positions as $position) : ?>
                            <option value="<?php echo $position->id ?>"><?php echo $position->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <!--password1-->
                <div class="form-group mt-2">
                    <input type="password" name="password1" id="password1" class="form-control
                        <?php
                        if (!empty(($password1))) {
                            echo $errors['password1'] ? 'is-invalid' : 'is-valid';
                        } else {
                            if ($errors['password1']) {
                                echo 'is-invalid';
                            }
                        }
                        ?>
                    " placeholder="Enter password*" value="<?php echo $password1 ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['password1'] ?? '' ?></small>
                    </div>
                </div>
                <!--password2-->
                <div class="form-group mt-2">
                    <input type="password" name="password2" id="password2" class="form-control
                        <?php
                        if (!empty(($password2))) {
                            echo $errors['password2'] ? 'is-invalid' : 'is-valid';
                        } else {
                            if ($errors['password2']) {
                                echo 'is-invalid';
                            }
                        }
                        ?>
                    " placeholder="Re-enter password*" value="<?php echo $password2 ?>">
                    <div class="text-danger">
                        <small><?php echo $errors['password2'] ?? '' ?></small>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-end align-items-center mt-2">
                    <a href="admin_users.php" class="btn btn-secondary mr-2">Cancel</a>
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