<?php
class VoucherCategory extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['name'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM voucher_categories";
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

            $this->validateVoucherCategoryName();

            return $this->errors;
        }
    }

    private function validateVoucherCategoryName()
    {
        // check if empty
        $val = $this->data['name'];
        if (empty($val)) {
            $this->addError('name', 'Voucher category name must not be empty');
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
            $sql = "INSERT INTO voucher_categories (name) VALUES (:name)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute(['name' => $name]);
            if ($run) {
                message('success', 'A new voucher category has been created');
                redirect('categories_voucher.php');
            }
        }
    }

    // delete category
    public function delete($id)
    {
        $sql = "DELETE FROM voucher_categories WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A voucher category has been deleted');
            redirect('categories_voucher.php');
        } else {
            message('danger', 'A voucher category cannot be deleted because of associated voucher');
            redirect('categories_voucher.php');
        }
    }
    // get single department
    public function getVoucherCategory($id)
    {
        $sql = "SELECT * FROM voucher_categories WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();;
    }



    //update category
    public function update($data)
    {
        $this->data = $data;
        $this->validate();
        $this->updateVoucherCategory();
    }
    private function updateVoucherCategory()
    {
        $name = $this->data['name'];
        $id = $this->data['id'];
        if (!array_filter($this->errors)) {
            $sql = "UPDATE voucher_categories set name=:name WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $updated = $stmt->execute(['name' => $name, 'id' => $id]);
            if ($updated) {
                message('success', 'A voucher category has been updated');
                redirect('categories_voucher.php');
            }
        }
    }
}
