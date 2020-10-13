<?php
session_start();
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->deleteProducts(array($_GET['pid']));
    header('Location: ../allproducts.php');
?>