<?php
class BlogsController {
    private $blogModel;
    
    public function __construct() {
        $this->blogModel = new BlogController();
    }
    
    public function index() {
        // Check for category filtering via query parameter
        $categoryId = null;
        $selectedCategory = null;
        
        if (isset($_GET['category'])) {
            $categoryController = new CategoryController();
            $selectedCategory = $categoryController->getBySlug($_GET['category']);
            
            if ($selectedCategory) {
                $categoryId = $selectedCategory->id;
            }
        }
        
        // Get blogs with optional category filtering
        $blogs = $this->blogModel->getAll(null, $categoryId);
        
        require_once BASE_PATH . '/views/blogs/index.php';
    }
    
    public function create() {
        // Set PHP upload limits as fallback for development server
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        @ini_set('memory_limit', '256M');
        
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

            // Handle audio upload voor podcast
            $audio_path = '';
            
            // Debug: Log audio upload attempt
            error_log("=== AUDIO UPLOAD DEBUG START ===");
            error_log("Audio file isset: " . (isset($_FILES['audio']) ? 'YES' : 'NO'));
            if (isset($_FILES['audio'])) {
                error_log("Audio file data: " . print_r($_FILES['audio'], true));
            }
            
            // Handle lokaal audio bestand upload
            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/audio/';
                $relative_upload_dir = 'uploads/blogs/audio/';
                
                // Uitgebreide logging voor debugging
                error_log("Audio upload attempt - BASE_PATH: " . BASE_PATH);
                error_log("Audio upload directory: " . $upload_dir);
                error_log("Directory exists: " . (file_exists($upload_dir) ? 'YES' : 'NO'));
                
                if (!file_exists($upload_dir)) {
                    $mkdir_result = mkdir($upload_dir, 0777, true);
                    error_log("Creating audio directory result: " . ($mkdir_result ? 'SUCCESS' : 'FAILED'));
                    if ($mkdir_result) {
                        chmod($upload_dir, 0777);
                        error_log("Audio directory permissions set to 0777");
                    }
                }
                
                error_log("Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
                
                $file_extension = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
                $allowed_audio = ['mp3', 'wav', 'ogg'];
                
                error_log("Audio file extension: " . $file_extension);
                error_log("Audio file size: " . $_FILES['audio']['size'] . " bytes");
                error_log("Audio temp file: " . $_FILES['audio']['tmp_name']);
                error_log("Audio temp file exists: " . (file_exists($_FILES['audio']['tmp_name']) ? 'YES' : 'NO'));
                
                if (in_array($file_extension, $allowed_audio)) {
                    // Check bestandsgrootte (max 100MB)
                    if ($_FILES['audio']['size'] <= 100 * 1024 * 1024) {
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        error_log("Audio target path: " . $target_path);
                        
                        if (move_uploaded_file($_FILES['audio']['tmp_name'], $target_path)) {
                            // Store the relative path in database for compatibility
                            $audio_path = $relative_upload_dir . $new_filename;
                            
                            // Set correct file permissions
                            chmod($target_path, 0644);
                            
                            error_log("Audio uploaded successfully: " . $audio_path);
                            error_log("Audio file size after upload: " . filesize($target_path));
                        } else {
                            error_log("Audio upload FAILED for file: " . $_FILES['audio']['name']);
                            error_log("Upload error details - Source: " . $_FILES['audio']['tmp_name'] . ", Target: " . $target_path);
                            $last_error = error_get_last();
                            if ($last_error) {
                                error_log("Last error: " . $last_error['message']);
                            }
                        }
                    } else {
                        error_log("Audio file too large: " . $_FILES['audio']['size'] . " bytes (max 100MB)");
                    }
                } else {
                    error_log("Invalid audio file extension: " . $file_extension . " for file: " . $_FILES['audio']['name']);
                }
            } else if (isset($_FILES['audio']) && $_FILES['audio']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Log upload errors
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                ];
                $error_code = $_FILES['audio']['error'];
                $error_message = isset($upload_errors[$error_code]) ? $upload_errors[$error_code] : 'Unknown upload error';
                error_log("Audio upload error (code $error_code): $error_message");
            } else {
                error_log("No audio file uploaded or UPLOAD_ERR_NO_FILE");
            }
            error_log("=== AUDIO UPLOAD DEBUG END ===");
            
            // Create blog post data
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'summary' => !empty($_POST['summary']) ? trim($_POST['summary']) : substr(strip_tags(trim($_POST['content'])), 0, 150) . '...',
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url,
                'audio_path' => $audio_path,
                'audio_url' => '', // Legacy field, no longer used
                'soundcloud_url' => '', // Legacy field, no longer used
                'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
                // Poll data
                'enable_poll' => isset($_POST['enable_poll']),
                'poll_question' => trim($_POST['poll_question'] ?? ''),
                'poll_option_a' => trim($_POST['poll_option_a'] ?? ''),
                'poll_option_b' => trim($_POST['poll_option_b'] ?? '')
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
                
                // Automatically regenerate sitemap
                $this->regenerateSitemap();
                
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
        // Set PHP upload limits as fallback for development server
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        @ini_set('memory_limit', '256M');
        
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
        
        // Haal categorieën op voor het formulier
        $categoryController = new CategoryController();
        $categories = $categoryController->getAll();
        
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
            
            // Debug: Log audio upload attempt for edit
            error_log("=== AUDIO UPLOAD DEBUG (EDIT) START ===");
            error_log("Audio file isset: " . (isset($_FILES['audio']) ? 'YES' : 'NO'));
            error_log("Existing audio_path: " . $audio_path);
            if (isset($_FILES['audio'])) {
                error_log("Audio file data: " . print_r($_FILES['audio'], true));
            }
            
            // Handle remove_audio checkbox
            if (isset($_POST['remove_audio']) && $_POST['remove_audio'] === 'on') {
                // Delete existing audio file
                if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                    unlink(BASE_PATH . '/' . $blog->audio_path);
                    error_log("Deleted existing audio file: " . $blog->audio_path);
                }
                $audio_path = '';
            }
            
            // Handle lokaal audio bestand upload
            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = BASE_PATH . '/uploads/blogs/audio/';
                $relative_upload_dir = 'uploads/blogs/audio/';
                
                // Uitgebreide logging voor debugging
                error_log("Audio upload attempt (edit) - BASE_PATH: " . BASE_PATH);
                error_log("Audio upload directory: " . $upload_dir);
                error_log("Directory exists: " . (file_exists($upload_dir) ? 'YES' : 'NO'));
                
                if (!file_exists($upload_dir)) {
                    $mkdir_result = mkdir($upload_dir, 0777, true);
                    error_log("Creating audio directory result: " . ($mkdir_result ? 'SUCCESS' : 'FAILED'));
                    if ($mkdir_result) {
                        chmod($upload_dir, 0777);
                        error_log("Audio directory permissions set to 0777");
                    }
                }
                
                error_log("Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
                
                $file_extension = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
                $allowed_audio = ['mp3', 'wav', 'ogg'];
                
                error_log("Audio file extension: " . $file_extension);
                error_log("Audio file size: " . $_FILES['audio']['size'] . " bytes");
                error_log("Audio temp file: " . $_FILES['audio']['tmp_name']);
                error_log("Audio temp file exists: " . (file_exists($_FILES['audio']['tmp_name']) ? 'YES' : 'NO'));
                
                if (in_array($file_extension, $allowed_audio)) {
                    // Check bestandsgrootte (max 100MB)
                    if ($_FILES['audio']['size'] <= 100 * 1024 * 1024) {
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        error_log("Audio target path: " . $target_path);
                        
                        if (move_uploaded_file($_FILES['audio']['tmp_name'], $target_path)) {
                            // Delete old audio if it exists
                            if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                                unlink(BASE_PATH . '/' . $blog->audio_path);
                                error_log("Deleted old audio file: " . $blog->audio_path);
                            }
                            // Store the relative path in database for compatibility
                            $audio_path = $relative_upload_dir . $new_filename;
                            
                            // Set correct file permissions
                            chmod($target_path, 0644);
                            
                            error_log("Audio uploaded successfully: " . $audio_path);
                            error_log("Audio file size after upload: " . filesize($target_path));
                        } else {
                            error_log("Audio upload FAILED for file: " . $_FILES['audio']['name']);
                            error_log("Upload error details - Source: " . $_FILES['audio']['tmp_name'] . ", Target: " . $target_path);
                            $last_error = error_get_last();
                            if ($last_error) {
                                error_log("Last error: " . $last_error['message']);
                            }
                        }
                    } else {
                        error_log("Audio file too large: " . $_FILES['audio']['size'] . " bytes (max 100MB)");
                    }
                } else {
                    error_log("Invalid audio file extension: " . $file_extension . " for file: " . $_FILES['audio']['name']);
                }
            } else if (isset($_FILES['audio']) && $_FILES['audio']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Log upload errors
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                ];
                $error_code = $_FILES['audio']['error'];
                $error_message = isset($upload_errors[$error_code]) ? $upload_errors[$error_code] : 'Unknown upload error';
                error_log("Audio upload error (code $error_code): $error_message");
            } else {
                error_log("No audio file uploaded or UPLOAD_ERR_NO_FILE");
            }
            error_log("=== AUDIO UPLOAD DEBUG (EDIT) END ===");
            
            // Handle category update
            $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
            
            // Handle poll updates
            $pollQuestion = isset($_POST['poll_question']) ? trim($_POST['poll_question']) : '';
            $pollOptionA = isset($_POST['poll_option_a']) ? trim($_POST['poll_option_a']) : '';
            $pollOptionB = isset($_POST['poll_option_b']) ? trim($_POST['poll_option_b']) : '';
            $deletePoll = isset($_POST['delete_poll']) && $_POST['delete_poll'] === 'on';
            $enablePoll = isset($_POST['enable_poll']) && $_POST['enable_poll'] === 'on';
            
            // Check if blog already has a poll
            $existingPoll = $this->blogModel->getPollByBlogId($id);
            
            if ($deletePoll && $existingPoll) {
                // Delete existing poll
                $this->blogModel->deletePoll($existingPoll->id);
            } elseif (!empty($pollQuestion) && !empty($pollOptionA) && !empty($pollOptionB)) {
                if ($existingPoll) {
                    // Update existing poll
                    $this->blogModel->updatePoll($existingPoll->id, $pollQuestion, $pollOptionA, $pollOptionB);
                } else {
                    // Create new poll
                    $this->blogModel->createPoll($id, $pollQuestion, $pollOptionA, $pollOptionB);
                }
            }

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'category_id' => $category_id,
                'image_path' => $image_path,
                'video_path' => $video_path,
                'video_url' => $video_url,
                'audio_path' => $audio_path,
                'audio_url' => '', // Legacy field, no longer used
                'soundcloud_url' => '' // Legacy field, no longer used
            ];
            
            if ($this->blogModel->update($data)) {
                // Automatically regenerate sitemap after blog update
                $this->regenerateSitemap();
                
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
            
            // Automatically regenerate sitemap after blog deletion
            $this->regenerateSitemap();
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
    
    private function regenerateSitemap() {
        try {
            // Voer het sitemap generatie script uit
            $output = shell_exec('cd ' . BASE_PATH . ' && php generate-sitemap.php > sitemap.xml 2>&1');
            
            // Log voor debugging
            error_log("Sitemap automatically regenerated after new blog post");
            
            // Optioneel: ping Google om de nieuwe sitemap te melden
            $this->pingGoogleSitemap();
            
        } catch (Exception $e) {
            // Log de fout maar laat de blog posting niet falen
            error_log("Failed to regenerate sitemap: " . $e->getMessage());
        }
    }
    
    private function pingGoogleSitemap() {
        try {
            // Ping Google Search Console om nieuwe sitemap te melden
            $sitemapUrl = urlencode('https://politiekpraat.nl/sitemap.xml');
            $pingUrl = "https://www.google.com/ping?sitemap=" . $sitemapUrl;
            
            // Maak een non-blocking HTTP request
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // 5 seconden timeout
                    'ignore_errors' => true
                ]
            ]);
            
            $result = file_get_contents($pingUrl, false, $context);
            error_log("Google sitemap ping sent successfully");
            
        } catch (Exception $e) {
            error_log("Failed to ping Google sitemap: " . $e->getMessage());
        }
    }
} 