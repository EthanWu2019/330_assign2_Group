<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];
$user_dir = 'secure/uploads/' . $username;

if (!is_dir($user_dir)) {
    mkdir($user_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file_name = basename($_FILES['file']['name']);
        $safe_name = filter_var($file_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $target_file = $user_dir . '/' . $safe_name;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $success = 'File uploaded successfully!';
        } else {
            $error = 'File upload failed! Error: ' . $_FILES['file']['error'];
        }
    }
}

if (isset($_GET['delete'])) {
    $file_to_delete = $user_dir . '/' . basename($_GET['delete']);
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        $success = 'File deleted successfully!';
    } else {
        $error = 'File not found!';
    }
}

$files = array_diff(scandir($user_dir), ['.', '..']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <h2>Upload File</h2>
        <form action="dashboard.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
        
        <h2>Your Files</h2>
        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="download.php?user=<?php echo urlencode($username); ?>&name=<?php echo urlencode($file); ?>">
                        <?php echo htmlspecialchars($file); ?>
                    </a>
                    <a href="dashboard.php?delete=<?php echo urlencode($file); ?>" class="delete">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>