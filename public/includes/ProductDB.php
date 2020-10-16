<?php

    /*
    *    Author: Ganesh Rao
    *    Post: PHP Rest API For Our Website.
    */

    class ProductDB{

        //the database connection variable
        private $con;

        //inside constructor
        //we are getting the connection link
        function __construct(){
            require_once 'DBConnect.php';
            $db = new DbConnect;
            $this->con = $db->connect();
        }

        public function deleteProducts($product_ids){
            $status = true;
            if(!empty($product_ids)){
                foreach($product_ids as $pid){

                    $stmt = $this->con->prepare("SELECT product_id FROM products WHERE product_id = ?;");
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

                    $stmt = $this->con->prepare("delete from product_variants where product_id = ?;");
                    $stmt->bind_param("s", $pid);
                    if(!$stmt->execute()){
                    $status = false;
                    }
                    $stmt->close();

                    $stmt = $this->con->prepare("delete from products where product_id = ?;");
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


        function updateProduct($product_id,$base_image,$title,$description,$tags,$related_products,$terms_condition,$variants,$main_category,$sub_category,$category){
            $status = false;
            $stmt = $this->con->prepare("UPDATE products SET base_image=?, title=?, description=?, tags=?, related_products=?, terms_condition=?, main_category=?,sub_category=?,category=? WHERE product_id=?;");
            $stmt->bind_param("ssssssiiis",$base_image,$title,$description,$tags,$related_products,$terms_condition,$main_category,$sub_category,$category,$product_id);
        
            if($stmt->execute()){
                
                $status = true;
            }
            $stmt->close();
            $stmt = $this->con->prepare("UPDATE product_variants SET variant_name = ?, quantity = ?, price = ?, discount=?, image = ?  WHERE product_id = ? and variant_id = ?;");
            if($stmt->bind_param("siddssi",$variant_name,$quantity,$price,$discount,$imageFiles,$product_id,$variant_id)){
                foreach($variants as $variant){
                    $variant_id = $variant['variant_id'];
                    $variant_name = $variant['variant_name'];
                    $quantity = $variant['quantity'];
                    $price = $variant['price'];
                    $discount = $variant['discount'];
                    $imageFiles = json_encode($variant['images'],true);

                    if($stmt->execute()){
                        $status = true;
                    }else{
                        //echo "<br>".$this->con->error;
                    }
                }
            }//echo "<br>".$this->con->error;
            return $status;
        }

        function getProductDetails($pid){
            $stmt = $this->con->prepare("SELECT base_image, title, description, tags, related_products, terms_condition, vendor_id, publish_date, main_category, sub_category, category, publish_status FROM products WHERE product_id = ?; ");
            $stmt->bind_param("s",$pid);
            $stmt->execute();
            if($stmt->bind_result($base_image, $title, $description, $tags, $related_products, $terms_condition, $vendor_id, $publish_date, $main_category, $sub_category, $category, $publish_status)){
                $stmt->fetch();
                return array("base_image"=>$base_image, "title"=>$title, "description"=>$description, "tags"=>$tags, "related_products"=>$related_products, "terms_condition"=>$terms_condition, "vendor_id"=>$vendor_id, "publish_date"=>$publish_date, "main_category"=>$main_category, "sub_category"=>$sub_category, "category"=>$category,"publish_status"=>$publish_status);
            }
            return null;
        }

        // Read products from database
        function getAllProducts(){
            $products = array(); 
            $stmt = $this->con->prepare("SELECT products.product_id, products.base_image, products.title, products.description, products.tags, products.related_products, products.terms_condition, vendor_users.fullname,vendor_users.supplier_name,products.publish_date,products.main_category,products.sub_category, products.category, products.publish_status FROM products inner join vendor_users on products.vendor_id = vendor_users.uid order by products.publish_date desc;");
            $stmt->execute();
            $stmt->bind_result($product_id, $base_image, $title, $description, $tags, $related_products, $terms_condition, $vendor_name,$supplier_name,$publish_date,$main_category,$sub_category,$category,$publish_status);
            
            while($stmt->fetch()){ 
                $product = array(); 
                $product['product_id'] = $product_id; 
                $product['base_image'] = $base_image; 
                $product['title'] = $title;  
                $product['description']=$description;
                $product['tags']=$tags;
                $product['related_products']=$related_products;
                $product['terms_condition']=$terms_condition;
                if(empty($supplier_name)){
                    $product['supplier_name']=$vendor_name;
                }else{
                    $product['supplier_name']=$supplier_name;
                }
                $product['publish_date']=$publish_date;
                $product['main_category']=$main_category;
                $product['sub_category']=$sub_category;
                $product['category']=$category;
                $product['publish_status']=$publish_status;
                array_push($products, $product);
            }
            $stmt->close();
            
           $result = array();
            foreach($products as $val){
                $product_variants=array();
                $product_variants['product_id']=$val['product_id'];
                $product_variants['base_image']=$val['base_image'];
                $product_variants['title']=$val['title'];
                $product_variants['description']=$val['description'];
                $product_variants['tags']=$val['tags'];
                $product_variants['related_products']=$val['related_products'];
                $product_variants['terms_condition']=$val['terms_condition'];
                $product_variants['supplier_name']=$val['supplier_name'];
                $product_variants['publish_date']=$val['publish_date'];
                $product_variants['main_category']=($val['main_category']==0?'Other':$this->getMainCategory($val['main_category']));
                $product_variants['sub_category']=($val['sub_category']==0?'Other':$this->getSubCategory($val['sub_category']));
                $product_variants['category']=($val['category']==0?'Other':$this->getCategory($val['category']));

                $product_variants['publish_status']=$val['publish_status'];
                
                $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity, price,discount, image FROM product_variants where product_id = ?");
                $stmt->bind_param("s", $val['product_id']);
                $stmt->execute();
                if($stmt->bind_result($variant_id, $variant_name, $quantity, $price, $discount, $image)){
                $variants = array();
                    while($stmt->fetch()){ 
                        $variant = array();
                        $variant['variant_id'] = $variant_id;
                        $variant['variant_name'] = $variant_name;
                        $variant['quantity'] = $quantity;
                        $variant['price'] = $price;
                        $variant['discount'] = $discount;
                        $variant['image'] = json_decode($image,true);
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
        // Read products from database
        function getAllPublishedProducts(){
            $products = array(); 
            
            $stmt = $this->con->prepare("SELECT products.product_id, products.base_image, products.title, products.description, products.tags, products.related_products, products.terms_condition, vendor_users.fullname,vendor_users.supplier_name,products.publish_date,products.main_category,products.sub_category,products.category FROM products inner join vendor_users on products.vendor_id = vendor_users.uid where products.publish_status = 'published' order by products.publish_date desc");
            $stmt->execute();
            $stmt->bind_result($product_id, $base_name, $title, $description, $tags, $related_products, $terms_condition, $vendor_name,$supplier_name,$publish_date,$main_category,$sub_category,$category);
            
            while($stmt->fetch()){ 
                $product = array(); 
                $product['product_id'] = $product_id; 
                $product['base_name'] = $base_name; 
                $product['title'] = $title;  
                $product['description']=$description;
                $product['tags']=$tags;
                $product['related_products']=$related_products;
                $product['terms_condition']=$terms_condition;
                if(empty($supplier_name)){
                    $product['supplier_name']=$vendor_name;
                }else{
                    $product['supplier_name']=$supplier_name;
                }
                $product['publish_date']=$publish_date;
                $product['main_category']=$main_category;
                $product['sub_category']=$sub_category;
                $product['category']=$category;
                array_push($products, $product);
            }
            $stmt->close();
           $result = array();
            foreach($products as $val){
                $product_variants=array();
                $product_variants['product_id']=$val['product_id'];
                $product_variants['base_name']=$val['base_name'];
                $product_variants['title']=$val['title'];
                $product_variants['description']=$val['description'];
                $product_variants['tags']=$val['tags'];
                $product_variants['related_products']=$val['related_products'];
                $product_variants['terms_condition']=$val['terms_condition'];
                $product_variants['supplier_name']=$val['supplier_name'];
                $product_variants['publish_date']=$val['publish_date'];
                $product_variants['main_category']=($val['main_category']==0?'Other':$this->getMainCategory($val['main_category']));
                $product_variants['sub_category']=($val['sub_category']==0?'Other':$this->getSubCategory($val['sub_category']));
                $product_variants['category']=($val['category']==0?'Other':$this->getCategory($val['category']));
                
                $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity, price, discount, image FROM product_variants where product_id = ?");
                $stmt->bind_param("s", $val['product_id']);
                $stmt->execute();
                if($stmt->bind_result($variant_id, $variant_name, $quantity, $price, $discount, $image)){
                $variants = array();
                    while($stmt->fetch()){ 
                        $variant = array();
                        $variant['variant_id'] = $variant_id;
                        $variant['variant_name'] = $variant_name;
                        $variant['quantity'] = $quantity;
                        $variant['price'] = $price;
                        $variant['discount'] = $discount;
                        $variant['image'] = json_decode($image,true);
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

        //get product 
        public function getOrderedProductInfo($pid,$variant_ids){
            $product = array();
            $stmt = $this->con->prepare("SELECT products.base_image, products.title,products.description,vendor_users.fullname,vendor_users.supplier_name FROM products inner join vendor_users on products.vendor_id = vendor_users.uid where product_id = ?;");
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            $stmt->bind_result($base_image,$title,$description,$vendor_name,$supplier_name);
            if($stmt->fetch()){
                $product['base_image']=$base_image;
                $product['title']=$title;
                $product['description']=$description;
                if(empty($supplier_name)){
                    $product['supplier_name']=$vendor_name;
                }else{
                    $product['supplier_name']=$supplier_name;
                }
            }
            //echo "<br>".$this->con->error;
            $stmt->close();
            $variants = array();
            foreach($variant_ids as $vid){
                $stmt = $this->con->prepare("SELECT variant_name, price, discount, image FROM product_variants where product_id = ? and variant_id = ?;");
                $stmt->bind_param("si", $pid,$vid);
                $stmt->execute();
                if($stmt->bind_result($variant_name, $price, $discount, $images)){
                    while($stmt->fetch()){ 
                        $variant = array();
                        $variant['variant_name'] = $variant_name;
                        $variant['price'] = $price-(($price*$discount)/100);
                        $variant['images'] = json_decode($images,true);
                        array_push($variants,$variant);
                    } 
                }
                //echo "<br>".$this->con->error;
                $stmt->close();
            }
            
            if(!empty($variants)) {
                $product['variants']=$variants;
            }
            if(!empty($product)){
                return $product;
            }
            return null;
        }

        // get variant
        public function getVariants($pid){
            
            $stmt = $this->con->prepare("SELECT variant_id, variant_name, quantity, price, discount, image FROM product_variants where product_id = ?");
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            if($stmt->bind_result($variant_id, $variant_name, $quantity, $price, $discount, $images)){
            $variants = array();
                while($stmt->fetch()){ 
                    $variant = array();
                    $variant['variant_id'] = $variant_id;
                    $variant['variant_name'] = $variant_name;
                    $variant['quantity'] = $quantity;
                    $variant['price'] = $price;
                    $variant['discount'] = $discount;
                    $variant['images'] = json_decode($images,true);
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

        public function addMainCategory($categoryName){
            $stmt = $this->con->prepare("insert into main_category (category_name)values(?);");
            $stmt->bind_param("s", ucfirst($categoryName));
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function RenameMainCategory($categoryID,$category_name){
            $stmt = $this->con->prepare("UPDATE main_category SET category_name=? WHERE id=?;");
            $stmt->bind_param("si", ucfirst($category_name),$categoryID);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function getMainCategory($categoryID){
            $stmt2 = $this->con->prepare("SELECT category_name FROM main_category WHERE id=?;");
            $stmt2->bind_param("i",$categoryID);
            $stmt2->execute();
            $stmt2->bind_result($category_name);
            $stmt2->fetch();
            $stmt2->close();
            if(!empty($category_name)){
                return $category_name;
            }
            return null;
        }

        public function DeleteMainCategory($categoryID){
            $status = false;
            $stmt = $this->con->prepare("DELETE FROM sub_category WHERE main_category_id=?;");
            $stmt->bind_param("i", $categoryID);
            if($stmt->execute()){
                $status = true;
            }
            $stmt->close();
            if($status){
                $stmt = $this->con->prepare("DELETE FROM main_category WHERE id=?;");
                $stmt->bind_param("i", $categoryID);
                if($stmt->execute()){
                    $status=true;
                }
                else{
                    $status=true;
                }
            }
            return $status;
        }

        public function setCategoryIcon($id,$base64Image){
            $stmt = $this->con->prepare("UPDATE main_category SET icon = ? where id = ?;");
            $stmt->bind_param("si", $imageFileName,$id);

            $target_dir = '../../admin/images/category_icons/';
            $imageFileName = rand()."_".time().".jpeg";
            $target_dir = $target_dir."/".$imageFileName;
            file_put_contents($target_dir,base64_decode($base64Image));

            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function readMainCategory(){
            $stmt = $this->con->prepare("select * from main_category order by category_name desc;");
            $stmt->execute();
            if($stmt->bind_result($id, $category_name, $icon)){
                $main_category=array();
                while($stmt->fetch()){
                    $category=array();
                    $category['id']=$id;
                    $category['category_name']=ucfirst($category_name);
                    $category['icon']=$icon;
                    array_push($main_category,$category);
                }
            }
            $stmt->close();
            if(!empty($main_category)) {
                return $main_category; 
            }
            else
                return null;
        }

        public function addSubCategory($categoryName,$mainCategoryID){
            $stmt = $this->con->prepare("insert into sub_category (sub_category_name,main_category_id)values(?,?);");
            $stmt->bind_param("si", $categoryName,$mainCategoryID);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function getSubCategory($categoryID){
            $stmt = $this->con->prepare("SELECT sub_category_name FROM sub_category WHERE id=?;");
            $stmt->bind_param("i",$categoryID);
            $stmt->execute();
            $stmt->bind_result($category_name);
            $stmt->fetch();
            $stmt->close();
            if(!empty($category_name)){
                return $category_name;
            }
            return null;
        }

        public function RenameSubCategory($categoryID,$category_name){
            $stmt = $this->con->prepare("UPDATE sub_category SET sub_category_name=? WHERE id=?;");
            $stmt->bind_param("si", ucfirst($category_name),$categoryID);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function DeleteSubCategory($categoryID){
            $status = false;
            $stmt = $this->con->prepare("DELETE FROM sub_category WHERE id=?;");
            $stmt->bind_param("i", $categoryID);
            if($stmt->execute()){
                $status=true;
            }
            return $status;
        }

        public function readSubCategory($mainCategoryID){
            $stmt = $this->con->prepare("select id,sub_category_name from sub_category where main_category_id=? order by sub_category_name desc;");
            $stmt->bind_param("i",$mainCategoryID);
            $stmt->execute();
            if($stmt->bind_result($id, $category_name)){
                $sub_category=array();
                while($stmt->fetch()){
                    $category=array();
                    $category['id']=$id;
                    $category['category_name']=ucfirst($category_name);
                    array_push($sub_category,$category);
                }
            }
            $stmt->close();
            if(!empty($sub_category)) {
                return $sub_category; 
            }
            else
                return null;
        }

        public function addCategory($categoryName,$subCategoryID){
            $stmt = $this->con->prepare("insert into category (category_name,sub_category_id)values(?,?);");
            $stmt->bind_param("si", $categoryName,$subCategoryID);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function getCategory($categoryID){
            $stmt = $this->con->prepare("SELECT category_name FROM category WHERE id=?;");
            $stmt->bind_param("i",$categoryID);
            $stmt->execute();
            $stmt->bind_result($category_name);
            $stmt->fetch();
            $stmt->close();
            if(!empty($category_name)){
                return $category_name;
            }
            return null;
        }

        public function renameCategory($categoryID,$category_name){
            $stmt = $this->con->prepare("UPDATE category SET category_name=? WHERE id=?;");
            $stmt->bind_param("si", ucfirst($category_name),$categoryID);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function deleteCategory($categoryID){
            $status = false;
            $stmt = $this->con->prepare("DELETE FROM category WHERE id=?;");
            $stmt->bind_param("i", $categoryID);
            if($stmt->execute()){
                $status=true;
            }
            return $status;
        }

        public function readCategory($subCategoryID){
            $stmt = $this->con->prepare("select id,category_name from category where sub_category_id=? order by category_name desc;");
            $stmt->bind_param("i",$subCategoryID);
            $stmt->execute();
            if($stmt->bind_result($id, $category_name)){
                $categories=array();
                while($stmt->fetch()){
                    $category=array();
                    $category['id']=$id;
                    $category['category_name']=ucfirst($category_name);
                    array_push($categories,$category);
                }
            }
            $stmt->close();
            if(!empty($categories)) {
                return $categories; 
            }
            else
                return null;
        }

        public function publishProduct($product_id){
            $stmt = $this->con->prepare("UPDATE products SET publish_status = ? , publish_date = ? WHERE product_id=?;");
            $stmt->bind_param("sss",$publish_status,$publish_date,$product_id);
			$publish_date =  date("Y-m-d").' '.date("H:i:s");
            $publish_status = 'published';
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function unPublishProduct($product_id){
            $stmt = $this->con->prepare("UPDATE products SET publish_status = ?  WHERE product_id=?;");
            $stmt->bind_param("ss",$publish_status,$product_id);
            $publish_status = 'unpublished';
            if($stmt->execute()){
                return true;    
            }
            return false;
        }

        public function orderPlaced($orderID){
            $status=false;
            $stmt = $this->con->prepare("UPDATE buyer_orders SET order_status = 'Received'  WHERE order_id=?;");
            $stmt->bind_param("i",$orderID);
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
                    require_once dirname(__FILE__) . '/VendorDB.php';
                    foreach(json_decode($productList,true) as $product_id => $product){
                        foreach($product as $value){
                            $currentVendorResult = (new VendorDB)->currentVendorProducts($product_id,$value['variant_id']);
                            
                            if($currentVendorResult[0]['quantity']>=$value['quantity']){
                                $quantity = $currentVendorResult[0]['quantity'] - $value['quantity'];
                                (new VendorDB)->updateVendorProductQuantity($product_id,$value['variant_id'],$quantity);
                            }
                        }
                    }
                }
            }
            return $status;
        }
        
        public function currentProducts($product_id,$variant_id){
            $stmt = $this->con->prepare("SELECT variant_id, quantity from product_variants where product_id = ? and variant_id=?;");
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

        public function updateProductQuantity($product_id,$variant_id,$quantity){
            $status=false;
            $stmt = $this->con->prepare("UPDATE product_variants SET quantity = ?  WHERE product_id=? and variant_id=?;");
            $stmt->bind_param("isi", $quantity, $product_id, $variant_id);
            if($stmt->execute()){
                $status=true;   
            }
            $stmt->close();
            return $status;
        }

        public function saveProductListOfList($list_id,$list_name,$list_items){
            $status=false;
            $stmt = $this->con->prepare("UPDATE product_list_list SET list_name = ? ,list_items = ? WHERE id = ?;");
            $stmt->bind_param("ssi", $list_name, $list_items, $list_id);
            if($stmt->execute()){
                $status=true;   
            }
            $stmt->close();
            return $status;
        }

        public function getProductListOfList(){
            $stmt = $this->con->prepare("select id,list_name,list_items from product_list_list order by id asc;");
            $stmt->execute();
            if($stmt->bind_result($list_id, $list_name, $list_items)){
                $productListOfList = array();
                while($stmt->fetch()){
                    $productList = array();
                    $productList['list_id']=$list_id;
                    $productList['list_name']=ucwords($list_name);
                    $productList['list_items']=$list_items;
                    array_push($productListOfList,$productList);
                }
            }
            $stmt->close();
            if(!empty($productListOfList)) {
                return $productListOfList; 
            }
            else
                return null;
        }

    }
?>