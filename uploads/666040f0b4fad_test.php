<?php
// Database connection using mysqli
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vulnerable code using $_GET
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = $user_id"; // Vulnerable to SQL injection
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "user_id: " . $row["user_id"]. " - Name: " . $row["name"]. " - Email: " . $row["email"]. "<br>";
        }
    } else {
        echo "0 results";
    }
}

// Vulnerable code using $_POST
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // Vulnerable to SQL injection
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Login successful";
    } else {
        echo "Invalid username or password";
    }
}

// Vulnerable code using $_REQUEST
if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%'"; // Vulnerable to SQL injection
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Product ID: " . $row["product_id"]. " - Name: " . $row["name"]. " - Price: " . $row["price"]. "<br>";
        }
    } else {
        echo "0 results";
    }
}

$conn->close();
?>
