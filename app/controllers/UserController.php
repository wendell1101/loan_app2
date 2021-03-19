<?php

// extends Connection class
class UserController extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['firstname', 'lastname', 'contact_number', 'gender', 'email', 'password1', 'password2'];
    private $loginFields = ['email', 'password'];

    public function __construct($data)
    {
        parent::__construct();
        $this->data = $data;
    }
    // Validate register
    public function validate()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error("$field must not be empty");
                return;
            }

            $this->validateFirstname();
            $this->validateLastname();
            $this->validateEmail();
            $this->validatePassword1();
            $this->validatePassword2();
            $this->validateGender();
            $this->validateContactNumber();
            $this->checkIfHasError();
            return $this->errors;
        }
    }

    // validate login
    public function validateLogin()
    {
        $this->validateEmail();
        $this->validateLoginPassword();
        $this->checkIfRegistered();
        return $this->errors;
    }

    //get data
    public function getData()
    {
        return $this->data;
    }
    // validate firstname
    private function validateFirstname()
    {
        //trim whitespace
        $val = trim($this->data['firstname']);
        // check if empty
        if (empty($val)) {
            $this->addError('firstname', 'Firstname must not be empty');
        } else {
            if (!preg_match("/^[a-z ,.'-]+$/i", $val)) {
                $this->addError('firstname', 'Firstname must be alphanumeric characters only');
            }
        }
    }
    //validate lastname
    private function validateLastname()
    {
        //trim whitespace
        $val = trim($this->data['lastname']);
        // check if empty
        if (empty($val)) {
            $this->addError('lastname', 'Lastname must not be empty');
        } else {
            if (!preg_match("/^[a-z ,.'-]+$/i", $val)) {
                $this->addError('lastname', 'Lastname must be must be alphanumeric characters only');
            }
        }
    }
    //validate email
    private function validateEmail()
    {
        //trim white space
        $val = trim($this->data['email']);
        // check if empty
        if (empty($val)) {
            $this->addError('email', 'Email must not be empty');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email must be a valid email');
            }
        }
    }

    //Validate password1
    private function validatePassword1()
    {
        // trim white space
        $val = trim($this->data['password1']);
        // check if empty
        if (empty($val)) {
            $this->addError('password1', 'Password must not be empty');
        } else {
            $password1 = trim($this->data['password1']);
            $password2 = trim($this->data['password2']);
            if (!empty($password2)) {
                if ($password1 !== $password2) {
                    $this->addError('password1', 'Passwords do not match. Please try again');
                }
            }
        }
    }

    //Validate password2
    private function validatePassword2()
    {
        // trim white space
        $val = trim($this->data['password2']);
        // check if empty
        if (empty($val)) {
            $this->addError('password2', 'Confirm-password must not be empty');
        } else {
            $password1 = trim($this->data['password1']);
            $password2 = trim($this->data['password2']);
            if ($password1 !== $password2) {
                $this->addError('password2', 'Passwords do not match. Please try again');
            }
        }
    }

    //Validate gender
    private function validateGender()
    {
        // trim white space
        $val = trim($this->data['gender']);
        // check if empty
        if ($val == "null") {
            $this->addError('gender', 'Gender is required');
        }
    }

    //validate contact number
    private function validateContactNumber()
    {
        // check if empty
        $val = $this->data['contact_number'];
        if (empty($val)) {
            $this->addError('contact_number', 'Contact number must be specified');
        } else if (!preg_match('((^(\+)(\d){12}$)|(^\d{11}$))', $val)) {
            $this->addError('contact_number', 'Contact number must be a valid 11-digit contact number');
        }
    }

    // validate login password
    private function validateLoginPassword()
    {
        $val = trim($this->data['password1']);
        if (empty($val)) {
            $this->addError('password1', 'Password must not be empty');
        }
    }

    // add Error
    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }
    //Check if no more errors
    private function checkIfHasError()
    {
        if (!array_filter($this->errors)) {
            $this->register();
        }
    }

    // register
    private function register()
    {
        // check if email already exists
        $sql = "SELECT * FROM users WHERE email=:email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $this->data['email']]);
        $user = $stmt->fetch();
        // if email already registered
        if ($stmt->rowCount()) {
            $this->errors['email'] = 'Email already exists. Please try a new one';
        } else {

            // register user using named params
            $sql = "INSERT INTO users (firstname, lastname, gender, contact_number,email, password, position_id)
            VALUES (:firstname, :lastname, :gender, :contact_number, :email, :password, :position_id)";
            $stmt = $this->conn->prepare($sql);
            // hash the password before saving to the database
            $password = md5($this->data['password1']);

            // bind param and execute
            $run = $stmt->execute([
                'firstname' => $this->data['firstname'],
                'lastname' => $this->data['lastname'],
                'gender' => $this->data['gender'],
                'contact_number' => $this->data['contact_number'],
                'email' => $this->data['email'],
                'password' => $password,
                'position_id' => 2,
            ]);
            $lastId = $this->conn->lastInsertId();
            if ($run) {
                message('success', 'Your account has been created successfully.
                 Please wait for account approval from membership committee to login.');
                redirect('login.php');
            }
        }
    }


    // Check if user is registered for login
    private function checkIfRegistered()
    {
        if (!array_filter($this->errors)) {
            $this->login();
        }
    }

    //Login
    private function login()
    {
        // hash the password first
        $password = md5($this->data['password1']);
        $email = $this->data['email'];
        // check if valid credentials
        $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
        $stmt = $this->conn->prepare($sql);
        // bind and execute
        $stmt->execute(['email' => $email, 'password' => $password]);
        $user = $stmt->fetch();
        if (!$stmt->rowCount() > 0) {
            $this->addError('email', 'Invalid Credentials. An email or password is incorrect. Please try again');
            $this->addError('password1', 'Invalid Credentials. An email or password is incorrect. Please try again');
        } else {
            if ($user->active === 0) {
                // $this->addError('email', 'Account is valid but not yet active');
                message('danger', 'Account is valid but not yet active');
                // redirect('login.php');
            } else {
                $_SESSION['id'] = $user->id;
                if ($user->position_id != 2) {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('index.php');
                }
            }
        }
    }
}
