<?php
// Test script voor blog upload functionaliteit
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/helpers.php';

echo "<h1>Blog Upload Test</h1>";
echo "<p>BASE_PATH: " . BASE_PATH . "</p>";
echo "<p>URLROOT: " . URLROOT . "</p>";
echo "<p>Server: " . $_SERVER['HTTP_HOST'] . "</p>";

// Controleer PHP upload instellingen
echo "<h2>PHP Upload Instellingen:</h2>";
echo "<p>upload_max_filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>post_max_size: " . ini_get('post_max_size') . "</p>";
echo "<p>max_file_uploads: " . ini_get('max_file_uploads') . "</p>";
echo "<p>file_uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</p>";
echo "<p>upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: 'Default') . "</p>";

// Controleer directories
echo "<h2>Directory Status:</h2>";
$upload_dir = BASE_PATH . '/uploads/blogs/images/';
echo "<p>Upload directory: " . $upload_dir . "</p>";
echo "<p>Directory exists: " . (file_exists($upload_dir) ? 'YES' : 'NO') . "</p>";
echo "<p>Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO') . "</p>";
echo "<p>Directory permissions: " . (file_exists($upload_dir) ? substr(sprintf('%o', fileperms($upload_dir)), -4) : 'N/A') . "</p>";

// Test upload form
echo "<h2>Test Upload:</h2>";
echo "<form method='POST' enctype='multipart/form-data'>";
echo "<p>Selecteer een afbeelding om te testen:</p>";
echo "<input type='file' name='test_image' accept='image/*' required>";
echo "<br><br>";
echo "<input type='submit' name='test_upload' value='Test Upload'>";
echo "</form>";

// Verwerk test upload
if (isset($_POST['test_upload']) && isset($_FILES['test_image'])) {
    echo "<h3>Upload Test Resultaat:</h3>";
    
    echo "<p><strong>Upload informatie:</strong></p>";
    echo "<p>Bestandsnaam: " . $_FILES['test_image']['name'] . "</p>";
    echo "<p>Bestandsgrootte: " . $_FILES['test_image']['size'] . " bytes</p>";
    echo "<p>Temp bestand: " . $_FILES['test_image']['tmp_name'] . "</p>";
    echo "<p>Upload error code: " . $_FILES['test_image']['error'] . "</p>";
    echo "<p>MIME type: " . $_FILES['test_image']['type'] . "</p>";
    
    if ($_FILES['test_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = BASE_PATH . '/uploads/blogs/images/';
        
        // Controleer of directory bestaat
        if (!file_exists($upload_dir)) {
            echo "<p style='color: red;'>Directory bestaat niet. Probeer aan te maken...</p>";
            if (mkdir($upload_dir, 0755, true)) {
                echo "<p style='color: green;'>Directory succesvol aangemaakt!</p>";
            } else {
                echo "<p style='color: red;'>Kon directory niet aanmaken!</p>";
            }
        }
        
        $file_extension = strtolower(pathinfo($_FILES['test_image']['name'], PATHINFO_EXTENSION));
        $allowed_images = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_images)) {
            $new_filename = 'test_' . uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $new_filename;
            
            echo "<p>Doel bestand: " . $target_path . "</p>";
            echo "<p>Temp bestand bestaat: " . (file_exists($_FILES['test_image']['tmp_name']) ? 'YES' : 'NO') . "</p>";
            
            if (move_uploaded_file($_FILES['test_image']['tmp_name'], $target_path)) {
                echo "<p style='color: green;'>✓ Upload SUCCESVOL!</p>";
                echo "<p>Bestand opgeslagen als: " . $new_filename . "</p>";
                echo "<p>Bestandsgrootte na upload: " . filesize($target_path) . " bytes</p>";
                echo "<p>Bestandspermissies: " . substr(sprintf('%o', fileperms($target_path)), -4) . "</p>";
                
                // Toon de afbeelding
                $relative_path = 'uploads/blogs/images/' . $new_filename;
                $image_url = URLROOT . '/' . $relative_path;
                echo "<p>Afbeelding URL: " . $image_url . "</p>";
                echo "<img src='" . $image_url . "' style='max-width: 200px; max-height: 200px;' alt='Test upload'>";
                
                // Test getBlogImageUrl functie
                $generated_url = getBlogImageUrl($relative_path);
                echo "<p>getBlogImageUrl result: " . $generated_url . "</p>";
                
                // Verwijder test bestand na 10 seconden
                echo "<p><em>Test bestand wordt automatisch verwijderd na weergave</em></p>";
                sleep(2);
                if (file_exists($target_path)) {
                    unlink($target_path);
                    echo "<p style='color: blue;'>Test bestand verwijderd</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Upload GEFAALD!</p>";
                
                // Meer gedetailleerde foutmeldingen
                echo "<p>Mogelijke oorzaken:</p>";
                echo "<ul>";
                echo "<li>Directory is niet schrijfbaar</li>";
                echo "<li>Schijfruimte vol</li>";
                echo "<li>PHP upload restrictions</li>";
                echo "<li>Bestandspad problemen</li>";
                echo "</ul>";
                
                // Probeer directory schrijfbaarheid te testen
                $test_file = $upload_dir . 'test_write.txt';
                if (file_put_contents($test_file, 'test')) {
                    echo "<p style='color: green;'>Directory IS schrijfbaar (test bestand aangemaakt)</p>";
                    unlink($test_file);
                } else {
                    echo "<p style='color: red;'>Directory is NIET schrijfbaar</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>Ongeldig bestandstype: " . $file_extension . "</p>";
        }
    } else {
        // Toon upload error
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'Bestand is groter dan upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'Bestand is groter dan MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'Bestand is slechts gedeeltelijk geüpload',
            UPLOAD_ERR_NO_FILE => 'Geen bestand geselecteerd',
            UPLOAD_ERR_NO_TMP_DIR => 'Tijdelijke map ontbreekt',
            UPLOAD_ERR_CANT_WRITE => 'Kan niet schrijven naar schijf',
            UPLOAD_ERR_EXTENSION => 'Upload gestopt door extensie'
        ];
        
        $error_msg = isset($upload_errors[$_FILES['test_image']['error']]) 
            ? $upload_errors[$_FILES['test_image']['error']] 
            : 'Onbekende upload fout';
            
        echo "<p style='color: red;'>Upload fout: " . $error_msg . " (Code: " . $_FILES['test_image']['error'] . ")</p>";
    }
}

// Toon recente blogs met afbeeldingen
echo "<h2>Recente blogs met afbeeldingen:</h2>";
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT id, title, image_path, created_at FROM blogs WHERE image_path IS NOT NULL AND image_path != '' ORDER BY created_at DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if (count($blogs) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Titel</th><th>Image Path</th><th>Bestand bestaat</th><th>URL</th></tr>";
        
        foreach ($blogs as $blog) {
            $full_path = BASE_PATH . '/' . $blog->image_path;
            $exists = file_exists($full_path);
            $url = getBlogImageUrl($blog->image_path);
            
            echo "<tr>";
            echo "<td>" . $blog->id . "</td>";
            echo "<td>" . htmlspecialchars(substr($blog->title, 0, 30)) . "...</td>";
            echo "<td style='font-size: 12px;'>" . htmlspecialchars($blog->image_path) . "</td>";
            echo "<td style='color: " . ($exists ? 'green' : 'red') . "'>" . ($exists ? 'JA' : 'NEE') . "</td>";
            echo "<td style='font-size: 12px;'>" . htmlspecialchars($url) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Geen blogs met afbeeldingen gevonden</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database fout: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><em>Verwijder dit bestand na het testen voor de veiligheid</em></p>";
?> 