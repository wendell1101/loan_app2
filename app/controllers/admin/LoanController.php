<?php
class AdminLoan extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['loan_number', 'mmebership_number', 'amount', 'term', 'department_id', 'loan_type_id'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM loans";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
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

    // get specific interest
    public function getInterest($loan_type_id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=:loan_type_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['loan_type_id' => $loan_type_id]);
        return $stmt->fetch();
    }
    private function moveTwoDecimalToTheLeft($num)
    {
        return $num * .01;
    }
    public function getLoan($id)
    {
        $sql = "SELECT * FROM loans WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function getLoanTypeName($id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $type = $stmt->fetch();
        return  $type->name;
    }
    public function getTotalAmount($loan_id)
    {
        $loan = $this->getLoan($loan_id);
        $loan_type = $this->getInterest($loan->loan_type_id);

        $amount = $loan->amount;
        $interest = $loan_type->interest;
        $percent = $amount *  $this->moveTwoDecimalToTheLeft($interest);
        $total_amount = $amount += $percent * $loan->term;
        return $total_amount;
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

            return $this->errors;
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
        $sql = "DELETE FROM loans WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A loan has been deleted');
            redirect('loans.php');
        } else {
            message('danger', 'A loan cannot be deleted because of associated data');
            redirect('loans.php');
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
        $this->updateLoan();
    }
    private function updateLoan()
    {
        $id = $this->data['id'];
        $status = $this->data['status'];


        $sql = "UPDATE loans SET status=:status WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $updated = $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);

        if ($updated) {
            message('success', 'A loan has been updated');
            redirect('loans.php');
        } else {
            echo 'error occured';
        }
    }
}
