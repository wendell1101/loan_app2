<?php

class User extends Connection
{

    public function __construct()
    {
        parent::__construct();
    }
    public static function Auth()
    {
        if (isset($_SESSION['id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getFullName()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return ucwords($user->firstname) . ' ' . ucwords($user->lastname);
        } else {
            return 'Guess';
        }
    }

    public function getUser()
    {
        if (self::Auth()) {
            $sql = "SELECT * FROM users WHERE id=:id AND active=1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $_SESSION['id']]);
            $user = $stmt->fetch();
            return $user;
        }
    }

    public function isAdmin()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return $user->position_id == 1;
        }
    }
    public function isTreasurer()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return ($user->position_id == 4 || $user->position_id == 5);
        }
    }
    public function isComaker()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return $user->position_id == 8;
        }
    }
    public function isMembershipCommittee()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return $user->position_id == 6;
        }
    }
    public function isFinancialCommitee()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return $user->position_id == 7;
        }
    }
    public function isPresident()
    {
        if (self::Auth()) {
            $user = $this->getUser();
            return $user->position_id == 3;
        }
    }
}
