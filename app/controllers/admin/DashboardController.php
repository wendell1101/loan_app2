<?php
class Dashboard extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user;
    }
    public function getDepartment($id)
    {
        $sql = "SELECT * FROM departments WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $department = $stmt->fetch();
        return $department;
    }
    public function getUsersCount()
    {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->rowCount();
    }
    public function getDepartmentsCount()
    {
        $stmt = $this->conn->query("SELECT * FROM departments");
        return $stmt->rowCount();
    }
    public function getLoanCountPerDepartment($department_id)
    {
        $sql = "SELECT * FROM loans WHERE department_id=$department_id";
        $stmt = $this->conn->query($sql);
        return $stmt->rowCount();
    }
    public function getLoanTermCount($term)
    {
        $sql = "SELECT * FROM loans WHERE term=$term";
        $stmt = $this->conn->query($sql);
        return $stmt->rowCount();
    }
    public function formatNumber($number)
    {
        return number_format((float)$number, 0, '.', '');
    }
    public function getLoanCountPercentagePerTerm($loanCount, $totalLoanCount)
    {
        $percentage =  $loanCount == 0 ? 0 : $loanCount / $totalLoanCount * 100;
        return $this->formatNumber($percentage);
    }
    public function getLoanCountPercentagePerDepartment($loanCount, $totalLoanCount)
    {
        $percentage =  $loanCount == 0 ? 0 : $loanCount / $totalLoanCount * 100;
        return $this->formatNumber($percentage);
    }
    public function getDepartments()
    {
        $stmt = $this->conn->query("SELECT * FROM departments");
        return $stmt->fetchAll();
    }
    public function getUsers()
    {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
    public function getPaymentsCount()
    {
        $stmt = $this->conn->query("SELECT * FROM payments");
        return $stmt->rowCount();
    }
    public function getLoanTypesCount()
    {
        $stmt = $this->conn->query("SELECT * FROM loan_types");
        return $stmt->rowCount();
    }
    public function getLoansCount()
    {
        $stmt = $this->conn->query("SELECT * FROM loans");
        return $stmt->rowCount();
    }

    public function getLoans()
    {
        $stmt = $this->conn->query("SELECT * FROM loans");
        return $stmt->fetchAll();
    }

    private function getReservations()
    {
        $stmt = $this->conn->query("SELECT * FROM reservations");
        return $stmt->fetchAll();
    }

    public function getReservationCountByMonth()
    {
        $data = [
            'jan' => 0,
            'feb' => 0,
            'mar' => 0,
            'april' => 0,
            'may' => 0,
            'june' => 0,
            'july' => 0,
            'aug' => 0,
            'sept' => 0,
            'oct' => 0,
            'nov' => 0,
            'dec' => 0,
        ];
        $reservations = $this->getReservations();
        foreach ($reservations as $reservation) {
            $date = formatDate($reservation->date_time);
            if (strpos(strtolower($date), 'january') !== false) {
                $data['jan']++;
            } else if (strpos(strtolower($date), 'february') !== false) {
                $data['feb']++;
            } else if (strpos(strtolower($date), 'march') !== false) {
                $data['mar']++;
            } else if (strpos(strtolower($date), 'april') !== false) {
                $data['april']++;
            } else if (strpos(strtolower($date), 'may') !== false) {
                $data['may']++;
            } else if (strpos(strtolower($date), 'june') !== false) {
                $data['june']++;
            } else if (strpos(strtolower($date), 'july') !== false) {
                $data['july']++;
            } else if (strpos(strtolower($date), 'august') !== false) {
                $data['aug']++;
            } else if (strpos(strtolower($date), 'september') !== false) {
                $data['sept']++;
            } else if (strpos(strtolower($date), 'october') !== false) {
                $data['oct']++;
            } else if (strpos(strtolower($date), 'november') !== false) {
                $data['nov']++;
            } else if (strpos(strtolower($date), 'december') !== false) {
                $data['dec']++;
            }
        }
        return $data;
    }
}
