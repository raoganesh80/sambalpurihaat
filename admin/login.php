<?php
session_start();

if(isset($_SESSION['LOGIN_STATUS'])){
  if($_SESSION['LOGIN_STATUS']===true){
    header('Location: index.php');
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = $_POST['InputUsername'];
  $password = $_POST['InputPassword'];

  if($username === 'Admin' AND $password === 'Admin'){
    $_SESSION['LOGIN_STATUS'] = true;
    header('Location: index.php');
    exit;
  }
  else{
    $_SESSION['LOGIN_STATUS'] = false;
  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SambalpuriHaat | Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
  

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap');
    .login-image{
      background-color:Crimson;
      padding-top:150px;
      font-size:40px;
      font-family: 'Noto Serif', serif;
    }
  </style>

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block login-image text-white text-uppercase text-center">SambalpuriHaat</div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="InputUsername" name="InputUsername"aria-describedby="emailHelp" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="InputPassword" name="InputPassword" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                    
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
