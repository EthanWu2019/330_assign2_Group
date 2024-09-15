<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
    $users = file('secure/users.txt', FILE_IGNORE_NEW_LINES);
    if (in_array($username, $users)) {
        $error = "Username already exists!";
    } else {
        file_put_contents('secure/users.txt', $username . PHP_EOL, FILE_APPEND);
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Register</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <button type="submit">Register</button>
        </form>
        <a href="index.php">Back to Login</a>
    </div>
</body>
</html>