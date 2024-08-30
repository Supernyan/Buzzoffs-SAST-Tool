<?php
session_start();
include('include/dbcon.php');
$user_id=$_SESSION['id'];
// Query to get the count of uploaded files per month
$filesQuery = "
    SELECT MONTH(dateUpload) AS month, COUNT(*) AS total
    FROM files
    WHERE user_id=$user_id 
    GROUP BY MONTH(dateUpload)
";

// Query to get the count of vulnerabilities per month
$vulnerabilitiesQuery = "
    SELECT MONTH(f.dateUpload) AS month, COUNT(*) AS total
    FROM results r
    JOIN files f ON r.file_id = f.id
    WHERE user_id=$user_id
    GROUP BY MONTH(f.dateUpload)
";

$filesResult = $con->query($filesQuery);
$vulnerabilitiesResult = $con->query($vulnerabilitiesQuery);

$filesData = array_fill(0, 12, 0);
$vulnerabilitiesData = array_fill(0, 12, 0);

if ($filesResult->num_rows > 0) {
    while ($row = $filesResult->fetch_assoc()) {
        $filesData[$row['month'] - 1] = $row['total'];
    }
}

if ($vulnerabilitiesResult->num_rows > 0) {
    while ($row = $vulnerabilitiesResult->fetch_assoc()) {
        $vulnerabilitiesData[$row['month'] - 1] = $row['total'];
    }
}

$con->close();

echo json_encode(array(
    'files' => $filesData,
    'vulnerabilities' => $vulnerabilitiesData
));
?>
