  <?php
  session_start();
  error_reporting(0);
  include('include/dbcon.php');
  if (!isset($_SESSION['id'])) {
    header('location:logout.php');
  } else {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BuzzOffs | History</title>
  <?php include('include/header.php'); ?>
   <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body class="dark-mode hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
  <?php include('include/nav.php'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">History</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All your previous uploads</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-striped table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>File name</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>View</th>
                  </tr>
                  </thead>
                  <tbody>

                    <?php
                      $i=0;
                      $ret = mysqli_query($con, "SELECT files.*, IF(results.file_id IS NOT NULL, 'Vulnerable', 'Clean') AS status, results.file_id AS result_file_id FROM files LEFT JOIN results ON files.id = results.file_id WHERE files.user_id=$user_id GROUP BY files.id ORDER BY files.dateUpload DESC;");
                      if (mysqli_num_rows($ret) == 0) {
                          echo "<tr>No records yet</tr>";
                      } else { 
                        while($row = mysqli_fetch_array($ret))
                        {
                          $i++;
                          $upload_date = date("j/n/Y g:i a", strtotime($row['dateUpload']));
                    ?>
                  <tr>
                  	<td><?php echo $i; ?></td>
                   <td><?php echo $row['name']; ?></td>
                    <td><?php echo $upload_date; ?></td>
                    <td class="project-state">
                        <span class="badge badge-<?php echo ($row['status'] === 'Vulnerable' ? 'danger' : 'success'); ?>"><?php echo $row['status']; ?></span>
                    </td>
                  <td class="project-actions">
                      <?php if ($row['status'] === 'Vulnerable') { ?>
                          <a class="btn btn-primary btn-sm" href="suggestion.php?id=<?php echo $row['id']; ?>"><i class="fas fa-eye"></i></a>
                      <?php } ?>
                      <a class="btn btn-danger btn-sm" data-confirm='Are you sure to delete this?' href="history.php?id=<?= $row['id']; ?>"><i class="fas fa-trash"></i></a>
                  </td>
                  </tr>
              <?php } } ?>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

<?php include('include/footer.php'); ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include('include/scripts.php'); ?>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- Page specific script -->
<script>
  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script type="text/javascript">
$(document).ready(function () {
  $(document).on('click', '[data-confirm]', function(e) {
    e.preventDefault(); // cancel default action
    var href = $(this).attr('href');
    var message = $(this).data('confirm');
    
    swal({
      title: "Are you sure?",
      text: message, 
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Data has been deleted!", {
          icon: "success",
          buttons: false,
          timer: 1500
        }).then(function() {
          window.location.href = href;
        });
      }
    });
  });
});

</script>
</body>
</html>
<?php 
//Delete
if(isset($_GET['id'])){
    $id=$_GET['id'];
    // Delete from files table
    $query_files = mysqli_query($con, "DELETE FROM files WHERE id='$id'");

    // Delete from results table where file_id matches the id being deleted from files table
    $query_results = mysqli_query($con, "DELETE FROM results WHERE file_id='$id'");

    if($query_files && $query_results){
        echo "<script>window.location.href='history.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
}

 } ?>

