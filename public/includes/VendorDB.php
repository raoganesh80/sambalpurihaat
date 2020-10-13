<?php

    /*
    *    Author: Ganesh Rao
    *    Post: PHP Rest API For Our Website.
    */

    class VendorDB{

        //the database connection variable
        private $con;

        //inside constructor
        //we are getting the connection link
        function __construct(){
            require_once 'DBConnect.php';
            $db = new DbConnect;
            $this->con = $db->connect();
        }

        /*  The Create Operation 
            The function will insert a new user in our database
        */
        public function createUser($uid, $fullname, $phone_no, $email, $login_with){

            if(!($this->isUserIdExist($uid) || $this->isPhoneNoExist($phone_no))){
                $stmt = $this->con->prepare("INSERT INTO vendor_users (uid, fullname, phone_no, email, login_with,reg_date) VALUES (?,?,?,?,?,?);");
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
            The function will check if we have the user in database
            and the password matches with the given or not 
            to authenticate the user accordingly    
        */
        public function userLoginWithPhone($phone_no){
            $stmt = $this->con->prepare("SELECT uid FROM vendor_users WHERE phone_no = ? and login_with = ?;");
            $stmt->bind_param("ss",$phone_no,$login_with);
            $login_with = "admin";
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }
 
        /*
            The Read Operation
            Function is returning all the users from database
        */
        public function getAllUsers(){
            
            $stmt = $this->con->prepare("SELECT uid, fullname, phone_no, email, login_with,reg_date,supplier_name FROM vendor_users order by reg_date desc;");
            $stmt->execute();
            $stmt->bind_result($uid, $fullname, $phone_no, $email, $login_with,$reg_date,$supplier_name);
            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                $user['fullname'] = $fullname; 
                $user['phone_no'] = $phone_no; 
                $user['email']=$email;
                $user['login_with']=$login_with; 
                $user['reg_date']=$reg_date;
                $user['supplier_name']=$supplier_name;
                
                array_push($users, $user);
            }
            if(!empty($users))             
                return $users; 
            else
                return null;
        }
        public function updateSupplierName($uid,$supplier_name){

            $stmt = $this->con->prepare("select * from vendor_users WHERE supplier_name = ?;");
            $stmt->bind_param("s", $supplier_name);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0){
                $result=array();
                $result['error']=true;
                $result['msg']='This name is already used, please try another name.';
                return $result;
            }
            $stmt->close();
            $stmt = $this->con->prepare("UPDATE vendor_users SET supplier_name = ? WHERE uid = ?");
            $stmt->bind_param("ss", $supplier_name, $uid);
            if($stmt->execute()){
                $result=array();
                $result['error']=false;
                $result['msg']='Successfully Saved';
                return $result;
            }
            $result=array();
            $result['error']=true;
            $result['msg']='Failed to saved';
            return $result;
        }

        public function savePromotional_suppliers($promotional_suppliers_list){
            $status=false;
            if(!empty($promotional_suppliers_list)){
                $stmt = $this->con->prepare("TRUNCATE promotional_suppliers");
                if($stmt->execute()){
                    $stmt = $this->con->prepare("INSERT INTO promotional_suppliers (supplier_id, name) VALUES (?,?);");
                    if($stmt->bind_param("ss",$supplier_id,$supplier_name)){
                        foreach($promotional_suppliers_list as $key => $val){
                            $supplier_id = $val[0];
                            $supplier_name = $val[1];
                            if($stmt->execute()){
                                $status=true;
                            }
                        }
                    }
                }
            }
            //echo "<br>".$this->con->error;
            return $status;
        }

        public function getSelectedPromotionalSuppliers(){
            $stmt = $this->con->prepare("SELECT _sr_no, supplier_id, name FROM promotional_suppliers");
            $stmt->execute();
            $stmt->bind_result($_sr_no,$supplier_id,$name);
            $suppliers = array(); 
            while($stmt->fetch()){ 
                $supplier = array(); 
                $supplier['sr_no'] = $_sr_no; 
                $supplier['supplier_id'] = $supplier_id; 
                $supplier['supplier_name'] = $name;
                array_push($suppliers, $supplier);
            }
            if(!empty($suppliers))             
                return $suppliers; 
            else
                return null;
        }

        public function getUnSelectedSuppliers(){
            $stmt = $this->con->prepare("SELECT uid, fullname, supplier_name FROM vendor_users where uid not in (select supplier_id from promotional_suppliers) ;");
            $stmt->execute();
            $stmt->bind_result($uid, $fullname,$supplier_name);
            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                if(empty($supplier_name)){
                    $user['supplier_name']=$fullname;
                }else{
                    $user['supplier_name']=$supplier_name;
                } 
                array_push($users, $user);
            }
            if(!empty($users))             
                return $users; 
            else
                return null;
        }

        /*
            The Read Operation
            Function is returning all the users from database
        */
        public function getAllProducts(){
            $products = array(); 
            
            $stmt = $this->con->prepare("select vendor_products.product_id, vendor_products.title, vendor_products.description, vendor_users.fullname,vendor_users.supplier_name, vendor_users.uid, vendor_products.save_date from vendor_products inner join vendor_users on vendor_products.vendor_id = vendor_users.uid order by vendor_products.save_date desc");
            $stmt->execute();
            $stmt->bind_result($product_id, $title, $description, $vendor_name,$supplier_name, $vendor_id, $save_date);
            
            while($stmt->fetch()){ 
                $product = array(); 
                $product['product_id'] = $product_id; 
                $product['title'] = $title; 
                $product['description']=$description;
                if(empty($supplier_name)){
                    $product['supplier_name']=$vendor_name;
                }else{
                    $product['supplier_name']=$supplier_name;
                }
                $product['vendor_id']=$vendor_id;
                $product['save_date']=$save_date;
                
                array_push($products, $product);
            }
            $stmt->close();
           $result = array();
            foreach($products as $val){
                $product_variants=array();
                $product_variants['product_id']=$val['product_id'];
                $product_variants['title']=$val['title'];
                $product_variants['description']=$val['description'];
                $product_variants['supplier_name']=$val['supplier_name'];
                $product_variants['vendor_id']=$val['vendor_id'];
                $product_variants['save_date']=$val['save_date'];
                
                $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity, current_quantity, price, image FROM vendor_product_variants where product_id = ?");
                $stmt->bind_param("s", $val['product_id']);
                $stmt->execute();
                if($stmt->bind_result($variant_id, $variant_name, $quantity,$current_quantity, $price, $image)){
                $variants = array();
                    while($stmt->fetch()){ 
                        $variant = array();
                        $variant['variant_id'] = $variant_id;
                        $variant['variant_name'] = $variant_name;
                        $variant['quantity'] = $quantity;
                        $variant['current_quantity'] = $current_quantity;
                        $variant['price'] = $price;
                        $variant['image'] = explode(",",$image);
                        array_push($variants,$variant);
                    }
                    if(!empty($variants))
                        $product_variants['variants']=$variants;
                    
                }
                array_push($result,$product_variants);
                $stmt->close();
            }
            //echo json_encode($result);
            if(!empty($result)) {
                return $result; 
            }
            else
                return null;
        }

        function getProductDetails($pid){
            
            $stmt = $this->con->prepare("select title, description, save_date from vendor_products where product_id = ?;");
            $stmt->bind_param("s",$pid);
            $stmt->execute();
            if($stmt->bind_result($title, $description, $save_date)){
                $stmt->fetch();
                return array("title"=>$title, "description"=>$description, "save_date"=>$save_date);
            }
            return null;
        }


        public function getProducts($uid){
            $products = array(); 
            
            $stmt = $this->con->prepare("SELECT product_id, title, description FROM vendor_products where vendor_id = ? ;");
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $stmt->bind_result($product_id, $title, $description);
            
            while($stmt->fetch()){ 
                $product = array(); 
                $product['product_id'] = $product_id; 
                $product['title'] = $title; 
                $product['description']=$description;
                array_push($products, $product);
            }
            $stmt->close();
           $result = array();
            foreach($products as $val){
                $product_variants=array();
                $product_variants['product_id']=$val['product_id'];
                $product_variants['title']=$val['title'];
                $product_variants['description']=$val['description'];
                
                $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity, current_quantity, price, image FROM vendor_product_variants where product_id = ?");
                $stmt->bind_param("s", $val['product_id']);
                $stmt->execute();
                if($stmt->bind_result($variant_id, $variant_name, $quantity,$current_quantity, $price, $image)){
                $variants = array();
                    while($stmt->fetch()){ 
                        $variant = array();
                        $variant['variant_id'] = $variant_id;
                        $variant['variant_name'] = $variant_name;
                        $variant['quantity'] = $quantity;
                        $variant['current_quantity'] = $current_quantity;
                        $variant['price'] = $price;
                        $variant['image'] = explode(",",$image);
                        array_push($variants,$variant);
                    }
                    if(!empty($variants))
                        $product_variants['variants']=$variants;
                    
                }
                array_push($result,$product_variants);
                $stmt->close();
            }
            //echo json_encode($result);
            if(!empty($result)) {
                return $result; 
            }
            else
                return null;
        }

        public function deleteProducts($product_ids){
            $status = true;
            if(!empty($product_ids)){
                foreach($product_ids as $pid){

                    $stmt = $this->con->prepare("SELECT product_id FROM vendor_products WHERE product_id = ?;");
                    $stmt->bind_param("s",$pid);
                    $stmt->execute();
                    $stmt->store_result();
                    $result = $stmt->num_rows > 0;
                    $stmt->close();

                    if(!$result){
                        echo '<br>'.$pid . ' not found!';
                        $status = false;
                        continue;
                    }

                    $stmt = $this->con->prepare("delete from vendor_product_variants where product_id = ?;");
                    $stmt->bind_param("s", $pid);
                    if(!$stmt->execute()){
                    $status = false;
                    }
                    $stmt->close();

                    $stmt = $this->con->prepare("delete from vendor_products where product_id = ?;");
                    $stmt->bind_param("s", $pid);
                    if(!$stmt->execute()){
                        $status = false;
                    }
                    $stmt->close();
                }
                return $status;
            }
            return false;
        }

        public function getVariants($pid){
            
            $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity,current_quantity, price, image FROM vendor_product_variants where product_id = ?");
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            if($stmt->bind_result($variant_id, $variant_name, $quantity,$current_quantity, $price, $images)){
            $variants = array();
                while($stmt->fetch()){ 
                    $variant = array();
                    $variant['variant_id'] = $variant_id;
                    $variant['variant_name'] = $variant_name;
                    $variant['quantity'] = $quantity;
                    $variant['current_quantity'] = $current_quantity;
                    $variant['price'] = $price;
                    $variant['images'] = explode(",",$images);
                    array_push($variants,$variant);
                } 
            }
            $stmt->close();
            //echo json_encode($result);
            if(!empty($variants)) {
                return $variants; 
            }
            else
                return null;
        }

        /*
            The Read Operation
            This function reads a specified user from database
        */
        public function getUser($uid){
            
            $stmt = $this->con->prepare("SELECT fullname, phone_no, email, login_with FROM vendor_users WHERE uid = ?");
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $stmt->bind_result($fullname, $phone_no, $email, $login_with);
            if($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                $user['fullname'] = $fullname; 
                $user['phone_no'] = $phone_no; 
                $user['email']=$email;
                $user['login_with']=$login_with;
                return $user; 
            }
            return null;
        }
        
        public function getUserByEmail($uid,$email){
            
            $stmt = $this->con->prepare("SELECT uid, fullname, phone_no, email, login_with FROM vendor_users WHERE uid = ? or email = ?");
            $stmt->bind_param("ss",$uid, $email);
            $stmt->execute();
            $stmt->bind_result($uid, $fullname, $phone_no, $email, $login_with);
            if($stmt->fetch()){ 
                $user = array(); 
                $user['uid'] = $uid; 
                $user['fullname'] = $fullname; 
                $user['phone_no'] = $phone_no; 
                $user['email']=$email;
                $user['login_with']=$login_with;
                return $user; 
            }
            return null;
        }


         /*
            The Update Operation
            The function will update an existing user
            from the database 
        */
        public function updateUser($uid, $fullname, $phone_no, $email){
            $stmt = $this->con->prepare("UPDATE vendor_users SET fullname = ?, phone_no= ?, email = ? WHERE uid = ?");
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
            $stmt = $this->con->prepare("DELETE FROM vendor_users WHERE uid = ?");
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

            $stmt = $this->con->prepare("SELECT uid FROM vendor_users WHERE email = ?");
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

            $stmt = $this->con->prepare("SELECT uid FROM vendor_users WHERE phone_no = ?");
            $stmt->bind_param("s",$phone_no);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;

        }

        public function getUidByPhoneNo($phone_no){

            $stmt = $this->con->prepare("SELECT uid FROM vendor_users WHERE phone_no = ?");
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

            $stmt = $this->con->prepare("SELECT uid FROM vendor_users WHERE uid = ?");
            $stmt->bind_param("s",$uid);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;

        }
        
        /*
            The Write Operation
            The function is save products in database
        */
        public function saveProduct($product_id,$title,$description,$variants,$vendor_id){

            $status = false;
            $stmt = $this->con->prepare("INSERT INTO vendor_products (product_id, title, description,vendor_id,save_date) VALUES (?,?,?,?,?);");
            $stmt->bind_param("sssss",$product_id,$title,$description,$vendor_id,$save_date);
            $save_date = date("Y-m-d").' '.date("H:i:s");
            if($stmt->execute()){
                
                $status = true;
            }
            else{
                //echo "<br>".$this->con->error;
                return FAILED;
            }
            $stmt->close();
            $stmt = $this->con->prepare("INSERT INTO vendor_product_variants (variant_name, quantity, current_quantity, price, image,product_id) VALUES (?,?,?,?,?,?);");
            if($stmt->bind_param("siidss",$variant_name,$quantity,$current_quantity,$price,$imageFiles,$product_id)){
                
                foreach($variants as $variant){
                
                    $variant_name = $variant['variant_name'];
                    $quantity = $variant['quantity'];
                    $current_quantity = $variant['quantity'];
                    $price = $variant['price'];
                    $images = $variant['image'];
                    $imageFiles = "";
                    if(!empty($images)){
                        foreach($images as $image){
                            $target_dir = '../admin/images/vendor_product/';
                            $imageFileName = rand()."_".time().".jpeg";
                            $target_dir = $target_dir."/".$imageFileName;
                            file_put_contents($target_dir,base64_decode($image));
                            $imageFiles .= $imageFileName.",";
                        }
                    }
                    $imageFiles = rtrim($imageFiles, ",");
                    $product_id = $product_id;
        
                    if($stmt->execute()){
                        $status = true;
                    }else{
                        //echo "<br>".$this->con->error;
                    }
                }
                
            }else{
                //echo "<br>".$this->con->error;
            }
            if($status){
                return PRODUCT_SAVED;
            }else{
                return FAILED;
            }
            
        }

        /*
            The Write Operation
            The function is save products in database
        */
        public function product_save_to_list($product_id,$base_image,$title,$description,$tags,$related_products,$trems_condition,$variants,$vendor_id,$main_category,$sub_category,$category){

            $status = false;
            $stmt = $this->con->prepare("INSERT INTO products (product_id, base_image, title, description,tags,related_products,terms_condition,vendor_id,main_category,sub_category,category,publish_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
            $stmt->bind_param("ssssssssiiis",$product_id,$base_image,$title,$description,$tags,$related_products,$trems_condition,$vendor_id,$main_category,$sub_category,$category,$publish_date);
            $publish_date = date("Y-m-d").' '.date("H:i:s");
            if($stmt->execute()){
                
                $status = true;
            }
            else{
                //echo "<br>".$this->con->error;
                return $status;
            }
            $stmt->close();
            $stmt = $this->con->prepare("INSERT INTO product_variants (variant_id, variant_name, quantity, price, discount, image,product_id) VALUES (?,?,?,?,?,?,?);");
            if($stmt->bind_param("isiddss",$variant_id,$variant_name,$quantity,$price,$discount,$imageFiles,$product_id)){
                
                foreach($variants as $variant){
                
                    $variant_id = $variant['variant_id'];
                    $variant_name = $variant['variant_name'];
                    $quantity = $variant['quantity'];
                    $price = $variant['price'];
                    $discount = $variant['discount'];
                    $images = $variant['images'];
                    $imageFiles = json_encode($images,true);
                    
                    // if(!empty($images)){
                    //     foreach($images as $image){
                    //         $imageFiles .= $image.",";
                    //     }
                    // }
                    // $imageFiles = rtrim($imageFiles, ",");
                    $product_id = $product_id;
        
                    if($stmt->execute()){
                        $status = true;
                    }else{
                        //echo "<br>".$this->con->error;
                    }
                }
                
            }else{
                //echo "<br>".$this->con->error;
            }
            return $status;
        }
        
        /*
            The Write Operation
            The function is update the product details in database
        */
        public function updateProduct($product_id,$title,$description,$variants,$vendor_id){

            $status = false;
            $stmt = $this->con->prepare("UPDATE vendor_products SET title = ?, description = ? where product_id = ? AND vendor_id = ?;");
            $stmt->bind_param("ssss",$title,$description,$product_id,$vendor_id);
        
            if($stmt->execute()){
                
                $status = true;
            }
            else{
                //echo "<br>".$this->con->error;
                return FAILED;
            }
            $stmt->close();

            $stmt = $this->con->prepare("select image from vendor_product_variants where product_id = ? AND variant_id = ?;");
            $stmt->bind_param("si",$product_id,$variant_id);
            $variant_old_images = array();
            foreach($variants as $variant){
                $variant_id = $variant['variant_id'];
                $stmt->execute();
                $stmt->bind_result($old_images);
                if($stmt->fetch()){
                    $variant_old_images[$variant_id] = explode(",",$old_images);
                }
            }
            $stmt->close();

            $stmt = $this->con->prepare("UPDATE vendor_product_variants SET variant_name = ?, quantity = ?, current_quantity = ? , price = ?, image = ? WHERE product_id = ? and variant_id = ?;");
            if($stmt->bind_param("siidssi",$variant_name,$quantity,$current_quantity,$price,$imageFiles,$product_id,$variant_id)){
                
                foreach($variants as $variant){
                    if($variant['variant_id']!=0){
                        $variant_id = $variant['variant_id'];
                        $variant_name = $variant['variant_name'];
                        $quantity = $variant['quantity'];
                        $current_quantity = $variant['quantity'];
                        $price = $variant['price'];
                        $images = $variant['image'];
                        $imageFiles = "";
                        
                        if(!empty($images)){
                            foreach($images as $image){
                                $matched = false;
                                foreach($variant_old_images[$variant_id] as $old_img){
                                    if($old_img === $image){
                                        $matched = true;
                                    }
                                }
                                if($matched){
                                    $imageFiles .= $image.",";
                                    continue;
                                }
                                $target_dir = '../admin/images/vendor_product/';
                                $imageFileName = rand()."_".time().".jpeg";
                                $target_dir = $target_dir."/".$imageFileName;
                                file_put_contents($target_dir,base64_decode($image));
                                $imageFiles .= $imageFileName.",";
                            }
                        }
                        $imageFiles = rtrim($imageFiles, ",");
            
                        if($stmt->execute()){
                            $status = true;
                        }else{
                            //echo "<br>".$this->con->error;
                        }
                    }
                }
                
            }else{
                //echo "<br>".$this->con->error;
            }
            $stmt->close();
            $stmt = $this->con->prepare("INSERT INTO vendor_product_variants (variant_name, quantity,current_quantity, price, image,product_id) VALUES (?,?,?,?,?,?);");
            if($stmt->bind_param("siidss",$variant_name,$quantity,$current_quantity,$price,$imageFiles,$product_id)){
                
                foreach($variants as $variant){
                    if($variant['variant_id']==0){
                        $variant_name = $variant['variant_name'];
                        $quantity = $variant['quantity'];
                        $current_quantity = $variant['quantity'];
                        $price = $variant['price'];
                        $images = $variant['image'];
                        $imageFiles = "";
                        if(!empty($images)){
                            foreach($images as $image){
                                $target_dir = '../admin/images/vendor_product/';
                                $imageFileName = rand()."_".time().".jpeg";
                                $target_dir = $target_dir."/".$imageFileName;
                                file_put_contents($target_dir,base64_decode($image));
                                $imageFiles .= $imageFileName.",";
                            }
                        }
                        $imageFiles = rtrim($imageFiles, ",");
                        $product_id = $product_id;
            
                        if($stmt->execute()){
                            $status = true;
                        }else{
                            //echo "<br>".$this->con->error;
                        }
                    }
                }
                
            }else{
                //echo "<br>".$this->con->error;
            }

            if($status){
                return PRODUCT_SAVED;
            }else{
                return FAILED;
            }  
        }
        
        public function saveMessage($uid, $name, $timestamp, $message, $image){

            $stmt = $this->con->prepare("INSERT INTO vendor_messages (uid, name, sending_time, message,image) VALUES (?,?,?,?,?);");
            $stmt->bind_param("sssss",$uid, $name, $timestamp, $message ,$imageFileName);

            $target_dir = '../admin/images/screenshot/';
            $imageFileName = rand()."_".time().".jpeg";
            $target_dir = $target_dir."/".$imageFileName;
            file_put_contents($target_dir,base64_decode($image));
            if($stmt->execute()){
                
                return true;
            }else{
                return false;
            }

        }

        public function alreadySaved($pid){
            $stmt = $this->con->prepare("SELECT product_id FROM products WHERE product_id = ?");
            $stmt->bind_param("s",$pid);
            $stmt->execute();
            $stmt->store_result();
            $result = $stmt->num_rows > 0;
            $stmt->close();
            return $result;
        }

        public function getInboxMessages(){
            $stmt = $this->con->prepare("SELECT serial_no, uid, name, message, sending_time, image FROM vendor_messages order by sending_time desc;");
            $stmt->execute();
            $stmt->bind_result($sr_no, $uid, $name, $msg, $sending_time, $image);
            $messages = array(); 
            while($stmt->fetch()){ 
                $message = array(); 
                $message['sr_no'] = $sr_no; 
                $message['uid'] = $uid; 
                $message['name'] = $name; 
                $message['msg'] = $msg;
                $message['sending_time']=$sending_time;
                $message['image']=$image;
                
                array_push($messages, $message);
            }
            if(!empty($messages))             
                return $messages; 
            else
                return null;
        }

        public function currentVendorProducts($product_id,$variant_id){
            $stmt = $this->con->prepare("SELECT variant_id, current_quantity from vendor_product_variants where product_id = ? and variant_id=?;");
            $stmt->bind_param("si",$product_id,$variant_id);
            $stmt->execute();
            if($stmt->bind_result($variant_id, $quantity)){
                $productQuantity=array();
                while($stmt->fetch()){
                    $product=array();
                    $product['variant_id']=$variant_id;
                    $product['quantity']=$quantity;
                    array_push($productQuantity,$product);
                }
            }
            $stmt->close();
            if(!empty($productQuantity)) {
                return $productQuantity; 
            }
            else
                return null;
        }

        public function updateVendorProductQuantity($product_id,$variant_id,$quantity){
            $status=false;
            $stmt = $this->con->prepare("UPDATE vendor_product_variants SET current_quantity = ?  WHERE product_id=? and variant_id=?;");
            $stmt->bind_param("isi", $quantity, $product_id, $variant_id);
            if($stmt->execute()){
                $status=true;   
            }
            $stmt->close();
            return $status;
        }

    }