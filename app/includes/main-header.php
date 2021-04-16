<?php
require_once 'core.php';
if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    header('Location: login.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE ?></title>
    <!--icon -->
    <link rel="icon" href="#" type="image/gif" sizes="16x16">
    <!--fontawesome5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

    <!--bootstrap-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/main.css?id=<?php echo time() ?>">
</head>

<body>
    <nav class="main-nav" id="main-nav">
        <a href="index.php" class="logo"><img src="assets/img/logo_light.png" alt="logo"></a>
        <ul>

            <li class="">
                <a href="#">Home</a>
            </li>
            <li>
                <a href="about.php">About</a>
            </li>

            <li>
                <a href="contact.php">Contact</a>
            </li>
            <li>
                <a href="loans.php">Loans</a>
            </li>
            <li>
                <a href="comakers.php">Comakers</a>
            </li>

            <?php if (User::Auth()) : ?>
                <li>
                    <a href="profile.php?id=<?php echo $_SESSION['id'] ?>">Profile</a>
                </li>
                <?php if ($user->isAdmin()) : ?>
                    <li>
                        <a href="admin/dashboard.php">Admin</a>
                    </li>
                <?php endif; ?>
                <li>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <button type="submit" class="text-white" name="logout" style="border:none; background:none; width:100%">Logout</button>
                    </form>
                </li>
            <?php else : ?>
                <li>
                    <a href="login.php">Login</a>
                </li>
                <li>
                    <a href="register.php">Register</a>
                </li>
            <?php endif ?>
        </ul>

        <span class="hamburger"><i class="fas fa-bars"></i></span>
    </nav>
    <ul class="side-nav">

        <a href="index.php" class="side-nav-logo"><img src="assets/img/logo_light.png" alt="logo"></a>
        <li class="">
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="about.php">About</a>
        </li>
        <li>
            <a href="contact.php">Contact</a>
        </li>
        <li>
            <a href="loans.php">Loans</a>
        </li>

        <?php if (User::Auth()) : ?>

            <li>
                <a href="profile.php?id=<?php echo $_SESSION['id'] ?>">Profile</a>
            </li>
            <li>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <button type="submit" class="text-white" name="logout" style="border:none; background:none; width:100%">Logout</button>
                </form>
            </li>
        <?php else : ?>
            <li>
                <a href="login.php">Login</a>
            </li>
            <li>
                <a href="register.php">Register</a>
            </li>
        <?php endif ?>
    </ul>

    <!--Back to top button-->
    <a id="button"><i class="fas fa-arrow-up arrow"></i></a>
    <!-- <div class="se-pre-con"></div> -->