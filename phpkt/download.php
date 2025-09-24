<?php
// Security check to prevent directory traversal
function isValidFile($filename) {
    // Only allow specific files for download
    $allowedFiles = [
        'reklaam/image.png',
        'reklaam/instagram-1.jpg', 
        'reklaam/instagram-4.jpg',
        'MOCK_DATA.csv',
        'orders.txt'
    ];
    
    return in_array($filename, $allowedFiles);
}

if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    
    // Validate the filename
    if (!isValidFile($filename)) {
        die('Access denied');
    }
    
    $filepath = __DIR__ . '/' . $filename;
    
    // Check if file exists
    if (!file_exists($filepath)) {
        die('File not found');
    }
    
    // Get file info
    $filesize = filesize($filepath);
    $filename_display = basename($filename);
    
    // Set headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename_display . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $filesize);
    
    // Clear output buffer
    ob_clean();
    flush();
    
    // Read and output file
    readfile($filepath);
    exit;
} else {
    die('No file specified');
}
?>