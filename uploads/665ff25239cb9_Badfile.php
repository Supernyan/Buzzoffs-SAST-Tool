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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = $_GET['username'];
    echo "Hello, " . $username;
}
?>
