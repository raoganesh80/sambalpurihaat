<?php 
    class DbConnect{
        
        private $con;

        function connect(){
            include_once dirname(__FILE__)  . '/Constants.php';
            date_default_timezone_set('Asia/Kolkata');
            $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
            if(mysqli_connect_errno()){
                return null; 
            }
            return $this->con; 
            
            
            
        }
        
    }