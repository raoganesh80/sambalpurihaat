<?php
    require '../../public/includes/VendorDB.php';
    $db = new VendorDB;
    $result = $db->deleteUser($_GET['uid']);
    header('Location: ../vendor_details.php');
?>