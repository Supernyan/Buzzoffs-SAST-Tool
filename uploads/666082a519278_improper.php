<?php
// CWE-287: Improper Authentication Example

// Dummy user data for demonstration purposes
$users = [
    'admin' => 'password123',
    'user' => 'userpass'
];

// Simulated login function
function login($username, $password) {
    global $users;

    // Incorrectly checks if username exists, but does not validate the password properly
    if (isset($users[$username])) {
        // Vulnerable: Does not compare the provided password with the stored one
        return true;
    }
    
    return false;
}

// Simulated request data (in a real scenario, this would come from POST data)
$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';

if (login($username, $password)) {
    echo "Welcome, " . htmlspecialchars($username) . "!";
} else {
    echo "Invalid credentials!";
}
?>
