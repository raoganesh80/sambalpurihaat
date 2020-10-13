<?php

require '../../public/includes/ProductDB.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $db = new ProductDB;
      $result = $db->renameCategory($_POST['inputID'],$_POST['inputCategoryName']);
      if($result){
        
      }else{

      }
      header('Location: ../add_categories.php');
    }

?>