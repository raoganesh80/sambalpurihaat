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
    $active_page_name = 'vendor_details';
    $active_section_name = 'vendors';
    $link = '
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    ';
    include('include/header.php');
?>
<!-- ============================================ -->
<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $db = new VendorDB;
      $result = $db->updateSupplierName($_POST['inputSupplierID'],$_POST['inputSupplierName']);
      if(!$result['error']){
        echo '<div class="alert alert-success" role="alert">
        '.$result['msg'].'
        </div>';
      }else{
        echo '<div class="alert alert-warning" role="alert">
        '.$result['msg'].'
        </div>';
      }

    }

?>
        <!-- Modal -->
        <div class="modal fade" id="setSupplierNameModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <div class="modal-body">
              <div class="form-row">
                    <div class="form-group col">
                      <label for="inputSupplierName">Set Supplier Name</label>
                      <input type="text" class="form-control" id="inputSupplierName" name="inputSupplierName"required>
                      <input type="text" class="form-control" id="inputSupplierID" name="inputSupplierID" style="display:none;">
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      <!-- Page Heading -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Create New User </h6>
        </div>
        <div class="card-body">
          <form id="createUser-form">
            <div class="form-row">
                    <div class="col-6 col-lg-3 form-group">
                        <input type="text" class="form-control" placeholder="Full Name" id="username" name="username" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-6 col-lg-3  form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-6 col-lg-3  form-group">
                        <input type="text" pattern="[7-9]{1}[0-9]{9}" title="Phone number with 7-9 and remaing 9 digit with 0-9" class="form-control" placeholder="Mobile No." id="mobile_no" name="mobile_no" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-6 col-lg-3  form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Save User </button>
                    </div> <!-- form-group// -->
                </div>
          </form>
        </div>
      </div>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h1 class="h4 mb-2 text-gray-600">Vendor Details</h1>
            </div>
            <div class="card-body">
              <div class="table-responsive">

              <?php

                $db = new VendorDB;
                $users = $db->getAllUsers();
                if(!empty($users)){
                  $table_heading = array('User ID','Name','Phone No.','Email ID','LogIn With','Registration Date','Supplier Name','Action');
                    echo '<table class="table table-bordered" id="vendorusers-table" width="100%" cellspacing="0"><thead><tr>';
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
                      echo '<td>'.$row['uid'].'</td>';
                      echo '<td>'.$row['fullname'].'</td>';
                      echo '<td>'.$row['phone_no'].'</td>';
                      echo '<td>'.$row['email'].'</td>';
                      echo '<td>'.$row['login_with'].'</td>';
                      echo '<td>'.$row['reg_date'].'</td>';
                      if(empty($row['supplier_name']))
                        echo '<td><span id="tv_supplier'.$row['uid'].'">N/A</span><button title="Set Name" type="button" class="btn m-2 btn-outline-primary" data-toggle="modal"  onclick="setSupplierName(\''.$row['uid'].'\')"><i class="fa fa-edit"></i></button></td>';
                      else
                      echo '<td><span id="tv_supplier'.$row['uid'].'">'.$row['supplier_name'].'</span><button type="button" title="Set Name"  class="btn m-2 btn-outline-primary" data-toggle="modal"  onclick="setSupplierName(\''.$row['uid'].'\')"><i class="fa fa-edit"></i></button></td>';
                      echo '<td><button title="Delete" type="button" onclick="deleteVendorUser(\''.$row["uid"].'\');" class="btn m-2 btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                      echo "</tr>";
                  }
                  echo '</tbody></table>';
                }else{
                  echo '<h4>Empty Users</h4>';
                }
              
              ?>

              </div>
            </div>
          </div>

          <div class="modal fade" id="modal-dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <p>Do you want to delete this User?</p>
              </div>
              <div class="modal-footer">
                <a href="#" id="btnYes" class="btn confirm">Yes</a>
                <a href="#" data-dismiss="modal" aria-hidden="true" class="btn secondary">No</a>
              </div>
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