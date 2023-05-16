<?php

class Utility extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_model');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, x-api-key,client-id');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Origin: *');
        if ("OPTIONS" === $_SERVER['REQUEST_METHOD']) {
            die();
        }
    }

    // CALL API
    public function call_api($method, $url, $header, $data = false)
    {
        
        $curl = curl_init();
        // return $response = array('status' => FALSE,'response' => $urlll ,'message' => $data );
        // return $data;
        switch ($method) {
            case "POST":
                //   return $response = array('status' => FALSE,'response' => $url ,'message' => $data );
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                   
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "Q_POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                    $data = http_build_query($data);
                   
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                     $url = sprintf("%s?%s", $url, http_build_query($data));

                if ($data) {
                    $url = $url . "/$data";
                }

        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
        }

        return $response;
    }


    //To check if exist
    function is_email_exist($email){
        $response = array("status_code" => "0" , "message" => "Users not found");
        $query = $this->db->query("select email from user_accounts where email = '$email'")->result_array();
        if ( sizeof($query ) > 0){
            $response = array("status_code" => "1" , "message" => "User details already exist");  
        }
        return $response;
    }

     function is_subscriber_exist($email){
        $response = array("status_code" => "0" , "message" => "Email not found");
        $query = $this->db->query("select email from email_subscribers where email = '$email'")->result_array();
        if ( sizeof($query ) > 0){
            $response = array("status_code" => "1" , "message" => "Email exist already on our mailing list, Kindly choose another mail");  
        }
        return $response;
    }

    //For Random Key 
    function get_operation_id($type){
        $value =  date('YmdHis');
        if($type == "NU"){
          $value = $value.$this->random_number(10);
        }elseif($type == "AN"){
          $value = $value.$this->random_alphanumeric(10);
        }elseIf($type == "AL"){
          $value = $value.$this->random_alphabet(10);
        }
        return $value;
      }

      function random_alphanumeric($maxlength = 17) {
        $chart = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
                         "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N" , "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $return_str = "";
        for ( $x=0; $x<=$maxlength; $x++ ) {
            $return_str .= $chart[rand(0, count($chart)-1)];
        }
        return $return_str;
    }
    function random_alphabet($maxlength = 17) {
        $chart = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
                       "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N" , "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $return_str = "";
        for ( $x=0; $x<=$maxlength; $x++ ) {
            $return_str .= $chart[rand(0, count($chart)-1)];
        }
        return $return_str;
      }
      function random_number($maxlength = 17) {
        $chart = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $return_str = "";
        for ( $x=0; $x<=$maxlength; $x++ ) {
            $return_str .= $chart[rand(0, count($chart)-1)];
        }
        return $return_str;
      }
      


    public function create_user($surename,$other_name,$compound_name,$country_residence,$phonenumber,$email){
        $insert_dt = date('Y-m-d H:i:s');
        $response = array();
        $query1 = "INSERT into user_accounts(surename,other_name,compound_name,country_residence,phonenumber,email,insert_dt)
                    VALUES ('$surename','$other_name','$compound_name','$country_residence','$phonenumber','$email', '$insert_dt')";

        $this->db->query($query1);
        $this->db->trans_commit();

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $response =   array('status_code' => '1','message' => "Ajase-Ipo Decendant Account Creation Unsuccessful");
        } else {
            $this->db->trans_commit();
             $response =  array('status_code' => '0' ,'message' => 'Ajase-Ipo Decendant Account Creation Successful');
            //  $message_content ='Congratulations on taking your first step to share goodness and benfit humanity through mosquepay.
            //  You can start by creating a campaign and updating your other account details. <br> To ask islamic related questions, please check out our Ask A Sheikh section
            //  <br> <b>Thanks for joining the family that shares goodness globally</b>';
            // $subject = 'Welcome to MosquePay';
            // $this->email_message($email,$user_name,$subject,$message_content);
        }
        return $response;
    }


}