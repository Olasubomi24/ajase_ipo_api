<?php
use Restserver\Libraries\REST_Controller;

require_once APPPATH . 'controllers/v1/Utility.php'; 
require_once("application/libraries/Format.php");
require(APPPATH.'/libraries/REST_Controller.php');

//

class Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	    header('Access-Control-Allow-Headers: Content-Type, x-api-key');
        header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Allow-Origin: *');
	   	if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
		  	die();
			}
    }

        public function user_account_post(){
        $surename = trim(ucfirst($this->input->post('surename')));
        $other_name = trim(ucfirst($this->input->post('other_name')));
        $compound_name = trim(ucfirst($this->input->post('compound_name')));
        $country_residence = trim($this->input->post('country_residence'));
        $phonenumber = trim($this->input->post('phonenumber'));
        $email = trim($this->input->post('email'));
       // $is_donorer= trim($this->input->post('is_donorer'));
        //$is_campaigner = trim($this->input->post('is_campaigner'));

      
        if (($surename == '') ){
            $this->response(array('status_code'=>'1',   'message'=>'Provide Surename'));
        }
              
        if (($other_name == '') ){
            $this->response(array('status_code'=>'1',   'message'=>'Provide Other Name'));
        }
        if($email == ''){
            $this->response(array('status_code'=>'1',  'message'=>'Provide correct email address'));
        }
        if($phonenumber == ''){
            $this -> response (array('status_code'=>'1',   'message'=>'Provide a valid Phone Number'));
        }
        if($compound_name == ''){
            $this -> response (array('status_code'=>'1',   'message'=>'compound_name cannot be empty'));
        }
       
        if( $country_residence == ''){
            $this -> response (array('status_code'=>'1',   'message'=>'country_residence cannot be empty'));
        }


        $utility = new Utility();

        $check_email = $utility->is_email_exist($email);
         if( $check_email['status_code'] != '0'){
            $this->response(array('status_code'=>$check_email['status_code'] ,  'message'=>$check_email['message']));
        }
       try {

         return  $this->response($utility->create_user($surename,$other_name,$compound_name,$country_residence,$phonenumber,$email));

       } catch (Exception $e) {
         //echo $e->getMessage();
         // die();
       $this->response(array('status_code' => '1' ,'message' =>'Registration error '.$e->getMessage()));
   }
      
    }


   
    public function email_subcriber_post(){
        $email = trim($this->input->post('email'));
        if($email == ''){
            $this ->response(array('status_code'=>'1', 'message' =>'Email field cannot be empty'));
        }
     
        $utility = new Utility();
        $check_email = $utility->is_subscriber_exist($email);
         if( $check_email['status_code'] != '0'){
            $this->response(array('status_code'=>$check_email['status_code'] ,  'message'=>$check_email['message']));
        }
       try {

         return  $this->response($utility->email_subscriber($email));

       } catch (Exception $e) {
         //echo $e->getMessage();
         // die();
       $this->response(array('status_code' => '1' ,'message' =>'Subscriber error '.$e->getMessage()));
    }
 
    }

    public function email_subcriber_get(){
        $utility = new Utility();
        return $this->response($utility ->get_email_subcribers());
    }
    
    
}


?>