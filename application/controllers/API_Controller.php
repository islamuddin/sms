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
		$this->load->model('projectsModel');
		$this->load->model('otpModel');

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
		$urltouse="";
		try{
			$urltouse =  'https://connect.jazzcmt.com/sendsms_url.html?Username=03053275170&Password=Jazz%40123&From=SINDHPOLICE&To='.urlencode($contact).'&Message='.urlencode($msg);
			$res="";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urltouse);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$res = curl_exec($ch);
			if (curl_errno($ch)) {
				$res =curl_error($ch);
			}
			curl_close($ch);

			return $urltouse."||".$res;
		} catch (\Exception $e) {
			return $urltouse."||".$e->getMessage();
		}
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
			$project = $this->projectsModel->fetch_record_by_api_key($api_key);

			if(empty($project)){
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
					$otp=$this->generateOTP(); // todo limit OTP generation against project & number for 24 hours 
					// date_default_timezone_set('Asia/Karachi');
					// $date = date('l, F j');
					// $message="Your one-time password (OTP) for ".$project->name." login is ".$otp.". This code is valid for 24 hours only. Please don't share this OTP with anyone. If you did not request this code, please ignore this message or contact us for support. Thank you!";
					$message="Your OTP for ".$project->name." login is ".$otp.". This code is valid for 24 hours only. Please don't share this OTP with anyone. Thank you!";
                    $response=$this->sendSmsNotification($number, $message);
					$resArray=explode("||",$response);
					$data = array(
						'project_id' => $project->id,
						'otp' => $otp,
						'message' => $message,
						'number' => $number,
						'url' => $resArray[0],
						'response' => $resArray[1],
						'status' => ($resArray[1]==='Message Sent Successfully!')? 1:0,
					);
					$this->otpModel->saveRecord($data);

					if($resArray[1]!='Message Sent Successfully!'){
						echo json_encode(["status"=>'failed',"response"=> $resArray[1],'number'=> $number]); exit;
					}else{
						echo json_encode(["status"=>'sent',"response"=> $resArray[1],'otp'=> $otp]); exit;
					}						
                } //catch exception
                catch (Exception $e) {
					echo json_encode(["status"=>'failed',"response"=> $e->getMessage(),'number'=> $number]); exit;
                }
		   }
	

		}else {
			echo json_encode(["error"=>"invalid request"]); exit;
		}

	}





	
}
