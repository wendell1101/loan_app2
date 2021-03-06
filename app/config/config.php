<?php

// constants
define('BASE_URL', 'http://localhost/loan_app/');
define('TITLE', 'FEA');
define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('CURRENT_URL', SERVER_NAME . REQUEST_URI);
define('EMAIL', 'xsuperdummy@gmail.com');
// define('EMAIL', 'xzuperdummy@gmail.com');
define('PASS', 'q1rr560h');



// Setup database Connection
class Connection
{
    public $conn;
    public function __construct()
    {
        try {
            // Set DSN
            $dsn = 'mysql:host=' . HOST . ';dbname=' . DB_NAME;
            // Create a PDO instance
            $this->conn = new PDO($dsn, USERNAME, PASSWORD);

            // Set default fetch attribute to object
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // For limits in query

        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->conn;
    }
}


$dsn = 'mysql:host=' . HOST . ';dbname=' . DB_NAME;

try {
    $conn = new PDO($dsn, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch object
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // for LIMITS

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
