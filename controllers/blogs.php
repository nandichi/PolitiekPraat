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
                $upload_dir = BASE_PATH . '/uploads/blogs/images/';
                $relative_upload_dir = 'uploads/blogs/images/';
                
                // Uitgebreide logging voor debugging
                error_log("Blog image upload attempt - BASE_PATH: " . BASE_PATH);
                error_log("Upload directory: " . $upload_dir);
                error_log("Directory exists: " . (file_exists($upload_dir) ? 'YES' : 'NO'));
                error_log("Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
                
                if (!file_exists($upload_dir)) {
                    $mkdir_result = mkdir($upload_dir, 0755, true);
                    error_log("Creating directory result: " . ($mkdir_result ? 'SUCCESS' : 'FAILED'));
                    if ($mkdir_result) {
                        chmod($upload_dir, 0755);
                        error_log("Directory permissions set to 0755");
                    }
                }
                
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed_images = ['jpg', 'jpeg', 'png', 'gif'];
                
                error_log("File extension: " . $file_extension);
                error_log("File size: " . $_FILES['image']['size']);
                error_log("Temp file: " . $_FILES['image']['tmp_name']);
                
                if (in_array($file_extension, $allowed_images)) {
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_path = $upload_dir . $new_filename;
                    
                    error_log("Target path: " . $target_path);
                    error_log("Temp file exists: " . (file_exists($_FILES['image']['tmp_name']) ? 'YES' : 'NO'));
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                        // Store the relative path in database for compatibility
                        $image_path = $relative_upload_dir . $new_filename;
                        
                        // Set correct file permissions
                        chmod($target_path, 0644);
                        
                        // Debug: Log the upload success
                        error_log("Blog image uploaded successfully: " . $image_path);
                        error_log("File size after upload: " . filesize($target_path));
                    } else {
                        // Debug: Log upload failure with more details
                        error_log("Blog image upload failed for file: " . $_FILES['image']['name']);
                        error_log("Upload error details - Source: " . $_FILES['image']['tmp_name'] . ", Target: " . $target_path);
                        error_log("Last error: " . error_get_last()['message']);
                    }
                } else {
                    error_log("Invalid file extension: " . $file_extension . " for file: " . $_FILES['image']['name']);
                }
            } else if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Log upload errors
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                $error_msg = isset($upload_errors[$_FILES['image']['error']]) ? $upload_errors[$_FILES['image']['error']] : 'Unknown upload error';
                error_log("Blog image upload error: " . $error_msg . " (Code: " . $_FILES['image']['error'] . ")");
            }

            // Handle video upload
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/videos/';
                $relative_upload_dir = 'uploads/blogs/videos/';
                
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
                            // Store the relative path in database for compatibility
                            $video_path = $relative_upload_dir . $new_filename;
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

            // Handle audio upload voor tekst-naar-spraak
            $audio_path = '';
            $audio_url = '';
            
            // Handle Google Drive audio URL
            if (!empty($_POST['audio_url'])) {
                $audio_url = trim($_POST['audio_url']);
                // Valideer Google Drive URLs
                if (strpos($audio_url, 'drive.google.com') !== false) {
                    // Extracteer file ID uit Google Drive URL
                    if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $audio_url, $matches) || 
                        preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $audio_url, $matches)) {
                        // URL is geldig, bewaar originele URL
                    } else {
                        $audio_url = ''; // Reset als Google Drive URL ongeldig is
                    }
                } else {
                    $audio_url = ''; // Reset als het geen Google Drive URL is
                }
            }

            // Handle SoundCloud audio URL
            $soundcloud_url = '';
            if (!empty($_POST['soundcloud_url'])) {
                $soundcloud_url = trim($_POST['soundcloud_url']);
                // Valideer SoundCloud URLs
                if (strpos($soundcloud_url, 'soundcloud.com') !== false) {
                    // Basis validatie voor SoundCloud URLs
                    if (preg_match('/^https:\/\/(www\.|m\.)?soundcloud\.com\/[^\/]+\/[^\/]+/', $soundcloud_url) ||
                        preg_match('/^https:\/\/(www\.|m\.)?soundcloud\.com\/[^\/]+\/sets\/[^\/]+/', $soundcloud_url)) {
                        // URL is geldig, bewaar originele URL
                    } else {
                        $soundcloud_url = ''; // Reset als SoundCloud URL ongeldig is
                    }
                } else {
                    $soundcloud_url = ''; // Reset als het geen SoundCloud URL is
                }
            }
            
            // Handle lokaal audio bestand upload (alleen als geen URL is opgegeven)
            if (empty($audio_url) && isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/audio/';
                $relative_upload_dir = 'uploads/blogs/audio/';
                
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
                $allowed_audio = ['mp3', 'wav', 'ogg'];
                
                if (in_array($file_extension, $allowed_audio)) {
                    // Check bestandsgrootte (max 50MB)
                    if ($_FILES['audio']['size'] <= 50 * 1024 * 1024) {
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['audio']['tmp_name'], $target_path)) {
                            // Store the relative path in database for compatibility
                            $audio_path = $relative_upload_dir . $new_filename;
                        }
                    }
                }
            }
            
            // Create blog post data
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'summary' => !empty($_POST['summary']) ? trim($_POST['summary']) : substr(strip_tags(trim($_POST['content'])), 0, 150) . '...',
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url,
                'audio_path' => $audio_path,
                'audio_url' => $audio_url,
                'soundcloud_url' => $soundcloud_url
            ];
            
            // Debug: Log what will be saved to database
            error_log("Blog data to save - Image path: " . $image_path . ", Video path: " . $video_path . ", Audio path: " . $audio_path);
            
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
                $upload_dir = BASE_PATH . '/uploads/blogs/images/';
                $relative_upload_dir = 'uploads/blogs/images/';
                
                // Uitgebreide logging voor debugging (edit functie)
                error_log("Blog image edit upload attempt - BASE_PATH: " . BASE_PATH);
                error_log("Upload directory: " . $upload_dir);
                error_log("Directory exists: " . (file_exists($upload_dir) ? 'YES' : 'NO'));
                error_log("Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
                
                if (!file_exists($upload_dir)) {
                    $mkdir_result = mkdir($upload_dir, 0755, true);
                    error_log("Creating directory result: " . ($mkdir_result ? 'SUCCESS' : 'FAILED'));
                    if ($mkdir_result) {
                        chmod($upload_dir, 0755);
                        error_log("Directory permissions set to 0755");
                    }
                }
                
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed_images = ['jpg', 'jpeg', 'png', 'gif'];
                
                error_log("File extension: " . $file_extension);
                error_log("File size: " . $_FILES['image']['size']);
                error_log("Temp file: " . $_FILES['image']['tmp_name']);
                
                if (in_array($file_extension, $allowed_images)) {
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_path = $upload_dir . $new_filename;
                    
                    error_log("Target path: " . $target_path);
                    error_log("Temp file exists: " . (file_exists($_FILES['image']['tmp_name']) ? 'YES' : 'NO'));
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                        // Delete old image if it exists  
                        if (!empty($blog->image_path) && file_exists(BASE_PATH . '/' . $blog->image_path)) {
                            unlink(BASE_PATH . '/' . $blog->image_path);
                            error_log("Old image deleted: " . $blog->image_path);
                        }
                        // Store the relative path in database for compatibility
                        $image_path = $relative_upload_dir . $new_filename;
                        
                        // Set correct file permissions
                        chmod($target_path, 0644);
                        
                        error_log("Blog image edit uploaded successfully: " . $image_path);
                        error_log("File size after upload: " . filesize($target_path));
                    } else {
                        error_log("Blog image edit upload failed for file: " . $_FILES['image']['name']);
                        error_log("Upload error details - Source: " . $_FILES['image']['tmp_name'] . ", Target: " . $target_path);
                        error_log("Last error: " . error_get_last()['message']);
                    }
                } else {
                    error_log("Invalid file extension: " . $file_extension . " for file: " . $_FILES['image']['name']);
                }
            } else if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Log upload errors for edit
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                $error_msg = isset($upload_errors[$_FILES['image']['error']]) ? $upload_errors[$_FILES['image']['error']] : 'Unknown upload error';
                error_log("Blog image edit upload error: " . $error_msg . " (Code: " . $_FILES['image']['error'] . ")");
            }

            // Handle new video upload if provided
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/videos/';
                $relative_upload_dir = 'uploads/blogs/videos/';
                
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
                            if (!empty($blog->video_path) && file_exists(BASE_PATH . '/' . $blog->video_path)) {
                                unlink(BASE_PATH . '/' . $blog->video_path);
                            }
                            // Store the relative path in database for compatibility
                            $video_path = $relative_upload_dir . $new_filename;
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

            // Handle audio updates
            $audio_path = isset($blog->audio_path) ? $blog->audio_path : ''; // Keep existing audio by default
            $audio_url = isset($blog->audio_url) ? $blog->audio_url : ''; // Keep existing audio URL by default
            $soundcloud_url = isset($blog->soundcloud_url) ? $blog->soundcloud_url : ''; // Keep existing SoundCloud URL by default
            
            // Handle updated Google Drive audio URL
            if (!empty($_POST['audio_url'])) {
                $audio_url = trim($_POST['audio_url']);
                // Valideer Google Drive URLs
                if (strpos($audio_url, 'drive.google.com') !== false) {
                    // Extracteer file ID uit Google Drive URL
                    if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $audio_url, $matches) || 
                        preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $audio_url, $matches)) {
                        // URL is geldig, bewaar originele URL
                        // Reset lokaal audio bestand als URL wordt gebruikt
                        if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                            unlink(BASE_PATH . '/' . $blog->audio_path);
                        }
                        $audio_path = '';
                    } else {
                        $audio_url = ''; // Reset als Google Drive URL ongeldig is
                    }
                } else {
                    $audio_url = ''; // Reset als het geen Google Drive URL is
                }
            } else {
                $audio_url = '';
            }

            // Handle updated SoundCloud audio URL
            if (!empty($_POST['soundcloud_url'])) {
                $soundcloud_url = trim($_POST['soundcloud_url']);
                // Valideer SoundCloud URLs
                if (strpos($soundcloud_url, 'soundcloud.com') !== false) {
                    // Basis validatie voor SoundCloud URLs
                    if (preg_match('/^https:\/\/(www\.|m\.)?soundcloud\.com\/[^\/]+\/[^\/]+/', $soundcloud_url) ||
                        preg_match('/^https:\/\/(www\.|m\.)?soundcloud\.com\/[^\/]+\/sets\/[^\/]+/', $soundcloud_url)) {
                        // URL is geldig, bewaar originele URL
                        // Reset lokaal audio bestand en Google Drive URL als SoundCloud wordt gebruikt
                        if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                            unlink(BASE_PATH . '/' . $blog->audio_path);
                        }
                        $audio_path = '';
                        $audio_url = '';
                    } else {
                        $soundcloud_url = ''; // Reset als SoundCloud URL ongeldig is
                    }
                } else {
                    $soundcloud_url = ''; // Reset als het geen SoundCloud URL is
                }
            } else {
                $soundcloud_url = '';
            }
            
            // Handle lokaal audio bestand upload (alleen als geen URL is opgegeven)
            if (empty($audio_url) && isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/audio/';
                $relative_upload_dir = 'uploads/blogs/audio/';
                
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
                $allowed_audio = ['mp3', 'wav', 'ogg'];
                
                if (in_array($file_extension, $allowed_audio)) {
                    // Check bestandsgrootte (max 50MB)
                    if ($_FILES['audio']['size'] <= 50 * 1024 * 1024) {
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['audio']['tmp_name'], $target_path)) {
                            // Delete old audio if it exists
                            if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                                unlink(BASE_PATH . '/' . $blog->audio_path);
                            }
                            // Store the relative path in database for compatibility
                            $audio_path = $relative_upload_dir . $new_filename;
                            $audio_url = ''; // Reset URL als lokaal bestand wordt gebruikt
                        }
                    }
                }
            }
            
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url,
                'audio_path' => $audio_path,
                'audio_url' => $audio_url,
                'soundcloud_url' => $soundcloud_url
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
            if (!empty($blog->image_path) && file_exists(BASE_PATH . '/' . $blog->image_path)) {
                unlink(BASE_PATH . '/' . $blog->image_path);
            }
            
            if (!empty($blog->video_path) && file_exists(BASE_PATH . '/' . $blog->video_path)) {
                unlink(BASE_PATH . '/' . $blog->video_path);
            }
            
            if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                unlink(BASE_PATH . '/' . $blog->audio_path);
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