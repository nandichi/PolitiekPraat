<?php
class BlogsController {
    private $blogModel;
    
    public function __construct() {
        $this->blogModel = new BlogController();
    }
    
    public function index() {
        $blogs = $this->blogModel->getAll();
        require_once BASE_PATH . '/views/blogs/index.php';
    }
    
    public function create() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file uploads
            $image_path = '';
            $video_path = '';
            $video_url = '';

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/blogs/images/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed_images = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($file_extension, $allowed_images)) {
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_path = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                        $image_path = $target_path;
                    }
                }
            }

            // Handle video upload
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/blogs/videos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
                $allowed_videos = ['mp4', 'webm', 'ogg'];
                
                if (in_array($file_extension, $allowed_videos)) {
                    // Check file size (max 100MB)
                    if ($_FILES['video']['size'] <= 100 * 1024 * 1024) {
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['video']['tmp_name'], $target_path)) {
                            $video_path = $target_path;
                        }
                    }
                }
            }

            // Handle video URL
            if (!empty($_POST['video_url'])) {
                $video_url = trim($_POST['video_url']);
                // Valideer YouTube en Vimeo URLs
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url) ||
                    preg_match('/(?:vimeo\.com\/)([0-9]+)/', $video_url)) {
                    // URL is geldig, we slaan hem op zoals hij is
                } else {
                    $video_url = ''; // Reset als URL ongeldig is
                }
            }
            
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url
            ];
            
            if ($this->blogModel->create($data)) {
                header('Location: ' . URLROOT . '/blogs');
                exit;
            } else {
                // Als er iets misgaat, toon dan het formulier opnieuw met een foutmelding
                $error = 'Er is iets misgegaan bij het opslaan van je blog. Probeer het opnieuw.';
                require_once BASE_PATH . '/views/blogs/create.php';
                return;
            }
        }
        
        require_once BASE_PATH . '/views/blogs/create.php';
    }
    
    public function view($slug) {
        $blog = $this->blogModel->getBySlug($slug);
        
        if (!$blog) {
            header('Location: ' . URLROOT . '/404');
            exit;
        }
        
        require_once BASE_PATH . '/views/blogs/view.php';
    }
    
    public function handleLike($slug) {
        header('Content-Type: application/json');
        
        // Lees de JSON body
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $action = $data->action ?? 'like';
        
        // Valideer de action
        if (!in_array($action, ['like', 'unlike'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ongeldige actie']);
            return;
        }
        
        $result = $this->blogModel->updateLikes($slug, $action);
        
        if ($result !== false) {
            echo json_encode(['success' => true, 'likes' => $result]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Kon like niet verwerken']);
        }
    }
} 