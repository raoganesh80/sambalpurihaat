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
    $active_page_name = 'customer_details';
    $active_section_name = 'customers';
    $link = '
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    ';
    include('include/header.php');
?>
<!-- ============================================ -->

      <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Customers Details</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Customers Registration</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                
              <?php

                  $db = new CustomerDB;
                  $users = $db->getAllUsers();
                  if(!empty($users)){
                  $table_heading = array('Approval','User ID','Name','Phone No.','Email ID','LogIn With','Registration Date');
                  echo '<table class="table table-bordered" id="users-table" width="100%" cellspacing="0"><thead><tr>';
                  foreach($table_heading as $heading){
                    echo '<th>'.$heading.'</th>';
                  }
                  echo '</tr></thead><tfoot><tr>';
                  foreach($table_heading as $heading){
                    echo '<th>'.$heading.'</th>';
                  }
                  echo '</tr></tfoot><tbody>';

                  foreach($users as $i => $row){
                    echo "<tr>";
                    echo '<td><button type="button" onmouseover="changeBtnCSS(this,'.$row["approval"].')" onmouseout="changeBtnCSS(this,'.$row["approval"].')" onclick="changeUserPermission('.$row['approval'].',\''.$row['uid'].'\');" class="btn m-2 '.(($row["approval"]===0)?"btn-outline-danger":"btn-outline-success").'">'.(($row["approval"]===0)?"Deny":"Grant").'</button></td>';
                    echo "<td>".$row['uid']."</td>";
                    echo "<td>".$row['fullname']."</td>";
                    echo "<td>".$row['phone_no']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['login_with']."</td>";
                    echo "<td>".$row['reg_date']."</td>";
                    echo "</tr>";
                  }
                  echo '</tbody></table>';
                }
                else{
                  echo '<h3>Empty Records<h3>';
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