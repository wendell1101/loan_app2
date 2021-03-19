<?php
// if(!$_SESSION['user']){
//     header('Location: login.php');
// }
if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    header('Location: login.php');
}
