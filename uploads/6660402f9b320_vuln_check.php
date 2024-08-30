<?php
// Function to check for hard-coded credentials
function checkForHardcodedCredentials($file_contents) {
    $lines = explode("\n", $file_contents);
    $vulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        // Check for patterns indicating hard-coded credentials
        if (
            preg_match('/(password|pass)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Generic check for passwords or variations like 'pass'
            preg_match('/(username|user|email)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Generic check for usernames or variations like 'user' or 'email'
            preg_match('/api_(key|token)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for API keys
            preg_match('/ftp_(password|username)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for FTP credentials
            preg_match('/smtp_(password|username)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for SMTP credentials
            preg_match('/(url|endpoint|api)_with_(username|password)\s*=\s*[\'"].*[\'"]/i', $line) || // Check for Remote API URLs with Authentication
            preg_match('/\bmysqli_connect\s*\(\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*\)\s*;/', $line) || // Check for mysqli_connect function call with parameters
            preg_match('/\bPDO\s*\(\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*\)/', $line) || // Check for PDO constructor call with DSN, username, and password
            preg_match('/\bmysql_connect\s*\(\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*\)/', $line) // Check for mysql_connect function call with host, username, and password
        ) {
            $vulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $vulnerabilities;
}

function checkForSQLInjection($file_contents) {
    $lines = explode("\n", $file_contents);
    $vulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        // Check for patterns suggestive of SQL injection (code smells)
        if (
            // User input directly concatenated into SQL strings
            preg_match('/\$[a-zA-Z0-9_]+\s*=\s*\$\w+\[\'\w+\'\];/', $line) || // User input variable + dynamic key
            preg_match('/\$\w+\s*=\s*("[^"]*"|\'[^\']*\')\s*\.\s*append\(\s*\$\w+\s*\);/', $line) || // User input appended to string
            // String concatenation without proper escaping (potential for later injection)
            preg_match('/\$\w+\s*=\s*("[^"]*"|\'[^\']*\')\s*\.\s*\$\w+\s*\.\s*("[^"]*"|\'[^\']*\');/', $line) ||
            // Unvalidated user input used in SQL function calls (prepared statements preferred)
            preg_match('/(mysqli|PDO|mysql)_\w+\(\s*\$\w+\s*\)/', $line) ||
            // SQL queries directly built with user input
            preg_match('/\bSELECT\b.*\bFROM\b.*\bWHERE\b/i', $line) || // Simple SQL SELECT queries
            preg_match('/\bINSERT INTO\b.*\bVALUES\b/i', $line) || // Simple SQL INSERT queries
            preg_match('/\bUPDATE\b.*\bSET\b.*\bWHERE\b/i', $line) || // Simple SQL UPDATE queries
            preg_match('/\bDELETE FROM\b.*\bWHERE\b/i', $line) // Simple SQL DELETE queries
        ) {
            $vulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $vulnerabilities;
}

// Function to check for improper input validation (CWE-20)
function checkForImproperInputValidation($file_contents) {
    $lines = explode("\n", $file_contents);
    $ImpIVulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        // Check for patterns indicating improper input validation
        if (
            preg_match('/\$_GET\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) || // Check for direct use of $_GET without validation
            preg_match('/\$_POST\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) || // Check for direct use of $_POST without validation
            preg_match('/\$_REQUEST\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) // Check for direct use of $_REQUEST without validation
        ) {
            $ImpIVulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line) . " (CWE-20: Improper Input Validation detected)";
        }
    }

    return $ImpIVulnerabilities;
}

// Function to check for improper authentication (CWE-287)
function checkForImproperAuthentication($file_contents) {
    $lines = explode("\n", $file_contents);
    $authVulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        // Check for patterns indicating improper authentication
        if (
            preg_match('/if\s*\(\s*\!\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)/', $line) || // Check for improper session checks
            preg_match('/if\s*\(\s*\!\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)/', $line) || // Check for improper cookie checks
            preg_match('/if\s*\(\s*empty\s*\(\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) || // Check for empty session checks
            preg_match('/if\s*\(\s*empty\s*\(\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) || // Check for empty cookie checks
            preg_match('/if\s*\(\s*isset\s*\(\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) || // Check for improper use of isset for session
            preg_match('/if\s*\(\s*isset\s*\(\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) || // Check for improper use of isset for cookie
            preg_match('/\$auth_token\s*=\s*[\'"][^\'"]*[\'"]/', $line) // Check for hard-coded authentication tokens
        ) {
            $authVulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $authVulnerabilities;
}


$vulnerabilities = [];
$sql_vulnerabilities = [];
$ImpIVulnerabilities = [];
$upload_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Read file contents from temporary location
        $file_contents = file_get_contents($_FILES['file']['tmp_name']);

        // Check for vulnerabilities
        $vulnerabilities = checkForHardcodedCredentials($file_contents);
        $sql_vulnerabilities = checkForSQLInjection($file_contents);
        $ImpIVulnerabilities = checkForImproperInputValidation($file_contents);
        $authVulnerabilities = checkForImproperAuthentication($file_contents);

        // Set upload status
        $upload_status = "File uploaded successfully.";
    } else {
        $upload_status = "Error uploading file.";
    }
}
?>