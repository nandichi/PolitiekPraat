<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include necessary files
require_once dirname(__DIR__) . '/includes/config.php';

// API Documentation
$apiDocs = [
    'name' => 'PolitiekPraat API',
    'version' => '1.0.0',
    'description' => 'REST API voor PolitiekPraat website',
    'base_url' => URLROOT . '/api',
    'endpoints' => [
        'blogs' => [
            'url' => '/api/blogs.php',
            'methods' => ['GET'],
            'description' => 'Blog endpoints',
            'endpoints' => [
                'get_all_blogs' => [
                    'method' => 'GET',
                    'url' => '/api/blogs.php',
                    'description' => 'Haal alle blogs op',
                    'response' => [
                        'success' => 'boolean',
                        'data' => 'array van blog objecten',
                        'count' => 'aantal blogs',
                        'message' => 'string'
                    ]
                ],
                'get_blog_by_id' => [
                    'method' => 'GET',
                    'url' => '/api/blogs.php?id={blog_id}',
                    'description' => 'Haal specifieke blog op',
                    'parameters' => [
                        'id' => 'Blog ID (integer)'
                    ],
                    'response' => [
                        'success' => 'boolean',
                        'data' => 'blog object',
                        'message' => 'string'
                    ]
                ]
            ]
        ],
        'auth' => [
            'url' => '/api/auth.php',
            'methods' => ['POST'],
            'description' => 'Authenticatie endpoints',
            'endpoints' => [
                'login' => [
                    'method' => 'POST',
                    'url' => '/api/auth.php?action=login',
                    'description' => 'Inloggen met email en wachtwoord',
                    'body' => [
                        'email' => 'string (verplicht)',
                        'password' => 'string (verplicht)'
                    ],
                    'response' => [
                        'success' => 'boolean',
                        'data' => [
                            'token' => 'JWT token',
                            'user' => 'user object'
                        ],
                        'message' => 'string'
                    ]
                ],
                'register' => [
                    'method' => 'POST',
                    'url' => '/api/auth.php?action=register',
                    'description' => 'Registreren van nieuwe gebruiker',
                    'body' => [
                        'username' => 'string (verplicht)',
                        'email' => 'string (verplicht)',
                        'password' => 'string (verplicht)'
                    ],
                    'response' => [
                        'success' => 'boolean',
                        'data' => [
                            'token' => 'JWT token',
                            'user' => 'user object'
                        ],
                        'message' => 'string'
                    ]
                ],
                'verify' => [
                    'method' => 'POST',
                    'url' => '/api/auth.php?action=verify',
                    'description' => 'Verificeer JWT token en haal gebruiker op',
                    'headers' => [
                        'Authorization' => 'Bearer {token}'
                    ],
                    'response' => [
                        'success' => 'boolean',
                        'data' => 'user object',
                        'message' => 'string'
                    ]
                ]
            ]
        ],
        'stemwijzer' => [
            'url' => '/api/stemwijzer.php',
            'methods' => ['GET', 'POST'],
            'description' => 'Stemwijzer endpoints (bestaande)',
            'endpoints' => [
                'get_data' => [
                    'method' => 'GET',
                    'url' => '/api/stemwijzer.php?action=data',
                    'description' => 'Haal alle stemwijzer data op'
                ],
                'get_questions' => [
                    'method' => 'GET',
                    'url' => '/api/stemwijzer.php?action=questions',
                    'description' => 'Haal alleen vragen op'
                ],
                'get_parties' => [
                    'method' => 'GET',
                    'url' => '/api/stemwijzer.php?action=parties',
                    'description' => 'Haal alleen partijen op'
                ]
            ]
        ]
    ],
    'authentication' => [
        'type' => 'JWT (JSON Web Token)',
        'header' => 'Authorization: Bearer {token}',
        'token_lifetime' => '7 dagen',
        'note' => 'Tokens worden automatisch gegenereerd bij login/register'
    ],
    'error_codes' => [
        '200' => 'Success',
        '400' => 'Bad Request - Ongeldige parameters',
        '401' => 'Unauthorized - Authenticatie vereist of ongeldige token',
        '403' => 'Forbidden - Geen toegang',
        '404' => 'Not Found - Resource niet gevonden',
        '405' => 'Method Not Allowed - HTTP methode niet ondersteund',
        '500' => 'Internal Server Error - Server fout'
    ],
    'example_requests' => [
        'login' => [
            'method' => 'POST',
            'url' => URLROOT . '/api/auth.php?action=login',
            'headers' => ['Content-Type: application/json'],
            'body' => [
                'email' => 'gebruiker@example.com',
                'password' => 'wachtwoord123'
            ]
        ],
        'get_blogs' => [
            'method' => 'GET',
            'url' => URLROOT . '/api/blogs.php',
            'headers' => []
        ],
        'get_blog_by_id' => [
            'method' => 'GET',
            'url' => URLROOT . '/api/blogs.php?id=1',
            'headers' => []
        ],
        'verify_token' => [
            'method' => 'POST',
            'url' => URLROOT . '/api/auth.php?action=verify',
            'headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer {your_jwt_token}'
            ]
        ]
    ]
];

echo json_encode($apiDocs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 