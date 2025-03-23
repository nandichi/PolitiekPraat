<?php
/**
 * Sitemap Generator Cron Script
 * 
 * This script can be called by a cron job to automatically regenerate
 * the sitemap.xml file. Recommended to run daily.
 * 
 * Example cron entry (daily at 3 AM):
 * 0 3 * * * php /path/to/your/site/cron-generate-sitemap.php
 */

// Define constants
define('BASE_PATH', dirname(__FILE__));

// Change to the base directory
chdir(BASE_PATH);

// Include the generate-sitemap.php script
$output = [];
$return_var = 0;

// Run the script and capture output
ob_start();
include_once BASE_PATH . '/generate-sitemap.php';
$output = ob_get_clean();

// Write a log entry
$log_entry = date('Y-m-d H:i:s') . " - Sitemap generated\n";
file_put_contents(BASE_PATH . '/logs/sitemap-generator.log', $log_entry, FILE_APPEND);

// Exit with success
exit(0); 