<?php
session_start();
$_SESSION['LOGIN_STATUS']=false;
header('Location: ../login.php');
exit;
?>