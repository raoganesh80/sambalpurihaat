<?php

require '../../public/includes/CustomerDB.php';
    $db = new CustomerDB;
    if($_GET['currentPermission']==0){
        $db->changeApproval($_GET['uid'],1);
    }else{
        $db->changeApproval($_GET['uid'],0);
    }
    //print_r($_GET);
?>