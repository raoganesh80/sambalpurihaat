<?php
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->deleteCategory($_GET['id']);
    header('Location: ../add_categories.php');
?>