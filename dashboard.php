<?php
session_start();
error_reporting(0);
include('include/dbcon.php');
if (!isset($_SESSION['id'])) {
  header('location:logout.php');
} else {

// Function to check for Use of Hard-coded Credentials (CWE-798)
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
          preg_match('/\bmysql_connect\s*\(\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*,\s*("[^"]*"|\'[^\']*\')\s*\)/', $line) || // Check for mysql_connect function call with host, username, and password
          preg_match('/(db_name|database_name|database|db|dbname)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for database names
          preg_match('/(db_host|database_host|host|server|server_name|servername|ser_name)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for database hosts
          preg_match('/(secret_key|jwt_token|encryption_key|session_key)\s*=\s*[\'"]\w+[\'"]/i', $line) || // Check for secret keys and tokens
          preg_match('/(base_url|upload_path|api_endpoint)\s*=\s*[\'"][^\'"]+[\'"]/i', $line) || // Check for hard-coded paths and URLs
          preg_match('/(admin_email|support_email)\s*=\s*[\'"][^\'"]+@[^\']+\'[\'"]/i', $line) || // Check for hard-coded email addresses
          preg_match('/\$config\[[\'"](debug_mode|log_level)[\'"]\]\s*=\s*[\'"][^\'"]+[\'"]/i', $line) || // Check for hard-coded configuration settings
          preg_match('/(server_ip|default_username|default_password)\s*=\s*[\'"][^\'"]+[\'"]/i', $line) // Check for hard-coded IP addresses and default credentials
        ) {
            $vulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $vulnerabilities;
}

// Function to check for SQL injection vulnerabilities (A03)
function checkForSQLInjection($file_contents) {
    $lines = explode("\n", $file_contents);
    $vulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        if (
            preg_match('/\$\w+\s*=\s*("[^"]*"|\'[^\']*\')\s*\.\s*append\(\s*\$\w+\s*\);/', $line) || // User input appended to string
            preg_match('/\$\w+\s*=\s*("[^"]*"|\'[^\']*\')\s*\.\s*\$\w+\s*\.\s*("[^"]*"|\'[^\']*\');/', $line) ||
            preg_match('/(mysqli|PDO|mysql)_\w+\(\s*\$\w+\s*\)/', $line) ||
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
    $vulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        if (
            preg_match('/\$_GET\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) || // Check for direct use of $_GET without validation
            preg_match('/\$_POST\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) || // Check for direct use of $_POST without validation
            preg_match('/\$_REQUEST\s*\[\s*[\'"][^\'"]*[\'"]\s*\]/', $line) // Check for direct use of $_REQUEST without validation
        ) {
            $vulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $vulnerabilities;
}

// Function to check for improper authentication (CWE-287)
function checkForImproperAuthentication($file_contents) {
    $lines = explode("\n", $file_contents);
    $vulnerabilities = array();

    foreach ($lines as $line_number => $line) {
        if (
            // Check for improper session checks
            preg_match('/if\s*\(\s*\!\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)/', $line) ||
            preg_match('/if\s*\(\s*\!\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)/', $line) ||

            // Check for empty session or cookie checks
            preg_match('/if\s*\(\s*empty\s*\(\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) ||
            preg_match('/if\s*\(\s*empty\s*\(\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) ||

            // Check for improper use of isset for session or cookie
            preg_match('/if\s*\(\s*isset\s*\(\s*\$_SESSION\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) ||
            preg_match('/if\s*\(\s*isset\s*\(\s*\$_COOKIE\s*\[\s*[\'"][^\'"]*[\'"]\s*\]\s*\)\s*\)/', $line) ||

            // Check for hard-coded authentication tokens or credentials
            preg_match('/\$auth_token\s*=\s*[\'"][^\'"]*[\'"]/', $line) ||
            preg_match('/\$username\s*=\s*[\'"][^\'"]*[\'"]/', $line) ||
            preg_match('/\$password\s*=\s*[\'"][^\'"]*[\'"]/', $line) ||

            // Detect direct comparison of plain-text passwords
            preg_match('/if\s*\(\s*\$_POST\s*\[\s*[\'"]password[\'"]\s*\]\s*==\s*[\'"][^\'"]*[\'"]\s*\)/', $line) ||

            // Check for predictable session IDs
            preg_match('/session_id\s*\(\s*[\'"][^\'"]*[\'"]\s*\)/', $line) ||

            // Check for direct use of user-supplied data for authentication
            preg_match('/if\s*\(\s*\$_SERVER\s*\[\s*[\'"]REMOTE_ADDR[\'"]\s*\]\s*==\s*[\'"][^\'"]*[\'"]\s*\)/', $line) ||

            // Check for lack of password hashing (direct comparison of plain text)
            preg_match('/if\s*\(\s*strcmp\s*\(\s*\$user_input_password\s*,\s*\$stored_password\s*\)\s*==\s*0\s*\)/', $line)
        ) {
            $vulnerabilities[] = "Line " . ($line_number + 1) . ": " . htmlspecialchars($line);
        }
    }

    return $vulnerabilities;
}

// Process file upload and scan for vulnerabilities
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Assign variables
        $uploaded_file = $_FILES['file']['tmp_name'];
        $user_id = $_SESSION['id'];
        $original_name = $_FILES['file']['name'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $upload_directory = "uploads/"; // Directory to store uploaded files
        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);

        // Validate file type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $file_mime_type = $finfo->file($uploaded_file);

        // Check if the file is a PHP file
        if ($file_mime_type === 'text/x-php' && strtolower($file_extension) === 'php') {
            // Generate a unique file name to prevent redundancy
            $new_file_name = uniqid() . '_' . $original_name; // Unique identifier + original file name
            $file_path = $upload_directory . $new_file_name;

            // Move uploaded file to desired location with the new file name
            if (move_uploaded_file($tmp_name, $file_path)) {
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Prepare SQL query to insert file details
                $sql = "INSERT INTO files (user_id, name, path) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "iss", $user_id, $original_name, $file_path);

                // Execute SQL query
                if (mysqli_stmt_execute($stmt)) {
                    // Get the auto-generated file_id
                    $file_id = mysqli_insert_id($con);

                    // Read file contents from the uploaded file
                    $file_contents = file_get_contents($file_path);

                    // Check for vulnerabilities
                    $hard_code_vulnerabilities = checkForHardcodedCredentials($file_contents);
                    $sql_vulnerabilities = checkForSQLInjection($file_contents);
                    $ImpIVulnerabilities = checkForImproperInputValidation($file_contents);
                    $authVulnerabilities = checkForImproperAuthentication($file_contents);

                    // Save vulnerabilities to the database
                    $vul_id = 1; // Example for hard-coded credentials
                    $count_vul1 = 0;
                    foreach ($hard_code_vulnerabilities as $vuln) {
                        $sql = "INSERT INTO results (file_id, vul_id, line) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        $count_vul1++;
                        mysqli_stmt_bind_param($stmt, "iis", $file_id, $vul_id, $vuln);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error saving vulnerability details: " . mysqli_error($con) . "<br>";
                        }
                    }
                    $vul_id = 2; // Example for SQL injection
                    $count_vul2 = 0;
                    foreach ($sql_vulnerabilities as $vuln) {
                        $sql = "INSERT INTO results (file_id, vul_id, line) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        $count_vul2++;
                        mysqli_stmt_bind_param($stmt, "iis", $file_id, $vul_id, $vuln);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error saving vulnerability details: " . mysqli_error($con) . "<br>";
                        }
                    }
                    $vul_id = 3; // Example for improper input validation
                    $count_vul3 = 0;
                    foreach ($ImpIVulnerabilities as $vuln) {
                        $sql = "INSERT INTO results (file_id, vul_id, line) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        $count_vul3++;
                        mysqli_stmt_bind_param($stmt, "iis", $file_id, $vul_id, $vuln);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error saving vulnerability details: " . mysqli_error($con) . "<br>";
                        }
                    }
                    $vul_id = 4; // Example for improper authentication
                    $count_vul4 = 0;
                    foreach ($authVulnerabilities as $vuln) {
                        $sql = "INSERT INTO results (file_id, vul_id, line) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        $count_vul4++;
                        mysqli_stmt_bind_param($stmt, "iis", $file_id, $vul_id, $vuln);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error saving vulnerability details: " . mysqli_error($con) . "<br>";
                        }
                    }
                } else {
                    echo "Error uploading file to database: " . mysqli_error($con);
                }
                // Close statement and connection
                mysqli_stmt_close($stmt);
                mysqli_close($con);
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
                 echo "<script>alert('Invalid file type. Only PHP files are allowed.');window.location.href = window.location.href;</script>";
        }
    } else {
        echo "<script>alert('Error uploading file.');window.location.href = window.location.href;</script>";

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BuzzOffs | Dashboard</title>
  <?php include('include/header.php'); ?>
  <link rel="stylesheet" type="text/css" href="dist/css/upload.css">
</head>
<body class="dark-mode hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
  <?php include('include/nav.php'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <!-- Info boxes -->
        <div class="row">
        <!-- DONUT CHART -->
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Total Vulnerabilities Found</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
            <!-- /.card -->
          <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Scanned Files vs Scanned Vulnerabilities</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- File upload -->
        <div class="row">
          <div class="col-12 d-flex justify-content-center" style="padding-top: 20px; margin-bottom: 10px;">
            <div class="row" style="width: 100%">
                <div class="col-md-12">               
                <h5>Upload .php file to check the vulnerabilities</h5>
                <div class="file-drop-area">
                  <span class="choose-file-button">Choose files</span>
                  <span class="file-message">or drag and drop files here</span>
                  <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <input class="file-input" id="fileInput" type="file" name="file" accept=".php">
                  </form>
                </div>      
                </div>
            </div>
          </div>
        </div>
      <!-- File upload and CodeMirror display -->
          <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) { ?>
          <!-- /.col --><br>
        <small><a href="suggestion.php?id=<?php echo $file_id; ?>" style="color: white;"><i class="fas fa-long-arrow-alt-right"></i><u> View Suggestions</u></a></small>
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-disease"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Hard-coded credentials</span>
                <span class="info-box-number"><?php echo $count_vul1; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-bug"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">SQL Injection</span>
                <span class="info-box-number"><?php echo $count_vul2; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-viruses"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Improper Input Validation</span>
                <span class="info-box-number"><?php echo $count_vul3; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-syringe"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Improper Authentication</span>
                <span class="info-box-number"><?php echo $count_vul4; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title">
                    Your code
                  </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <textarea id="codeMirrorDemo" class="p-3" readonly><?php echo htmlentities(file_get_contents($file_path)); ?></textarea>

                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include('include/footer.php'); ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include('include/scripts.php'); ?>
<script type="text/javascript">
  $(function () {
    // Fetch data from the PHP script
    $.ajax({
        url: 'get_donutchart.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData        = {
                labels: [
                    'Hard Coded Credentials',
                    'SQL Injection',
                    'Improper Input Validation',
                    'Improper Authentication',
                ],
                datasets: [
                    {
                        data: [
                            data.hard_coded_credentials,
                            data.sql_injection,
                            data.improper_input_validation,
                            data.improper_authentication
                        ],
                        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
                    }
                ]
            }
            var donutOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })
        },
        error: function(error) {
            console.error('Error fetching data', error);
        }
    });
    //-------------
    //- BAR CHART -
    //-------------

    $.ajax({
        url: 'get_barlinechart.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var areaChartData = {
                labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label               : 'Scanned Files',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius         : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : data.files
                    },
                    {
                        label               : 'Scanned Vulnerabilities',
                        type                : 'line',
                        backgroundColor     : 'rgba(210, 214, 222, 1)',
                        borderColor         : 'rgba(210, 214, 222, 1)',
                        pointRadius         : true,
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        fill                : false,
                        data                : data.vulnerabilities
                    }
                ]
            }

            var barChartCanvas = $('#barChart').get(0).getContext('2d');
            var barChartData = $.extend(true, {}, areaChartData);
            var temp0 = areaChartData.datasets[0];
            var temp1 = areaChartData.datasets[1];
            barChartData.datasets[0] = temp1;
            barChartData.datasets[1] = temp0;

            var barChartOptions = {
                responsive              : true,
                maintainAspectRatio     : false,
                datasetFill             : false,
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
  })

  $(document).on('change', '.file-input', function() {
      
  var filesCount = $(this)[0].files.length;
  
  var textbox = $(this).prev();

  if (filesCount === 1) {
    var fileName = $(this).val().split('\\').pop();
    textbox.text(fileName);
  } else {
    textbox.text(filesCount + ' files selected');
  }
});

  // Event listener for file input change
  document.getElementById('fileInput').addEventListener('change', function() {
      // Get the form element
      var form = document.getElementById('uploadForm');

      // Submit the form
      form.submit();
  });
  $(function () {
    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai",
      readOnly: true,
      lineNumbers: true 
    });
  });
</script>
</body>
</html>
<?php } ?>
