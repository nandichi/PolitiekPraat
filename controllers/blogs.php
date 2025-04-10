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
            
            // Create blog post data
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'summary' => !empty($_POST['summary']) ? trim($_POST['summary']) : substr(strip_tags(trim($_POST['content'])), 0, 150) . '...',
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url
            ];
            
            // Create the blog post
            $blogId = $this->blogModel->create($data);
            
            if ($blogId) {
                // Send notifications to subscribers
                define('CALLED_FROM_BLOG_CONTROLLER', true);
                require_once BASE_PATH . '/controllers/newsletter.php';
                $newsletterController = new Newsletter();
                $newsletterController->sendNewBlogNotifications($blogId);
                
                // Redirect to blogs page
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
    
    public function manage() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        
        // Get current page from URL parameter
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Ensure page is at least 1
        if ($page < 1) {
            $page = 1;
        }
        
        // Set number of blogs per page
        $perPage = 5;
        
        // Get total blogs count for pagination
        $totalBlogs = $this->blogModel->getTotalBlogCountByUserId($_SESSION['user_id']);
        
        // Calculate total pages
        $totalPages = ceil($totalBlogs / $perPage);
        
        // Ensure page doesn't exceed total pages
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        // Get blogs for the current user with pagination
        $blogs = $this->blogModel->getAllByUserId($_SESSION['user_id'], $page, $perPage);
        
        // Pass pagination data to the view
        $paginationData = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'totalBlogs' => $totalBlogs
        ];
        
        require_once BASE_PATH . '/views/blogs/manage.php';
    }
    
    public function edit($id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        
        if (!$id) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        $blog = $this->blogModel->getById($id);
        
        // Check if blog exists and belongs to the current user
        if (!$blog || $blog->author_id != $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file uploads
            $image_path = $blog->image_path; // Keep existing image by default
            $video_path = $blog->video_path; // Keep existing video by default
            $video_url = $blog->video_url;   // Keep existing video URL by default

            // Handle new image upload if provided
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
                        // Delete old image if it exists
                        if (!empty($blog->image_path) && file_exists($blog->image_path)) {
                            unlink($blog->image_path);
                        }
                        $image_path = $target_path;
                    }
                }
            }

            // Handle new video upload if provided
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
                            // Delete old video if it exists
                            if (!empty($blog->video_path) && file_exists($blog->video_path)) {
                                unlink($blog->video_path);
                            }
                            $video_path = $target_path;
                        }
                    }
                }
            }

            // Handle updated video URL
            if (!empty($_POST['video_url'])) {
                $video_url = trim($_POST['video_url']);
                // Validate YouTube and Vimeo URLs
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url) ||
                    preg_match('/(?:vimeo\.com\/)([0-9]+)/', $video_url)) {
                    // URL is valid, store as is
                } else {
                    $video_url = ''; // Reset if URL is invalid
                }
            } else {
                $video_url = '';
            }
            
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url
            ];
            
            if ($this->blogModel->update($data)) {
                header('Location: ' . URLROOT . '/blogs/manage');
                exit;
            } else {
                // If something goes wrong, show the form again with an error message
                $error = 'Er is iets misgegaan bij het bijwerken van je blog. Probeer het opnieuw.';
                require_once BASE_PATH . '/views/blogs/edit.php';
                return;
            }
        }
        
        require_once BASE_PATH . '/views/blogs/edit.php';
    }
    
    public function delete($id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        
        if (!$id) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        // Check if blog exists and belongs to the current user
        $blog = $this->blogModel->getById($id);
        
        if (!$blog || $blog->author_id != $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        // Delete the blog
        if ($this->blogModel->delete($id)) {
            // Delete associated files if they exist
            if (!empty($blog->image_path) && file_exists($blog->image_path)) {
                unlink($blog->image_path);
            }
            
            if (!empty($blog->video_path) && file_exists($blog->video_path)) {
                unlink($blog->video_path);
            }
        }
        
        header('Location: ' . URLROOT . '/blogs/manage');
        exit;
    }
    
    public function updateLikes($id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        
        if (!$id) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        // Check if blog exists and belongs to the current user
        $blog = $this->blogModel->getById($id);
        
        if (!$blog || $blog->author_id != $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/blogs/manage');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['likes'])) {
                $likes = (int)$_POST['likes'];
                if ($this->blogModel->updateLikesDirectly($id, $likes)) {
                    header('Location: ' . URLROOT . '/blogs/manage');
                    exit;
                }
            }
        }
        
        // Als we hier komen, toon dan het formulier
        require_once BASE_PATH . '/views/blogs/update_likes.php';
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