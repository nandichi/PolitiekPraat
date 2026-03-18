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

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $perPage = 9;
        $totalBlogs = $this->blogModel->getTotalBlogCount($categoryId);
        $totalPages = (int) ceil($totalBlogs / $perPage);

        if ($totalPages > 0 && $currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        // Get blogs with optional category filtering
        $blogs = $this->blogModel->getPaginated($currentPage, $perPage, $categoryId);

        $paginationData = [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'totalBlogs' => $totalBlogs,
        ];

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
                $upload_result = store_uploaded_media(
                    $_FILES['image'],
                    BASE_PATH . '/uploads/blogs/images/',
                    'uploads/blogs/images/',
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
                );

                if ($upload_result['ok']) {
                    $image_path = $upload_result['path'];
                } else {
                    error_log('Blog image upload rejected: ' . $upload_result['error']);
                }
            }

            // Handle video upload
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $upload_result = store_uploaded_media(
                    $_FILES['video'],
                    BASE_PATH . '/uploads/blogs/videos/',
                    'uploads/blogs/videos/',
                    ['mp4', 'webm', 'ogg'],
                    ['video/mp4', 'video/webm', 'video/ogg', 'application/ogg'],
                    100 * 1024 * 1024
                );

                if ($upload_result['ok']) {
                    $video_path = $upload_result['path'];
                } else {
                    error_log('Blog video upload rejected: ' . $upload_result['error']);
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

            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_result = store_uploaded_media(
                    $_FILES['audio'],
                    BASE_PATH . '/uploads/blogs/audio/',
                    'uploads/blogs/audio/',
                    ['mp3', 'wav', 'ogg'],
                    ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'application/ogg'],
                    100 * 1024 * 1024
                );

                if ($upload_result['ok']) {
                    $audio_path = $upload_result['path'];
                } else {
                    error_log('Blog audio upload rejected: ' . $upload_result['error']);
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
                
                // Trigger asynchrone sitemap-regeneratie (non-blocking)
                $this->queueSitemapRegeneration();
                
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
                $upload_result = store_uploaded_media(
                    $_FILES['image'],
                    BASE_PATH . '/uploads/blogs/images/',
                    'uploads/blogs/images/',
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
                );

                if ($upload_result['ok']) {
                    if (!empty($blog->image_path) && file_exists(BASE_PATH . '/' . $blog->image_path)) {
                        unlink(BASE_PATH . '/' . $blog->image_path);
                    }
                    $image_path = $upload_result['path'];
                } else {
                    error_log('Blog image edit upload rejected: ' . $upload_result['error']);
                }
            }

            // Handle new video upload if provided
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $upload_result = store_uploaded_media(
                    $_FILES['video'],
                    BASE_PATH . '/uploads/blogs/videos/',
                    'uploads/blogs/videos/',
                    ['mp4', 'webm', 'ogg'],
                    ['video/mp4', 'video/webm', 'video/ogg', 'application/ogg'],
                    100 * 1024 * 1024
                );

                if ($upload_result['ok']) {
                    if (!empty($blog->video_path) && file_exists(BASE_PATH . '/' . $blog->video_path)) {
                        unlink(BASE_PATH . '/' . $blog->video_path);
                    }
                    $video_path = $upload_result['path'];
                } else {
                    error_log('Blog video edit upload rejected: ' . $upload_result['error']);
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

            // Handle remove_audio checkbox
            if (isset($_POST['remove_audio']) && $_POST['remove_audio'] === 'on') {
                if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                    unlink(BASE_PATH . '/' . $blog->audio_path);
                }
                $audio_path = '';
            }

            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload_result = store_uploaded_media(
                    $_FILES['audio'],
                    BASE_PATH . '/uploads/blogs/audio/',
                    'uploads/blogs/audio/',
                    ['mp3', 'wav', 'ogg'],
                    ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'application/ogg'],
                    100 * 1024 * 1024
                );

                if ($upload_result['ok']) {
                    if (!empty($blog->audio_path) && file_exists(BASE_PATH . '/' . $blog->audio_path)) {
                        unlink(BASE_PATH . '/' . $blog->audio_path);
                    }
                    $audio_path = $upload_result['path'];
                } else {
                    error_log('Blog audio edit upload rejected: ' . $upload_result['error']);
                }
            }
            
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
                // Trigger asynchrone sitemap-regeneratie na update (non-blocking)
                $this->queueSitemapRegeneration();
                
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
            
            // Trigger asynchrone sitemap-regeneratie na verwijderen (non-blocking)
            $this->queueSitemapRegeneration();
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
    
    private function queueSitemapRegeneration() {
        $queueDir = BASE_PATH . '/storage/queues';
        $queueFile = $queueDir . '/sitemap-regenerate.json';

        try {
            if (!is_dir($queueDir) && !mkdir($queueDir, 0755, true) && !is_dir($queueDir)) {
                throw new RuntimeException('Kon sitemap queue map niet aanmaken');
            }

            $payload = [
                'queued_at' => date('c'),
                'source' => 'blogs_controller',
            ];

            file_put_contents($queueFile, json_encode($payload, JSON_UNESCAPED_SLASHES));
            error_log('Sitemap regeneratie in queue gezet');

            // Fire-and-forget worker; faalt dit, dan kan cron scripts/process_sitemap_queue.php oppakken.
            $worker = escapeshellarg(BASE_PATH . '/scripts/process_sitemap_queue.php');
            @shell_exec('php ' . $worker . ' > /dev/null 2>&1 &');
        } catch (Throwable $e) {
            // Nooit blog-publicatie blokkeren
            error_log('Kon sitemap regeneratie niet in queue zetten: ' . $e->getMessage());
        }
    }
}
