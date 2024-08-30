<?php
// File: safe.php
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

echo 'Connected successfully.';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);
    echo "Hello, " . $username;
}
?>
