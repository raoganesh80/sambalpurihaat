<?php
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->DeleteSubCategory($_GET['id']);
    header('Location: ../add_categories.php');
?>