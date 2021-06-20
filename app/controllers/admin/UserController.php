<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AdminUser extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['firstname', 'lastname', 'gender', 'contact_number', 'email', 'password1', 'password2'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index($status)
    {
        if ($status === '' || $status === 'all') {
            $sql = "SELECT * FROM users WHERE approved_by_m1 IS NOT NULL AND approved_by_m2 IS NOT NULL AND approved_by_m3 IS NOT NULL AND approved_by_president=1";
            $stmt = $this->conn->query($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM users WHERE active=:status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['status' => $status]);
            return $stmt->fetchAll();
        }
    }

    public function getMembershipApprovals()
    {
        $sql = "SELECT * FROM membership_approval";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    //get users for membership comittee approval
    public function getPendingMemberships()
    {
        $u_id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE approved_by_m1 IS NULL OR approved_by_m2 IS NULL OR approved_by_m3 IS NULL  AND approved_by_president=0 ";
        $stmt = $this->conn->query($sql);
        $users = $stmt->fetchAll();
        return $users;
    }
    public function getDeclinedMemberships()
    {
        $u_id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE reason_for_decline IS NOT NULL";
        $stmt = $this->conn->query($sql);
        $users = $stmt->fetchAll();
        return $users;
    }
    //get users for president approval
    public function getPresidentPendingMemberships()
    {
        $u_id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE approved_by_m1 IS NOT NULL AND approved_by_m2 IS NOT NULL AND approved_by_m3 IS NOT NULL AND approved_by_president=0";
        $stmt = $this->conn->query($sql);
        $users = $stmt->fetchAll();
        return $users;
    }

    public function updateByMembershipCommittee($data)
    {
        $user_id = $data['id'];
        $activeUser = $this->getUser($user_id);
        $active_user_id = $activeUser->id;
        $u_id = $_SESSION['id'];

        if ($data['approved'] == 1) {

            // pag null ang approved_by_m1 lagyan ng laman
            if (is_null($activeUser->approved_by_m1)) {
                $sql = "UPDATE users SET approved_by_m1=$u_id, reason_for_decline=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            } else if (is_null($activeUser->approved_by_m2)) {
                $sql = "UPDATE users SET approved_by_m2=$u_id, reason_for_decline=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            } else if (is_null($activeUser->approved_by_m3)) {
                $sql = "UPDATE users SET approved_by_m3=$u_id, reason_for_decline=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            }
        } else if ($data['approved'] == 0) {
            if (!is_null($activeUser->approved_by_m1) && $activeUser->approved_by_m1 == $u_id) {
                $sql = "UPDATE users SET approved_by_m1=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            } else if (!is_null($activeUser->approved_by_m2) && $activeUser->approved_by_m2 == $u_id) {
                $sql = "UPDATE users SET approved_by_m2=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            } else if (!is_null($activeUser->approved_by_m3) && $activeUser->approved_by_m3 == $u_id) {
                $sql = "UPDATE users SET approved_by_m3=NULL WHERE id=$user_id";
                $run = $stmt = $this->conn->query($sql);
            }
        }
        if ($run) {
            message('success', 'A member status has been updated');
            redirect('pending_memberships.php');
        }
    }
    public function membershipDecline($data)
    {

        $reason_for_decline = $data['reason_for_decline'];
        $user_id = $data['user_id'];
        $sql = "UPDATE users SET reason_for_decline=:reason_for_decline WHERE id=:user_id";
        $stmt = $this->conn->prepare($sql);
        $run = $stmt->execute([
            'reason_for_decline' => $reason_for_decline,
            'user_id' => $user_id,
        ]);
        if ($run) {
            message('success', 'A member status has been updated');
            if ($_SESSION['position_id'] == 6) {
                redirect('pending_memberships.php');
            } elseif ($_SESSION['position_id'] == 3) {
                redirect('pending_memberships_president.php');
            }
        }
    }
    public function updateMemberByPresident($data)
    {
        $user_id = $data['id'];
        $activeUser = $this->getUser($user_id);
        $active_user_id = $activeUser->id;
        $u_id = $_SESSION['id'];
        if ($data['approved'] == 1) {
            $sql = "UPDATE users SET approved_by_president=1 WHERE id=$user_id";
            $run = $this->conn->query($sql);
        } else if ($data['approved'] == 0) {
            $sql = "UPDATE users SET approved_by_president=0 WHERE id=$user_id";
            $run = $this->conn->query($sql);
        }
        if ($run) {
            message('success', 'A member status has been updated');
            redirect('pending_memberships_president.php');
        }
    }

    // check if current user has approve the member
    public function checkIfActiveMembershipCommitteeHasApproved($id)
    {
        $user_id = $_SESSION['id'];

        $sql = "SELECT * FROM users WHERE (approved_by_m1=$user_id OR approved_by_m2=$user_id OR approved_by_m3=$user_id) AND id=$id";
        $stmt =  $this->conn->query($sql);
        return $stmt->rowCount();
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
        }
    }

    public function getUserDeposit($id)
    {
        $sql = "SELECT * FROM fixed_deposits WHERE user_id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $deposits = $stmt->fetchAll();
        $total = 0;
        foreach ($deposits as $deposit) {
            $total += $deposit->amount;
        }
        return $total;
    }
    public function getUserSavings($id)
    {
        $sql = "SELECT * FROM savings WHERE user_id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $deposits = $stmt->fetchAll();
        $total = 0;
        foreach ($deposits as $deposit) {
            $total += $deposit->amount;
        }
        return $total;
    }
    public function getUserRegularLoans($id)
    {
        $status = 'approved';
        $type = 1;
        $sql = "SELECT * FROM loans WHERE user_id=:id AND status=:status AND loan_type_id=:type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id, 'status' => $status, 'type' => $type]);
        $loans = $stmt->fetchAll();
        $total = 0;
        foreach ($loans as $loan) {
            $total += $loan->total_amount;
        }
        return $total;
    }
    public function getUserLoansObj($id)
    {
        $status = 'approved';
        $type = 1;
        $sql = "SELECT * FROM loans WHERE user_id=:id AND status=:status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id, 'status' => $status]);
        $loans = $stmt->fetchAll();
        return $loans;
    }
    public function getUserCharacterLoans($id)
    {
        $status = 'approved';
        $type = 3;
        $sql = "SELECT * FROM loans WHERE user_id=:id AND status=:status AND loan_type_id=:type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id, 'status' => $status, 'type' => $type]);
        $loans = $stmt->fetchAll();
        $total = 0;
        foreach ($loans as $loan) {
            $total += $loan->total_amount;
        }
        return $total;
    }

    public function getLoanTypeName($id)
    {
        $sql = "SELECT * FROM loan_types WHERE id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $type = $stmt->fetch();
        return  $type->name;
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

            $this->validateEmail();
            $this->validatePassword1();
            $this->validatePassword2();
            // $this->validateGender();
            // $this->validateContactNumber();
            return $this->errors;
        }
    }

    // validate firstname
    private function validateFirstname()
    {
        //trim whitespace
        $val = trim($this->data['firstname']);
        // check if empty
        if (empty($val)) {
            $this->addError('firstname', 'Firstname must not be empty');
        } else {
            // match any lowercase/uppercase letter, any digits from 0-9, atleast 3-20 characters
            if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $val)) {
                $this->addError('firstname', 'Firstname must be 3-20 characters and alphanumeric');
            }
        }
    }
    //validate lastname
    private function validateLastname()
    {
        //trim whitespace
        $val = trim($this->data['lastname']);
        // check if empty
        if (empty($val)) {
            $this->addError('lastname', 'Lastname must not be empty');
        } else {
            // match any lowercase/uppercase letter, any digits from 0-9, atleast 3-20 characters
            if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $val)) {
                $this->addError('lastname', 'Lastname must be 3-20 characters and alphanumeric');
            }
        }
    }
    //validate email
    private function validateEmail()
    {
        //trim white space
        $val = trim($this->data['email']);
        // check if empty
        if (empty($val)) {
            $this->addError('email', 'Email must not be empty');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email must be a valid email');
            }
        }
    }

    //Validate password1
    private function validatePassword1()
    {
        // trim white space
        $val = trim($this->data['password1']);
        // check if empty
        if (empty($val)) {
            $this->addError('password1', 'Password must not be empty');
        } else {
            $password1 = trim($this->data['password1']);
            $password2 = trim($this->data['password2']);
            if (!empty($password2)) {
                if ($password1 !== $password2) {
                    $this->addError('password1', 'Passwords do not match. Please try again');
                }
            }
        }
    }

    //Validate password2
    private function validatePassword2()
    {
        // trim white space
        $val = trim($this->data['password2']);
        // check if empty
        if (empty($val)) {
            $this->addError('password2', 'Confirm-password must not be empty');
        } else {
            $password1 = trim($this->data['password1']);
            $password2 = trim($this->data['password2']);
            if ($password1 !== $password2) {
                $this->addError('password2', 'Passwords do not match. Please try again');
            }
        }
    }



    //Validate gender
    private function validateGender()
    {
        // trim white space
        $val = trim($this->data['gender']);
        // check if empty
        if ($val == "null") {
            $this->addError('gender', 'Gender is required');
        }
    }

    //validate contact number
    private function validateContactNumber()
    {
        // check if empty
        $val = $this->data['contact_number'];
        if (empty($val)) {
            $this->addError('contact_number', 'Contact number must be specified');
        } else if (!preg_match('((^(\+)(\d){12}$)|(^\d{11}$))', $val)) {
            $this->addError('contact_number', 'Contact number must be a valid 11-digit contact number');
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
                // hash the password before saving to the database
                $rand1 = rand(1, 10);
                $rand2 = rand(1, 45);
                $rand3 = rand(1, 100);
                $rand4 = rand(1, 99);
                $account_number = time() . rand($rand1 * $rand2, $rand3 * $rand4);
                $firstname = $this->data['firstname'];
                $middlename = $this->data['middlename'];
                $lastname = $this->data['lastname'];
                $home_address = $this->data['home_address'];
                $permanent_address = $this->data['permanent_address'];
                $gender = $this->data['gender'];
                $birth_date = $this->data['birth_date'];
                $contact_number = $this->data['contact_number'];
                $email = $this->data['email'];
                $password = md5($this->data['password1']);
                $position_id = $this->data['position_id'];
                $sg = $this->data['sg'];
                $employment_status = $this->data['employment_status'];
                $department_id = $this->data['department_id'];
                $name_of_spouse = $this->data['name_of_spouse'];
                $fathers_name = $this->data['fathers_name'];
                $mothers_maiden_name = $this->data['mothers_maiden_name'];
                $beneficiary = $this->data['beneficiary'];
                $paid_membership = 0;
                $active = 0;
                $reason1 = $this->data['reason1'];
                $reason2 = $this->data['reason2'];
                $reason3 = $this->data['reason3'];
                $sql = "INSERT INTO users (account_number, firstname, middlename, lastname, home_address, permanent_address, gender, birth_date,
                contact_number,email, password, position_id, sg, employment_status, department_id, name_of_spouse, fathers_name,
                mothers_maiden_name, beneficiary, paid_membership, active, reason1, reason2, reason3)
                VALUES (:account_number, :firstname, :middlename, :lastname, :home_address, :permanent_address, :gender, :birth_date,
                :contact_number,:email, :password, :position_id, :sg, :employment_status, :department_id, :name_of_spouse, :fathers_name,
                :mothers_maiden_name, :beneficiary, :paid_membership, :active, :reason1, :reason2,:reason3)";
                $stmt = $this->conn->prepare($sql);
                $run = $stmt->execute([
                    'account_number' => $account_number,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'home_address' => $home_address,
                    'permanent_address' => $permanent_address,
                    'gender' => $gender,
                    'birth_date' => $birth_date,
                    'contact_number' => $contact_number,
                    'email' => $email,
                    'password' => $password,
                    'position_id' => $position_id,
                    'sg' => $sg,
                    'employment_status' => $employment_status,
                    'department_id' => $department_id,
                    'name_of_spouse' => $name_of_spouse,
                    'fathers_name' => $fathers_name,
                    'mothers_maiden_name' => $mothers_maiden_name,
                    'beneficiary' => $beneficiary,
                    'paid_membership' => $paid_membership,
                    'active' => $active,
                    'reason1' => $reason1,
                    'reason2' => $reason2,
                    'reason3' => $reason3,
                ]);


                $lastId = $this->conn->lastInsertId();
                if ($run) {
                    message('success', 'A new user has been created and moved to the membership committee');
                    redirect('admin_users.php');
                }
            }
        }
    }


    // delete category
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A user has been deleted');
            redirect('admin_users.php');
        } else {
            message('danger', 'A user cannot be deleted because of associated data');
            redirect('admin_users.php');
        }
    }
    public function deleteFromMembershipCommittee($id)
    {
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A user has been deleted');
            redirect('pending_memberships.php');
        } else {
            message('danger', 'A user cannot be deleted because of associated data');
            redirect('pending_memberships.php');
        }
    }
    public function deleteMemberFromPresident($id)
    {
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $deleted = $stmt->execute(['id' => $id]);
        if ($deleted) {
            message('success', 'A user has been deleted');
            redirect('pending_memberships_president.php');
        } else {
            message('danger', 'A user cannot be deleted because of associated data');
            redirect('pending_memberships_president.php');
        }
    }


    // get loan

    public function getLoan($id)
    {
        $sql = "SELECT * FROM loans WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
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
    // get single department
    public function getDepartment($id)
    {
        $sql = "SELECT * FROM departments WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $department = $stmt->fetch();
        return $department;
    }

    //update category
    public function update($data)
    {
        $this->data = $data;
        $this->validate();
        $this->updateUser();
    }
    private function updateUser()
    {
        $id = $this->data['id'];
        $active = $this->data['active'];
        $position_id = $this->data['position_id'];
        $paid_membership = $this->data['paid_membership'];
        $activeUser = $this->getUser($id);

        $sql = "UPDATE users SET paid_membership=$paid_membership, active=$active, position_id=$position_id WHERE id=$id";
        $updated = $this->conn->query($sql);
        if ($updated) {
            if ($active == 1) {
                $this->send_mail($activeUser);
            } else {
                message('success', 'A user has been updated');
                redirect('admin_users.php');
            }
        } else {
            echo 'error occured';
        }
    }

    //send mail
    private function send_mail($user)
    {
        //send email

        // Load Composer's autoloader
        require '../mail/Exception.php';
        require '../mail/PHPMailer.php';
        require '../mail/SMTP.php';
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer();
        try {

            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = EMAIL;                     // SMTP username
            $mail->Password   = PASS;                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('fea@gmail.com', 'Faculty and Employee Association');
            $mail->addAddress($user->email);     // Add a recipient


            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Membership Update';
            $mail->Body    = "
                <h3>Good day $user->firstname $user->lastname! </h3>
                <h4>Your membership status has been approved. </h4>
                <p>Thank you for trusting us. </p><br><br>

            ";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $sent = $mail->send();
            if ($sent) {
                message('success', 'A user has been updated. Email notification has been sent');
                redirect('admin_users.php');
            } else {
                message('success', 'A loan has been updated but email notification has not been sent due to some problems. Please try again');
                redirect('admin_users.php');
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
