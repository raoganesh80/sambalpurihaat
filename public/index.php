<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';
require 'includes/VendorDB.php';
require 'includes/CustomerDB.php';
require 'includes/ProductDB.php';
require 'includes/MsgModule.php';

$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

/* 
	!!!TODO
*	endpoint : /vendor/saveproduct
*	parameters : product_id ,title, description, variants, uid
*	method : POST
*/
$app->post('/vendor/saveproduct',function(Request $request, Response $response){

	if(!haveEmptyParameters(array('product_id','title','variants','uid'), $request, $response)){
		$request_data = $request->getParsedBody();
        
        $product_id = $request_data['product_id'];
		$title = $request_data['title'];
		$description = $request_data['description'];
		$variants = json_decode($request_data['variants'],true);
		$vendor_id = $request_data['uid'];

		$db = new VendorDB;

		$result = $db->saveProduct($product_id,$title, $description, $variants,$vendor_id);
		if($result == PRODUCT_SAVED){

			$message = array();
				$message['error'] = false;
				$message['message'] = 'Product Saved Successfully';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(201);

		}else if($result == FAILED){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Some Error Occurred';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(422);
		}
	}
	return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(422);  
});

/* 
	!!!TODO
*	endpoint : /vendor/updateroduct
*	parameters : product_id ,title, description, variants, uid
*	method : POST
*/
$app->post('/vendor/updateproduct',function(Request $request, Response $response){

	if(!haveEmptyParameters(array('product_id','title','uid','variants'), $request, $response)){
		$request_data = $request->getParsedBody();
        
        $product_id = $request_data['product_id'];
		$title = $request_data['title'];
		$description = $request_data['description'];
		$variants = json_decode($request_data['variants'],true);
		$vendor_id = $request_data['uid'];

		$db = new VendorDB;

		$result = $db->updateProduct($product_id,$title, $description, $variants,$vendor_id);
		if($result == PRODUCT_SAVED){

			$message = array();
				$message['error'] = false;
				$message['message'] = 'Product Update Successfully';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(201);

		}else if($result == FAILED){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Some Error Occurred';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(422);
		}
	}
	return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(422);  
});


/* 
	!!!TODO
*	endpoint : /vendor/products/{uid}
*	method : GET
*/
$app->get('/vendor/products/{uid}', function(Request $request, Response $response,array $args){
	$uid = $args['uid'];

	$db = new VendorDB; 
		$products = $db->getProducts($uid);
		if(empty($products))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User not found"; 
			$response->write(json_encode($response_data));

			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}
		$response_data = array();
		$response_data['error'] = false; 
		$response_data['message'] = "Products data";
		$response_data['products'] = $products; 
		$response->write(json_encode($response_data));
	
	return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	
});

/* 
	!!!TODO
*	endpoint : /vendor/createuser
*	parameters : uid, fullname, phone_no, email, login_with
*	method : POST
*/
	$app->post('/vendor/createuser',function(Request $request, Response $response){
		if(!haveEmptyParameters(array('uid','fullname','phone_no','login_with'), $request, $response)){
			$request_data = $request->getParsedBody();

			$uid = $request_data['uid'];
			$fullname = ucwords($request_data['fullname']);
			$phone_no = $request_data['phone_no'];
			$email = strtolower($request_data['email']);
			$login_with = $request_data['login_with'];

			$db = new VendorDB;

			$result = $db->createUser($uid, $fullname, $phone_no, $email, $login_with);

			if($result == USER_CREATED){

				$message = array();
				$message['error'] = false;
				$message['message'] = 'User Created Successfully';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(201);
			
			}else if($result == USER_FAILURE) {

				$message = array();
				$message['error'] = true;
				$message['message'] = 'Some Error Occurred';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(422);
			
			}else if($result == USER_EXISTS){

				$message = array();
				$message['error'] = true;
				$message['message'] = 'User Already Exist';

				$response->write(json_encode($message));

				return $response
					->withHeader('Content-type','application/json')
					->withStatus(422);
			}
		}
        $message = array();
		$message['error'] = true;
		$response->write(json_encode($message));
        
		return $response
			->withHeader('Content-type','application/json')
			->withStatus(422);
	});

	/* 
	!!!TODO
*	endpoint : /vendor/loginuser
*	parameters : phone_no
*	method : POST
*/
$app->post('/vendor/loginuser', function(Request $request, Response $response){
	
	if(!haveEmptyParameters(array('phone_no'), $request, $response)){

		$request_data = $request->getParsedBody(); 

		$phone_no = $request_data['phone_no'];

		$db = new VendorDB;

		$result = $db->userLoginWithPhone($phone_no);

		if(!$result){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Unauthorized User';

			$response->write(json_encode($message));

			return $response
				->withHeader('Content-type','application/json')
				->withStatus(200);

		}else{

			$message = array();
			$message['error'] = false;
			$message['message'] = 'Authorized User';
			$message['uid'] = (new VendorDB)->getUidByPhoneNo($phone_no);

			$response->write(json_encode($message));
			
			return $response
						->withHeader('Content-type','application/json')
						->withStatus(200);
		}
	}	
	return $response
					->withHeader('Content-type','application/json')
					->withStatus(200);
});

	/* 
	!!!TODO
*	endpoint : /vendor/sendotp
*	parameters : phone_no
*	method : POST
*/
$app->post('/vendor/sendotp', function(Request $request, Response $response){
	
	if(!haveEmptyParameters(array('phone_no'), $request, $response)){

		$request_data = $request->getParsedBody(); 

		$phone_no = $request_data['phone_no'];

		$sms = new MsgModule;

		$otp = $sms->sendOTP($phone_no);

		if($otp == SEND_SMS_FAILURE){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Sending Message Failed';

			$response->write(json_encode($message));

			return $response
				->withHeader('Content-type','application/json')
				->withStatus(200);

		}else{
			$message = array();
			$message['error'] = false;
			$message['message'] = 'OTP send successfully';
			$message['otp'] = $otp;
			$message['uid'] = (new VendorDB)->getUidByPhoneNo($phone_no);

			$response->write(json_encode($message));
			
			return $response
						->withHeader('Content-type','application/json')
						->withStatus(200);
		}
	}	
	return $response
					->withHeader('Content-type','application/json')
					->withStatus(200);
});


/* 
	!!!TODO
*	endpoint : /vendor/getuser
*	parameters : uid
*	method : POST
*/
$app->post('/vendor/getuser', function(Request $request, Response $response){

	if(!haveEmptyParameters(array('uid'), $request, $response)){

		$request_data = $request->getParsedBody(); 

		$uid = $request_data['uid'];
		$db = new VendorDB; 
		$user = $db->getUser($uid);
		if(empty($user))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User not found"; 
			$response->write(json_encode($response_data));

			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}
		$response_data = array();
		$response_data['error'] = false; 
		$response_data['message'] = "Login successfully";
		$response_data['user'] = $user; 
		$response->write(json_encode($response_data));

		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}
	return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
});


/* 
	!!!TODO
*	endpoint : /vendor/getuserbyemail
*	parameters : uid,email
*	method : POST
*/
$app->post('/vendor/getuserbyemail', function(Request $request, Response $response){

	if(!haveEmptyParameters(array('uid','email'), $request, $response)){

		$request_data = $request->getParsedBody(); 
		$uid = $request_data['uid'];
		$email = $request_data['email'];
		$db = new VendorDB; 
		$user = $db->getUserByEmail($uid,$email);
		if(empty($user))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User not found"; 
			$response->write(json_encode($response_data));

			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}
		$response_data = array();
		$response_data['error'] = false; 
		$response_data['message'] = "Login successfully";
		$response_data['user'] = $user; 
		$response->write(json_encode($response_data));

		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}
	return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
});


/* 
	!!!TODO
*	endpoint : /vendor/allusers
*	method : GET
*/
	$app->get('/vendor/allusers', function(Request $request, Response $response){
		$db = new VendorDB; 
		$users = $db->getAllUsers();
		if(empty($users))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User not found"; 
			$response->write(json_encode($response_data));
			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}

		$response_data = array();
		$response_data['error'] = false; 
		$response_data['users'] = $users; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 

	});


/* 
	!!!TODO
*	endpoint : /vendor/updateuser/{uid}
*	parameters : email, vendor_name , fname, lname
*	method : PUT
*/
	$app->put('vendor/updateuser/{uid}', function(Request $request, Response $response, array $args){
		$uid = $args['uid'];
		if(!haveEmptyParameters(array('fname','lname','email'), $request, $response)){
			$request_data = $request->getParsedBody(); 
			$fname = $request_data['fname'];
			$lname = $request_data['lname'];
			if(isset($request_data['vendor_name']))
				$vendor_name = $request_data['vendor_name'];
			else
				$vendor_name = null; 
			$email = $request_data['email']; 
		 
			$db = new VendorDB; 
			if($db->updateUser($uid, $fname, $lname, $vendor_name, $email)){
				$response_data = array(); 
				$response_data['error'] = false; 
				$response_data['message'] = 'User Updated Successfully';
				$user = $db->getUserByEmail($email);
				$response_data['user'] = $user; 
				$response->write(json_encode($response_data));
				return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200);  
			
			}else{
				$response_data = array(); 
				$response_data['error'] = true; 
				$response_data['message'] = 'Please try again later';
				$user = $db->getUserByEmail($email);
				$response_data['user'] = $user; 
				$response->write(json_encode($response_data));
				return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200);  
				  
			}
		}
		
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200);  
	});

/* 
	!!!TODO
*	endpoint : /vendor/updatepassword
*	parameters : email, password , newpassword
*	method : PUT
*/
	$app->put('/vendor/updatepassword', function(Request $request, Response $response){
		if(!haveEmptyParameters(array('currentpassword', 'newpassword', 'email'), $request, $response)){
			
			$request_data = $request->getParsedBody(); 
			$currentpassword = $request_data['currentpassword'];
			$newpassword = $request_data['newpassword'];
			$email = $request_data['email']; 
			$db = new VendorDB; 
			$result = $db->updatePassword($currentpassword, $newpassword, $email);
			if($result == PASSWORD_CHANGED){
				$response_data = array(); 
				$response_data['error'] = false;
				$response_data['message'] = 'Password Changed';
				$response->write(json_encode($response_data));
				return $response->withHeader('Content-type', 'application/json')
								->withStatus(200);
			}else if($result == PASSWORD_DO_NOT_MATCH){
				$response_data = array(); 
				$response_data['error'] = true;
				$response_data['message'] = 'You have given wrong password';
				$response->write(json_encode($response_data));
				return $response->withHeader('Content-type', 'application/json')
								->withStatus(200);
			}else if($result == PASSWORD_NOT_CHANGED){
				$response_data = array(); 
				$response_data['error'] = true;
				$response_data['message'] = 'Some error occurred';
				$response->write(json_encode($response_data));
				return $response->withHeader('Content-type', 'application/json')
								->withStatus(200);
			}
		}
		return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(422);  
	});

/* 
	!!!TODO
*	endpoint : /vendor/deleteproduct/{pid}
*	method : get
*/
	$app->get('/vendor/deleteproduct/{pid}', function(Request $request, Response $response, array $args){
		$pid = $args['pid'];
		$pids = explode(",",$pid);
		$db = new VendorDB; 
		$response_data = array();
		if($db->deleteProducts($pids)){
			$response_data['error'] = false; 
			$response_data['message'] = 'Products has been deleted';    
		}else{
			$response_data['error'] = true; 
			$response_data['message'] = 'Plase try again later';
		}
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200);
	});

/* 
	!!!TODO
*	endpoint : /vendor/sendmessage
*	parameters : uid, name , timestamp, message and image
*	method : POST
*/
$app->post('/vendor/sendmessage', function(Request $request, Response $response){

	if(!haveEmptyParameters(array('uid','name','timestamp','message','image'), $request, $response)){
		$request_data = $request->getParsedBody(); 
		$uid = $request_data['uid'];
		$name = $request_data['name'];
		$timestamp = $request_data['timestamp'];
		$message = $request_data['message'];
		$image = $request_data['image'];


		$db = new VendorDB;

		$result = $db->saveMessage($uid, $name, $timestamp,$message ,$image);

		if($result === true){
			$message = array();
			$message['error'] = false;
			$message['message'] = 'Message Sending Successfully!';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(201);
		}else{
			$message = array();
			$message['error'] = true;
			$message['message'] = 'Message Sending Failed!';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(201);
		}
		return $response
						->withHeader('Content-type','application/json')
						->withStatus(201);

	}

});

/* 
	!!!TODO
*	endpoint : /vendor/promotional_suppliers
*	method : GET
*/
$app->get('/vendor/promotional_suppliers', function(Request $request, Response $response){
	$db = new VendorDB; 
	$suppliers = $db->getSelectedPromotionalSuppliers();
	if(empty($suppliers))
	{
		$response_data = array();
		$response_data['error'] = true; 
		$response_data['message'] = "User not found"; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}

	$response_data = array();
	$response_data['error'] = false; 
	$response_data['suppliers'] = $suppliers; 
	$response->write(json_encode($response_data));
	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200); 

});

	/* ******************************* Customer APIs ***************************************** */

	/* 
	!!!TODO
*	endpoint : /customer/sendotp
*	parameters : phone_no
*	method : POST
*/
$app->post('/customer/sendotp', function(Request $request, Response $response){
	
	if(!haveEmptyParameters(array('phone_no'), $request, $response)){

		$request_data = $request->getParsedBody(); 

		$phone_no = $request_data['phone_no'];

		$sms = new MsgModule;

		$otp = $sms->sendOTP($phone_no);

		if($otp == SEND_SMS_FAILURE){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Sending Message Failed';

			$response->write(json_encode($message));

			return $response
				->withHeader('Content-type','application/json')
				->withStatus(200);

		}else{
			$message = array();
			$message['error'] = false;
			$message['message'] = 'OTP send successfully';
			$message['otp'] = $otp;
			$message['uid'] = (new CustomerDB)->isPhoneNoExist($phone_no);

			$response->write(json_encode($message));
			
			return $response
						->withHeader('Content-type','application/json')
						->withStatus(200);
		}
	}	
	return $response
					->withHeader('Content-type','application/json')
					->withStatus(200);
});

/*
!!!TODO
*	endpoint : /customer/getuser/{uid}
*	parameters : uid
*	method : GET
*/
$app->get('/customer/getuser/{uid}', function(Request $request, Response $response, array $args){

	$uid = $args['uid'];
	
		$db = new CustomerDB; 
		$user = $db->getUser($uid);
		if(empty($user))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User not found"; 
			$response->write(json_encode($response_data));

			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}
		$response_data = array();
		$response_data['error'] = false; 
		$response_data['message'] = "Login successfully";
		$response_data['user'] = $user; 
		$response->write(json_encode($response_data));

		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
});

/* 
	!!!TODO
*	endpoint : /customer/allusers
*	method : GET
*/
$app->get('/customer/allusers', function(Request $request, Response $response){
	$db = new VendorDB; 
	$users = $db->getAllUsers();
	if(empty($users))
	{
		$response_data = array();
		$response_data['error'] = true; 
		$response_data['message'] = "User not found"; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}

	$response_data = array();
	$response_data['error'] = false; 
	$response_data['users'] = $users; 
	$response->write(json_encode($response_data));
	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200); 

});


/* 
	!!!TODO
*	endpoint : /customer/createuser
*	parameters : uid, fullname, phone_no, email, login_with
*	method : POST
*/
$app->post('/customer/createuser',function(Request $request, Response $response){
	if(!haveEmptyParameters(array('uid','fullname','phone_no','email','login_with'), $request, $response)){
		$request_data = $request->getParsedBody();

		$uid = $request_data['uid'];
		$fullname = ucwords($request_data['fullname']);
		$phone_no = $request_data['phone_no'];
		$email = strtolower($request_data['email']);
		$login_with = $request_data['login_with'];

		$db = new CustomerDB;

		$result = $db->createUser($uid, $fullname, $phone_no, $email, $login_with);

		if($result == USER_CREATED){

			$message = array();
			$message['error'] = false;
			$message['message'] = 'User Created Successfully';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(201);
		
		}else if($result == USER_FAILURE) {

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Some Error Occurred';

			$response->write(json_encode($message));

			return $response
						->withHeader('Content-type','application/json')
						->withStatus(422);
		
		}else if($result == USER_EXISTS){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'User Already Exist';

			$response->write(json_encode($message));

			return $response
				->withHeader('Content-type','application/json')
				->withStatus(422);
		}
	}

	return $response
		->withHeader('Content-type','application/json')
		->withStatus(422);
});

/* 
	!!!TODO
*	endpoint : /getallproducts
*	method : GET
*/
$app->get('/getallproducts', function(Request $request, Response $response){
	$db = new ProductDB; 
	$products = $db->getAllPublishedProducts();
	if(empty($products))
	{
		$response_data = array();
		$response_data['error'] = true; 
		$response_data['message'] = "Products not found"; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}

	$response_data = array();
	$response_data['error'] = false; 
	$response_data['products'] = $products; 
	$response->write(json_encode($response_data));
	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200); 

});

/* 
	!!!TODO
*	endpoint : /getproductlistoflist
*	method : GET
*/
$app->get('/getproductlistoflist', function(Request $request, Response $response){
	$db = new ProductDB; 
	$productsListOfList = $db->getProductListOfList();
	if(empty($productsListOfList))
	{
		$response_data = array();
		$response_data['error'] = true; 
		$response_data['message'] = "Products list not found"; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}

	$response_data = array();
	$response_data['error'] = false; 
	$response_data['products'] = $productsListOfList; 
	$response->write(json_encode($response_data));
	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200); 

});

/* 
	!!!TODO
*	endpoint : /getallcategory
*	method : GET
*/
$app->get('/getallcategory', function(Request $request, Response $response){
	$db = new ProductDB; 
	$MainCategory = $db->readMainCategory();
	if(empty($MainCategory))
	{
		$response_data = array();
		$response_data['error'] = true; 
		$response_data['message'] = "MainCategory not found"; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}

	$response_data = array();
	$response_data['error'] = false; 
	$response_data['MainCategory'] = $MainCategory; 
	$response->write(json_encode($response_data));
	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200); 

});

/* 
	!!!TODO
*	endpoint : /customer/ordernow
*	parameters : productList,uid
*	method : POST
*/
$app->post('/customer/ordernow',function(Request $request, Response $response){
	if(!haveEmptyParameters(array('productList','uid'), $request, $response)){
		$request_data = $request->getParsedBody();

		if(!empty(json_decode($request_data['productList'],true))){

			$uid = $request_data['uid'];
			$productList = $request_data['productList'];
			date_default_timezone_set('Asia/Kolkata');
			$orderDate = date("Y-m-d").' '.date("H:i:s");

			$db = new CustomerDB;

			$result = $db->OrderNow($productList,$orderDate,$uid);

			if($result){

				$message = array();
				$message['error'] = false;
				$message['message'] = 'Order Successfully';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(201);
			
			}else{

				$message = array();
				$message['error'] = true;
				$message['message'] = 'Some Error Occurred';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(422);
			
			}
		}else{
			$message = array();
				$message['error'] = true;
				$message['message'] = 'Product not selected';

				$response->write(json_encode($message));

				return $response
							->withHeader('Content-type','application/json')
							->withStatus(422);
		}
	}

	return $response
		->withHeader('Content-type','application/json')
		->withStatus(422);
});

/* 
	!!!TODO
*	endpoint : /customer/getorders
*	parameters : uid
*	method : GET
*/
$app->get('/customer/getorders/{uid}', function(Request $request, Response $response, array $args){

	$uid = $args['uid'];
	if(!empty($uid)){
		$db = new CustomerDB; 
		$orders = $db->getOrders($uid);
		if(empty($orders))
		{
			$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "Orders not found"; 
			$response->write(json_encode($response_data));
			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200); 
		}

		$response_data = array();
		$response_data['error'] = false; 
		$response_data['orders'] = $orders; 
		$response->write(json_encode($response_data));
		return $response
		->withHeader('Content-type', 'application/json')
		->withStatus(200); 
	}else{
		$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "User Id Required"; 
			$response->write(json_encode($response_data));
			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);
	}
	return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);

});

/* 
	!!!TODO
*	endpoint : /customer/cancelorder
*	parameters : order_id
*	method : GET
*/
$app->get('/customer/cancelorder/{order_id}', function(Request $request, Response $response, array $args){

	$orderID = $args['order_id'];
	if(!empty($orderID)){
		$db = new CustomerDB; 
		$check = $db->checkOrderExist($orderID);
		if($check){
			$result = $db->cancelOrder($orderID);
			if($result)
			{
				$response_data = array();
				$response_data['error'] = false; 
				$response_data['message'] = "Order Cancelled Successfully"; 
				$response->write(json_encode($response_data));
				return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200); 
			}
			else{
				$response_data = array();
				$response_data['error'] = true; 
				$response_data['message'] = "Some Error Occurred"; 
				$response->write(json_encode($response_data));
				return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200);
			}
		}else{
			$response_data = array();
				$response_data['error'] = true; 
				$response_data['message'] = "Order id not found"; 
				$response->write(json_encode($response_data));
				return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200);
		}

	}else{
		$response_data = array();
			$response_data['error'] = true; 
			$response_data['message'] = "Order Id Required"; 
			$response->write(json_encode($response_data));
			return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);
	}
	return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);
	

});


	function haveEmptyParameters($required_params, $request, $response){
		$error = false;
		$error_params = '';
		$request_params = $request->getParsedBody();

		foreach($required_params as $param){
			if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
				$error = true;
				$error_params .= $param . ',';
			}
		}

		if($error){
			$error_details = array();
			$error_details['error'] = true;
			$error_details['message'] = 'Required Parameters ' . substr($error_params, 0, -1) . ' are missing or empty';
			$response->write(json_encode($error_details));
		}
		return $error;
	}

	

$app->run();