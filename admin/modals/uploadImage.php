<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $base64Image = $_POST['base64Image'];
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->setCategoryIcon($id,$base64Image);
    if($result){
        $msg='Success';
    }else{
        $msg='Failed';
    }
    echo $msg;
}

?>