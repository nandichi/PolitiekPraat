<?php

class AuthAPI {
    private $db;
    private $authController;
    private $secretKey;
    
    public function __construct() {
        $this->db = new Database();
        $this->authController = new AuthController();
        
        // JWT secret key - in productie moet dit in een config file
        $this->secretKey = 'PolitiekPraat_JWT_Secret_2024_Secure_Key_' . (defined('DB_NAME') ? DB_NAME : 'default');
    }
    
    public function handle($method, $segments) {
        $action = isset($segments[1]) ? $segments[1] : '';
        
        switch ($method) {
            case 'POST':
                switch ($action) {
                    case 'login':
                        $this->login();
                        break;
                    case 'register':
                        $this->register();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'refresh':
                        $this->refresh();
                        break;
                    default:
                        $this->sendError('Invalid auth action', 400);
                }
                break;
                
            case 'GET':
                switch ($action) {
                    case 'me':
                        $this->getCurrentUser();
                        break;
                    case 'verify':
                        $this->verifyToken();
                        break;
                    default:
                        $this->sendError('Invalid auth action', 400);
                }
                break;
                
            default:
                $this->sendError('Method not allowed', 405);
        }
    }
    
    private function login() {
        $input = $this->getJsonInput();
        
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->sendError('Email en wachtwoord zijn verplicht', 400);
        }
        
        // Valideer email formaat
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendError('Ongeldig email formaat', 400);
        }
        
        try {
            // Zoek gebruiker
            $this->db->query("SELECT id, username, email, password, is_admin, profile_photo, created_at FROM users WHERE email = :email");
            $this->db->bind(':email', $email);
            $user = $this->db->single();
            
            if (!$user || !password_verify($password, $user->password)) {
                $this->sendError('Ongeldige inloggegevens', 401);
            }
            
            // Genereer JWT token
            $token = $this->generateJWT($user);
            
            // Return user data en token
            $this->sendResponse([
                'token' => $token,
                'user' => [
                    'id' => (int)$user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'is_admin' => (bool)$user->is_admin,
                    'profile_photo' => $user->profile_photo,
                    'created_at' => $user->created_at
                ]
            ]);
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function register() {
        $input = $this->getJsonInput();
        
        if (!$input) {
            $this->sendError('Invalid JSON input', 400);
        }
        
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';
        
        // Validatie
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $this->sendError('Alle velden zijn verplicht', 400);
        }
        
        if (strlen($username) < 3) {
            $this->sendError('Gebruikersnaam moet minimaal 3 karakters bevatten', 400);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendError('Ongeldig email formaat', 400);
        }
        
        if (strlen($password) < 6) {
            $this->sendError('Wachtwoord moet minimaal 6 karakters bevatten', 400);
        }
        
        if ($password !== $confirmPassword) {
            $this->sendError('Wachtwoorden komen niet overeen', 400);
        }
        
        try {
            // Controleer of email al bestaat
            $this->db->query("SELECT id FROM users WHERE email = :email");
            $this->db->bind(':email', $email);
            if ($this->db->single()) {
                $this->sendError('Dit email adres is al in gebruik', 409);
            }
            
            // Controleer of username al bestaat
            $this->db->query("SELECT id FROM users WHERE username = :username");
            $this->db->bind(':username', $username);
            if ($this->db->single()) {
                $this->sendError('Deze gebruikersnaam is al in gebruik', 409);
            }
            
            // Hash wachtwoord
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Maak nieuwe gebruiker aan
            $this->db->query("INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())");
            $this->db->bind(':username', $username);
            $this->db->bind(':email', $email);
            $this->db->bind(':password', $hashedPassword);
            
            if ($this->db->execute()) {
                $userId = $this->db->lastInsertId();
                
                // Haal de nieuwe gebruiker op
                $this->db->query("SELECT id, username, email, is_admin, profile_photo, created_at FROM users WHERE id = :id");
                $this->db->bind(':id', $userId);
                $newUser = $this->db->single();
                
                // Genereer JWT token
                $token = $this->generateJWT($newUser);
                
                $this->sendResponse([
                    'message' => 'Account succesvol aangemaakt',
                    'token' => $token,
                    'user' => [
                        'id' => (int)$newUser->id,
                        'username' => $newUser->username,
                        'email' => $newUser->email,
                        'is_admin' => (bool)$newUser->is_admin,
                        'profile_photo' => $newUser->profile_photo,
                        'created_at' => $newUser->created_at
                    ]
                ], 201);
            } else {
                $this->sendError('Er is een fout opgetreden bij het aanmaken van het account', 500);
            }
            
        } catch (Exception $e) {
            $this->sendError('Database fout: ' . $e->getMessage(), 500);
        }
    }
    
    private function logout() {
        // Voor JWT tokens is logout meestal client-side
        // Hier kunnen we eventueel een token blacklist implementeren
        $this->sendResponse(['message' => 'Succesvol uitgelogd']);
    }
    
    private function refresh() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Ongeldig token', 401);
        }
        
        // Genereer nieuw token
        $token = $this->generateJWT($user);
        
        $this->sendResponse([
            'token' => $token,
            'user' => [
                'id' => (int)$user->id,
                'username' => $user->username,
                'email' => $user->email,
                'is_admin' => (bool)$user->is_admin,
                'profile_photo' => $user->profile_photo,
                'created_at' => $user->created_at
            ]
        ]);
    }
    
    private function getCurrentUser() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Niet geauthenticeerd', 401);
        }
        
        $this->sendResponse([
            'user' => [
                'id' => (int)$user->id,
                'username' => $user->username,
                'email' => $user->email,
                'is_admin' => (bool)$user->is_admin,
                'profile_photo' => $user->profile_photo,
                'created_at' => $user->created_at
            ]
        ]);
    }
    
    private function verifyToken() {
        $user = $this->getCurrentUserFromToken();
        if (!$user) {
            $this->sendError('Token is ongeldig', 401);
        }
        
        $this->sendResponse([
            'valid' => true,
            'user_id' => (int)$user->id
        ]);
    }
    
    private function generateJWT($user) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60) // 24 uur geldig
        ]);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $this->secretKey, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    private function verifyJWT($token) {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) !== 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $tokenParts;
        
        // Verificeer signature
        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        // Decode payload
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);
        
        // Controleer expiration
        if ($payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
    
    private function getCurrentUserFromToken() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return null;
        }
        
        $token = $matches[1];
        $payload = $this->verifyJWT($token);
        
        if (!$payload) {
            return null;
        }
        
        // Haal actuele gebruikersdata op uit database
        try {
            $this->db->query("SELECT id, username, email, is_admin, profile_photo, created_at FROM users WHERE id = :id");
            $this->db->bind(':id', $payload['user_id']);
            return $this->db->single();
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    private function sendError($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
} 