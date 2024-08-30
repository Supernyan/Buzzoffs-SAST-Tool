<?php
session_start();
error_reporting(0);
include('include/dbcon.php');

if (!isset($_SESSION['id'])) {
    header('location:logout.php');
} else {
    $fileContent = '';
    $resultsData = '';

    if (isset($_GET['id'])) {
        $fileId = intval($_GET['id']);
        
        // Prepare and execute the query to fetch file path
        $query = $con->prepare("SELECT * FROM files WHERE id = ?");
        $query->bind_param("i", $fileId);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filePath = $row['path'];
            
            // Check if the file exists and is readable
            if (file_exists($filePath) && is_readable($filePath)) {
                $fileContent = file_get_contents($filePath);
            } else {
                $fileContent = "Error: Unable to read the file.";
            }
        } else {
            $fileContent = "Error: File not found.";
        }
        
        $query->close();

         // Prepare and execute the query to fetch results data
        $resultsQuery = $con->prepare("SELECT * FROM results WHERE file_id = ? AND vul_id = 1");
        $resultsQuery->bind_param("i", $fileId);
        $resultsQuery->execute();
        $results = $resultsQuery->get_result();
        $count_vul1=0;
        if ($results->num_rows > 0) {
            while ($resultRow = $results->fetch_assoc()) {
                $resultsData1 .= "<p>" . $resultRow['line'] . "</p>";
                $count_vul1++;
            }
        } else {
            $resultsData1 = "<p>No results found for the given file and vulnerability ID.</p>";
        }
        
         // Prepare and execute the query to fetch results data
        $resultsQuery = $con->prepare("SELECT * FROM results WHERE file_id = ? AND vul_id = 2");
        $resultsQuery->bind_param("i", $fileId);
        $resultsQuery->execute();
        $results = $resultsQuery->get_result();
        $count_vul2=0;
        if ($results->num_rows > 0) {
            while ($resultRow = $results->fetch_assoc()) {
                $resultsData2 .= "<p>" . $resultRow['line'] . "</p>";
                $count_vul2++;
            }
        } else {
            $resultsData2 = "<p>No results found for the given file and vulnerability ID.</p>";
        }

         // Prepare and execute the query to fetch results data
        $resultsQuery = $con->prepare("SELECT * FROM results WHERE file_id = ? AND vul_id = 3");
        $resultsQuery->bind_param("i", $fileId);
        $resultsQuery->execute();
        $results = $resultsQuery->get_result();
        $count_vul3=0;
        if ($results->num_rows > 0) {
            while ($resultRow = $results->fetch_assoc()) {
                $resultsData3 .= "<p>" . $resultRow['line'] . "</p>";
                $count_vul3++;
            }
        } else {
            $resultsData3 = "<p>No results found for the given file and vulnerability ID.</p>";
        }

         // Prepare and execute the query to fetch results data
        $resultsQuery = $con->prepare("SELECT * FROM results WHERE file_id = ? AND vul_id = 4");
        $resultsQuery->bind_param("i", $fileId);
        $resultsQuery->execute();
        $results = $resultsQuery->get_result();
        $count_vul4=0;
        if ($results->num_rows > 0) {
            while ($resultRow = $results->fetch_assoc()) {
                $resultsData4 .= "<p>" . $resultRow['line'] . "</p>";
                $count_vul4++;
            }
        } else {
            $resultsData4 = "<p>No results found for the given file and vulnerability ID.</p>";
        }

        $resultsQuery->close();
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>BuzzOffs | Suggestion</title>
      <?php include('include/header.php'); ?>
      <style>
      .indent {
          margin-left: 20px;
      }
      .phase-heading {
          color: #a1c9ff;
      }
	  pre{ 
		  color: white;
	  }
  </style>
    </head>
    <body class="dark-mode hold-transition sidebar-mini sidebar-collapse">
      <!-- Site wrapper -->
      <div class="wrapper">
       <?php include('include/nav.php'); ?>
       <!-- Content Wrapper. Contains page content -->
       <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Suggestion</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Suggestion</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"></h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Hard coded credentials</span>
                          <span class="info-box-number text-center text-muted mb-0"><?php echo $count_vul1; ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">SQL Injection</span>
                          <span class="info-box-number text-center text-muted mb-0"><?php echo $count_vul2; ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Improper Input Validation</span>
                          <span class="info-box-number text-center text-muted mb-0"><?php echo $count_vul3; ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Improper Authentication</span>
                          <span class="info-box-number text-center text-muted mb-0"><?php echo $count_vul4; ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <h4>Mitigation Methods</h4>
                      <div id="accordion">
                        <?php if($count_vul1>0){ ?>
                        <div class="card card-primary card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                              
                              <li class="pt-2 px-3"><h3 class="card-title"><a class="d-block w-100" data-toggle="collapse" href="#collapse1">Hard-coded Credentials&emsp;</a></h3></li>
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#hard-vuln" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Vulnerability</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#hard-mitigation" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Mitigation</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-suggest-tab" data-toggle="pill" href="#hard-suggest" role="tab" aria-controls="custom-tabs-two-suggest" aria-selected="false">Secure Code Practice</a>
                              </li>
                            </ul>
                          </div>
                          <div id="collapse1" class="collapse show" data-parent="#accordion">
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                              <div class="tab-pane fade show active" id="hard-vuln" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                               <?php echo $resultsData1; ?>
                              </div>
                              <div class="tab-pane fade" id="hard-mitigation" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
                                          <tbody>
                                              <tr>
                                                  <td valign="top">
                                                    <a href="https://cwe.mitre.org/data/definitions/798.html" target="_blank">CWE-798: Use of Hard-coded Credentials
                                                      </a>
                                                      <h5 class="phase-heading">Phase:  Architecture and Design</h5>
                                                      <div class="indent">For outbound authentication: store passwords, keys, and other credentials outside of the code in a strongly-protected, encrypted configuration file or database that is protected from access by all outsiders, including other local users on the same system. Properly protect the key. If you cannot use encryption to protect the file, then make sure that the permissions are as restrictive as possible.</div>
                                                      <p></p>
                                                      <div class="indent">In Windows environments, the Encrypted File System (EFS) may provide some protection. </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td valign="top">
                                                      <p></p>
                                                      <h5 class="phase-heading">Phase:  Architecture and Design</h5>
                                                      <div class="indent">For inbound authentication: Rather than hard-code a default username and password, key, or other authentication credentials for first-time logins, utilize a "first login" mode that requires the user to enter a unique strong password or key. </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td valign="top">
                                                      <p></p>
                                                      <h5 class="phase-heading">Phase:  Architecture and Design</h5>
                                                      <div class="indent">If the product must contain hard-coded credentials or they cannot be removed, perform access control checks and limit which entities can access the feature that requires the hard-coded credentials. For example, a feature might only be enabled through the system console instead of through a network connection. </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td valign="top">
                                                      <p></p>
                                                      <h5 class="phase-heading">Phase:  Architecture and Design</h5>
                                                      <div class="indent">For inbound authentication using passwords: apply strong one-way hashes to passwords and store those hashes in a configuration file or database with appropriate access control. That way, theft of the file/database still requires the attacker to try to crack the password. When handling an incoming password during authentication, take the hash of the password and compare it to the saved hash. </div>
                                                      <p></p>
                                                      <div class="indent">Use randomly assigned salts for each separate hash that is generated. This increases the amount of computation that an attacker needs to conduct a brute-force attack, possibly limiting the effectiveness of the rainbow table method. </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td valign="top">
                                                      <p></p>
                                                      <h5 class="phase-heading">Phase:  Architecture and Design</h5>
                                                      <div class="indent">For front-end to back-end connections: Three solutions are possible, although none are complete. </div>
                                                      <ul>
                                                          <li>
                                                              <div class="indent">The first suggestion involves the use of generated passwords or keys that are changed automatically and must be entered at given time intervals by a system administrator. These passwords will be held in memory and only be valid for the time intervals. </div>
                                                          </li>
                                                          <li>
                                                              <div class="indent">Next, the passwords or keys should be limited at the back end to only performing actions valid for the front end, as opposed to having full access. </div>
                                                          </li>
                                                          <li>
                                                              <div class="indent">Finally, the messages sent should be tagged and checksummed with time-sensitive values so as to prevent replay-style attacks. </div>
                                                          </li>
                                                      </ul>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                              </div>
							  <div class="tab-pane fade" id="hard-suggest" role="tabpanel" aria-labelledby="custom-tabs-two-suggest-tab">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
                                       <thead>
										<tr>
										  <th style="padding: 8px;">Code Type</th>
										  <th style="padding: 8px;">Code Example</th>
										</tr>
									  </thead>
									  <tbody>
										<tr>
										  <td style="padding: 8px; vertical-align: top;">Insecure</td>
										  <td style="padding: 8px; vertical-align: top;">
											<span>
									// Insecure: Hard-coded credentials<br>
									$password = 'supersecret'; // Hard-coded password<br>
									$api_key = '1234567890abcdef'; // Hard-coded API key
											</span>
										  </td>
										</tr>
										<tr>
										  <td style="padding: 8px; vertical-align: top;">Secure</td>
										  <td style="padding: 8px; vertical-align: top;">
											<span>
									// Secure: Use environment variables<br>
									$password = getenv('DB_PASSWORD'); // Retrieve password from environment variables<br>
									$api_key = getenv('API_KEY'); // Retrieve API key from environment variables
											</span>
										  </td>
										</tr>
									  </tbody>
                                </table>
                              </div>
							</div>
                          </div>
                          <!-- /.card -->
                        </div>
                       </div>
                     <?php } 
                      if($count_vul2>0){ ?>
                        <div class="card card-primary card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                              
                              <li class="pt-2 px-3"><h3 class="card-title"><a class="d-block w-100" data-toggle="collapse" href="#collapse2">SQL Injection&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;</a></h3></li>
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#sql-vuln" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Vulnerability</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#sql-mitigation" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Mitigation</a>
                              </li>
							   <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-suggest-tab" data-toggle="pill" href="#sql-suggest" role="tab" aria-controls="custom-tabs-two-suggest" aria-selected="false">Secure Code Practice</a>
                              </li>
                            </ul>
                          </div>
                          <div id="collapse2" class="collapse show" data-parent="#accordion">
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                              <div class="tab-pane fade show active" id="sql-vuln" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                               <?php echo $resultsData2; ?>
                              </div>
                              <!--Ref: https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html-->
                              <div class="tab-pane fade" id="sql-mitigation" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                              <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
                                  <tbody>
                                      <tr>
                                          <td valign="top">
                                             <a href="https://owasp.org/Top10/A03_2021-Injection/" target="_blank">A03:2021 – Injection
                                                      </a>
                                              <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                              <div class="indent">Defense Option 1: Prepared Statements (with Parameterized Queries)
                                                  <p>When developers are taught how to write database queries, they should be told to use prepared statements with variable binding (aka parameterized queries). Prepared statements are simple to write and easier to understand than dynamic queries, and parameterized queries force the developer to define all SQL code first and pass in each parameter to the query later.</p>
                                                  <p>If database queries use this coding style, the database will always distinguish between code and data, regardless of what user input is supplied. Also, prepared statements ensure that an attacker is not able to change the intent of a query, even if SQL commands are inserted by an attacker.</p>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td valign="top">
                                              <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                              <div class="indent">Defense Option 2: Stored Procedures
                                                  <p>Though stored procedures are not always safe from SQL injection, developers can use certain standard stored procedure programming constructs. This approach has the same effect as the use of parameterized queries as long as the stored procedures are implemented safely (which is the norm for most stored procedure languages).</p>
                                              </div>
                                              <div class="indent">Safe Approach to Stored Procedures
                                                  <p>If stored procedures are needed, the safest approach to using them requires the developer to build SQL statements with parameters that are automatically parameterized, unless the developer does something largely out of the norm. The difference between prepared statements and stored procedures is that the SQL code for a stored procedure is defined and stored in the database itself, and then called from the application. Since prepared statements and safe stored procedures are equally effective in preventing SQL injection, your organization should choose the approach that makes the most sense for you.</p>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td valign="top">
                                              <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                              <div class="indent">Defense Option 3: Allow-list Input Validation
                                                  <p>If you are faced with parts of SQL queries that can't use bind variables, such as the names of tables or columns as well as the sort order indicator (ASC or DESC), input validation or query redesign is the most appropriate defense. When names of tables or columns are needed, ideally those values come from the code and not from user parameters.</p>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td valign="top">
                                              <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                              <div class="indent">Defense Option 4: STRONGLY DISCOURAGED: Escaping All User-Supplied Input
                                                  <p>In this approach, the developer will escape all user input before putting it in a query. It is very database-specific in its implementation. This methodology is frail compared to other defenses and we CANNOT guarantee that this option will prevent all SQL injections in all situations.</p>
                                                  <p>If an application is built from scratch or requires low risk tolerance, it should be built or re-written using parameterized queries, stored procedures, or some kind of Object Relational Mapper (ORM) that builds your queries for you.</p>
                                              </div>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                              </div>
							   <div class="tab-pane fade" id="sql-suggest" role="tabpanel" aria-labelledby="custom-tabs-two-suggest-tab">
                                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
									<thead>
										<tr>
											<th style="padding: 8px;">Code Type</th>
											<th style="padding: 8px;">Code Example</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="padding: 8px; vertical-align: top;">Insecure</td>
											<td style="padding: 8px; vertical-align: top;">
												<span>
													// Insecure: SQL Injection Vulnerable<br>
													$sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";<br>
													$result = mysqli_query($conn, $sql);
												</span>
											</td>
										</tr>
										<tr>
											<td style="padding: 8px; vertical-align: top;">Secure</td>
											<td style="padding: 8px; vertical-align: top;">
												<span>
													// Secure: Use Prepared Statements<br>
													$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");<br>
													$stmt->bind_param("s", $_POST['username']);<br>
													$stmt->execute();<br>
													$result = $stmt->get_result();
												</span>
											</td>
										</tr>
									</tbody>
								</table>
                              </div>
							</div>
                          </div>
                          <!-- /.card -->
                        </div>
                       </div>
                     <?php } 
                       if($count_vul3>0){ ?>
                       <div class="card card-primary card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist"> 
                              <li class="pt-2 px-3"><h3 class="card-title"><a class="d-block w-100" data-toggle="collapse" href="#collapse3">Improper Input Validation</a></h3></li>
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#improperinput-vuln" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Vulnerability</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#improperinput-mitigation" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Mitigation</a>
                              </li>
							  <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-suggest-tab" data-toggle="pill" href="#improperinput-suggest" role="tab" aria-controls="custom-tabs-two-suggest" aria-selected="false">Secure Code Practice</a>
                              </li>
                            </ul>
                          </div>
                          <div id="collapse3" class="collapse show" data-parent="#accordion">
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                              <div class="tab-pane fade show active" id="improperinput-vuln" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                               <?php echo $resultsData3; ?>
                              </div>
                              <!--Ref: https://cwe.mitre.org/data/definitions/287.html -->
                              <div class="tab-pane fade" id="improperinput-mitigation" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                              <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                             <a href="https://cwe.mitre.org/data/definitions/20" target="_blank">CWE-20: Improper Input Validation
                                                      </a>
                                            <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                            <div class="indent">Strategy: Attack Surface Reduction
                                                <p>Consider using language-theoretic security (LangSec) techniques that characterize inputs using a formal language and build "recognizers" for that language. This effectively requires parsing to be a distinct layer that enforces a boundary between raw input and internal data representations, instead of allowing parser code to be scattered throughout the program, where it could be subject to errors or inconsistencies that create weaknesses.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                            <div class="indent">Strategy: Libraries or Frameworks
                                                <p>Use an input validation framework such as Struts or the OWASP ESAPI Validation API. Note that using a framework does not automatically address all input validation problems; be mindful of weaknesses that could arise from misusing the framework itself (CWE-1173).</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phases: Architecture and Design; Implementation</h5>
                                            <div class="indent">Strategy: Attack Surface Reduction
                                                <p>Understand all the potential areas where untrusted inputs can enter your software: parameters or arguments, cookies, anything read from the network, environment variables, reverse DNS lookups, query results, request headers, URL components, e-mail, files, filenames, databases, and any external systems that provide data to the application. Remember that such inputs may be obtained indirectly through API calls.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">Strategy: Input Validation
                                                <p>Assume all input is malicious. Use an "accept known good" input validation strategy, i.e., use a list of acceptable inputs that strictly conform to specifications. Reject any input that does not strictly conform to specifications, or transform it into something that does.</p>
                                                <p>When performing input validation, consider all potentially relevant properties, including length, type of input, the full range of acceptable values, missing or extra inputs, syntax, consistency across related fields, and conformance to business rules. As an example of business rule logic, "boat" may be syntactically valid because it only contains alphanumeric characters, but it is not valid if the input is only expected to contain colors such as "red" or "blue."</p>
                                                <p>Do not rely exclusively on looking for malicious or malformed inputs. This is likely to miss at least one undesirable input, especially if the code's environment changes. This can give attackers enough room to bypass the intended validation. However, denylists can be useful for detecting potential attacks or determining which inputs are so malformed that they should be rejected outright. Effectiveness: High</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                            <div class="indent">
                                                <p>For any security checks that are performed on the client side, ensure that these checks are duplicated on the server side, in order to avoid CWE-602. Attackers can bypass the client-side checks by modifying values after the checks have been performed, or by changing the client to remove the client-side checks entirely. Then, these modified values would be submitted to the server.</p>
                                                <p>Even though client-side checks provide minimal benefits with respect to server-side security, they are still useful. First, they can support intrusion detection. If the server receives input that should have been rejected by the client, then it may be an indication of an attack. Second, client-side error-checking can provide helpful feedback to the user about the expectations for valid input. Third, there may be a reduction in server-side processing time for accidental input errors, although this is typically a small savings.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">
                                                <p>When your application combines data from multiple sources, perform the validation after the sources have been combined. The individual data elements may pass the validation step but violate the intended restrictions after they have been combined.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">
                                                <p>Be especially careful to validate all input when invoking code that crosses language boundaries, such as from an interpreted language to native code. This could create an unexpected interaction between the language boundaries. Ensure that you are not violating any of the expectations of the language with which you are interfacing. For example, even though Java may not be susceptible to buffer overflows, providing a large argument in a call to native code might trigger an overflow.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">
                                                <p>Directly convert your input type into the expected data type, such as using a conversion function that translates a string into a number. After converting to the expected data type, ensure that the input's values fall within the expected range of allowable values and that multi-field consistencies are maintained.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">
                                                <p>Inputs should be decoded and canonicalized to the application's current internal representation before being validated (CWE-180, CWE-181). Make sure that your application does not inadvertently decode the same input twice (CWE-174). Such errors could be used to bypass allowlist schemes by introducing dangerous inputs after they have been checked. Use libraries such as the OWASP ESAPI Canonicalization control.</p>
                                                <p>Consider performing repeated canonicalization until your input does not change any more. This will avoid double-decoding and similar scenarios, but it might inadvertently modify inputs that are allowed to contain properly-encoded dangerous content.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <h5 class="phase-heading">Phase: Implementation</h5>
                                            <div class="indent">
                                                <p>When exchanging data between components, ensure that both components are using the same character encoding. Ensure that the proper encoding is applied at each interface. Explicitly set the encoding you are using whenever the protocol allows you to do so.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                              </div>
							  <div class="tab-pane fade" id="improperinput-suggest" role="tabpanel" aria-labelledby="custom-tabs-two-suggest-tab">
								 <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
										<thead>
											<tr>
												<th style="padding: 8px;">Code Type</th>
												<th style="padding: 8px;">Code Example</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="padding: 8px; vertical-align: top;">Insecure</td>
												<td style="padding: 8px; vertical-align: top;">
													<span>
														// Insecure: Improper Input Validation<br>
														$username = $_POST['username']; // Direct use without validation<br>
														echo "Welcome, " . $username;
													</span>
												</td>
											</tr>
											<tr>
												<td style="padding: 8px; vertical-align: top;">Secure</td>
												<td style="padding: 8px; vertical-align: top;">
													<span>
														// Secure: Proper Input Validation<br>
														$username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');<br>
														echo "Welcome, " . $username;
													</span>
												</td>
											</tr>
										</tbody>
									</table>
                              </div>
							</div>
                          </div>
                          <!-- /.card -->
                        </div>
                       </div>
                      <?php } 
                        if($count_vul4>0){
                      ?>
                        <div class="card card-primary card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist"> 
                              <li class="pt-2 px-3"><h3 class="card-title"><a class="d-block w-100" data-toggle="collapse" href="#collapse4">Improper Authentication&nbsp;&nbsp;</a></h3></li>
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#improperauth-vuln" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Vulnerability</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#improperauth-mitigation" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Mitigation</a>
                              </li>
							  <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-suggest-tab" data-toggle="pill" href="#improperauth-suggest" role="tab" aria-controls="custom-tabs-two-suggest" aria-selected="false">Secure Code Practice</a>
                              </li>
                            </ul>
                          </div>
                          <div id="collapse4" class="collapse show" data-parent="#accordion">
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                              <div class="tab-pane fade show active" id="improperauth-vuln" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                               <?php echo $resultsData4; ?>
                              </div>
                              <!--Ref: https://cwe.mitre.org/data/definitions/287.html 
                                https://owasp.org/www-project-enterprise-security-api/-->
                              <div class="tab-pane fade" id="improperauth-mitigation" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                          <a href="https://cwe.mitre.org/data/definitions/287.html" target="_blank">CWE-287: Improper Authentication
                                                      </a>
                                            <h5 class="phase-heading">Phase: Architecture and Design</h5>
                                            <div class="indent">Strategy: Libraries or Frameworks
                                                <p>Use an authentication framework or library such as the OWASP ESAPI Authentication feature.</p>
                                                <p>What is ESAPI?</p>
                                                <p>ESAPI (The OWASP Enterprise Security API) is a free, open source, web application security control library that makes it easier for programmers to write lower-risk applications. The ESAPI libraries are designed to make it easier for programmers to retrofit security into existing applications. The ESAPI libraries also serve as a solid foundation for new development.</p>
                                                <p>Allowing for language-specific differences, all OWASP ESAPI versions have the same basic design:</p>
                                                <ul>
                                                    <li>
                                                        <div class="indent">There is a set of security control interfaces. They define, for example, types of parameters that are passed to types of security controls.</div>
                                                    </li>
                                                    <li>
                                                        <div class="indent">There is a reference implementation for each security control. The logic is not organization‐specific and the logic is not application‐specific. An example: string‐based input validation. (Note that some of the reference implementations are simply “toy” examples to illustrate how to implement a specific interface [e.g., ESAPI for Java’s org.owasp.esapi.reference.FileBasedAuthenticator] whereas others are full-fledged enterprise ready reference implementations [e.g., org.owasp.esapi.reference.DefaultEncoder or org.owasp.esapi.reference.DefaultValidator].)</div>
                                                    </li>
                                                    <li>
                                                        <div class="indent">There are optionally your own implementations for each security control. There may be application logic contained in these classes which may be developed by or for your organization. An example: enterprise authentication.</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                              </div>
                              <div class="tab-pane fade" id="improperauth-suggest" role="tabpanel" aria-labelledby="custom-tabs-two-suggest-tab">
                                 <table width="100%" cellpadding="0" cellspacing="0" border="0" class="Detail">
									<thead>
										<tr>
											<th style="padding: 8px;">Code Type</th>
											<th style="padding: 8px;">Code Example</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="padding: 8px; vertical-align: top;">Insecure</td>
											<td style="padding: 8px; vertical-align: top;">
												<span>
													// Insecure: Improper Authentication<br>
													if (isset($_SESSION['user'])) {<br>
													&nbsp;&nbsp;echo "Access granted";<br>
													} else {<br>
													&nbsp;&nbsp;echo "Access denied";<br>
													}
												</span>
											</td>
										</tr>
										<tr>
											<td style="padding: 8px; vertical-align: top;">Secure</td>
											<td style="padding: 8px; vertical-align: top;">
												<span>
													// Secure: Proper Authentication<br>
													if (isset($_SESSION['user']) && hash_equals($_SESSION['user_token'], $_POST['token'])) {<br>
													&nbsp;&nbsp;echo "Access granted";<br>
													} else {<br>
													&nbsp;&nbsp;echo "Access denied";<br>
													}
												</span>
											</td>
										</tr>
									</tbody>
								</table>
                              </div>
							</div>
                          </div>
                          <!-- /.card -->
                        </div>
                       </div>
                     <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                  <h5 class="text-muted">Uploaded file</h5>
                  <ul class="list-unstyled">
                    <li>
                      <a href="#" class="text-secondary"><i class="far fa-fw fa-file-word"></i> <?php echo $row['name']; ?> </a>
                    </li>
                  </ul>
                  <div class="col-md-12">
                    <div class="card card-outline card-info">
                      <div class="card-header">
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body p-0">
                        <textarea id="codeMirrorDemo" class="p-3" readonly><?php echo htmlspecialchars($fileContent); ?></textarea>
                      </div>
                      <div class="card-footer">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <?php include('include/footer.php'); ?>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <?php include('include/scripts.php'); ?>

    <script type="text/javascript">
      $(function () {
      // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
          mode: "htmlmixed",
          theme: "monokai",
          readOnly: true,
          lineNumbers: true 
        });
      })
    </script>
  </body>
  </html>
  <?php } ?>