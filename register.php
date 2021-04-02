<?php
require_once 'core.php';
require_once 'app/middlewares/GuessMiddleware.php';

// instantiate user validator
$user = new UserController($_POST);
$positions = $user->getPositions2();
$departments = $user->getDepartments();

$firstname = $middlename = $lastname = $home_address = $permanent_address = '';
$gender = $birth_date = $contact_number = $email = $password1 = $password2 = '';
$position_id = $sg = $employment_status = $department_id = $name_of_spouse = '';
$fathers_name = $mothers_maiden_name = '';
if (isset($_POST['register'])) {

    $errors = $user->validate();
    //get the data
    $data = $user->getData();
    $firstname = sanitize($data['firstname']);
    $middlename = sanitize($data['middlename']);
    $lastname = sanitize($data['lastname']);
    $home_address = sanitize($data['home_address']);
    $permanent_address = sanitize($data['permanent_address']);
    $gender = sanitize($data['gender']);
    $birth_date = sanitize($data['birth_date']);
    $contact_number = sanitize($data['contact_number']);
    $email = sanitize($data['email']);
    $password1 = sanitize($data['password1']);
    $password2 = sanitize($data['password2']);
    $position_id = sanitize($data['position_id']);
    $sg = sanitize($data['sg']);
    $employment_status = sanitize($data['employment_status']);
    $department_id = sanitize($data['department_id']);
    $name_of_spouse = sanitize($data['name_of_spouse']);
    $fathers_name = sanitize($data['fathers_name']);
    $mothers_maiden_name = sanitize($data['mothers_maiden_name']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body style="background: #eee">
    <div class="container">
        <div class="row p-4">
            <div class="col-md-10 mx-auto">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="p-3 shadow">
                    <h1 class="text-center text-success mb-3">Create Your Account</h1>
                    <h4>Personal Information</h4>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">

                                <select name="gender" id="gender" required class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" onfocus="(this.type='date')" name=" birth_date" id="birth_date" placeholder="Birth date" required class="form-control" value="<?php echo $birth_date ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="firstname" id="firstname" required placeholder="Firstname" class="form-control" value="<?php echo $firstname ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="middlename" id="middlename" required placeholder="Middlename" class="form-control" value="<?php echo $middlename ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="lastname" id="lastname" required placeholder="Lastname" class="form-control" value="<?php echo $lastname ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="home_address" id="home_address" required placeholder="Home Address" class="form-control" value="<?php echo $home_address ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="permanent_address" id="permanent_address" required placeholder="Permanent Address" class="form-control" value="<?php echo $permanent_address ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="number" name="contact_number" id="contact_number" required placeholder="Contact Number" class="form-control" value="<?php echo $contact_number ?>">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-5">Account Information</h4>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="email" id="email" placeholder="Email Address" class="form-control
                                <?php
                                if (!empty(($password1))) {
                                    echo $errors['email'] ? 'is-invalid' : 'is-valid';
                                } else {
                                    if ($errors['email']) {
                                        echo 'is-invalid';
                                    }
                                }
                                ?>
                                " value="<?php echo $email ?>">
                                <div class="text-danger">
                                    <small><?php echo $errors['email'] ?? '' ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="password" name="password1" id="password1" placeholder="Password" class="form-control
                                <?php
                                if (!empty(($password1))) {
                                    echo $errors['password1'] ? 'is-invalid' : 'is-valid';
                                } else {
                                    if ($errors['password1']) {
                                        echo 'is-invalid';
                                    }
                                }
                                ?>
                                " value="<?php echo $password1 ?>">
                                <div class="text-danger">
                                    <small><?php echo $errors['password1'] ?? '' ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="password" name="password2" id="password2" placeholder="Confirm-Password" class="form-control
                                <?php
                                if (!empty(($password1))) {
                                    echo $errors['password2'] ? 'is-invalid' : 'is-valid';
                                } else {
                                    if ($errors['password2']) {
                                        echo 'is-invalid';
                                    }
                                }
                                ?>
                                " value="<?php echo $password1 ?>">
                                <div class="text-danger">
                                    <small><?php echo $errors['password2'] ?? '' ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-5">Employment Information</h4>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <select name="position_id" id="position_id" class="form-control" required>
                                    <option value="">Select Position</option>
                                    <?php foreach ($positions as $position) : ?>
                                        <option value="<?php echo $position->id ?>" <?php echo ($position_id === $position->id) ? 'selected' : '' ?>><?php echo $position->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="number" name="sg" id="sg" placeholder="SG" required class="form-control" value="<?php echo $sg ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <select name="employment_status" id="employment_status" required class="form-control">
                                    <option value="">Select Employment Status</option>
                                    <option value="regular">Regular</option>
                                    <option value="job_order">Job Order</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <select name="department_id" id="department_id" required class="form-control">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $department) : ?>
                                        <option value="<?php echo $department->id ?>" <?php echo ($department_id === $department->id) ? 'selected' : '' ?>><?php echo $department->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-5">Other Information</h4>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="name_of_spouse" id="name_of_spouse" placeholder="Name of Spouse (optional)" class="form-control" value="<?php echo $name_of_spouse ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="fathers_name" id="fathers_name" required placeholder="Fathers Name" class="form-control" value="<?php echo $fathers_name ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="mothers_maiden_name" required id="mothers_maiden_name" placeholder="Mothers Maiden Name" class="form-control" value="<?php echo $mothers_maiden_name ?>">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <span class="text-muted">Already a user? <a href="login.php">Login Here</a></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="register" class="btn btn-success btn-lg">Register</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>