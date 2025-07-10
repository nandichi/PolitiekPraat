<?php
class JWTHelper {
    private static $jwtSecret = 'politiekpraat_jwt_secret_2024'; // In productie zou dit een veilige random string moeten zijn

    public static function generateToken($user) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7) // 7 dagen
        ]);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, self::$jwtSecret);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    public static function verifyToken($token) {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return false;
            }
            
            $payload = json_decode(base64_decode($parts[1]));
            if (!$payload) {
                return false;
            }
            
            // Controleer of token niet verlopen is
            if ($payload->exp < time()) {
                return false;
            }
            
            // Controleer signature
            $expectedSignature = hash_hmac('sha256', $parts[0] . '.' . $parts[1], self::$jwtSecret);
            if ($parts[2] !== $expectedSignature) {
                return false;
            }
            
            return $payload;
            
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getTokenFromHeaders() {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (strpos($authHeader, 'Bearer ') === 0) {
                return substr($authHeader, 7);
            }
        }
        
        return null;
    }

    public static function requireAuth() {
        $token = self::getTokenFromHeaders();
        
        if (!$token) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'No token provided',
                'message' => 'Geen authenticatie token opgegeven'
            ]);
            exit();
        }
        
        $payload = self::verifyToken($token);
        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid token',
                'message' => 'Ongeldige authenticatie token'
            ]);
            exit();
        }
        
        return $payload;
    }

    public static function requireAdmin() {
        $payload = self::requireAuth();
        
        if (!$payload->is_admin) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Admin access required',
                'message' => 'Administrator rechten vereist'
            ]);
            exit();
        }
        
        return $payload;
    }
} 