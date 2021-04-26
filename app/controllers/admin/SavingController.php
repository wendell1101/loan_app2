<?php
class Savings extends Connection
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
        $sql = "SELECT * FROM savings WHERE amount !=0";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFixedDeposits()
    {

        $sql = "SELECT * FROM fixed_deposits";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function savings()
    {
        $userId = $_SESSION['id'];
        $sql = "SELECT * FROM savings WHERE amount>0";
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

    public function getSaving($id)
    {
        $sql = 'SELECT * FROM savings WHERE id=:id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $this->data = $data;
        $this->validate();
        $this->checkIfHasError();
    }
    public function withdraw($data)
    {
        $this->data = $data;
        $this->validate();
        $this->withdrawSaving();
    }

    public function withdrawSaving()
    {
        // dump($this->data);
        if (!array_filter($this->errors)) {
            $amount = $this->data['amount'];
            // $payment_by = $this->data['payment_by'];
            // $user_id = $this->data['user_id'];
            $saving_id = $this->data['saving_id'];
            $saving = $this->getSaving($saving_id);
            $payment_by = $saving->payment_by;
            if ($saving->amount < $amount) {
                $this->addError('amount', 'Amount should not be greater than' . $saving->amount);
            }
            $user_id = $saving->user_id;
            $new_amount = $saving->amount - $amount;
            $payment_saving_withdraw = $amount;
            if (!array_filter($this->errors)) {
                // insert new payment

                $rand1 = rand(1, 10);
                $rand2 = rand(1, 45);
                $rand3 = rand(1, 100);
                $rand4 = rand(1, 99);
                $reference_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
                // $sql = "INSERT INTO payments (reference_number, payment_by, payment_saving_withdraw, user_id)
                // VALUES(:reference_number, :payment_by, :payment_saving_withdraw, :user_id)";
                // $stmt = $this->conn->prepare($sql);
                // $saved = $stmt->execute([
                //     'reference_number' => $reference_number,
                //     'payment_by' => $payment_by,
                //     'payment_saving_withdraw' => $payment_saving_withdraw,
                //     'user_id' => $user_id,
                // ]);
                // if ($saved) {
                //update saving
                $sql = "UPDATE savings SET amount=$new_amount, withdraw_amount=$amount WHERE user_id=$user_id AND id=$saving_id";
                $run = $this->conn->query($sql);
                if ($run) {
                    message('success', 'Withdrawal of saving done. User saving Updated');
                    redirect('savings.php');
                }
                // }
            }
        }
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
            // $this->validatePayer();
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
            $sql = "INSERT INTO savings (reference_number, payment_by, amount, user_id)
            VALUES(:reference_number, :payment_by, :amount, :id)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute([
                'reference_number' => $reference_number,
                'payment_by' => $payment_by,
                'amount' => $amount,
                'id' => $userId,
            ]);
            if ($run) {
                message('success', 'A new deposit to savings has been created');
                redirect('savings.php');
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
