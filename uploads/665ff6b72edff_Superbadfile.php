<?php
// Hard-coded credentials (DO NOT use this in production!)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'password123';
$db_name = 'my_database';

// Connect to the database
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Improper input validation (vulnerable to XSS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; // No validation or sanitization
    echo "Hello, " . $username; // Displaying user input directly
}

// SQL injection vulnerability
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql = "SELECT * FROM users WHERE username = '$search_term'";
    $result = $mysqli->query($sql);
    // Process the query result...
}

// Improper authentication (vulnerable to session hijacking)
session_start();
if (isset($_SESSION['user_id'])) {
    // User is authenticated
    // ...
} else {
    // Redirect to login page
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable PHP Script</title>
</head>
<body>
    <!-- Form for improper input validation -->
    <form method="post">
        <label for="username">Enter your username:</label>
        <input type="text" id="username" name="username">
        <button type="submit">Submit</button>
    </form>

    <!-- Search form vulnerable to SQL injection -->
    <form method="get">
        <label for="search">Search users:</label>
        <input type="text" id="search" name="search">
        <button type="submit">Search</button>
    </form>
</body>
</html>
