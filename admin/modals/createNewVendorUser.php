<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uid = rand(10000,99999);
    $fullname = $_POST['username'];
    $phone_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    $login_with = 'admin';

    require '../../public/includes/VendorDB.php';
    $db = new VendorDB;
    if(!$db->isEmailExist($email)){
        $result = $db->createUser($uid, $fullname, $phone_no, $email, $login_with);
        if($result == 101){
            echo 'ok';
        }else if($result == 102){
            echo 'Mobile No. already exist';
        }
    }else{
        echo 'Email already exist';
    }
    
}
?>