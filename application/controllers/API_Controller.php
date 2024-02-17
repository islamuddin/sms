<?php
defined('BASEPATH') or exit('No direct script access allowed');

class API_Controller extends CI_Controller
{


	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contactsModel');
		$this->load->model('messagesModel');

		$this->load->library('form_validation');
		$this->load->helper('date');
		$this->load->library('curl'); 

		// $this->load->helper('url');
		// $this->load->helper('file');
		// $this->load->helper('download');
		// $this->load->library('zip');

		//$this->isLoggedIn();
	}


	function sendSmsNotification($contact, $msg)
    {
        // $msg = "SP testing";
        // $contact = '03323967646';

        $domain = "https://connect.jazzcmt.com/sendsms_url.html";
        $login = "?Username=03053275170&Password=Jazz@123";
        $sender = "&From=SINDHPOLICE";
        $receiver = "&To=" . urlencode($contact);
        $message = "&Message=" . urlencode($msg);

        $url   = $domain;
        $url  .= $login;
        $url  .= $sender;
        $url  .= $receiver;
        $url  .= $message;
        $urltouse =  $url;
        // echo $urltouse; die;
        $response = $this->curl->simple_get($urltouse, false, array(CURLOPT_USERAGENT => true));
		//echo json_encode(["message"=>$response ]); exit;
       return $response . ":" . $urltouse;
        die;
        return 'pending';
    }

	public function sendMessage(){
		header('Content-Type: application/json');
		if($this->input->server('REQUEST_METHOD') === 'POST'){
	
			// echo "<pre>"; print_r($this->input->post()); exit;

			$api_key=$this->input->post("api_key");
			if(empty($api_key)){
				echo json_encode(["error"=>"api_key is required"]); exit;
			}
			if($api_key !== "hrmis3967646"){
				echo json_encode(["error"=>"api_key is invalid"]); exit;
			}

			
			$number=$this->input->post("number");
			$message=$this->input->post("message");
			
			if(empty($number)){
				echo json_encode(["error"=>"number is required"]); exit;
			}
			else if(empty($message)){
				echo json_encode(["error"=>"message is required"]); exit;
			}
			else{
				try {
					
                    $this->sendSmsNotification($number, $message);
					echo json_encode(["message"=>"Message Sent"]); exit;
                } //catch exception
                catch (Exception $e) {
					echo json_encode(["error"=> $e->getMessage()]); exit;
                }
		   }
	

		}else {
			echo json_encode(["error"=>"invalid request"]); exit;
		}

	}
	function generateOTP() {
		$otp = rand(100000, 999999);		
		return $otp;
	}

	public function getOTP(){
		header('Content-Type: application/json');
		if($this->input->server('REQUEST_METHOD') === 'POST'){
	
			// echo "<pre>"; print_r($this->input->post()); exit;

			$api_key=$this->input->post("api_key");
			if(empty($api_key)){
				echo json_encode(["error"=>"api_key is required"]); exit;
			}
			if($api_key !== "hrmis3967646"){
				echo json_encode(["error"=>"api_key is invalid"]); exit;
			}

			
			$number=$this->input->post("number");
			
			if(empty($number)){
				echo json_encode(["error"=>"number is required"]); exit;
			}
			else{
				// todo add function to count request to see usage of API
				// echo json_encode(["otp"=>"644048"]); exit;


				try {
					date_default_timezone_set('Asia/Karachi');
					$date = date('l, F j');

					$otp="644048";
                    $this->sendSmsNotification($number, "Your HRMIS OTP code for $date is : ".$otp);
					echo json_encode(["otp"=>$otp]); exit;
                } //catch exception
                catch (Exception $e) {
					echo json_encode(["error"=> $e->getMessage()]); exit;
                }
		   }
	

		}else {
			echo json_encode(["error"=>"invalid request"]); exit;
		}

	}





	
}
