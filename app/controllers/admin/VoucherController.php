<?php
class Voucher extends Connection
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
        $sql = "SELECT * FROM vouchers ORDER BY created_at DESC ";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function getVoucherCategories()
    {
        $sql = "SELECT * FROM voucher_categories";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id=$id";
        $stmt = $this->conn->query($sql);
        return  $stmt->fetch();
    }
    public function getMembers()
    {
        $sql = "SELECT * FROM users WHERE active=1 AND paid_membership=1";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
    public function getVoucherCategory($id)
    {
        $sql = "SELECT * FROM voucher_categories WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();;
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

            // $this->validateVoucherCategoryName();

            return $this->errors;
        }
    }

    private function validateVoucherName()
    {
        // check if empty
        $val = $this->data['name'];
        if (empty($val)) {
            $this->addError('name', 'Voucher name must not be empty');
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
            $rand1 = rand(1, 10);
            $rand2 = rand(1, 45);
            $rand3 = rand(1, 100);
            $rand4 = rand(1, 99);
            $receipt_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
            $voucher_category_id = $this->data['voucher_category_id'];
            $user_id = $this->data['user_id'];
            $amount = $this->data['amount'];
            $care_of = $_SESSION['id'];

            $sql = "INSERT INTO vouchers (receipt_number, voucher_category_id, user_id, amount, care_of)
            VALUES (:receipt_number, :voucher_category_id, :user_id, :amount, :care_of)";
            $stmt = $this->conn->prepare($sql);
            $run = $stmt->execute([
                'receipt_number' => $receipt_number,
                'voucher_category_id' => $voucher_category_id,
                'user_id' => $user_id,
                'amount' => $amount,
                'care_of' => $care_of,
            ]);

            if ($run) {
                message('success', 'A new voucher has been created');
                redirect('vouchers.php');
            }
        }
    }

    // delete category
    public function delete($id)
    {
        $sql = "DELETE FROM vouchers WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A voucher has been deleted');
            redirect('vouchers.php');
        } else {
            message('danger', 'Something went wrong please try again');
            redirect('vouchers.php');
        }
    }
    // get single department
    public function getVoucher($id)
    {
        $sql = "SELECT * FROM vouchers WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();;
    }



    //update category
    public function update($data)
    {
        $this->data = $data;
        $this->validate();
        $this->updateVoucher();
    }
    // ieedit pa to
    private function updateVoucher()
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
