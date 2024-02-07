<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_Controller extends CI_Controller
{
	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contactsModel');
		$this->load->model('messagesModel');
		$this->load->library('form_validation');

		$this->isLoggedIn();
	}

	
	public function dashboard()
	{
		if ($this->isAdmin() != TRUE) {
			$this->load->model('contactsModel');
			 $data['contacts_total'] = $this->contactsModel->getCountAllRecords();		
			 $data['messages_total'] = $this->messagesModel->getCountAllRecords();
			 $data['sms_sent'] = $this->messagesModel->getCountSMSSent();
			 

			$this->load->view('include/header', $this->global);
			$this->load->view('dashboard', $data);
			$this->load->view('include/footer');
		} else {
			$this->loadThis();
		}
	}




	/**
	 * This function is used to load the change password screen
	 */
	function loadChangePass()
	{
		$this->global['pageTitle'] = 'CodeInsect : Change Password';

		$this->load->view('include/header', $this->global);
		$this->load->view('changePassword');
		$this->load->view('include/footer');
	}


	/**
	 * This function is used to change the password of the user
	 */
	function changePassword()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('oldPassword', 'Old password', 'required|max_length[20]');
		$this->form_validation->set_rules('newPassword', 'New password', 'required|max_length[20]');
		$this->form_validation->set_rules('cNewPassword', 'Confirm new password', 'required|matches[newPassword]|max_length[20]');

		if ($this->form_validation->run() == FALSE) {
			$this->loadChangePass();
		} else {
			$oldPassword = $this->input->post('oldPassword');
			$newPassword = $this->input->post('newPassword');

			$resultPas = $this->Visitor_Model->matchOldPassword($this->vendorId, md5($oldPassword));

			if (empty($resultPas)) {
				$this->session->set_flashdata('nomatch', 'Your old password not correct');
				redirect('loadChangePass');
			} else {
				$usersData = array('password' => md5($newPassword), 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

				$result = $this->Visitor_Model->changePassword($this->vendorId, $usersData);

				if ($result > 0) {
					$this->session->set_flashdata('success', 'Password updation successful');
				} else {
					$this->session->set_flashdata('error', 'Password updation failed');
				}

				redirect('loadChangePass');
			}
		}
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





		/**
	 * This function is used to load the set of views
	 */
	function loadThis()
	{
		$this->global['pageTitle'] = 'SMS Management System.. : Access Denied';

		$this->load->view('include/header', $this->global);
		$this->load->view('access');
		$this->load->view('include/footer');
	}


	/**
	 * This function is used to logged out user from system
	 */
	function logout()
	{
		$this->session->sess_destroy();

		redirect('/login');
	}


	
}
