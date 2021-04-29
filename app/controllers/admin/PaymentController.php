<?php
class Payment extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['loan_id', 'payment_by', 'payment_amount'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM payments ORDER BY id DESC";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
    public function getLoans()
    {
        $sql = "SELECT * FROM loans WHERE status='active'";
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

    //get savings
    public function getSavings()
    {
        $sql = "SELECT * FROM savings WHERE amount !=0";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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
    public function getPayment($id)
    {
        $sql = "SELECT * FROM payments WHERE id=:id";
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
    public function getSingleLoan($id)
    {
        $sql = "SELECT * FROM loans WHERE id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $type = $stmt->fetch();
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
            $this->validateLoan();
            // $this->validatePayerName();
            $this->validatePaymentAmount();
            return $this->errors;
        }
    }



    //add error

    private function validateLoan()
    {
        if ($this->data['loan_id'] == 'null') {
            $this->addError('loan_id', 'Loan must be specified');
        }
    }
    private function validatePayerName()
    {
        if (empty($this->data['payment_by'])) {
            $this->addError('payment_by', 'Payer name must be specified');
        }
    }
    private function validatePaymentAmount()
    {
        if (empty($this->data['payment_amount'])) {
            $this->addError('payment_amount', 'Payment amount must be specified');
        }
    }
    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

    //Check if no more errors then insert data
    private function checkIfHasError()
    {
        if (!array_filter($this->errors)) {
            $rand1 = rand(1, 10);
            $rand2 = rand(1, 45);
            $rand3 = rand(1, 100);
            $rand4 = rand(1, 99);
            $reference_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);

            $payment_amount = $this->data['payment_amount'];
            $loan_id = $this->data['loan_id'];
            $activeLoan = $this->getLoan($loan_id);
            $user_id = $activeLoan->user_id;

            $activeUser = $this->getUser($user_id);
            // dump($activeUser);
            $payment_by = $activeUser->firstname . ' ' . $activeUser->lastname;
            $activeLoan = $this->getLoan($loan_id);

            if ($payment_amount > $activeLoan->total_amount) {
                $this->addError('payment_amount', "Payment amount should not be greater than the loan balance of PHP $activeLoan->total_amount");
            }
            if (!array_filter($this->errors)) {
                $sql = "INSERT INTO payments (reference_number, payment_by, payment_amount, user_id, loan_id)
                VALUES(:reference_number, :payment_by, :payment_amount, :user_id, :loan_id)";
                $stmt = $this->conn->prepare($sql);

                $saved = $stmt->execute([
                    'reference_number' => $reference_number,
                    'payment_by' => $payment_by,
                    'payment_amount' => $payment_amount,
                    'user_id' => $user_id,
                    'loan_id' => $loan_id,
                ]);
                $payment = $stmt->fetch();


                if ($saved) {
                    $lastId = $this->conn->lastInsertId();

                    $activePayment = $this->getPayment($lastId);
                    $activeLoan = $this->getLoan($activePayment->loan_id);
                    $new_total_amount = $activeLoan->total_amount - $activePayment->payment_amount;


                    $sql = "UPDATE loans SET total_amount=:new_total_amount WHERE id=:id";
                    $stmt = $this->conn->prepare($sql);
                    $updated = $stmt->execute([
                        'new_total_amount' => $new_total_amount,
                        'id' => $activeLoan->id,
                    ]);
                    if ($updated) {
                        message('success', 'New payment has been created');
                        redirect('payments.php');
                    }
                }
            }
        }
    }

    public function createPayment($data)
    {
        $rand1 = rand(1, 10);
        $rand2 = rand(1, 45);
        $rand3 = rand(1, 100);
        $rand4 = rand(1, 99);
        $reference_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
        $payment_saving = $data['payment_saving'];
        $payment_fixed_deposit = $data['payment_fixed_deposit'];
        // $payment_by = $data['payment_by'];

        $user_id = $data['user_id'];
        $activeUser = $this->getUser($user_id);
        $payment_by = $activeUser->firstname . ' ' . $activeUser->lastname;


        $sql = "INSERT INTO payments (reference_number, payment_by, payment_saving, payment_fixed_deposit, user_id)
        VALUES(:reference_number, :payment_by, :payment_saving, :payment_fixed_deposit, :user_id)";
        $stmt = $this->conn->prepare($sql);

        $saved = $stmt->execute([
            'reference_number' => $reference_number,
            'payment_by' => $payment_by,
            'payment_saving' => $payment_saving,
            'payment_fixed_deposit' => $payment_fixed_deposit,
            'user_id' => $user_id,
        ]);
        if ($saved) {
            $lastId = $this->conn->lastInsertId();
            // create savings
            $activePayment = $this->getPayment($lastId);
            $reference_number = $activePayment->reference_number;
            $payment_by = $activePayment->payment_by;
            $payment_saving = $activePayment->payment_saving;
            $payment_fixed_deposit = $activePayment->payment_fixed_deposit;
            $user_id = $activePayment->user_id;
            $sql = "INSERT INTO savings (reference_number, payment_by, amount, user_id)
            VALUES(:reference_number, :payment_by, :amount, :id)";
            $stmt = $this->conn->prepare($sql);
            $run_savings = $stmt->execute([
                'reference_number' => $reference_number,
                'payment_by' => $payment_by,
                'amount' => $payment_saving,
                'id' => $user_id,
            ]);
            // create fixed deposit

            $sql = "INSERT INTO fixed_deposits (reference_number, payment_by, amount, user_id)
            VALUES(:reference_number, :payment_by, :amount, :id)";
            $stmt = $this->conn->prepare($sql);
            $run_deposits = $stmt->execute([
                'reference_number' => $reference_number,
                'payment_by' => $payment_by,
                'amount' => $payment_fixed_deposit,
                'id' => $user_id,
            ]);
            if ($run_savings && $run_deposits) {
                message('success', 'New payment has been created');
                redirect('payments.php');
            }
        }
        // if ($run) {
        //     message('success', 'New payment has been created');
        //     redirect('payments.php');
        // }
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

    public function getUsers()
    {
        $sql = "SELECT * FROM users where active=1 AND paid_membership=1";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
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

    //create penalty
    public function createPenalty($data)
    {
        $reason = $data['reason'];
        $amount = $data['amount'];
        $service_fee = $data['service_fee'];
        $loan_id = $data['loan_id'];
        $payment_id = $data['payment_id'];
        $sql = "INSERT INTO loan_penalties (reason, amount, service_fee, loan_id, payment_id)
        VALUES (:reason, :amount, :service_fee, :loan_id, :payment_id)";
        $stmt = $this->conn->prepare($sql);
        $run = $stmt->execute([
            'reason' => $reason,
            'amount' => $amount,
            'service_fee' => $service_fee,
            'loan_id' => $loan_id,
            'payment_id' => $payment_id,
        ]);


        if ($run) {
            //update payment has_penalty
            $sql = "UPDATE payments SET has_penalty=1 WHERE id=$payment_id";
            $updated = $this->conn->query($sql);
            if ($updated) {
                message('success', 'A penalty has been issued successfully');
                redirect('payments.php');
            }
        }
    }

    public function getPenalty($loan_id, $payment_id)
    {
        $sql = "SELECT * FROM loan_penalties WHERE loan_id=$loan_id AND payment_id=$payment_id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch();
    }
}
