<?php
require_once 'app/database/database.php';
require_once 'app/config/config.php';
require_once 'app/models/User.php';
require_once 'app/helpers/kernel.php';
require_once 'app/controllers/Controllers.php';

$user = new User();

session_start();
