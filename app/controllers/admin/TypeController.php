<?php
class Type extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['name', 'interest'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM loan_types";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $types = $stmt->fetchAll();
    }

    public function getData()
    {
        return $this->data;
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

            $this->validateTypeName();
            $this->validateInterestRate();


            return $this->errors;
        }
    }

    private function validateTypeName()
    {
        // check if empty
        $val = $this->data['name'];
        if (empty($val)) {
            $this->addError('name', 'Loan type name must not be empty');
        }
    }

    // validate loan interest rate
    private function validateInterestRate()
    {
        // check if empty
        $val = $this->data['interest'];
        if (empty($val)) {
            $this->addError('interest', 'Loan interest rate must not be empty');
        } else {
            if (!preg_match('(\d+\.\d{1,2})', $this->data['interest'])) {
                $this->addError('interest', 'Loan interest rate must be a number with 2 decimal places');
            }
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
            $name = $this->data['name'];
            $interest = $this->data['interest'];
            $sql = "INSERT INTO loan_types (name, interest) VALUES (:name, :interest)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute(['name' => $name, 'interest' => $interest]);
            if ($run) {
                message('success', 'A new loan type has been created');
                redirect('types.php');
            }
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
