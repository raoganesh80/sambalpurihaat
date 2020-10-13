<?php
session_start();
if(isset($_SESSION['LOGIN_STATUS'])){
    if(!$_SESSION['LOGIN_STATUS']===true){
      header('Location: login.php');
    }
}else{
  header('Location: login.php');
}
?>
<!-- ============================================ -->
<?php
    $active_page_name = 'inbox';
    $active_section_name = 'messages';
    $link = '
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    ';
    include('include/header.php');
?>
<!-- ============================================ -->

      <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Messages</h1>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Inbox</h6>
            </div>
            <div class="card-body">
            <div class="card-body">
              <div class="table-responsive">

              <?php

                $db = new VendorDB;
                $messages = $db->getInboxMessages();
                if(!empty($messages)){
                  $table_heading = array("Index","Sender Name", "Message", "Date Time");
                    echo '<table class="table table-bordered" id="message-table" width="100%" cellspacing="0"><thead><tr>';
                    foreach($table_heading as $heading){
                      echo '<th>'.$heading.'</th>';
                    }
                    echo '</tr></thead><tfoot><tr>';
                    foreach($table_heading as $heading){
                      echo '<th>'.$heading.'</th>';
                    }
                    echo '</tr></tfoot><tbody>';

                    foreach($messages as $i => $row){
                      echo "<tr>";
                      echo '<td>'.$row['sr_no'].'</td>';
                      echo '<td>'.$row['name'].'</td>';
                      echo '<td>'.$row['msg'].'</td>';
                      echo '<td>'.$row['sending_time'].'</td>';
                  }
                  echo '</tbody></table>';
                }else{
                  echo '<h4>Empty Messages</h4>';
                }
              
              ?>

              </div>
            </div>
          </div>

<!-- ============================================ -->
<?php
    $script = '
            <!-- Page level plugins -->
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/datatables-demo.js"></script>
        <script src="js/table-orders.js"></script>
    ';
    include('include/footer.php');
?>
<!-- ============================================ -->