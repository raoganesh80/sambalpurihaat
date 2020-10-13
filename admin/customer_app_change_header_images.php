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
    $active_page_name = 'customer_app_change_header_images';
    $active_section_name = 'Tools';
    $link='<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>';
    include('include/header.php');
?>
<!-- ============================================ -->
<?php
if($_SERVER['REQUEST_METHOD']=="POST" && !empty($_FILES["fileToUpload"]["name"])){

  if(isset($_POST['app_banner1']))
  {
    $_FILES["fileToUpload"]["name"]='app_banner1.jpg';
  }
  if(isset($_POST['app_banner2'])){
    $_FILES["fileToUpload"]["name"]='app_banner2.jpg';
  }
  if(isset($_POST['app_banner3'])){
    $_FILES["fileToUpload"]["name"]='app_banner3.jpg';
  }
  if(isset($_POST['app_banner4'])){
    $_FILES["fileToUpload"]["name"]='app_banner4.jpg';
  }
  if(isset($_POST['app_banner5'])){
    $_FILES["fileToUpload"]["name"]='app_banner5.jpg';
  }
  $target_dir = "images/banners/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $error_msg="";
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      $error_msg.="File is not an image. ";
      $uploadOk = 0;
    }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    $error_msg.="Sorry, your file is too large. ";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "jpeg" ) {
    $error_msg.="Sorry, only JPG, JPEG files are allowed. ";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $error_msg.="Sorry, your file was not uploaded. ";
    echo '<div class="alert alert-danger" role="alert">
    '.$error_msg.'
  </div>';
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $error_msg.="The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. ";
    } else {
      $error_msg.="Sorry, there was an error uploading your file. ";
    }
    echo '<div class="alert alert-success" role="alert">
    '.$error_msg.'
  </div>';
  }

}
?>

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Change Header Images</h1>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Change the vendor app header images.</h6>
    </div>
    <div class="card-body">
      <div class="container row align-items-center mb-4">
        <img id="profile-img-tag1" src="images/banners/app_banner1.jpg" class="img-lg mr-5">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          Select image to upload(size 750x400):
          <input type="file" name="fileToUpload" id="fileToUpload1">
          <input type="submit" value="Upload Image" name="app_banner1">
        </form>
      </div>
      <div class="container row align-items-center mb-4">
        <img id="profile-img-tag2"  src="images/banners/app_banner2.jpg" class="img-lg mr-5">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          Select image to upload(size 750x400):
          <input type="file" name="fileToUpload" id="fileToUpload2">
          <input type="submit" value="Upload Image" name="app_banner2">
        </form>
      </div>
      <div class="container row align-items-center mb-4">
        <img id="profile-img-tag3"  src="images/banners/app_banner3.jpg" class="img-lg mr-5">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          Select image to upload(size 750x400):
          <input type="file" name="fileToUpload" id="fileToUpload3">
          <input type="submit" value="Upload Image" name="app_banner3">
        </form>
      </div>
      <div class="container row align-items-center mb-4">
        <img id="profile-img-tag4"  src="images/banners/app_banner4.jpg" class="img-lg mr-5">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          Select image to upload(size 750x400):
          <input type="file" name="fileToUpload" id="fileToUpload4">
          <input type="submit" value="Upload Image" name="app_banner4">
        </form>
      </div>
      <div class="container row align-items-center mb-4">
        <img id="profile-img-tag5"  src="images/banners/app_banner5.jpg" class="img-lg mr-5">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          Select image to upload(size 750x400):
          <input type="file" name="fileToUpload" id="fileToUpload5">
          <input type="submit" value="Upload Image" name="app_banner5">
        </form>
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
        <script src="js/product_model.js"></script>
        <script type="text/javascript">
    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $(id).attr("src", e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#fileToUpload1").change(function(){
        readURL(this,"#profile-img-tag1");
    });
    $("#fileToUpload2").change(function(){
      readURL(this,"#profile-img-tag2");
  });
  $("#fileToUpload3").change(function(){
    readURL(this,"#profile-img-tag3");
});
$("#fileToUpload4").change(function(){
  readURL(this,"#profile-img-tag4");
});
$("#fileToUpload5").change(function(){
  readURL(this,"#profile-img-tag5");
});
</script>
    ';
    include('include/footer.php');
?>
<!-- ============================================ -->