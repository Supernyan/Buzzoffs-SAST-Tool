<?php
session_start();
include('include/dbcon.php');
//Fetch data from vulnerability table for Donut chart
// Query to get the count of each vulnerability type
// Prepare and bind
$stmt = $con->prepare("
    SELECT 
        SUM(r.vul_id = 1) AS hard_coded_credentials,
        SUM(r.vul_id = 2) AS sql_injection,
        SUM(r.vul_id = 3) AS improper_input_validation,
        SUM(r.vul_id = 4) AS improper_authentication
    FROM 
        results r
    JOIN 
        files f ON r.file_id = f.id
    WHERE 
        f.user_id = ?
");
$stmt->bind_param("i", $_SESSION['id']); // Assuming user_id is an integer
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    // Fetch the data
    $data = $result->fetch_assoc();
} else {
    // Default values if no data found
    $data = [
        'hard_coded_credentials' => 0,
        'sql_injection' => 0,
        'improper_input_validation' => 0,
        'improper_authentication' => 0
    ];
}
echo json_encode($data);
$con->close();

?>