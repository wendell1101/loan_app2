<?php
class FixedDeposit extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['amount',];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $userId = $_SESSION['id'];
        $sql = "SELECT * FROM fixed_deposits  WHERE user_id=$userId";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLoanTypes()
    {
        $sql = "SELECT * FROM loan_types";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
    public function getLoanType($id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $type = $stmt->fetch();
        return  $type->name;
    }
    public function getLoan($id)
    {
        $sql = "SELECT * FROM loans WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function getPayments($id)
    {
        $sql = "SELECT * FROM payments WHERE loan_id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
    public function getDepartments()
    {
        $sql = "SELECT * FROM departments";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
    public function getComakers()
    {
        $sql = "SELECT * FROM users where position_id=8 OR position_id=9";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
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
            $this->validateAmount();
            return $this->errors;
        }
    }
    private function validateAmount()
    {
        if (empty($this->data['amount'])) {
            $this->addError('amount', 'Amount to deposit must be specify');
        }
    }




    //add error

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
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


    private function checkIfHasError()
    {
        if (!array_filter($this->errors)) {
            $amount = $this->data['amount'];
            $userId = $_SESSION['id'];
            $sql = "INSERT INTO fixed_deposits (amount, user_id) VALUES(:amount, :id)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute([
                'amount' => $amount,
                'id' => $userId,
            ]);
            if ($run) {
                echo 'deposited';
            }
        }
    }



    //get department
    public function getDepartment($id)
    {
        $sql = "SELECT * FROM departments WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }



    // get single tyoe
    public function getType($id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $type = $stmt->fetch();
        return $type;
    }
}
