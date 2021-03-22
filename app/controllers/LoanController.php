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
        $sql = "SELECT * FROM loans";
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

            $this->validateLoanType();
            // $this->validateAmount();
            $this->validateTerm();
            $this->validateDepartment();
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
        }
    }
    // validate department
    private function validateDepartment()
    {
        if ($this->data['department_id'] === 'null') {
            $this->addError('department_id', 'You must choose a department');
        }
    }
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

            redirect("finalize_loan.php");
        }
    }

    public function finalizeLoan()
    {

        // $sql = "INSERT INTO loans ()"
    }

    //get department
    public function getDepartment($id)
    {
        $sql = "SELECT * FROM departments WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // save loan
    public function saveLoan()
    {

        $activeLoan = $_SESSION['active_loan'];
        $id = $_SESSION['id'];
        //LOAN-2021-4
        $loan_number = "LOAN" . '-' . date("Y") . '-' . $id;
        //MEM-2021-8
        $membership_number = "MEM" . '-' . date("Y") . '-' . $id;
        $status = "pending";

        // total amount

        $amount = $activeLoan['amount'];

        $total_amount = $_SESSION['total_amount'];
        $sql = "INSERT INTO loans (loan_number, membership_number, amount, term, status, department_id,
        loan_type_id, total_amount, user_id) VALUES(:loan_number, :membership_number, :amount, :term, :status, :department_id,
        :loan_type_id, :total_amount, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $run = $stmt->execute([
            'loan_number' => $loan_number,
            'membership_number' => $membership_number,
            'amount' => $amount,
            'term' => $activeLoan['term'],
            'status' => $status,
            'department_id' => $activeLoan['department_id'],
            'loan_type_id' => $activeLoan['loan_type_id'],
            'total_amount' => $total_amount,
            'user_id' => $id,
        ]);
        if ($run) {
            $lastId = $this->conn->lastInsertId();
            redirect("loan_detail.php?id=$lastId");
        }
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
