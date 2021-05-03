<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AdminLoan extends Connection
{
    private $data;
    private $errors = [];
    private static $fields = ['loan_number', 'mmebership_number', 'amount', 'term', 'department_id', 'loan_type_id'];
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = "SELECT * FROM loans WHERE comaker1_id IS NOT NULL AND comaker2_id IS NOT NULL
                    AND approved_by_c1=1 AND approved_by_c2=1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
    public function getLoansPerComaker()
    {
        $user_id = $_SESSION['id'];

        $sql = "SELECT * FROM loans";
        $stmt = $this->conn->query($sql);
        $loans = $stmt->fetchAll();
        $loan_users_id = [];
        foreach ($loans as $loan) {
            $loan_users_id = $loan->position_id;
        }
        // echo $loan_users_id;
        if (strpos($user_id, $loan_users_id) !== false) {
            echo 'true';
        }
        die();
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
    public function getComaker($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
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

            return $this->errors;
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
                $sql = "INSERT INTO users (firstname, lastname, gender, contact_number,email, password, position_id)
            VALUES (:firstname, :lastname, :gender, :contact_number, :email, :password, :position_id)";
                $stmt = $this->conn->prepare($sql);
                // hash the password before saving to the database
                $password = md5($this->data['password1']);

                // bind param and execute
                $run = $stmt->execute([
                    'firstname' => $this->data['firstname'],
                    'lastname' => $this->data['lastname'],
                    'gender' => $this->data['gender'],
                    'contact_number' => $this->data['contact_number'],
                    'email' => $this->data['email'],
                    'password' => $password,
                    'position_id' => $this->data['position_id'],
                ]);
                $lastId = $this->conn->lastInsertId();
                if ($run) {
                    message('success', 'A new user has been created');
                    redirect('admin_users.php');
                }
            }
        }
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


        $sql = "UPDATE loans SET status=:status, approved_at=now() WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $updated = $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);


        if ($updated) {
            $loan = $this->getLoan($id);
            $activeUser = $this->getUser($loan->user_id);
            $this->send_mail($activeUser, $loan);
        } else {
            message('danger', 'A loan has been updated, but failed to send email notification');
            redirect('loans.php');
        }
    }
    private function send_mail($user, $loan)
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
            $mail->Subject = 'Loan Update';
            $mail->Body    = "
                <h3>Good day $user->firstname $user->lastname! </h3>
                <h4>Your loan status is now $loan->status. </h4>
                <p>Thank you for trusting us. </p><br><br>
                <p>Transaction Id: $loan->transaction_id </p>

            ";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $sent = $mail->send();
            if ($sent) {
                message('success', 'A loan has been updated. Email notification has been sent');
                redirect('loans.php');
            } else {
                message('success', 'A loan has been updated but email notification has not been sent due to some problems. Please try again');
                redirect('loans.php');
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
