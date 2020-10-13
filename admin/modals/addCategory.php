<?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $categoryName = $_POST['category_name'];
        $subCategoryID = $_GET['id'];
        if(!empty($categoryName)||!empty($subCategoryID)){
            require '../../public/includes/ProductDB.php';
            $db = new ProductDB;
            $result = $db->addCategory($categoryName,$subCategoryID);
        }
    }
    header('Location: ../add_categories.php');

?>