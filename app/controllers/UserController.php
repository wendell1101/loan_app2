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
    //get all positions

    public function getPositions()
    {
        $stmt = $this->conn->query('SELECT * FROM positions');
        return  $stmt->fetchAll();
    }
    public function getPositions2()
    {
        $stmt = $this->conn->query('SELECT * FROM positions WHERE id!=8');
        return  $stmt->fetchAll();
    }

    // get all departments

    public function getDepartments()
    {
        $sql = "SELECT * FROM departments";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
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
            // hash the password before saving to the database
            $rand1 = rand(1, 10);
            $rand2 = rand(1, 45);
            $rand3 = rand(1, 100);
            $rand4 = rand(1, 99);
            $account_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
            $firstname = $this->data['firstname'];
            $middlename = $this->data['middlename'];
            $lastname = $this->data['lastname'];
            $home_address = $this->data['home_address'];
            $permanent_address = $this->data['permanent_address'];
            $gender = $this->data['gender'];
            $birth_date = $this->data['birth_date'];
            $contact_number = $this->data['contact_number'];
            $email = $this->data['email'];
            $password = md5($this->data['password1']);
            $position_id = $this->data['position_id'];
            $sg = $this->data['sg'];
            $employment_status = $this->data['employment_status'];
            $department_id = $this->data['department_id'];
            $name_of_spouse = $this->data['name_of_spouse'];
            $fathers_name = $this->data['fathers_name'];
            $mothers_maiden_name = $this->data['mothers_maiden_name'];
            $paid_membership = 0;
            $active = 0;

            $sql = "INSERT INTO users (account_number, firstname, middlename, lastname, home_address, permanent_address, gender, birth_date,
            contact_number,email, password, position_id, sg, employment_status, department_id, name_of_spouse, fathers_name,
            mothers_maiden_name, paid_membership, active)
            VALUES (:account_number, :firstname, :middlename, :lastname, :home_address, :permanent_address, :gender, :birth_date,
            :contact_number,:email, :password, :position_id, :sg, :employment_status, :department_id, :name_of_spouse, :fathers_name,
            :mothers_maiden_name, :paid_membership, :active)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute([
                'account_number' => $account_number,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'home_address' => $home_address,
                'permanent_address' => $permanent_address,
                'gender' => $gender,
                'birth_date' => $birth_date,
                'contact_number' => $contact_number,
                'email' => $email,
                'password' => $password,
                'position_id' => $position_id,
                'sg' => $sg,
                'employment_status' => $employment_status,
                'department_id' => $department_id,
                'name_of_spouse' => $name_of_spouse,
                'fathers_name' => $fathers_name,
                'mothers_maiden_name' => $mothers_maiden_name,
                'mothers_maiden_name' => $mothers_maiden_name,
                'paid_membership' => $paid_membership,
                'active' => $active,
            ]);

            $lastId = $this->conn->lastInsertId();
            if ($run) {
                message('success', 'Your account has been created successfully.
                 Please wait for account approval from membership committee to login.');
                redirect('login.php');
            } else {
                echo 'error occured';
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
            if ($user->active === 0 || $user->paid_membership === 0) {
                // $this->addError('email', 'Account is valid but not yet active');
                message('danger', 'Account is valid but not yet active');
                // redirect('login.php');
            } else {
                $_SESSION['id'] = $user->id;
                $_SESSION['position_id'] = $user->position_id;
                if ($user->position_id != 2) {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('index.php');
                }
            }
        }
    }
}
