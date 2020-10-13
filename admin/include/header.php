<?php 
  require_once '../public/includes/VendorDB.php';
  require_once '../public/includes/ProductDB.php';
  require_once '../public/includes/CustomerDB.php';
  $vendorDB = new VendorDB;
  $inboxMSGs = $vendorDB->getInboxMessages();                 
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="refresh" content="300" > 
  

  <title>SambalpuriHaat | Admin Panel</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/app.css">

  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<!-- jQuery
<script src="js/jquery-2.0.0.min.js" type="text/javascript"></script> -->

<!-- Bootstrap4 files-->
<!-- <script src="js/bootstrap.bundle.min.js" type="text/javascript"></script> -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>

<!-- Font awesome 5 -->
<link href="fonts/fontawesome/css/all.min.css" type="text/css" rel="stylesheet">

<!-- custom style -->
<link href="css/ui.css" rel="stylesheet" type="text/css"/>
<link href="css/responsive.css" rel="stylesheet" />

  <!-- custom javascript -->
  <script src="js/script.js" type="text/javascript"></script>



  <!-- Custom links for this page -->
  <?php echo empty($link) ? '' : $link ?>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <!-- <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div> -->
        <div class="sidebar-brand-text mx-3">SambalpuriHaat</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php echo $active_section_name ==='dashboard' ?'active':'' ?>">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>

      <!-- Nav Item - Charts -->
      <li class="nav-item <?php echo $active_page_name ==='allproducts' ?'active':'' ?>">
      <a class="nav-link " href="allproducts.php"/>
      <i class="fa fa-shopping-cart"></i>
          <span>Products</span></a>
      </li>

      <li class="nav-item <?php echo $active_page_name ==='inbox' ?'active':'' ?>">
        <a class="nav-link" href="inbox.php">
        <i class="fa fa-inbox" aria-hidden="true"></i>
          <span>Message</span></a>
      </li>

      <li class="nav-item <?php echo $active_page_name ==='customer_orders' ?'active':'' ?>">
        <a class="nav-link" href="customer_orders.php">
        <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
          <span>Orders</span></a>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Apps
      </div>

      <!-- Nav Item - Vendors Collapse Menu -->
      <li class="nav-item <?php echo $active_section_name ==='vendors' ?'active':'' ?>">
        <a class="nav-link <?php echo $active_section_name ==='vendors' ?'':'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseVendor" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fa fa-users"></i>
          <span>Vendor App</span>
        </a>
        <div id="collapseVendor" class="collapse <?php echo $active_section_name ==='vendors' ?'show':'' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Vendor Records :</h6>
            <a class="collapse-item <?php echo $active_page_name ==='vendor_details' ?'active':'' ?>" href="vendor_details.php">Vendor Details</a>
            <a class="collapse-item <?php echo $active_page_name ==='vendor_products' ?'active':'' ?>" href="vendor_products.php">Uploaded Products</a>
            
          </div>
        </div>
      </li>

      <!-- Nav Item - Customers Collapse Menu -->
      <li class="nav-item <?php echo $active_section_name ==='customers' ?'active':'' ?>">
        <a class="nav-link <?php echo $active_section_name ==='customers' ?'':'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseCustomers" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fa fa-users"></i>
          <span>Customer App</span>
        </a>
        <div id="collapseCustomers" class="collapse <?php echo $active_section_name ==='customers' ?'show':'' ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Customer Records :</h6>
            <a class="collapse-item <?php echo $active_page_name ==='customer_details' ?'active':'' ?>" href="customer_details.php">All Customers</a>
            
          </div>
        </div>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        System
      </div>

      <!-- Nav Item - Tools Collapse Menu -->
      <li class="nav-item <?php echo $active_section_name ==='Tools' ?'active':'' ?>">
        <a class="nav-link <?php echo $active_section_name ==='Tools' ?'':'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseTools" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fa fa-wrench" aria-hidden="true"></i>
          <span>Tools</span>
        </a>
        <div id="collapseTools" class="collapse <?php echo $active_section_name ==='Tools' ?'show':'' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Application Tools :</h6>
            <a class="collapse-item <?php echo $active_page_name ==='add_categories' ?'active':'' ?>" href="add_categories.php">Add Categories</a>
            <a class="collapse-item <?php echo $active_page_name ==='promotional_suppliers' ?'active':'' ?>" href="promotional_suppliers.php">Promotional Suppliers</a>
            <h6 class="collapse-header">App Header Images :</h6>
            <a class="collapse-item <?php echo $active_page_name ==='vendor_app_change_header_images' ?'active':'' ?>" href="vendor_app_change_header_images.php">VendorApp Images</a>
            <a class="collapse-item <?php echo $active_page_name ==='customer_app_change_header_images' ?'active':'' ?>" href="customer_app_change_header_images.php">CustomerApp Images</a>
            
          </div>
          
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter"><?php echo empty($inboxMSGs)?'':count($inboxMSGs);?></span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <?php
                if(!empty($inboxMSGs)){
                  for($i=0;$i<4;$i++){
                    if(empty($inboxMSGs[$i]['sr_no'])){break;}
                    echo '<a class="dropdown-item d-flex align-items-center" href="inbox.php">
                  <div class="font-weight-bold">
                    <div class="text-truncate">'.$inboxMSGs[$i]['msg'].'</div>
                    <div class="small text-gray-500">'.$inboxMSGs[$i]['name'].' · 58m</div>
                  </div>
                </a>';
                  }
                }
                ?>
                <a class="dropdown-item text-center small text-gray-500" href="inbox.php">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
