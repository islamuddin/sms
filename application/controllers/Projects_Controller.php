<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects_Controller extends CI_Controller
{


	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('projectsModel');

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


	public function add()
	{

		// Load data for dropdowns from the database.

		// Load the form view with dropdown data.
		$this->load->view('include/header', $this->global);
		$this->load->view('projects/add', []);
		$this->load->view('include/footer');

		if($this->input->server('REQUEST_METHOD') === 'POST'){
		}

	}


	


	public function save(){
		// Handle form submission and save data to the 'projects' table.
		$data = array(
			'name' => $this->input->post('name'),
			'api_key' => $this->input->post('api_key')
		);

		// Call the model function to save the record
		$id = $this->projectsModel->saveRecord($data);

		if ($id) {
			// Success: Redirect to a success page or show a success message.
			redirect('projects/all');
		} else {
			// Error: Handle the error, possibly show an error message.
			// You may want to use CI's validation library for more robust validation.
			echo "Error saving the duplicate API Key.<a href='javascript:history.go(-1)'>Go Back</a>"; exit();
		}
	}

	public function edit() {
		$id=$this->input->get('id');
		$data['record'] = $this->projectsModel->fetch_record_by_id($id);

		$this->load->view('include/header', $this->global);
		$this->load->view('projects/edit', $data);
		$this->load->view('include/footer');
	}	
	public function update(){

		if($this->input->server('REQUEST_METHOD') === 'POST'){
			$data = array(
				'name' => $this->input->post('name'),
				'api_key' => $this->input->post('api_key')
				);
		
			$id = $this->input->post('id');
		
			// Load the model and update the record
			$this->load->model('projectsModel');
			$result = $this->projectsModel->updateRecord($id, $data);
		
			if ($result) {
				// Successful update, redirect or set a success message
				$this->session->set_flashdata('update_message', 'Records updated successfully.');
				// redirect('records/all'); // Redirect to the records listing page
				redirect('projects/view?id=' . $id); // Redirect back to the edit page
			} else {
				// Update failed, set an error message
				$this->session->set_flashdata('error', 'Failed to update the record. Please try again.');
				redirect('projects/edit/' . $id); // Redirect back to the edit page
			}			
		}

	}
	
	public function view() {
		// var_dump('working');
		// die;
		$id=$this->input->get('id');
		$data['record'] = $this->projectsModel->getRecordById($id);	
		$data['messages'] = [];	

		// echo "<pre>";
		// print_r($data);
		// exit;

		$this->load->view('include/header', $this->global);
		$this->load->view('projects/view', $data);
		$this->load->view('include/footer');
	}	


	public function delete() {
		// var_dump('working');
		// die;
		$id=$this->input->get('id');
		$data['record'] = $this->projectsModel->deleteRecordById($id);	
		redirect('projects/all'); 
	}	

    public function deleteSelected() {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');

            if (!empty($ids)) {
                $result = $this->projectsModel->deleteSelectedRecords($ids);

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
		$data['records'] = $this->projectsModel->getAllRecords();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('projects/all', $data);
		$this->load->view('include/footer');
	}







	
}
