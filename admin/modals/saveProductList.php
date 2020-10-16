<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $list_id = $_POST['id'];
        $list_name = $_POST['list_name'];
        $list_items = $_POST['list_items'];
        require '../../public/includes/ProductDB.php';
        $db = new ProductDB;
        $result = $db->saveProductListOfList($list_id,$list_name,$list_items);
        if($result){
            echo 'List items saved successfully.';
        }else{
            echo 'Failed to save list items.';
        }
    }

?>