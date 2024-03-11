<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Otp_Controller extends CI_Controller
{


	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('otpModel');
		$this->load->model('messagesModel');

		$this->load->library('form_validation');
		$this->load->helper('date');
		// $this->load->helper('url');
		// $this->load->helper('file');
		// $this->load->helper('download');
		// $this->load->library('zip');

		$this->isLoggedIn();
	}

	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn()
	{
		$isLoggedIn = $this->session->userdata('isLoggedIn');

		if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect('/login');
		} else {
			$this->role = $this->session->userdata('role');
			$this->vendorId = $this->session->userdata('userId');
			$this->user_name = $this->session->userdata('user_name');
			$this->name = $this->session->userdata('name');
			$this->roleText = $this->session->userdata('roleText');
			$this->createdDtm = $this->session->userdata('createdDtm');
			$this->global['name'] = $this->name;
			$this->global['role'] = $this->role;
			$this->global['role_text'] = $this->roleText;
			$this->global['createdDtm'] = $this->createdDtm;
			$this->global['pageTitle'] = 'SMS Management System';
			$this->global['branch'] = 'Admin Head Quarter ';
		}
	}

	/**
	 * This function is used to check the access
	 */
	function isAdmin()
	{
		if ($this->role != ROLE_ADMIN) {
			return true;
		} else {
			return false;
		}
	}

	public function all() {
		date_default_timezone_set('Asia/Karachi');

		// todo filter flag
		$type=$this->input->get("type");
		$sent=$this->input->get("sent");
		$failed=$this->input->get("failed");
	
		$data['records']="";
		if(!empty($type)){
			$data['records'] = $this->otpModel->getAllRecords($type);
		}else{
			$data['records'] = $this->otpModel->getAllRecords();
		}
	
		// Load data for the view
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/all', $data);
		$this->load->view('include/footer');
	}

	public function view() {
		$id=$this->input->get('id');
		$number=$this->input->get('number');

		if(!empty($id)){
			$data['type']="id";
			$data['record'] = $this->otpModel->getRecordById($id);	
		}
		if(!empty($number)){
			$data['type']="number";
			$data['messages'] = $this->otpModel->messagesByNumber($number);
		}


		$this->load->view('include/header', $this->global);
		$this->load->view('otp/view', $data);
		$this->load->view('include/footer');
	}	

	public function summaryReport() {
		// Load data for the view
		$data['records'] = $this->otpModel->getReportData();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/summaryReport', $data);
		$this->load->view('include/footer');
	}

	public function datewiseReport() {
		// Load data for the view
		$data['records'] = $this->otpModel->getDateWiseData();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/datewiseReport', $data);
		$this->load->view('include/footer');
	}

	public function monthwiseReport() {
		// Load data for the view
		$data['records'] = $this->otpModel->getMonthWiseData();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/monthwiseReport', $data);
		$this->load->view('include/footer');
	}
public function all_otps_ajax(){
	 // POST data
	 $postData = $this->input->post();
	 
	//  $user_role = $this->session->userdata('role');
	 // var_dump($user_role);
	 // die;

	 // Get data
	 
		 $data = $this->otpModel->get_allotps($postData);
	 
	//  var_dump($data);
	//  die;

	 echo json_encode($data);

}


}
