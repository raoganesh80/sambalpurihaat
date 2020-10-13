<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST['category_name'];
    if(!empty($categoryName)){
        require '../../public/includes/ProductDB.php';
        $db = new ProductDB;
        $result = $db->addMainCategory($categoryName);
    }
}
    header('Location: ../add_categories.php');

?>