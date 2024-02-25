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




	
	public function import()
	{

		// Load data for dropdowns from the database.

		// Load the form view with dropdown data.
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/import', []);
		$this->load->view('include/footer');

		if($this->input->server('REQUEST_METHOD') === 'POST'){
		}

	}

	public function importAction(){
		$data = array(
			'name' => $this->input->post('name'),
			'contact_no' => $this->input->post('contact_no')
		);
		// todo print above data in echo json_encode to check if data if fine
		// echo json_encode($data);
		// Call the model function to save the record
		$id = $this->otpModel->saveRecord($data);
		// to json api response 
		if($id){
			echo json_encode([ 
				'success' => true,
				'response' => $id,
				'message' => 'Record added successfully'				
			 ]);	exit;
		}else{
			echo json_encode([ 
				'success' => false,
				'message' => 'Error adding record'
				
			 ]); exit;
		}
	}


	public function save(){
		// Handle form submission and save data to the 'contacts' table.
		$data = array(
			'name' => $this->input->post('name'),
			'contact_no' => $this->input->post('contact_no')
		);

		// Call the model function to save the record
		$id = $this->otpModel->saveRecord($data);

		if ($id) {
			// Success: Redirect to a success page or show a success message.
			redirect('otp/all');
		} else {
			// Error: Handle the error, possibly show an error message.
			// You may want to use CI's validation library for more robust validation.
			echo "Error saving the duplicate contact.<a href='javascript:history.go(-1)'>Go Back</a>"; exit();
		}
	}

	public function edit() {
		$id=$this->input->get('id');
		$data['record'] = $this->otpModel->fetch_record_by_id($id);

		$this->load->view('include/header', $this->global);
		$this->load->view('otp/edit', $data);
		$this->load->view('include/footer');
	}	
	public function update(){

		if($this->input->server('REQUEST_METHOD') === 'POST'){
			$data = array(
				'name' => $this->input->post('name'),
				'contact_no' => $this->input->post('contact_no')
				);
		
			$id = $this->input->post('id');
		
			// Load the model and update the record
			$this->load->model('otpModel');
			$result = $this->otpModel->updateRecord($id, $data);
		
			if ($result) {
				// Successful update, redirect or set a success message
				$this->session->set_flashdata('update_message', 'Records updated successfully.');
				// redirect('records/all'); // Redirect to the records listing page
				redirect('otp/view?id=' . $id); // Redirect back to the edit page
			} else {
				// Update failed, set an error message
				$this->session->set_flashdata('error', 'Failed to update the record. Please try again.');
				redirect('otp/edit/' . $id); // Redirect back to the edit page
			}			
		}

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


	public function delete() {
		// var_dump('working');
		// die;
		$id=$this->input->get('id');
		$data['record'] = $this->otpModel->deleteRecordById($id);	
		redirect('otp/all'); 
	}	

    public function deleteSelected() {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');

            if (!empty($ids)) {
                $result = $this->otpModel->deleteSelectedRecords($ids);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Selected records deleted successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting selected records.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No records selected for deletion.']);
            }
        } else {
			echo json_encode(['status' => 'error', 'message' => 'No ajax request']);
        }
    }	


	public function all() {
		// Load data for the view
		$data['records'] = $this->otpModel->getAllRecords();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('otp/all', $data);
		$this->load->view('include/footer');
	}







	
}
