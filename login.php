<?php
require_once 'core.php';
require_once 'app/middlewares/GuessMiddleware.php';
$email = $password1 = '';
if (isset($_POST['login'])) {
    // instantiate user validator
    $user = new UserController($_POST);
    $errors = $user->validateLogin();
    //get the data
    $data = $user->getData();
    $email = sanitize($data['email']);
    $password1 = sanitize($data['password1']);
}

?>

<!DOCTYPE html>
<html lang="en">

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
        <div class="row">
            <div class="col-md-5 mx-auto shadow  p-3 rounded register-form">
                <h2 class="text-center text-title mb-2">Login Here</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <?php include 'app/includes/message.php' ?>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control
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
                    if (!empty(($email))) {
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
                        <span class="text-muted">Not yet a user? <a href="register.php">Register Here</a></span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="login" class="btn btn-success btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>