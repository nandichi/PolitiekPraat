<?php
class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        // Hash het wachtwoord
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $this->db->query("INSERT INTO users (username, email, password) 
                         VALUES (:username, :email, :password)");
        
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        return $this->db->execute();
    }

    public function login($email, $password) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        if($row && password_verify($password, $row->password)) {
            $_SESSION['user_id'] = $row->id;
            $_SESSION['username'] = $row->username;
            $_SESSION['is_admin'] = $row->is_admin;
            return true;
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['is_admin']);
        session_destroy();
    }
} 