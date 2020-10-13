<?php

    /*
    *    Author: Ganesh Rao
    *    Post: TextLocal API Operations.
    */

    class MsgModule{
        
        function __construct(){
            require_once dirname(__FILE__) . '/textlocal.class.php';
            include_once dirname(__FILE__)  . '/Constants.php';
            
        }

        public function sendOTP($mobile_no){

            $textlocal = new Textlocal(false,false, TEXTLOCAL_API_KEY);

            $numbers = array($mobile_no);
            $sender = 'TXTLCL';
            $otp = mt_rand(10000,99999);
            $message = 'Your VendorApp code is ' . $otp;

            try {
                $result = $textlocal->sendSms($numbers, $message, $sender);
                return $otp;
            } catch (Exception $e) {
                print_r(' Error: ' . $e->getMessage());
            }
            return SEND_SMS_FAILURE;
        }

    }