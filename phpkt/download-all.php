<?php
// download-all.php - Create and download a ZIP file with all available files

// Check if ZIP extension is available
if (!extension_loaded('zip')) {
    die('ZIP extension is not available on this server.');
}

// Define the files to include
$files = [
    'reklaam/image.png' => 'images/main-image.png',
    'reklaam/instagram-1.jpg' => 'images/instagram-1.jpg', 
    'reklaam/instagram-4.jpg' => 'images/instagram-4.jpg',
    'MOCK_DATA.csv' => 'data/mock-data.csv',
    'orders.txt' => 'data/orders.txt'
];

// Create a temporary ZIP file
$zipFileName = 'phpkt-files-' . date('Y-m-d-H-i-s') . '.zip';
$zipPath = sys_get_temp_dir() . '/' . $zipFileName;

$zip = new ZipArchive();
$result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

if ($result !== TRUE) {
    die('Cannot create ZIP file. Error: ' . $result);
}

$filesAdded = 0;

// Add files to ZIP
foreach ($files as $source => $destination) {
    $sourcePath = __DIR__ . '/' . $source;
    
    if (file_exists($sourcePath)) {
        $zip->addFile($sourcePath, $destination);
        $filesAdded++;
    }
}

// Add a readme file with information
$readmeContent = "PHPKT Files Download\n";
$readmeContent .= "==================\n\n";
$readmeContent .= "Downloaded on: " . date('Y-m-d H:i:s') . "\n";
$readmeContent .= "Files included: " . $filesAdded . "\n\n";
$readmeContent .= "Contents:\n";
$readmeContent .= "- images/: Gallery and promotional images\n";
$readmeContent .= "- data/: CSV and text data files\n\n";
$readmeContent .= "For more information, visit the phpkt website.\n";

$zip->addFromString('README.txt', $readmeContent);

$zip->close();

// Check if ZIP was created successfully
if (!file_exists($zipPath)) {
    die('Failed to create ZIP file.');
}

// Get file size
$fileSize = filesize($zipPath);

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . $fileSize);

// Clear output buffer
ob_clean();
flush();

// Output the ZIP file
readfile($zipPath);

// Clean up - delete the temporary ZIP file
unlink($zipPath);

exit;
?>