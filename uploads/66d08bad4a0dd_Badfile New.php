<?php
// File: vulnerable.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'password123';
$db_name = 'my_database';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

echo 'Connected successfully.';

// Insecure code: No validation or sanitization at all
$name = $_POST['name'];
$email = $_POST['email'];

// Directly using user input in queries and output
$query = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
mysqli_query($connection, $query);

echo "Hello, " . $name . "! Your email is " . $email . ".";

// Function to log in a user (SQL Injection vulnerability)
function loginUser($conn, $username, $password) {
    // Unsanitized input directly in the SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation Example</title>
</head>
<body>
    <h1>Submit Your Information</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Submit</button>
    </form>
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
session_start();

// Improper session check
if (!$_SESSION['user_logged_in']) {
    echo "User is not logged in.";
}

// Improper use of cookies for authentication
if (empty($_COOKIE['auth_token'])) {
    echo "Authentication token is missing.";
}

if ($_POST['username'] == $username && $_POST['password'] == $password) {
    echo "Authenticated successfully with hard-coded credentials.";
} else {
    echo "Invalid credentials.";
}

// Direct use of user-supplied data for authentication
if ($_SERVER['REMOTE_ADDR'] == '192.168.1.100') {
    echo "User authenticated based on IP address.";
}

// Predictable session ID
session_id('123456');
session_start();

// Plain-text password comparison (no hashing)
if (strcmp($_POST['password'], $password) == 0) {
    echo "Passwords match.";
}
?>