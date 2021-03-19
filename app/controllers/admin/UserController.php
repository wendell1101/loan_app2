<?php
class AdminUser extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['firstname', 'lastname', 'gender', 'contact_number', 'email', 'password1', 'password2'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->query($sql);
        return $users = $stmt->fetchAll();
    }

    public function getData()
    {
        return $this->data;
    }
    public function getPosition($id)
    {
        $sql = "SELECT * FROM positions WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $position = $stmt->fetch();
        if ($position->name) {
            return $position->name;
        } else {
            return 'admin';
        }
    }
    public function create($data)
    {
        $this->data = $data;
        $this->validate();
        $this->checkIfHasError();
    }
    //Error handling
    // Validate category name
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
            return $this->errors;
        }
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
            // match any lowercase/uppercase letter, any digits from 0-9, atleast 3-20 characters
            if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $val)) {
                $this->addError('firstname', 'Firstname must be 3-20 characters and alphanumeric');
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
            // match any lowercase/uppercase letter, any digits from 0-9, atleast 3-20 characters
            if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $val)) {
                $this->addError('lastname', 'Lastname must be 3-20 characters and alphanumeric');
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

    //add error

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

    //Check if no more errors then insert data
    private function checkIfHasError()
    {
        if (!array_filter($this->errors)) {

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
                    'position_id' => $this->data['position_id'],
                ]);
                $lastId = $this->conn->lastInsertId();
                if ($run) {
                    message('success', 'A new user has been created');
                    redirect('admin_users.php');
                }
            }
        }
    }


    // delete category
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A user has been deleted');
            redirect('admin_users.php');
        } else {
            message('danger', 'A user cannot be deleted because of associated reservation');
            redirect('admin_users.php');
        }
    }

    //get positions
    public function getPositions()
    {
        $stmt = $this->conn->query('SELECT * FROM positions');
        return  $stmt->fetchAll();
    }
    // get single category
    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user;
    }

    //update category
    public function update($data)
    {
        $this->data = $data;
        $this->validate();
        $this->updateUser();
    }
    private function updateUser()
    {
        $id = $this->data['id'];
        $active = $this->data['active'];
        $position_id = $this->data['position_id'];

        $sql = "UPDATE users SET active=$active, position_id=$position_id WHERE id=$id";
        $updated = $this->conn->query($sql);
        if ($updated) {
            message('success', 'A user has been updated');
            redirect('admin_users.php');
        } else {
            echo 'error occured';
        }
    }
}
