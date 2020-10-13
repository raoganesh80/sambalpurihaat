<?php

    /*
    *    Author: Ganesh Rao
    *    Post: PHP Rest API For Our Website.
    */

    class CustomerDB{

        //the database connection variable
        private $con;

        //inside constructor
        //we are getting the connection link
        function __construct(){
            require_once dirname(__FILE__) . '/DBConnect.php';
            $db = new DbConnect;
            $this->con = $db->connect();
        }

        /*  The Create Operation 
            The function will insert a new user in our database
        */
        public function createUser($uid, $fullname, $phone_no, $email, $login_with){

            if(!$this->isUserIdExist($uid)){
                $stmt = $this->con->prepare("INSERT INTO users (uid, fullname, phone_no, email, login_with,reg_date) VALUES (?,?,?,?,?,?);");
                $stmt->bind_param("ssssss",$uid, $fullname, $phone_no, $email, $login_with,$reg_date);
                $reg_date = date("Y-m-d").' '.date("H:i:s");
                if($stmt->execute()){
                    return USER_CREATED;
                }else{
                    return USER_FAILURE;
                }
            }
            return USER_EXISTS;
        }

        /* 
            The Read Operation 
            The function will check if we have the user in database
            and the password matches with the given or not 
            to authenticate the user accordingly    
        */
        public function userLogin($email, $password){
            if($this->isEmailExist($email)){
                $hashed_password = $this->getUsersPasswordByEmail($email); 
                if(password_verify($password, $hashed_password)){
                    return USER_AUTHENTICATED;
                }else{
                    return USER_PASSWORD_DO_NOT_MATCH; 
                }
            }else{
                return USER_NOT_FOUND; 
            }
        }
 
        /*
            The Read Operation
            Function is returning all the users from database
        */
        public function getAllUsers(){
            
            $stmt = $this->con->prepare("SELECT uid, fullname, phone_no, email, login_with, reg_date ,approval FROM users");
            $stmt->execute();
            $stmt->bind_result($uid, $fullname, $phone_no, $email, $login_with,$reg_date,$approval);
            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                $user['fullname'] = $fullname; 
                $user['phone_no'] = $phone_no; 
                $user['email']=$email;
                $user['login_with']=$login_with; 
                $user['reg_date']=$reg_date; 
                $user['approval']=$approval; 
                
                array_push($users, $user);
            }
            if(!empty($users))             
                return $users; 
            else
                return null;
        }
 
        /*
            The Read Operation
            This function reads a specified user from database
        */
        public function getUser($uid){
            
            $stmt = $this->con->prepare("SELECT fullname, phone_no, email, login_with, approval FROM users WHERE uid = ?");
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $stmt->bind_result($fullname, $phone_no, $email, $login_with,$approval);
            if($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                $user['fullname'] = $fullname; 
                $user['phone_no'] = $phone_no; 
                $user['email']=$email;
                $user['login_with']=$login_with;
                $user['approval']=$approval;
                return $user; 
            }
            return null;
        }

        public function changeApproval($uid,$value){
            $stmt = $this->con->prepare("UPDATE users SET approval = ? WHERE uid = ?");
            $stmt->bind_param("is", $value, $uid);
            if($stmt->execute())
                return true; 
            return false; 
        }


         /*
            The Update Operation
            The function will update an existing user
            from the database 
        */
        public function updateUser($uid, $fullname, $phone_no, $email){
            $stmt = $this->con->prepare("UPDATE users SET fullname = ?, phone_no= ?, email = ? WHERE uid = ?");
            $stmt->bind_param("ssss", $fullname, $phone_no, $email, $uid);
            if($stmt->execute())
                return true; 
            return false; 
        }
 
        /*
            The Delete Operation
            This function will delete the user from database
        */
        public function deleteUser($uid){
            $stmt = $this->con->prepare("DELETE FROM users WHERE uid = ?");
            $stmt->bind_param("i", $uid);
            if($stmt->execute())
                return true; 
            return false; 
        }


        /*
            The Read Operation
            The function is checking if the user exist in the database or not
        */
        public function isEmailExist($email){

            $stmt = $this->con->prepare("SELECT uid FROM users WHERE email = ?");
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;

        }

        /*
            The Read Operation
            The function is checking if the user exist in the database or not
        */
        public function isPhoneNoExist($phone_no){

            $stmt = $this->con->prepare("SELECT uid FROM users WHERE phone_no = ?");
            $stmt->bind_param("s",$phone_no);
            $stmt->execute();
            $stmt->bind_result($uid);
            if($stmt->fetch()){
                return $uid;
            }
            return null;

        }

        /*
            The Read Operation
            The function is checking if the user exist in the database or not
        */
        public function isUserIdExist($uid){

            $stmt = $this->con->prepare("SELECT uid FROM users WHERE uid = ?");
            $stmt->bind_param("s",$uid);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;

        }

        public function OrderNow($productList,$orderDate,$userID){
            $status=false;
            $stmt = $this->con->prepare("insert into buyer_orders (product_list,order_date,user_id) values (?,?,?);");
            $stmt->bind_param("sss",$productList,$orderDate,$userID);
            if($stmt->execute()){
                $status = true;
            }
            $stmt->close();
            if($status){
                require_once dirname(__FILE__) . '/ProductDB.php';
                
                foreach(json_decode($productList,true) as $product_id => $product){
                    
                    foreach($product as $value){
                        $currentResult = (new ProductDB)->currentProducts($product_id,$value['variant_id']);
                        
                        if($currentResult[0]['quantity']>=$value['quantity']){
                            $quantity = $currentResult[0]['quantity'] - $value['quantity'];
                            (new ProductDB)->updateProductQuantity($product_id,$value['variant_id'],$quantity);
                        }
                    }
                }

            }
            return $status;
        }

        public function getAllOrders(){
            $stmt = $this->con->prepare("select buyer_orders.order_id, buyer_orders.product_list,users.uid,buyer_orders.order_date, buyer_orders.order_status from buyer_orders INNER join users on buyer_orders.user_id = users.uid order by buyer_orders.order_date desc;");
            $stmt->execute();
            $stmt->bind_result($orderID, $productList,$userID,$orderDate,$orderStatus);
            $orders = array();
            while($stmt->fetch()){ 
                $order = array();
                $order['orderID'] = $orderID; 
                $order['productList'] = json_decode($productList,true);
                $order['userID'] = $userID; 
                $order['orderDate']=$orderDate;
                $order['orderStatus']=$orderStatus;
                array_push($orders, $order);
            }
            if(!empty($orders))             
                return $orders; 
            else
                return null;
        }

        public function getOrders($uid){
            $stmt = $this->con->prepare("SELECT order_id,product_list,order_date,order_status FROM buyer_orders WHERE user_id = ?;");
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $stmt->bind_result($orderID, $productList ,$orderDate,$orderStatus);
            $orders = array();
            while($stmt->fetch()){ 
                $order = array();
                $order['orderID'] = $orderID; 
                $order['productList'] = array();
                $order['orderDate']=$orderDate;
                $order['orderStatus']=$orderStatus;
                require_once dirname(__FILE__) . '/ProductDB.php';
                $db = new ProductDB;
                foreach(json_decode($productList,true) as $key => $value){
                    $variant_ids = array();
                    foreach($value as $k => $v){
                        array_push($variant_ids,$v['variant_id']);
                    }
                    $products = $db->getOrderedProductInfo($key,$variant_ids);
                    // $products['product_id']=$key;
                    for($i=0;$i<count($variant_ids);$i++){
                        $products['variants'][$i]['quantity']=$value[$i]['quantity'];
                    }
                    // echo "<br><br>";
                    // echo json_encode($products);
                    $order['productList'][$key] = $products;
                }
                array_push($orders, $order);
            }
            $stmt->close();
        
            if(!empty($orders))             
                return $orders; 
            else
                return null;
        }


        public function checkOrderExist($orderID){
            $stmt = $this->con->prepare("SELECT order_id FROM buyer_orders WHERE order_id = ?");
            $stmt->bind_param("i",$orderID);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }
        public function cancelOrder($orderID){
            $status=false;
            $stmt = $this->con->prepare("UPDATE buyer_orders SET order_status = 'Cancelled' WHERE order_id = ?;");
            $stmt->bind_param("i", $orderID);
            if($stmt->execute()){
                $status=true;    
            }
            $stmt->close();
            if($status){
                $stmt = $this->con->prepare("select product_list from buyer_orders where order_id=?;");
                $stmt->bind_param("i",$orderID);
                $stmt->execute();
                $stmt->bind_result($productList);
                $stmt->fetch();
                $stmt->close();

                if(!empty($productList)){
                    require_once dirname(__FILE__) . '/ProductDB.php';
                    foreach(json_decode($productList,true) as $product_id => $product){
                        foreach($product as $value){
                            $currentResult = (new ProductDB)->currentProducts($product_id,$value['variant_id']);
                            
                            $quantity = $currentResult[0]['quantity'] + $value['quantity'];
                            (new ProductDB)->updateProductQuantity($product_id,$value['variant_id'],$quantity);
                        }
                    }
                }
            }
            return $status;
        }

        public function countActiveOrders(){
            $stmt = $this->con->prepare("SELECT count(order_id) FROM `buyer_orders` WHERE order_status='active';");
            $stmt->execute();
            $stmt->bind_result($totalActiveOrders);
            $stmt->fetch();
            $stmt->close();
            return $totalActiveOrders;
        } 

    }