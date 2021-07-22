<?php
class Loan extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['loan_type_id', 'amount', 'term', 'department', 'total_amount'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $userId = $_SESSION['id'];
        $sql = "SELECT * FROM loans  WHERE user_id=$userId AND approved_by_c1=1 AND approved_by_c2=1";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function checkifUserIsAllowedToLoan()
    {
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM fixed_deposits WHERE user_id=$id";
        $stmt = $this->conn->query($sql);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
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
    public function getUsersLoan($user_id)
    {
        $sql = "SELECT * FROM loans WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
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
    public function getUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
    public function getOtherUsers()
    {
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE id!=$id AND position_id=2 AND active=1 AND paid_membership=1 AND is_comaker=0";
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

            // $this->validateLoanType();
            // $this->validateAmount();
            $this->validateTerm();
            // $this->validateTotalAmount();

            return $this->errors;
        }
    }

    private function validateLoanType()
    {
        if ($this->data['loan_type_id'] === 'null') {
            $this->addError('loan_type_id', 'You must choose a loan type');
        }
    }

    // validate loan amount
    private function validateAmount()
    {
        // check if empty
        $val = $this->data['amount'];
        if (empty($val)) {
            $this->addError('amount', 'Loan amount must not be empty');
        } else {
            if (!preg_match('(\d+\.\d{1,2})', $this->data['amount'])) {
                $this->addError('amount', 'Loan amount rate must be a number with 2 decimal places');
            }
        }
    }
    // validate loan term
    private function validateTerm()
    {
        if ($this->data['term'] === 'null') {
            $this->addError('term', 'You must choose a loan term');
        } elseif (($this->data['loan_type_id'] === 3) && ($this->data['term'] !== 5)) {
            $this->addError('term', 'You must select 5 months as fixed term for character loan');
        }
    }
    // validate department

    // validate total amount
    private function validateTotalAmount()
    {
        // check if empty
        $val = $this->data['total_amount'];
        if (empty($val)) {
            $this->addError('total_amount', 'Total amount must not be empty');
        } else {
            if (!preg_match('(\d+\.\d{1,2})', $this->data['total_amount'])) {
                $this->addError('amount', 'Loan total amount rate must be a number with 2 decimal places');
            }
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

            $loan = $this->getInterest($this->data['loan_type_id']);
            $amount = $this->data['amount'];
            $interest = $loan->interest;
            $percent = $this->data['amount'] *  $this->moveTwoDecimalToTheLeft($interest);
            $total_amount = $amount += $percent * $this->data['term'];
            $_SESSION['active_loan'] = $this->data;
            $_SESSION['total_amount'] = $total_amount;
            $_SESSION['interest_amount_per_month'] = $percent;
            $_SESSION['comakers'] = $this->data['comaker_id'];

            redirect("finalize_loan.php");
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
    public function getLoanByType($name)
    {
        $sql = "SELECT * FROM loan_types WHERE name=:name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        $loan =  $stmt->fetch();
        return $loan;
    }

    // check if current user has fixed_deposits
    public function checkIfHasFixedDeposit($id)
    {
        $sql = "SELECT * FROM fixed_deposits WHERE user_id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $deposits = $stmt->fetchAll();
        $total = 0;
        foreach ($deposits as $deposit) {
            $total += $deposit->amount;
        }
        return $total > 0;
    }
    public function getLoanableAmount($id)
    {
        $sql = "SELECT * FROM fixed_deposits WHERE user_id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $deposits = $stmt->fetchAll();
        $total = 0;
        foreach ($deposits as $deposit) {
            $total += $deposit->amount;
        }
        $activeUser = $this->getUser($_SESSION['id']);
        $loanable_amount = 0;
        if ($activeUser->employment_status == 'regular') {
            $loanable_amount = $total * 3;
        } elseif ($activeUser->employment_status == 'job_order') {
            $loanable_amount = $total * 2;
        }

        return $loanable_amount;
    }

    public function getComaker1()
    {
        $sql = "SELECT * FROM users WHERE position_id=8";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch();
    }
    public function getComaker2()
    {
        $sql = "SELECT * FROM users WHERE position_id=9";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch();
    }

    public function saveLoan()
    {

        $activeLoan = $_SESSION['active_loan'];
        $id = $_SESSION['id'];
        $current_user = $this->getUser($id);
        $department_id = $current_user->department_id;

        // transaction_id
        $transaction_id = bin2hex(random_bytes(7));
        //LOAN-2021-4
        $loan_number = "LOAN" . '-' . date("Y") . '-' . $id;
        //MEM-2021-8
        $membership_number = "MEM" . '-' . date("Y") . '-' . $id;
        $status = "pending";
        $comakers = $_SESSION['comakers'];
        $term = $activeLoan['term'];
        $amount = $activeLoan['amount'];
        $amount_per_month = $amount / $term;
        $amount_per_kinsenas = $amount_per_month / 2;
        $interest_amount = $_SESSION['interest_amount_per_month'] * $activeLoan['term'];

        $interest_amount_per_month = $_SESSION['interest_amount_per_month'];
        $interest_amount_per_kinsenas = $interest_amount_per_month / 2;



        $total_amount = $_SESSION['total_amount'];

        if ($comakers) {
            $sql = "INSERT INTO loans (transaction_id, loan_number, membership_number, amount,
            amount_per_kinsenas, amount_per_month, interest_amount, interest_amount_per_kinsenas,
            interest_amount_per_month, total_amount, term, status,
            loan_type_id, user_id, department_id, comaker1_id, comaker2_id) VALUES(:transaction_id, :loan_number, :membership_number, :amount,
            :amount_per_kinsenas, :amount_per_month, :interest_amount, :interest_amount_per_kinsenas,
            :interest_amount_per_month, :total_amount, :term, :status,
            :loan_type_id, :user_id, :department_id, :comaker1_id, :comaker2_id)";
            $stmt = $this->conn->prepare($sql);
            $saved = $stmt->execute([
                'transaction_id' => $transaction_id,
                'loan_number' => $loan_number,
                'membership_number' => $membership_number,
                'amount' => $amount,
                'amount_per_kinsenas' => $amount_per_kinsenas,
                'amount_per_month' => $amount_per_month,
                'interest_amount' => $interest_amount,
                'interest_amount_per_kinsenas' => $interest_amount_per_kinsenas,
                'interest_amount_per_month' => $interest_amount_per_month,
                'total_amount' => $total_amount,
                'term' => $activeLoan['term'],
                'status' => $status,
                'loan_type_id' => $activeLoan['loan_type_id'],
                'user_id' => $id,
                'department_id' => $department_id,
                'comaker1_id' => $comakers[0],
                'comaker2_id' => $comakers[1],
            ]);



            if ($saved) {
                $lastId = $this->conn->lastInsertId();
                //update user is_comaker to true
                $sql = "UPDATE users SET is_comaker=1 WHERE id=$comakers[0]";
                $run1 = $this->conn->query($sql);
                $sql = "UPDATE users SET is_comaker=1 WHERE id=$comakers[1]";
                $run2 = $this->conn->query($sql);
                redirect("loan_detail.php?id=$lastId");
            }
        }



        // if ($run) {
        //     $lastId = $this->conn->lastInsertId();
        //     redirect("loan_detail.php?id=$lastId");
        // } else {
        //     echo 'There was an error';
        // }
    }

    // delete category
    public function delete($id)
    {
        $sql = "DELETE FROM loan_types WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A loan type has been deleted');
            redirect('types.php');
        } else {
            message('danger', 'A loan type cannot be deleted because of associated loans');
            redirect('types.php');
        }
    }
    // get single category
    public function getType($id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $type = $stmt->fetch();
        return $type;
    }
    public function getComakers($loan_id)
    {
        $activeLoan = $this->getLoan($loan_id);
        $comaker1 = $activeLoan->comaker1_id;
        $comaker2 = $activeLoan->comaker2_id;
        $sql = "SELECT * from users WHERE id=$comaker1 OR id=$comaker2 LIMIT 2";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }


    //update category
    public function update($data)
    {
        $this->data = $data;
        $this->validate();
        $this->updateType();
    }
    private function updateType()
    {
        $name = $this->data['name'];
        $interest = $this->data['interest'];
        $id = $this->data['id'];
        if (!array_filter($this->errors)) {
            $sql = "UPDATE loan_types SET name=:name, interest=:interest WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $updated = $stmt->execute(['name' => $name, 'interest' => $interest, 'id' => $id]);
            if ($updated) {
                message('success', 'A loan type has been updated');
                redirect('types.php');
            }
        }
    }
}
