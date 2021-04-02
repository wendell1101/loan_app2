<?php
class AdminFixedDeposit extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['amount'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $userId = $_SESSION['id'];
        $sql = "SELECT * FROM fixed_deposits";
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
    public function getUsers()
    {
        $sql = "SELECT * FROM users where active=1 AND paid_membership=1";
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
            $this->validatePayer();
            return $this->errors;
        }
    }
    private function validateAmount()
    {
        if (empty($this->data['amount'])) {
            $this->addError('amount', 'Amount to deposit should not be empty');
        }
    }
    private function validatePayer()
    {
        if (empty($this->data['payment_by'])) {
            $this->addError('payment_by', 'Fullname of payer should not be empty');
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
            $rand1 = rand(1, 10);
            $rand2 = rand(1, 45);
            $rand3 = rand(1, 100);
            $rand4 = rand(1, 99);
            $reference_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
            $amount = $this->data['amount'];
            $payment_by = $this->data['payment_by'];
            $userId = $this->data['user_id'];
            $sql = "INSERT INTO fixed_deposits (reference_number, payment_by, amount, user_id)
            VALUES(:reference_number, :payment_by, :amount, :id)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute([
                'reference_number' => $reference_number,
                'payment_by' => $payment_by,
                'amount' => $amount,
                'id' => $userId,
            ]);
            if ($run) {
                message('success', 'A new deposit has been created');
                redirect('fixed_deposits.php');
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
