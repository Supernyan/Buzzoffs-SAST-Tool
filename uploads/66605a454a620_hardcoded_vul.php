<?php 
$password = 'mypassword123'; // Generic check for passwords or variations like 'pass'
$pass = 'mypassword123';

$username = 'admin'; // Generic check for usernames or variations like 'user' or 'email'
$user = 'admin';
$email = 'admin@example.com';

$api_key = 'abcdef123456'; // Check for API keys
$api_token = 'ghijkl789012';

$ftp_password = 'ftppass123'; // Check for FTP credentials
$ftp_username = 'ftpuser';

$smtp_password = 'smtppass123'; // Check for SMTP credentials
$smtp_username = 'smtpuser';

$url_with_username = 'http://example.com/api?username=admin'; // Check for Remote API URLs with Authentication
$endpoint_with_password = 'http://example.com/api?password=secret';
$api_with_username = 'http://example.com/api?username=admin&password=secret';

$connection = mysqli_connect('localhost', 'root', 'password123', 'my_database'); // Check for mysqli_connect function call with parameters

$pdo = new PDO('mysql:host=localhost;dbname=my_database', 'root', 'password123'); // Check for PDO constructor call with DSN, username, and password

$connection = mysql_connect('localhost', 'root', 'password123'); // Check for mysql_connect function call with host, username, and password

$db_name = 'my_database'; // Check for database names
$database_name = 'my_database';
$database = 'my_database';
$db = 'my_database';

$db_host = 'localhost'; // Check for database hosts
$database_host = 'localhost';
$host = 'localhost';

$secret_key = 'mysecretkey123'; // Check for secret keys and tokens
$jwt_token = 'jwtsecret123';
$encryption_key = 'encryptionkey123';
$session_key = 'sessionkey123';

$base_url = 'http://example.com/'; // Check for hard-coded paths and URLs
$upload_path = '/var/www/uploads/';
$api_endpoint = 'http://api.example.com/v1/';

$admin_email = 'admin@example.com'; // Check for hard-coded email addresses
$support_email = 'support@example.com';

$config['debug_mode'] = true; // Check for hard-coded configuration settings
$config['log_level'] = 'debug';

$server_ip = '192.168.1.1'; // Check for hard-coded IP addresses and default credentials
$default_username = 'admin';
$default_password = 'admin123';

?>