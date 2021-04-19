<?php
session_start();
require_once 'app/database/database.php';
require_once 'app/config/config.php';
require_once 'app/models/User.php';
require_once 'app/helpers/kernel.php';
require_once 'app/controllers/Controllers.php';

$user = new User();


function dump($value) // to be deleted soon

{
    echo "<pre>", print_r($value, true), "</pre>";
    die();
}

