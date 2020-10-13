<?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $categoryName = $_POST['category_name'];
        $mainCategoryID = $_GET['id'];
        if(!empty($categoryName)||!empty($mainCategoryID)){
            require '../../public/includes/ProductDB.php';
            $db = new ProductDB;
            $result = $db->addSubCategory($categoryName,$mainCategoryID);
        }
    }
    header('Location: ../add_categories.php');

?>