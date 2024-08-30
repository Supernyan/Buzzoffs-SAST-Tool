<?php
// File: vulnerable.php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = $_GET['username'];
    echo "Hello, " . $username;
}
?>
