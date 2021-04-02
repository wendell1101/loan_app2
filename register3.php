<?php
require_once 'core.php';
require_once 'app/middlewares/GuessMiddleware.php';

$firstname = $lastname = $email = $password1 = $password2 = $gender = $contact_number = '';
if (isset($_POST['register'])) {
    // instantiate user validator
    $user = new UserController($_POST);
    $errors = $user->validate();

    //get the data
    $data = $user->getData();
    $firstname = sanitize($data['firstname']);
    $lastname = sanitize($data['lastname']);
    $email = sanitize($data['email']);
    $gender = sanitize($data['gender']);
    $contact_number = sanitize($data['contact_number']);
    $password1 = sanitize($data['password1']);
    $password2 = sanitize($data['password2']);
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

<body class="auth-body">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto shadow mt-5 bg-white p-3 rounded register-form register">
                <h2 class="text-center text-title mb-2">Register Here</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="firstname">Firstname</label>
                                <input type="text" name="firstname" id="firstname" class="form-control
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

                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="lastname">Lastname</label>
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
                            " value="<?php echo $lastname ?>">
                                <div class="text-danger">
                                    <small><?php echo $errors['lastname'] ?? '' ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control
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
                                <label for="gender">Gender</label>
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
                    <div class="form-group">
                        <label for="email">Email</label>
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
                    " value="<?php echo $email ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['email'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password1">Password</label>
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
                    " value="<?php echo $password1 ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['password1'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirm-Password</label>
                        <input type="password" name="password2" id="password1" class="form-control
                    <?php
                    if (!empty(($password2))) {
                        echo $errors['password2'] ? 'is-invalid' : 'is-valid';
                    } else {
                        if ($errors['password2']) {
                            echo 'is-invalid';
                        }
                    }
                    ?>
                    " value="<?php echo $password2 ?>">
                        <div class="text-danger">
                            <small><?php echo $errors['password2'] ?? '' ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="text-muted">Already a user? <a href="login.php">Login Here</a></span>
                    </div>
                    <div class="d-grid">

                        <button type="submit" name="register" class="btn btn-info btn-block">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>