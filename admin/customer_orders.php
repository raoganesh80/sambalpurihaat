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
    $active_page_name = 'customer_orders';
    $link = '
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    ';
    include('include/header.php');
?>
<!-- ============================================ -->
      <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Customers Orders</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">All Customer Orders</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                
              <?php

                  $db = new CustomerDB;
                  $orders = $db->getAllOrders();
                  //print_r($orders);
                  if(!empty($orders)){
                  $table_heading = array('OrderID', 'Product List','Order By','Date','Order Status');
                  echo '<table class="table table-bordered" id="order-table" width="100%" cellspacing="0"><thead><tr>';
                  foreach($table_heading as $heading){
                    echo '<th>'.$heading.'</th>';
                  }
                  echo '</tr></thead><tfoot><tr>';
                  foreach($table_heading as $heading){
                    echo '<th>'.$heading.'</th>';
                  }
                  echo '</tr></tfoot><tbody>';

                  foreach($orders as $i => $row){
                    echo "<tr>";
                    echo "<td>". $row['orderID'] . "</td>";
                    echo "<td>";
                      foreach($row['productList'] as $key => $val){
                        $variant_ids = array();
                        $quantity = array();
                        for($i=0;$i<count($val);$i++){
                          array_push($variant_ids,$val[$i]['variant_id']);
                          array_push($quantity,$val[$i]['quantity']);
                        }
                        //print_r($variant_ids);
                        echo '<button type="button" id="'.$key.'" class="btn btn-outline-primary m-2 text-uppercase productViewBtn" onclick="getOrder(\''.$key.'\','.json_encode($variant_ids).','.json_encode($quantity).');" data-toggle="modal">'.$key.'</button><br>';
                        
                      }
                    echo "</td>";
                    $user = $db->getUser($row['userID']);
                    echo "<td>". $user['fullname']."<br>". $user['phone_no'] ."<br>" .$user['email'] . "</td>";
                    echo "<td>". $row['orderDate'] . "</td>";
                    if($row['orderStatus']==="Active"){
                      echo "<td><span id='text-order-status' class='text-success'>". $row['orderStatus'] . "</span><br><button class='btn btn-sm btn-outline-primary' onclick='orderPlaced(\"".$row['orderID']."\");'>Placed</button></td>";
                    }else{
                      echo $row['orderStatus']==="Received"?"<td class='text-primary'>". $row['orderStatus'] . "</td>":"<td class='text-danger'>". $row['orderStatus'] . "</td>";
                    }
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


  <!-- Modal -->
<div class="modal fade" id="productView" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase" id="ModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="container">
        <h2 id="product_title">Hello World</h2>
        <h6 id="supplier_name" class="text-danger">Brand Name</h6>
        <p id="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores nisi tempora, molestias neque qui reprehenderit porro sequi ipsam vero recusandae dolore optio harum aliquid aperiam dolorem alias consequuntur ab labore!</p>
        <div id="variants" class="float">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Sub Total</th>
                      <th id="sub_total"></th>
                    </tr>
                </tfoot>
            <tbody id="tableBody">
              
            </tbody>
          </table>
        </div>
        </div>
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