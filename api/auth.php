<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/Database.php';
require_once __DIR__ . '/JWTHelper.php';

class AuthAPI {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login($email, $password) {
        try {
            $this->db->query("SELECT id, username, email, password, is_admin FROM users WHERE email = :email");
            $this->db->bind(':email', $email);
            
            $user = $this->db->single();
            
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'message' => 'Ongeldige inloggegevens'
                ];
            }
            
            if (!password_verify($password, $user->password)) {
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'message' => 'Ongeldige inloggegevens'
                ];
            }
            
            // Genereer JWT token
            $token = JWTHelper::generateToken($user);
            
            return [
                'success' => true,
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => (int)$user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'is_admin' => (bool)$user->is_admin
                    ]
                ],
                'message' => 'Succesvol ingelogd'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het inloggen'
            ];
        }
    }

    public function register($username, $email, $password) {
        try {
            // Controleer of email al bestaat
            $this->db->query("SELECT id FROM users WHERE email = :email");
            $this->db->bind(':email', $email);
            $existingUser = $this->db->single();
            
            if ($existingUser) {
                return [
                    'success' => false,
                    'error' => 'Email already exists',
                    'message' => 'Dit email adres is al in gebruik'
                ];
            }
            
            // Controleer of gebruikersnaam al bestaat
            $this->db->query("SELECT id FROM users WHERE username = :username");
            $this->db->bind(':username', $username);
            $existingUsername = $this->db->single();
            
            if ($existingUsername) {
                return [
                    'success' => false,
                    'error' => 'Username already exists',
                    'message' => 'Deze gebruikersnaam is al in gebruik'
                ];
            }
            
            // Hash het wachtwoord
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Voeg gebruiker toe
            $this->db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $this->db->bind(':username', $username);
            $this->db->bind(':email', $email);
            $this->db->bind(':password', $hashedPassword);
            
            if ($this->db->execute()) {
                $userId = $this->db->lastInsertId();
                
                // Genereer JWT token voor nieuwe gebruiker
                $user = (object)[
                    'id' => $userId,
                    'username' => $username,
                    'email' => $email,
                    'is_admin' => false
                ];
                
                $token = JWTHelper::generateToken($user);
                
                return [
                    'success' => true,
                    'data' => [
                        'token' => $token,
                        'user' => [
                            'id' => (int)$user->id,
                            'username' => $user->username,
                            'email' => $user->email,
                            'is_admin' => (bool)$user->is_admin
                        ]
                    ],
                    'message' => 'Account succesvol aangemaakt'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Registration failed',
                    'message' => 'Registratie mislukt'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het registreren'
            ];
        }
    }



    public function getCurrentUser($token) {
        $payload = JWTHelper::verifyToken($token);
        if (!$payload) {
            return [
                'success' => false,
                'error' => 'Invalid token',
                'message' => 'Ongeldige token'
            ];
        }
        
        try {
            $this->db->query("SELECT id, username, email, is_admin FROM users WHERE id = :id");
            $this->db->bind(':id', $payload->user_id);
            $user = $this->db->single();
            
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'User not found',
                    'message' => 'Gebruiker niet gevonden'
                ];
            }
            
            return [
                'success' => true,
                'data' => [
                    'id' => (int)$user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'is_admin' => (bool)$user->is_admin
                ],
                'message' => 'Gebruiker succesvol opgehaald'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'message' => 'Er is een fout opgetreden bij het ophalen van gebruiker'
            ];
        }
    }
}

// Initialize API
$authAPI = new AuthAPI();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $action = $_GET['action'] ?? 'login';
            
            switch ($action) {
                case 'login':
                    if (!isset($input['email']) || !isset($input['password'])) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Missing required fields',
                            'message' => 'Email en wachtwoord zijn verplicht'
                        ]);
                        break;
                    }
                    
                    $result = $authAPI->login($input['email'], $input['password']);
                    
                    if (!$result['success']) {
                        http_response_code(401);
                    }
                    
                    echo json_encode($result);
                    break;
                    
                case 'register':
                    if (!isset($input['username']) || !isset($input['email']) || !isset($input['password'])) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'error' => 'Missing required fields',
                            'message' => 'Gebruikersnaam, email en wachtwoord zijn verplicht'
                        ]);
                        break;
                    }
                    
                    $result = $authAPI->register($input['username'], $input['email'], $input['password']);
                    
                    if (!$result['success']) {
                        http_response_code(400);
                    }
                    
                    echo json_encode($result);
                    break;
                    
                case 'verify':
                    $token = JWTHelper::getTokenFromHeaders();
                    
                    if (!$token) {
                        http_response_code(401);
                        echo json_encode([
                            'success' => false,
                            'error' => 'No token provided',
                            'message' => 'Geen token opgegeven'
                        ]);
                        break;
                    }
                    
                    $result = $authAPI->getCurrentUser($token);
                    
                    if (!$result['success']) {
                        http_response_code(401);
                    }
                    
                    echo json_encode($result);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action',
                        'message' => 'Ongeldige actie'
                    ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed',
                'message' => 'Deze HTTP methode wordt niet ondersteund'
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
        'message' => 'Er is een interne server fout opgetreden'
    ]);
} 