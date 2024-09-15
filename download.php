<?php
if (isset($_GET['user']) && isset($_GET['name'])) {
    $username = filter_var($_GET['user'], FILTER_SANITIZE_STRING);
    $file_name = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
    $file_path = 'secure/uploads/' . $username . '/' . $file_name;
    
    if (file_exists($file_path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        readfile($file_path);
        exit();
    } else {
        echo "File not found!";
    }
}
?>