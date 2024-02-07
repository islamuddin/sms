<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Visitor_Controller extends CI_Controller
{
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $createdDtm = '';
	protected $roleText = '';
	protected $ia_signature = '';
	protected $ia_name = '';
	protected $today_on_leave = '';
	protected $today_atleave = '';

	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Visitor_Model');

		$this->load->library('form_validation');
		$this->load->library('image_lib');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->library('zip');

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
			$this->name = $this->session->userdata('name');
			$this->roleText = $this->session->userdata('roleText');
			$this->createdDtm = $this->session->userdata('createdDtm');
			// $this->ia_signature = $this->Visitor_Model->get_active_signature('ia_signature');
			// $this->ia_name = $this->session->userdata('ia_name');
			// $this->db->where('status', 0); // OTHER CONDITIONS IF ANY
			$date = new DateTime("now");
			$curr_date = $date->format('Y-m-d ');
			// $this->db->where('leave_date_from <=', $curr_date);
			// $this->db->where('leave_date_to >=', $curr_date);
			// $this->db->from('tbl_leave_orders'); //TABLE NAME
			// $data['today_on_leave'] = $this->db->count_all_results();

			// $this->today_on_leave = $data['today_on_leave'];


			// $data['today_atleave']= $this->Visitor_Model->get_todat_atleave();
			// $this->today_atleave = $data['today_atleave'];
			// $this->global['today_atleave'] = $this->today_atleave;

			$this->global['today_on_leave'] = $this->today_on_leave;


			$this->global['name'] = $this->name;
			$this->global['role'] = $this->role;
			$this->global['role_text'] = $this->roleText;
			$this->global['createdDtm'] = $this->createdDtm;
			$this->global['pageTitle'] = 'SMS Management System..';
			$this->global['branch'] = 'Admin Head Quarter ';
			// $this->global['ia_signature'] = $this->ia_signature;
			// $this->global['ia_name'] = $this->ia_name;
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

	function isMonitor()
	{
		if ($this->role != ROLE_MONITOR) {
			return true;
		} else {
			return false;
		}
	}
	function isOperator()
	{
		if ($this->role != ROLE_OPERATOR) {
			return true;
		} else {
			return false;
		}
	}

	function isCheckin()
	{
		if ($this->role != ROLE_CHECKIN) {
			return true;
		} else {
			return false;
		}
	}

	function isCheckout()
	{
		if ($this->role != ROLE_CHECKOUT) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function is used to check the access
	 */
	function isTicker()
	{
		// if($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) { return true; }
		// else {return false; }
		if ($this->role = ROLE_ADMIN || $this->role = ROLE_MONITOR || $this->role = ROLE_OPERATOR) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * This function is used to load the set of views
	 */
	function loadThis()
	{
		$this->global['pageTitle'] = 'SMS Management System. : Access Denied';

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





	// =========================================================

	/**
	 * This function is used to load the user list
	 */

	function userListing()
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {

			$users = $this->Visitor_Model->userListing();
			$data = array(
				'users_data' => $users
			);
			$data['userRecords'] = $this->Visitor_Model->userListing();
			$data['roles'] = $this->Visitor_Model->getUserRoles();
			// $this->global['pageTitle'] = 'CARD : User Listing';
			$this->load->view('include/header', $this->global);
			$this->load->view('users', $data);
			$this->load->view('include/footer');
		}
	}



	/**
	 * This function is used to load the add new form
	 */
	function addNew()
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {
			$this->load->model('Visitor_Model');
			$data['roles'] = $this->Visitor_Model->getUserRoles();
			$data['places'] = $this->Visitor_Model->getUserPlace();
			$data['gates'] = $this->Place_Model->get_all_gates();


			$this->load->view('include/header', $this->global);
			$this->load->view('addNew', $data);
			$this->load->view('include/footer');
		}
	}

	/**
	 * This function is used to add new user to the system
	 */
	function addNewUser()
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]|xss_clean');
			$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|xss_clean|max_length[50]');
			$this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
			$this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
			$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|xss_clean');
			$this->form_validation->set_rules('place', 'Place', 'trim|required|numeric');
			$this->form_validation->set_rules('gate', 'Gate', 'trim|required|numeric');

			if ($this->form_validation->run() == FALSE) {
				$this->addNew();
			} else {
				$name = ucwords(strtolower($this->input->post('fname')));
				$cnic_no = $this->input->post('cnic_no');
				$designation = $this->input->post('designation');
				$user_name = $this->input->post('user_name');
				$password = $this->input->post('password');
				$roleId = $this->input->post('role');
				$mobile = $this->input->post('mobile');
				$place = $this->input->post('place');
				$gate = $this->input->post('gate');
				// $vendor = $this->$vendorId;

				$userInfo = array(
					'user_name' => $user_name, 'password' => md5($password), 'roleId' => $roleId, 'name' => $name,'cnic_no' => $cnic_no,'designation' => $designation,
					'mobile' => $mobile, 'place_id' => $place, 'gate_id' => $gate, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s')
				);

				$this->load->model('Visitor_Model');
				$result = $this->Visitor_Model->addNewUser($userInfo);

				if ($result > 0) {
					$this->session->set_flashdata('success', 'New User created successfully');
				} else {
					$this->session->set_flashdata('error', 'User creation failed');
				}

				redirect('addNew');
			}
		}
	}


	/**
	 * This function is used load user edit information
	 * @param number $userId : Optional : This is user id
	 */
	function editOld($userId = NULL)
	{
		if ($this->isAdmin() == TRUE || $userId == 1) {
			$this->loadThis();
		} else {
			if ($userId == null) {
				redirect('userListing');
			}
			$d=$this->Visitor_Model->getUserInfo($userId);
			// var_dump($d);
			// die;

			$data['roles'] = $this->Visitor_Model->getUserRoles();
			$data['userInfo'] = $this->Visitor_Model->getUserInfo($userId);
			
			$data['places'] = $this->Visitor_Model->getUserPlace();
			$data['gates'] = $this->Place_Model->get_all_gates();

			$this->global['pageTitle'] = 'DLS : Edit User';
			$this->load->view('include/header', $this->global);
			$this->load->view('editOld', $data);
			$this->load->view('include/footer');
		}
	}


	/**
	 * This function is used to edit the user information
	 */
	function editUser()
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {
			$this->load->library('form_validation');

			$userId = $this->input->post('userId');

			$this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]|xss_clean');
			$this->form_validation->set_rules('user_name', 'Username', 'trim|required|xss_clean|max_length[128]');
			$this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
			$this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
			$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|xss_clean');

			if ($this->form_validation->run() == FALSE) {
				$this->editOld($userId);
			} else {
				$name = ucwords(strtolower($this->input->post('fname')));
				$cnic_no = $this->input->post('cnic_no');
				$designation = $this->input->post('designation');
				$user_name = $this->input->post('user_name');
				$password = $this->input->post('password');
				$roleId = $this->input->post('role');
				$mobile = $this->input->post('mobile');
				$place = $this->input->post('place');
				$gate = $this->input->post('gate');

				$userInfo = array();

				if (empty($password)) {
					$userInfo = array(
						'user_name' => $user_name, 'roleId' => $roleId, 'name' => $name,'cnic_no' => $cnic_no,'designation' => $designation,
						'mobile' => $mobile, 'place_id' => $place, 'gate_id' => $gate, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s')
					);
				// 	var_dump($userInfo);
				// die;
				} else {
					$userInfo = array(
						'user_name' => $user_name, 'password' => md5($password), 'roleId' => $roleId, 'name' => $name,'cnic_no' => $cnic_no,'designation' => $designation,
						'mobile' => $mobile, 'place_id' => $place, 'gate_id' => $gate, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s')
					);
				}

				$result = $this->Visitor_Model->editUser($userInfo, $userId);
				// var_dump($userInfo);
				// die;

				if ($result == true) {
					$this->session->set_flashdata('success', 'User updated successfully');
				} else {
					$this->session->set_flashdata('error', 'User updation failed');
				}

				redirect('all-users');
			}
		}
	}


	/**
	 * This function is used to delete the user using userId
	 * @return boolean $result : TRUE / FALSE
	 */
	function deleteUser($userId)
	{
		if ($this->isAdmin() == TRUE) {
			echo (json_encode(array('status' => 'access')));
		} else {
			// $userId = $this->input->post('userId');
			$userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

			$result = $this->Visitor_Model->deleteUser($userId, $userInfo);

			// if ($result > 0) {
			// 	echo (json_encode(array('status' => TRUE)));
			// } else {
			// 	echo (json_encode(array('status' => FALSE)));
			// }
			if ($result == true) {
				$this->session->set_flashdata('success', 'User Account Deleted successfully');
			} else {
				$this->session->set_flashdata('error', 'User Account Deletion failed');
			}
			redirect('all-users');
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



	public function index()
	{
		// $this->load->view('include/header', $this->global);
		// $this->load->view('dashboard');
		// $this->load->view('include/footer');

		// if($this->isAdmin() != TRUE)
		//  {
		// 	$this->dashboard();

		// } else if ($this->isMonitor() != TRUE){
		// 	$this->dashboard();
		// } else if ($this->isOperator() != TRUE){
		// 	$this->new_visitor();
		// } else if ($this->isCheckin() != TRUE){
		// 	$this->checkout();
		// } else if ($this->isCheckout() != TRUE){
		// 	$this->checkout();

		// }
	}




	public function chart()
	{
		$data['chart_data'] = $this->Visitor_Model->pie_chart();
		$this->load->view('dash-chartgoogle', $data);
	}
	// NOTIFICATION
	public function user_notifi()
	{
		$v = $this->input->post('view');
		echo  $op = $this->Visitor_Model->fetch_data($v);

		return $op;
	}
	// public function note(){
	// $data['abc'] = $this->Visitor_Model->user_notification();
	// $this->load->view('include/notification',$data);
	// }
	// END NOTIFICATION
	// public function total_visitors(){
	// 	$data= $this->Visitor_Model->totalvisitor();
	// 	echo $data;
	// 	// var_dump($data);
		
		
	// }
	public function dashboard()
	{
		if ($this->isAdmin() != TRUE || $this->isMonitor() != TRUE) {


			$date = new DateTime("now");
            $curr_date = $date->format('Y-m-d ');
			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 1);
            
            $this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['IGP_SINDH'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 63);
            
            $this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['DIGP_Headquarter'] = $this->db->count_all_results();


			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 2);
            
            $this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['Establishment'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 3);
            
            $this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['Finance_and_logistic'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 4);
            
            $this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['Operations'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 5);$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['Admin'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 6);$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['IT'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 7);
			$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['estate_management'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 8);
			$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['legal'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 9); 
			$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['Training'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 10); 
			$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['t_n_t'] = $this->db->count_all_results();

			$this->db->where('is_employee', 0);
			$this->db->where('hav_veh', 0);
            $this->db->where('date(added_date)', $curr_date);
            $this->db->where('department.parent_id', 11); 
			$this->db->from('visitors'); //TABLE NAME
			$this->db->join('department', 'department.id = visitors.sub_branch', 'left');
            $data['research'] = $this->db->count_all_results();


			$data['last_five'] = $this->Visitor_Model->get_last_entry()->result();
			$data['last_five_detected'] = $this->Visitor_Model->get_last_fivedected()->result();


			$data['totalvisitors'] = $this->Visitor_Model->totalvisitor();
			$data['totalvcheckout'] = $this->Visitor_Model->totalv_checkout();
			$data['todayvisitors'] = $this->Visitor_Model->todayvisitor();
			$data['todayvcheckout'] = $this->Visitor_Model->todayv_checkout();
			$data['totalvehicles'] = $this->Visitor_Model->totalvehicle();
			$data['totalveh_checkout'] = $this->Visitor_Model->totalveh_checkout();
			$data['todayvehicles'] = $this->Visitor_Model->todayvehicle();
			$data['todayveh_checkout'] = $this->Visitor_Model->todayveh_checkout();
			$data['totalsuspect'] = $this->Visitor_Model->totalsuspect();
			$data['todaysuspect'] = $this->Visitor_Model->todaysuspect();
			$data['vc'] = $this->Visitor_Model->visitor_counter();
			$data['chart_data'] = $this->Visitor_Model->pie_chart();
			$data['chart_data'] = $this->Visitor_Model->line_chart();
			$data['alldept'] = $this->Department_Model->get_all_dept_hds();

			
			// $data['chart_first'] = $this->load->view('dash-chartgoogle.php', $data, TRUE);
			// $data['chart_first'] = $this->load->view('dash-chartP.php', $data, TRUE);
			// $data['chart_first'] = $this->load->view('dash-chart1.php', $data, TRUE);
			// $data['chart_second'] = $this->load->view('dash-chart2.php', $data, TRUE);
			$this->load->view('include/header', $this->global);
			$this->load->view('dashboard', $data);
			$this->load->view('include/footer');
		// } else if ($this->isMonitor() != TRUE) {
		// 	$data['last_five'] = $this->Visitor_Model->get_last_entry()->result();


		// 	$data['totalvisitors'] = $this->Visitor_Model->totalvisitor();
		// 	$data['todayvisitors'] = $this->Visitor_Model->todayvisitor();
		// 	$data['totalvehicles'] = $this->Visitor_Model->totalvehicle();
		// 	$data['todayvehicles'] = $this->Visitor_Model->todayvehicle();
		// 	$data['totalsuspect'] = $this->Visitor_Model->totalsuspect();
		// 	$data['todaysuspect'] = $this->Visitor_Model->todaysuspect();
		// 	$data['chart_data'] = $this->Visitor_Model->pie_chart();
		// 	$data['chart_data'] = $this->Visitor_Model->line_chart();
		// 	// $data['chart_first'] = $this->load->view('dash-chartgoogle.php', $data, TRUE);
		// 	// $data['chart_first'] = $this->load->view('dash-chartP.php', $data, TRUE);
		// 	// $data['chart_first'] = $this->load->view('dash-chart1.php', $data, TRUE);
		// 	// $data['chart_second'] = $this->load->view('dash-chart2.php', $data, TRUE);
		// 	$this->load->view('include/header', $this->global);
		// 	$this->load->view('dashboard', $data);
		// 	$this->load->view('include/footer');
		} else if ($this->isOperator() != TRUE) {
			$this->new_visitor();
		} else if ($this->isCheckout() != TRUE) {
			$this->checkout_new();
		} else if ($this->isCheckin() != TRUE) {
			$this->checkout();
		} else {
			$this->loadThis();
		}
	}

	// ============= Add Visitor ===============================
	public function test_image()
	{
		// $img = "data:image/jpeg;base64,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";
		// $img = str_replace("data:image/jpeg;base64,", "", $img);
		// $imageData = base64_decode($img);
		// $source = imagecreatefromstring($imageData);
		// $imageName = "demo.png";
		// imagepng($source, 'assets/images/' . $imageName);

		$img_name = $this->input->post('name');
		$v_id = $this->input->post('id');

		$img = $this->input->post('visitor_img');

		// $img = "data:image/jpeg;base64,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";
		$img = str_replace("data:image/jpeg;base64,", "", $img);
		$imageData = base64_decode($img);
		$source = imagecreatefromstring($imageData);
		$imageName = $img_name . $v_id . '.png';
		imagepng($source, 'assets/images/' . $imageName);
		var_dump($imageName);
	}

	// --------============== Check erro -------
	public	function check_hr()
	{
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {

			$data = array(
				'button' => 'Create',
				'action' => site_url('Visitor_Controller/create_visitor_action'),
				// 'id' => set_value('id') , 
				// 'cnic' => set_value('cnic') , 
				// 'name' => set_value('name') , 
			);

			$data['equipment_type'] = $this->Visitor_Model->get_equipment_type()->result();

			$data['allpurpose'] = $this->Visit_Purpose_Model->get_all_purpose();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			$data['last_visitor_id'] = $this->Visitor_Model->get_last_pass()->result();
			$data['last_companion_id'] = $this->Visitor_Model->get_last_companion()->result();
			$data['last_pass_no'] = $this->Visitor_Model->get_last_pass()->result();
			// $data['districts'] = $this->Visitor_model->getDistricts();
			// $data['designation'] = $this->Visitor_model->getDesignation();

			$this->load->view('include/header', $this->global);
			// $this->load->view('new_visitor', $data);
			$this->load->view('new_visitor_check', $data);

			$this->load->view('include/footer');
		}
	}

	//------ error==============================

	// --------------- employee checkin --------------
	public	function emp_checkin()
	{
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {

			$data = array(
				'button' => 'Create',
				'action' => site_url('Visitor_Controller/create_empchkin_action'),
				// 'id' => set_value('id') , 
				// 'cnic' => set_value('cnic') , 
				// 'name' => set_value('name') , 
			);

			$data['equipment_type'] = $this->Visitor_Model->get_equipment_type()->result();

			$data['allpurpose'] = $this->Visit_Purpose_Model->get_all_purpose();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			$data['last_visitor_id'] = $this->Visitor_Model->get_last_pass()->result();
			$data['last_companion_id'] = $this->Visitor_Model->get_last_companion()->result();
			$data['last_pass_no'] = $this->Visitor_Model->get_last_pass()->result();
			// $data['districts'] = $this->Visitor_model->getDistricts();
			// $data['designation'] = $this->Visitor_model->getDesignation();

			$this->load->view('include/header', $this->global);
			$this->load->view('employee_checkin', $data);
			// $this->load->view('new_visitor_check', $data);

			$this->load->view('include/footer');
		}
	}
	public function create_empchkin_action()
	{

		$this->_rules();
		// $this->form_validation->set_rules('cnic', 'CNIC No already Checked in today Try next,', 'trim|required|xss_clean|is_unique[visitors.cnic]');
		if ($this->form_validation->run() == FALSE) {
			$this->new_visitor();
		} else {

			$last_id = $this->Visitor_Model->get_last_id()->result();
			// $pass = $last_pass->;
			$v_id = 00001;
			$new_pass_no = 00001;
			foreach ($last_id as $id) {
				$new_pass_no = $id->pass_no + 1;
				$v_id = $id->id + 1;
			}


			// ------ nadra image -----------
			// $nimg_name = $this->input->post('name');
			$nv_id = $v_id;

			$nimg = $this->input->post('nadra_image');

			if ($nimg == '') {
				$nadra_image = null;
			} else {
				$n_img = str_replace("data:image/jpeg;base64,", "", $nimg);
				$imageData = base64_decode($n_img);
				$source = imagecreatefromstring($imageData);
				// $source = @imagecreatefromstring(file_get_contents($imageData));
				$nadra_image = 'n_' . $nv_id . '.jpg';
				imagepng($source, 'assets/images/nadra_images/' . $nadra_image);
			}

			// ----- // nadra image ---------

			$v_id = $v_id;

			$img = $this->input->post('visitor_img');
			if ($img == '') {
				$imageName = 'noimage.jpg';
			} else {
				$img = str_replace("data:image/jpeg;base64,", "", $img);
				$imageData = base64_decode($img);
				$source = imagecreatefromstring($imageData);
				$imageName = 'v_' . $v_id . '.png';
				imagepng($source, 'assets/images/visitor_images/' . $imageName);
			}
			// var_dump($imageName);
			// die();


			$date = new DateTime("now");
			$curr_date = $date->format('Y-m-d ');
			$current_year = date('Y');
			$appointment = date('Y-m-d H:i:s', strtotime($this->input->post('appoin_date')));
			$apt_date = '';
			if ($appointment != $current_year) {
				$apt_date = $curr_date;
			} else {
				$apt_date = $appointment;
			}
			$date_f = '';

			$date_from = date('Y-m-d H:i:s', strtotime($this->input->post('date_from')));

			if ($date_from != $current_year) {
				$date_f = $date_from;
			} else {
				$date_f = $curr_date;
			}
			$date_t = '';
			$date_to = date('Y-m-d H:i:s', strtotime($this->input->post('date_to')));
			if ($date_to != $current_year) {
				$date_t = $date_to;
			} else {
				$date_t = $curr_date;
			}

			$data = array(


				// 'id' => $this->input->post('id', TRUE),
				'pass_no' => $new_pass_no, //$this->input->post('pass_no', TRUE),
				'is_pre_appointed' => $this->input->post('is_pre_appointed', TRUE),
				'cnic' => $this->input->post('cnic', TRUE),
				'name' => $this->input->post('name', TRUE),
				'fname' => $this->input->post('fname', TRUE),
				'address' => $this->input->post('address', TRUE),
				'visitor_img' => $imageName,
				'visitor_image' => $this->input->post('visitor_image', TRUE),
				'nadra_image' => $nadra_image,

				'rank' => $this->input->post('rank', TRUE),

				'purpose' => $this->input->post('purpose', TRUE),
				'contact_no' => $this->input->post('contact_no', TRUE),
				
				'dept' => $this->input->post('dept', TRUE),
				'sub_branch' => $this->input->post('sub_branch', TRUE),
				// 'floor' => $this->input->post('floor', TRUE),
				'hrm_verified' => $this->input->post('vhrm_verified', TRUE),
				'nadra_verified' => $this->input->post('vnadra_verified', TRUE),
				'cro_verified' => $this->input->post('vcro_verified', TRUE),
				// 'officer_called' => $this->input->post('officer_called', TRUE),
				'is_employee' => $this->input->post('is_employee', TRUE),
				'current_posting' => $this->input->post('current_posting', TRUE),
				'read_status' => $this->input->post('vread_status', TRUE),



				// 'check-in' => date('Y-m-d H:i:s', strtotime("+5 hours")),
				// 'appoin_date' => $apt_date,
				// 'appoin_date' => date('Y-m-d H:i:s',strtotime($this->input->post('appoin_date'))),
				// 'date_from' => $date_f,
				// 'date_to' => $date_t,
				'status' => 0,

				'check_in' => date('Y-m-d H:i:s'),
				'added_date' => date('Y-m-d H:i:s'),
				// 'updated_on' => date('Y-m-d H:i:s'),
				'added_by' => $this->vendorId,
			);

			// var_dump($data);
			// die();

			$result=$this->Visitor_Model->insert_new_visitor($data);
			// $this->Visitor_Model->insert_companion($v_id);
			// $this->Visitor_Model->insert_v_equipment();
			// $result=$this->session->set_flashdata('success', 'Pass Generated Successfully');
			if ($result > 0) {
				$this->session->set_flashdata('success', 'Checked in successfully');
			} else {
				$this->session->set_flashdata('error', 'Checked in failed');
				
			}

			redirect('emp-checkin');
		}
			// redirect('emp-checkin');
		// }
	}

	// -------------- // employee checkin-------------


	public	function new_visitor()
	{
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {

			$data = array(
				'button' => 'Create',
				'action' => site_url('Visitor_Controller/create_visitor_action'),
				// 'id' => set_value('id') , 
				// 'cnic' => set_value('cnic') , 
				// 'name' => set_value('name') , 
			);

			$data['equipment_type'] = $this->Visitor_Model->get_equipment_type()->result();

			$data['allpurpose'] = $this->Visit_Purpose_Model->get_all_purpose();
			$data['alldept'] = $this->Department_Model->get_all_dept_hds();
			$data['sub_branch'] = $this->Department_Model->get_all_subbranch();
			$data['last_visitor_id'] = $this->Visitor_Model->get_last_pass()->result();
			$data['last_companion_id'] = $this->Visitor_Model->get_last_companion()->result();
			$data['last_pass_no'] = $this->Visitor_Model->get_last_pass()->result();
			// $data['districts'] = $this->Visitor_model->getDistricts();
			// $data['designation'] = $this->Visitor_model->getDesignation();

			$this->load->view('include/header', $this->global);
			$this->load->view('new_visitor', $data);
			// $this->load->view('new_visitor_check', $data);

			$this->load->view('include/footer');
		}
	}


	public function create_visitor_action()
	{

		$this->_rules();
		if ($this->form_validation->run() == FALSE) {
			$this->new_visitor();
		} else {

			$last_id = $this->Visitor_Model->get_last_id()->result();
			// $pass = $last_pass->;
			$v_id = 00001;
			$new_pass_no = 00001;
			foreach ($last_id as $id) {
				$new_pass_no = $id->pass_no + 1;
				$v_id = $id->id + 1;
			}


			// ------ nadra image -----------
			// $nimg_name = $this->input->post('name');
			$nv_id = $v_id;

			$nimg = $this->input->post('nadra_image');

			if ($nimg == '') {
				$nadra_image = null;
			} else {
				$n_img = str_replace("data:image/jpeg;base64,", "", $nimg);
				$imageData = base64_decode($n_img);
				$source = imagecreatefromstring($imageData);
				// $source = @imagecreatefromstring(file_get_contents($imageData));
				$nadra_image = 'n_' . $nv_id . '.jpg';
				imagepng($source, 'assets/images/nadra_images/' . $nadra_image);
			}

			// ----- // nadra image ---------

			// $target_dir = "assets/images";
			// $target_file = $target_dir . time() . basename($_FILES["profile_image"]["name"]);
			// $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
			// $profile_img = time() . basename($_FILES["profile_image"]["name"]);
			// move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);

			// $img_name = $this->input->post('name');
			$v_id = $v_id;

			$img = $this->input->post('visitor_img');
			if ($img == '') {
				$imageName = 'noimage.jpg';
			} else {
				$img = str_replace("data:image/jpeg;base64,", "", $img);
				$imageData = base64_decode($img);
				$source = imagecreatefromstring($imageData);
				$imageName = 'v_' . $v_id . '.png';
				imagepng($source, 'assets/images/visitor_images/' . $imageName);
			}
			// var_dump($imageName);
			// die();


			$date = new DateTime("now");
			$curr_date = $date->format('Y-m-d ');
			$current_year = date('Y');
			$appointment = date('Y-m-d H:i:s', strtotime($this->input->post('appoin_date')));
			$apt_date = '';
			if ($appointment != $current_year) {
				$apt_date = $curr_date;
			} else {
				$apt_date = $appointment;
			}
			$date_f = '';

			$date_from = date('Y-m-d H:i:s', strtotime($this->input->post('date_from')));

			if ($date_from != $current_year) {
				$date_f = $date_from;
			} else {
				$date_f = $curr_date;
			}
			$date_t = '';
			$date_to = date('Y-m-d H:i:s', strtotime($this->input->post('date_to')));
			if ($date_to != $current_year) {
				$date_t = $date_to;
			} else {
				$date_t = $curr_date;
			}

			$data = array(


				// 'id' => $this->input->post('id', TRUE),
				'pass_no' => $new_pass_no, //$this->input->post('pass_no', TRUE),
				'is_pre_appointed' => $this->input->post('is_pre_appointed', TRUE),
				'letter_no' => $this->input->post('letter_no', TRUE),
				'serial_no' => $this->input->post('serial_no', TRUE),
				'cnic' => $this->input->post('cnic', TRUE),
				'name' => $this->input->post('name', TRUE),
				'fname' => $this->input->post('fname', TRUE),
				'address' => $this->input->post('address', TRUE),
				'visitor_img' => $imageName,
				'visitor_image' => $this->input->post('visitor_image', TRUE),
				'nadra_image' => $nadra_image,

				'rank' => $this->input->post('rank', TRUE),

				'no_of_persons' => $this->input->post('no_of_persons', TRUE),
				'purpose' => $this->input->post('purpose', TRUE),
				'contact_no' => $this->input->post('contact_no', TRUE),
				'reference' => $this->input->post('reference', TRUE),
				'dept' => $this->input->post('dept', TRUE),
				'sub_branch' => $this->input->post('sub_branch', TRUE),
				'floor' => $this->input->post('floor', TRUE),
				'hrm_verified' => $this->input->post('vhrm_verified', TRUE),
				'nadra_verified' => $this->input->post('vnadra_verified', TRUE),
				'cro_verified' => $this->input->post('vcro_verified', TRUE),
				'officer_called' => $this->input->post('officer_called', TRUE),
				'is_employee' => $this->input->post('is_employee', TRUE),
				'current_posting' => $this->input->post('current_posting', TRUE),
				'read_status' => $this->input->post('vread_status', TRUE),



				// 'check-in' => date('Y-m-d H:i:s', strtotime("+5 hours")),
				'appoin_date' => $apt_date,
				// 'appoin_date' => date('Y-m-d H:i:s',strtotime($this->input->post('appoin_date'))),
				'date_from' => $date_f,
				'date_to' => $date_t,
				'status' => 0,

				'check_in' => date('Y-m-d H:i:s'),
				'added_date' => date('Y-m-d H:i:s'),
				// 'updated_on' => date('Y-m-d H:i:s'),
				'added_by' => $this->vendorId,
			);

			// var_dump($data);
			// die();

			$this->Visitor_Model->insert_new_visitor($data);
			$this->Visitor_Model->insert_companion($v_id);
			$this->Visitor_Model->insert_v_equipment();
			$this->session->set_flashdata('success', 'Pass Generated Successfully');
			redirect('view-pass/' . $v_id);
		}
	}

	

	public function all_visitors()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->get_all_visitors();
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			// $data['leaves'] = $this->Visitor_Model->getleaves();


			//  $this->db->select_sum('leave_allowed_days');
			//     $this->db->where('emp_id', $employee->emp_id);
			//     $this->db->where('leave_emp_leave_type', 'casual');
			//     // $this->db->where('month(leave_order_date)', $month);
			//     $this->db->where('year(leave_order_date)', date('Y'));

			//     $query = $this->db->get('tbl_leave_orders');
			//     $total = 0;
			//     foreach ($query->result() as $row) {
			//         // echo $row->leave_allowed_days;
			//         $total += $row->leave_allowed_days;
			//     };
			//     $data['CL'] = $total;




			$this->load->view('include/header', $this->global);
			$this->load->view('all_visitors', $data);
			$this->load->view('include/footer');
		}
	}

	public function today_visitors()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->get_today_visitors();
			
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			// $data['leaves'] = $this->Visitor_Model->getleaves();




			$this->load->view('include/header', $this->global);
			$this->load->view('today_visitors', $data);
			$this->load->view('include/footer');
		}
	}
	public function today_employees()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->get_today_emps();
			
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			
			$this->load->view('include/header', $this->global);
			$this->load->view('today_employees', $data);
			$this->load->view('include/footer');
		}
	}

	public function today_vehicles()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->todayvehicles();
			
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept_hds();
			$data['sub_branch'] = $this->Department_Model->get_all_subbranch();
		
			$this->load->view('include/header', $this->global);
			$this->load->view('today_vehicles', $data);
			$this->load->view('include/footer');
		}
	}

	public function preappointed_visitors()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->get_preappointed_visitors();
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			// $data['leaves'] = $this->Visitor_Model->getleaves();





			$this->load->view('include/header', $this->global);
			$this->load->view('pre_appointed_visitors', $data);
			$this->load->view('include/footer');
		}
	}

	public function public_visitors()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {




			$visitor = $this->Visitor_Model->get_public_visitors();
			$data = array(
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			// $data['leaves'] = $this->Visitor_Model->getleaves();





			$this->load->view('include/header', $this->global);
			$this->load->view('public_visitors', $data);
			$this->load->view('include/footer');
		}
	}

	public

	function update_visitor($id)
	{
		$row = $this->Visitor_Model->get_visitor_by_id($id);
		if ($row) {

			$data = array(
				'button' => 'update',
				'action' => site_url('Visitor_Controller/update_visitor_action'),
				'id' => set_value('id', $row->id),

				// 'id' => $this->input->post('id', TRUE),
				'pass_no' => set_value('pass_no', $row->pass_no),
				'is_pre_appointed' => set_value('is_pre_appointed', $row->is_pre_appointed),
				'letter_no' => set_value('letter_no', $row->letter_no),
				'serial_no' => set_value('serial_no', $row->serial_no),

				'cnic' => set_value('cnic', $row->cnic),
				'name' => set_value('name', $row->name),
				'fname' => set_value('fname', $row->fname),
				'address' => set_value('address', $row->address),
				'visitor_img' => set_value('visitor_img', $row->visitor_img),
				'visitor_image' => set_value('visitor_image', $row->visitor_image),
				'nadra_image' => set_value('nadra_image', $row->nadra_image),

				'rank' => set_value('rank', $row->rank),

				'no_of_persons' => set_value('no_of_persons', $row->no_of_persons),
				'purpose' => set_value('purpose', $row->purpose),
				'contact_no' => set_value('contact_no', $row->contact_no),
				'reference' => set_value('reference', $row->reference),
				'dept' => set_value('dept', $row->dept),
				'sub_branch' => set_value('sub_branch',$row->sub_branch),
				'floor' => set_value('floor', $row->floor),
				'hrm_verified' => set_value('hrm_verified',  $row->hrm_verified),
				'cro_verified' => set_value('cro_verified',  $row->cro_verified),
				'nadra_verified' => set_value('nadra_verified',  $row->nadra_verified),
				'officer_called' => set_value('officer_called',  $row->officer_called),
				'is_employee' => set_value('is_employee', $row->is_employee),
				'current_posting' => set_value('current_posting', $row->current_posting),
				'read_status' => set_value('read_status',  $row->read_status),



				// 'check-in' => date('Y-m-d H:i:s', strtotime("+5 hours")),


				'appoin_date' => set_value('appoin_date', date('Y-m-d H:i:s', strtotime($row->appoin_date))),
				'date_from' => set_value('date_from', date('Y-m-d H:i:s', strtotime($row->date_from))),
				'date_to' => set_value('date_to', date('Y-m-d H:i:s', strtotime($row->date_to))),
				'added_date' => set_value('added_date', $row->added_date),

				'added_by' => set_value('added_by', $row->added_by),

			);

			// $data['designation'] = $this->Visitor_Model->getDesignation();

			$data['allpurpose'] = $this->Visit_Purpose_Model->get_all_purpose();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			$data['companion'] = $this->Visitor_Model->get_companion($id)->result();
			$data['v_equipment'] = $this->Visitor_Model->get_v_equipment($id)->result();
			$data['equipment_type'] = $this->Visitor_Model->get_equipment_type()->result();
			$this->load->view('include/header', $this->global);
			$this->load->view('update_visitor', $data);
			$this->load->view('include/footer');
		} else {
			$this->session->set_flashdata('error', 'Record Not found');
			redirect(site_url('all-visitors'));
		}
	}
	public function repeat()
	{
		$this->load->view('repeat');
	}
	public

	function update_visitor_action()
	{
		$this->_rules();
		if ($this->form_validation->run() == FALSE) {
			// $cc = $this->update_visitor($this->input->post('id'));
			$this->update_visitor($this->input->post('id'));
		} else {

			// ------ nadra image -----------
			// $nimg_name = $this->input->post('name');
			$nv_id = $this->input->post('id');

			$nimg = $this->input->post('nadra_image');
			if ($nimg == '') {
				$nadra_image = null;
			} else {

				$n_img = str_replace("data:image/jpeg;base64,", "", $nimg);
				$imageData = base64_decode($n_img);
				$source = imagecreatefromstring($imageData);
				$nadra_image = 'n_' . $nv_id . '.jpg';
				imagepng($source, 'assets/images/nadra_images/' . $nadra_image);
			}

			// ----- // nadra image ---------


			// $img_name = $this->input->post('name');
			$v_id = $this->input->post('id');

			$img = $this->input->post('visitor_img');
			$imageName = '';
			if ($img == '') {
				$imageName = 'noimage.jpg';
			} else {
				$img = str_replace("data:image/jpeg;base64,", "", $img);
				$imageData = base64_decode($img);
				$source = imagecreatefromstring($imageData);
				$imageName = 'v_' . $v_id . '.png';
				imagepng($source, 'assets/images/visitor_images/' . $imageName);
			}
			// var_dump($imageName);
			// die();



			$data = array(

				// 'id' => $this->input->post('id', TRUE),
				'pass_no' => $this->input->post('pass_no', TRUE),
				'is_pre_appointed' => $this->input->post('is_pre_appointed', TRUE),
				'letter_no' => $this->input->post('letter_no', TRUE),
				'serial_no' => $this->input->post('serial_no', TRUE),
				'cnic' => $this->input->post('cnic', TRUE),
				'name' => $this->input->post('name', TRUE),
				'fname' =>  $this->input->post('fname', TRUE),
				'address' =>  $this->input->post('address', TRUE),
				'visitor_img' =>  $this->input->post('visitor_img', TRUE),
				'visitor_img' => $imageName,
				'nadra_image' => $nadra_image,

				'rank' => $this->input->post('rank', TRUE),

				'no_of_persons' => $this->input->post('no_of_persons', TRUE),
				'purpose' => $this->input->post('purpose', TRUE),
				'contact_no' => $this->input->post('contact_no', TRUE),
				'reference' => $this->input->post('reference', TRUE),
				'dept' => $this->input->post('dept', TRUE),
				'sub_branch' => $this->input->post('sub_branch', TRUE),
				'floor' => $this->input->post('floor', TRUE),
				'hrm_verified' => $this->input->post('vhrm_verified', TRUE),
				'cro_verified' => $this->input->post('vcro_verified', TRUE),
				'nadra_verified' => $this->input->post('vnadra_verified', TRUE),
				'officer_called' => $this->input->post('officer_called', TRUE),
				'is_employee' => $this->input->post('is_employee', TRUE),
				'current_posting' => $this->input->post('current_posting', TRUE),
				'read_status' => $this->input->post('vread_status', TRUE),


				// 'check-in' => date('Y-m-d H:i:s', strtotime("+5 hours")),
				'appoin_date' => date('Y-m-d H:i:s', strtotime($this->input->post('appoin_date'))),
				'date_from' => date('Y-m-d H:i:s', strtotime($this->input->post('date_from'))),
				'date_to' => date('Y-m-d H:i:s', strtotime($this->input->post('date_to'))),
				'check_in' => date('Y-m-d H:i:s'),
				'updated_on' => date('Y-m-d H:i:s'),
				'updated_by' => $this->vendorId,
			);
			// var_dump($data);
			// die();
			$this->Visitor_Model->update_companion();
			$this->Visitor_Model->update_v_equipment();
			$this->Visitor_Model->update_visitor($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('success', 'Update Record Success');
			redirect(site_url('all-visitors'));
		}
	}

	// =-=--==---- get department with floor =======------------


	// get sub category by category_id
	function get_floor()
	{
		$department = $this->input->post('name', TRUE);
		$data = $this->Visitor_Model->get_floor_data($department)->result();
		echo json_encode($data);
	}
	function get_all_subbranch()
	{
		$department_id = $this->input->post('dept',TRUE);
		$data = $this->Visitor_Model->get_sub_branches($department_id)->result();
		echo json_encode($data);
		// var_dump(json_encode($data)) ;
		// die();
	}

	// ======= // get department with floor =======================

	// ===== View Passs ==========
	public

	function view_pass($id)
	{
		$row = $this->Visitor_Model->get_visitor_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'pass_no' => $row->pass_no,
				'cnic'  => $row->cnic,
				'name'  => $row->name,

				'rank'  => $row->rank,

				'no_of_persons' => $row->no_of_persons,
				'purpose'  => $row->purpose,
				'contact_no' => $row->contact_no,
				'reference' => $row->reference,
				'dept'  => $row->dept,
				'sub_branch'  => $row->sub_branch,
				'floor'  => $row->floor,
				'visitor_img' => $row->visitor_img,
				'officer_called' => $row->officer_called,
				'hav_veh' => $row->hav_veh,
				'veh_no' => $row->veh_no,
				'date_from' => $row->date_from,
				'date_to' => $row->date_to,
				'is_pre_appointed' => $row->is_pre_appointed,
				'check_in' => $row->check_in,
				'appoin_date' => $row->appoin_date,


				'added_date'  => $row->added_date,
				'added_by' => $row->added_by,
				'updated_on'  => $row->updated_on,
				'updated_by' => $row->updated_by,
			);

			// $data['districts'] = $this->Visitor_Model->getDistricts();
			$data['companion'] = $this->Visitor_Model->get_companion_with_id($id)->result();
			$data['eq_count'] = $this->Visitor_Model->get_counteq_byid($id);
			$data['alldept'] = $this->Department_Model->get_all_dept();

			$this->load->view('include/header', $this->global);
			$this->load->view('view_pass', $data);
			$this->load->view('include/footer');
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('all-visitors'));
		}
	}


	// -------delete visitor ==================
	public function delete_visitor($id)
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {
			$row = $this->Visitor_Model->get_visitor_by_id($id);
			if ($row) {
				$this->Visitor_Model->delete_visitor($id);
				$this->session->set_flashdata('success', 'Delete Record Success');
				redirect(site_url('all-visitors'));
			} else {
				$this->session->set_flashdata('error', 'Record Not Found');
				redirect(site_url('all-visitors'));
			}
		}
	}

	// ===============   Pre appointed visitors ===========================================

	// ===== insert new
	public function pre_appointed_visitor()
	{

		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {

			$data = array(
				'button' => 'Create',
				'action' => site_url('Visitor_Controller/create_pre_visitor_action'),
				// 'id' => set_value('id') , 
				// 'cnic' => set_value('cnic') , 
				// 'name' => set_value('name') , 
			);

			$data['equipment_type'] = $this->Visitor_Model->get_equipment_type()->result();

			$data['allpurpose'] = $this->Visit_Purpose_Model->get_all_purpose();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			
			$data['last_visitor_id'] = $this->Visitor_Model->get_last_entry()->result();
			$data['last_pass_no'] = $this->Visitor_Model->get_last_entry()->result();
			// $data['districts'] = $this->Visitor_model->getDistricts();
			// $data['designation'] = $this->Visitor_model->getDesignation();

			$this->load->view('include/header', $this->global);
			$this->load->view('pre_appointed_visitor', $data);
			$this->load->view('include/footer');
		}
	}


	public function create_pre_visitor_action()
	{
		$this->_rules();
		if ($this->form_validation->run() == FALSE) {
			$this->new_visitor();
		} else {



			// ------ nadra image -----------
			// $nimg_name = $this->input->post('name');
			$nv_id = $this->input->post('id');

			$nimg = $this->input->post('nadra_image');

			$n_img = str_replace("data:image/jpeg;base64,", "", $nimg);
			$imageData = base64_decode($n_img);
			$source = imagecreatefromstring($imageData);
			$nadra_image = 'n_' . $nv_id . '.jpg';
			imagepng($source, 'assets/images/nadra_images/' . $nadra_image);

			// ----- // nadra image ---------


			// $img_name = $this->input->post('name');
			$v_id = $this->input->post('id');

			$img = $this->input->post('visitor_img');

			$img = str_replace("data:image/jpeg;base64,", "", $img);
			$imageData = base64_decode($img);
			$source = imagecreatefromstring($imageData);
			$imageName = 'v_' . $v_id . '.png';
			imagepng($source, 'assets/images/visitor_images/' . $imageName);
			// var_dump($imageName);
			// die();



			$last_pass = $this->Visitor_Model->get_last_pass()->result();
			// $pass = $last_pass->;
			// $pass_id = 00000;
			foreach ($last_pass as $pass) {
				$new_pass_no = $pass->pass_no + 1;
			}

			$data = array(
				// 'id' => $this->input->post('id', TRUE),
				'pass_no' => $new_pass_no,
				'is_pre_appointed' => $this->input->post('is_pre_appointed', TRUE),
				'cnic' => $this->input->post('cnic', TRUE),
				'name' => $this->input->post('name', TRUE),
				'fname' => $this->input->post('fname', TRUE),
				'address' => $this->input->post('address', TRUE),
				'visitor_img' => $imageName,
				'nadra_image' => $nadra_image,

				'rank' => $this->input->post('rank', TRUE),

				'no_of_persons' => $this->input->post('no_of_persons', TRUE),
				'purpose' => $this->input->post('purpose', TRUE),
				'contact_no' => $this->input->post('contact_no', TRUE),
				'reference' => $this->input->post('reference', TRUE),
				'dept' => $this->input->post('dept', TRUE),
				
				'floor' => $this->input->post('floor', TRUE),



				// 'check-in' => date('Y-m-d H:i:s', strtotime("+5 hours")),
				'check_in' => date('Y-m-d H:i:s'),
				'added_date' => date('Y-m-d H:i:s'),
				// 'updated_on' => date('Y-m-d H:i:s'),
				'added_by' => $this->vendorId,
			);

			$this->Visitor_Model->insert_new_visitor($data);
			$this->Visitor_Model->insert_companion();
			$this->Visitor_Model->insert_v_equipment();
			$this->session->set_flashdata('inert_message', 'Record Created Successfully');
			redirect(site_url('new-pre-appinted'));
		}
	}



	// =====// insert new
	// ==============  // Pre appointed visitors ==========================================


	// ---------------Reports ----------------------------------------

	public function reports()
	{
		$depts = $this->Visitor_Model->getDepts();

		$data['floor'] = $this->Visitor_Model->getFloor();
		$data['prps'] = $this->Visitor_Model->getPurpose();
		$data['dept'] = $depts;
		$this->load->view('include/header', $this->global);
		$this->load->view('reports', $data);
		$this->load->view('include/footer');
	}

	public function visitorList()
	{

		// POST data
		$postData = $this->input->post();

		// Get data
		$data = $this->Visitor_Model->get_Visitors($postData);

		echo json_encode($data);
	}

	function users_report()
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {

			$users = $this->Visitor_Model->user_report();
			$data = array(
				'users_data' => $users
			);
			$data['users'] = $this->Visitor_Model->userListing();
			$data['places'] = $this->Visitor_Model->getUserPlace();
			$data['gates'] = $this->Place_Model->get_all_gates();
			$data['roles'] = $this->Visitor_Model->getUserRoles();
			// $this->global['pageTitle'] = 'CARD : User Listing';
			$this->load->view('include/header', $this->global);
			$this->load->view('users_report', $data);
			$this->load->view('include/footer');
		}
	}




	public function cri_report()
	{
		$depts = $this->Visitor_Model->getDepts();

		$data['floor'] = $this->Visitor_Model->getFloor();
		$data['prps'] = $this->Visitor_Model->getPurpose();
		$data['dept'] = $depts;
		$this->load->view('include/header', $this->global);
		$this->load->view('cri_report', $data);
		$this->load->view('include/footer');
	}

	public function cri_list()
	{

		// POST data
		$postData = $this->input->post();

		// Get data
		$data = $this->Visitor_Model->get_cri_Visitors($postData);

		echo json_encode($data);
	}


	public function cro_report()
	{
		$depts = $this->Visitor_Model->getDepts();

		$data['floor'] = $this->Visitor_Model->getFloor();
		$data['prps'] = $this->Visitor_Model->getPurpose();
		$data['dept'] = $depts;
		$this->load->view('include/header', $this->global);
		$this->load->view('cro_report', $data);
		$this->load->view('include/footer');
	}

	public function cro_list()
	{

		// POST data
		$postData = $this->input->post();

		// Get data
		$data = $this->Visitor_Model->get_cro_Visitors($postData);

		echo json_encode($data);
	}

	//  --------------// Reports ------------------------------------

	public function checkin()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {
			$visitor = $this->Visitor_Model->get_today_visitors();
			$data = array(
				'button' => 'checkin',
				'action' => site_url('Visitor_Controller/checkin_action'),
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			// $data['leaves'] = $this->Visitor_Model->getleaves();



			$this->load->view('include/header', $this->global);
			$this->load->view('checkin', $data);
			$this->load->view('include/footer');
		}
	}

	public function checkin_action($id)
	{

		$data = array(

			'status' => 1,
			'check_in' => date('Y-m-d H:i:s'),
			'updated_by' => $this->vendorId,
		);
		$this->Visitor_Model->update_checkin($id, $data);
		$this->session->set_flashdata('success', 'Successfully Checked Out! ');
		redirect(site_url('checkin'));
	}
	// ----------------checkout ----------------------------------
	public function checkout()
	{
		// if($this->isAdmin() == TRUE)
		if ($this->isTicker() == TRUE) {
			$this->loadThis();
		} else {
			$visitor = $this->Visitor_Model->get_todayall_visitors();
			$data = array(
				'button' => 'checkout',
				'action' => site_url('Visitor_Controller/checkout_action'),
				'visitors_data' => $visitor
			);

			$data['user'] = $this->Visitor_Model->userListing();
			$data['alldept'] = $this->Department_Model->get_all_dept_hds();
			$data['sub_branch'] = $this->Department_Model->get_all_subbranch();
			// $data['leaves'] = $this->Visitor_Model->getleaves();

			$this->load->view('include/header', $this->global);
			$this->load->view('checkout', $data);
			$this->load->view('include/footer');
		}
	}



	public function my_visitors($get_cnic)
	{
		// $get_cnic = $this->input->post('search_checkout', TRUE);

		$data = $this->Visitor_Model->visitor_search($get_cnic);
		$abc = json_encode($data);
		// var_dump($data);
		// die();
		// var_dump($data['find_visitor']);
		// die();

		echo $abc;
	}

	public function visitor_checkin($get_cnic)
	{
		// $get_cnic = $this->input->post('search_checkout', TRUE);

		$data = $this->Visitor_Model->visitor_search_checkin($get_cnic);
		$abc = json_encode($data);
		// var_dump($data);
		// die();
		// var_dump($data['find_visitor']);
		// die();

		echo $abc;
	}

	public function get_eqbyid($id)
	{
		// $get_cnic = $this->input->post('search_checkout', TRUE);

		$data = $this->Visitor_Model->get_today_vequipments($id);
		$abc = json_encode($data);
		// var_dump($data);
		// die();
		// var_dump($data['find_visitor']);
		// die();

		echo $abc;
	}

	public function checkout_new()
	{
		$this->load->view('include/header', $this->global);
		$this->load->view('checkout_new');
		$this->load->view('include/footer');
	}

	public function checkout_action($id)
	{

		$data = array(

			'status' => 1,
			'check_out' => date('Y-m-d H:i:s'),
			'updated_by' => $this->vendorId,
		);
		$this->Visitor_Model->update_checkout($id, $data);
		$this->session->set_flashdata('success', 'Successfully Checked Out! ');
		redirect(site_url('checkout_new'));
	}





	// ===--------- // checkiout ------------------------------
	// ----------------  reject & objections ---------------
	function reject($id)
	{
		if ($this->isAdmin() == TRUE) {
			$this->loadThis();
		} else {
			$row = $this->card_model->get_requests_by_id($id);
			if ($row) {
				$data = array(
					'button' => 'Reject',
					'action' => site_url('card/reject_action'),
					'id' => set_value('id', $row->id),
					'status' => set_value('status', $row->status),
					'approval_date' => date('Y-m-d H:i:s'),

				);
				// $this->load->view('includes/header', $this->global); 
				$this->load->view('reject', $data);
				// $this->load->view('includes/footer'); 
			} else {
				$this->session->set_flashdata('error', 'Record Not Found');
				redirect(site_url('all-requests'));
			}
		}
	}

	//  public function checkout_action() 
	// 	 { 

	// $this->_rules(); 
	// if ($this->form_validation->run() !== FALSE) 
	//     { 
	//     $this->update($this->input->post('id', TRUE)); 
	//     } 
	//   else 
	//     { 

	//  $data = array( 

	// 	 'status' => 1 , 
	// 	//  'comments' => $this->input->post('comments', TRUE) , 
	// 	'check_out' => date('Y-m-d H:i:s') , 
	// 	'updated_by' => $this->vendorId , 
	//  ); 
	//  $this->Visitor_Model->update_checkout($this->input->post('id', TRUE) , $data); 
	//  $this->session->set_flashdata('success', 'Successfully Cehcked Out! '); 
	//  redirect(site_url('checkout')); 
	// } 
	//  } 




	// ---------------- // reject & objections -------------
	function check_officer($cnic)
	{

		// $cnic = $this->input->post('cnic',TRUE);
		try {
			$data = $this->Visitor_Model->get_employee_bycnic($cnic);
			echo json_encode($data);
			// return json_encode($data);
			// var_dump($data);
			// die();
			// return $data;
		} catch (Exception $ex) {
			// echo var_dump($ex);
		}

		// echo '<h1> test </h1>';
	}

	// --------api ---------------------------------------------------------------
	function pullofficerfromhrms($cnic)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://officer16.sindhpolice.gov.pk/api/fir/officer_data/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('cnic' => $cnic),
			CURLOPT_HTTPHEADER => array(
				'X-API-KEY: f4f52bf8c5ad7fc759d1d4156b25a4c7b3d1e2',
				'Authorization: Basic YXBpdXNlcjpLVmRYc1I2Uk45RzlTPGRT',
				'Cookie: ci_session=r96b8sp6hktsc4oes7of4q9j57kl03gg'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	//  =================================================================================
	function new_hr($cnic)
	{
		//  $API_Endpoint= "http://officer16.sindhpolice.gov.pk/api/fir/officer_data";
		$API_Endpoint = "http://192.168.200.251/hrmis/api/fir/officer_data";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		// Set the API operation, version, and API signature in the request.
		$nvpreq =  "User =apiuser&Password=KVdXsR6RN9G9S<dS&cnic=" . $cnic . "";
		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		$headers = ['X-API-KEY: f4f52bf8c5ad7fc759d1d4156b25a4c7b3d1e2'];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// Get response from the server.
		$httpResponse = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		if ($curl_errno > 0 || (!$httpResponse)) {
			// echo "cURL Error ($curl_errno): $curl_error\n";
			echo json_encode("Failed");
			//  exit("  failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
		//  var_dump($httpResponse);
		//  die();
		header('Content-Type: application/json');
		echo $httpResponse;

		//  json_decode($httpResponse);

	}

	public function visitor_detail($id)
	{

		$row = $this->Visitor_Model->get_visitor_by_id($id);
		//  $data = array(
		// 	'button' => 'checkout', 
		// 	'action' => site_url('Visitor_Controller/checkout_action') ,
		// 	'visitors_data' => $row
		// );

		if ($row) {
			$data = array(
				'button' => 'checkout',
				'action' => site_url('Visitor_Controller/checkout_action'),
				'id' => $row->id,
				'is_pre_appointed' => $row->is_pre_appointed,
				'veh_type' => $row->veh_type,
				'veh_no' => $row->veh_no,
				'pass_no' => $row->pass_no,
				'cnic'  => $row->cnic,
				'name'  => $row->name,
				'fname'  => $row->fname,
				'address'  => $row->address,

				'rank'  => $row->rank,

				'no_of_persons' => $row->no_of_persons,
				'purpose'  => $row->purpose,
				'contact_no' => $row->contact_no,
				'reference' => $row->reference,
				'dept'  => $row->dept,
				'sub_branch'  => $row->sub_branch,
				'floor'  => $row->floor,
				'visitor_img' => $row->visitor_img,
				'visitor_image' => $row->visitor_image,
				'nadra_image' => $row->nadra_image,
				'cro_verified' => $row->cro_verified,
				'nadra_verified' => $row->nadra_verified,
				'officer_called' => $row->officer_called,
				'check_in' => $row->check_in,
				'check_out' => $row->check_out,


				'added_date'  => $row->added_date,
				'added_by' => $row->added_by,
				'updated_on'  => $row->updated_on,
				'updated_by' => $row->updated_by,
			);
			$data['companion'] = $this->Visitor_Model->get_companion_with_id($id)->result();
			$data['equipment'] = $this->Visitor_Model->get_v_equipment($id)->result();
			$data['alldept'] = $this->Department_Model->get_all_dept();
			// $data['allsubbranches'] = $this->Department_Model->get_all_subbranch();

			//  var_dump($data);
			//  die();

			$this->load->view('include/header', $this->global);
			$this->load->view('visitor_detail', $data);
			$this->load->view('include/footer');
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('all-visitors'));
		}
	}
	public function check_nadra($cnic)
	{

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://192.168.80.79:8080/nadra/CheckFromNadra?login_name=testuser&access_code=VGVuYW50LU5BRFJBLTg3NTVAIyQlJl4&access_node=4710364257&cnic=' . $cnic . '&login_id=7MA4YWxkTrZu0gW',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'postman-token: 3fe4d58b-f261-f2ce-14c4-4d8fb01ed92c',
				'Accept: application/json',
				'Content-Type: application/json',
				'Cookie: ci_session=2cs63d09oq0p3oojbtlpha44o14jq86t'
			),
		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
			$result = 'Failed';
			echo json_encode($result);
			// var_dump($error_msg);
			// die;
			// return $result;
		} else {

			curl_close($curl);
			echo $response;
		}
	}
	public function check_fir($cnic)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://202.83.168.251/FIR/criminal_api/checkperson?cnic=',
			// CURLOPT_URL => 'http://192.168.200.251/FIR/criminal_api/checkperson?cnic=',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('cnic' => $cnic, 'X_API_KEY' => 'F528BF1B36P2A8K7ICSETA1A2F3N5FD7FA61SINDHPOLICE'),
			CURLOPT_HTTPHEADER => array(
				'X_API_KEY: F528BF1B36P2A8K7ICSETA1A2F3N5FD7FA61SINDHPOLICE',
				'Cookie: d1777482_1a65x448=eU2rIOuT1XMqhX2UFBz1eXsDg1f1kKCG0KAhltYRwI9jYzwJQsXPcDhQpzJHJsGKkTcrDEcPeIaaUk6f4ChtPDbtP7GTJa5%2FRzfChUtH5HlgmQ5uMIKHfYLoCgjiIX7l3g6R5LG1v4CCnQLs3lpIQjSk12pmpqA7gF7xtMiWaizrqMLmW9qw7aWWs7z%2FWXTFisuskSMaBoXpRt3YS8pvc96ZvlSmttGiy7EVZruZDQaYZ2ek8cbilfwODt1NWdECRRw53cNxH99JV15xXY0XcKizycP9NXgCXdty6BmyhXygmp%2BktRuN2eLAtN7cTpBWRzK8bMd%2FzlGSUAGGwzvGGw%3D%3D150bb8d453b6cc13ec513527fbf3082f5cd5a32a'
			),
		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
			$result = 'Failed';
			// echo $error_msg;
			// var_dump($error_msg);
			// die;
			return $result;
		} else {


			curl_close($curl);
			// echo $response;

			$result = json_decode($response);

			return $result;
		}
		// var_dump($result);
		// die;
	}

	public function save_fir($cnic)
	{

		$this->db->where('psrms_cnic', $cnic);
		$record = $this->db->get('apipsrms')->result_array();
		if (!empty($record)) {
			echo json_encode('detected');
		}
		//  var_dump($record);
		//  die;
		if (empty($record)) {
			$record = $this->check_fir($cnic);
			if ($record != "Failed") {
				$cro = $record;
			} else if ($record == "Failed") {
				$cro = $record;
			} else {
				$cro = "";
			}
			$result = $cro;
			// $result= $cro;
			// $cro = "";
			// return $result	
			// var_dump($result);
			// die;

			if ($cro != "Failed" && $cro != "") {
				foreach ($cro as $row) {
					$data = array(
						'psrms_cnic' => $cnic,
						'fir_district' => $row->fir_district,
						'fir_ps' => $row->fir_district,
						'fir_no' => $row->fir_no,
						'fir_year' => $row->fir_year,
						'fir_offence_date' => $row->fir_offence_date,
						'fir_offecnce' => $row->fir_offecnce,
						'fir_status' => $row->fir_status,
						'sus_name' => $row->sus_name,
						'sus_parent_name' => $row->sus_parent_name,
						'sus_gender' => $row->sus_gender,
						'sus_cast' => $row->sus_cast,
						'sus_address' => $row->sus_address,
						'sus_phone' => $row->sus_phone
					);

					$result = $this->db->insert('apipsrms', $data);

					if ($result == true) {
						// $this->session->set_flashdata('success', 'Verified successfully');
						$success = 'detected';
						// echo json_encode($success);
						echo json_encode($success);
					}

					// var_dump($data);
					// die();


					// $this->db->insert(
					// 	array(
					// 		'fir_district' => $row->fir_district,
					// 		'fir_ps' => $row->fir_district,
					// 		'fir_no' => $row->fir_no,
					// 		'fir_year' => $row->fir_year,
					// 		'fir_offence_date' => $row->fir_offence_date,
					// 		'fir_offecnce' => $row->fir_offecnce,
					// 		'fir_status' => $row->fir_status,
					// 		'sus_name' => $row->sus_name,
					// 		'sus_parent_name' => $row->sus_parent_name,
					// 		'sus_gender' => $row->sus_gender,
					// 		'sus_cast' => $row->sus_cast,
					// 		'sus_address' => $row->sus_address,
					// 		'sus_phone' => $row->sus_phone,
					// 	);
					// );
				}
			} else {
				// $result= json_encode($record);
				// return $result;
				// var_dump($record);
				// die;

				echo json_encode($result);
			}
			// $record = $this->db->get_where('cnic', $cnic)->result_array();

			// return $record;

			// var_dump($result);
			// 		die;
		}
	}



	public function check_cro($cnic)
	{


		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://202.83.168.198/crodashboard/api/SindhAPI/GetDataByCnic?cnic=' . $cnic,
			// CURLOPT_URL => 'http://192.168.200.198/crodashboard/api/SindhAPI/GetDataByCnic?cnic='.$cnic,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'X-Key: S1NDH90KARACH1BAD33N'
			),
		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
			$result = 'Failed';
			// var_dump($result);
			// die;
			return $result;
		} else {

			curl_close($curl);
			// echo $response;

			$result = json_decode($response);
			// var_dump($response);
			// die();
			return $result;
		}
	}

	public function save_cro($cnic)
	{

		// $this->db->where('cnic', $cnic);
		//  = $cnic;
		$string = $cnic;
		//php string replace
		$nic_wd = str_replace("-", "", $string);

		$cro = $this->db->get_where('apicro', array('cnic_no' => $cnic))->result_array();
		if (!empty($cro)) {
			echo "Verified";
		}
		// echo json_encode($cro);
		// var_dump($cro);
		// die;
		if (empty($cro)) {
			//    $cro = json_decode($this->check_cro($nic_wd), true);
			$cro = $this->check_cro($nic_wd);
			// var_dump($cro);
			// die;

			if ($cro != 'Failed') {
				$cro = $cro;
			} else if ($cro == "Failed") {
				$cro = "Failed";
			} else {
				$cro = "";
			}
			// $result= json_encode($cro);
			$result = json_decode($cro);

			// $result= json_encode($record);


			var_dump($result);
			die;

			if ($cro != "Failed" && $cro != "") {
				foreach ($cro as $row) {
					$data = array(
						'cnic_no' => $cnic,
						'cro_no' => $row['cro_no'],
						//    'cnic' => $row['cnic'],
						'cro_full_name' => $row['cro_full_name'],
						'cro_father_name' => $row['cro_father_name'],
						'cro_age' => $row['cro_age'],
						'category_desc' => $row['category_desc'],
						'record_district' => $row['record_district'],
						'cro_photo_front' => $row['cro_photo_front']
					);

					$this->db->insert('apicro', $data);

					//    var_dump($data);
					//    die();

				}
			} else {
				echo json_encode($cro);
			}
		} else {
			echo json_encode($cro);
		}
	}

	function _rules()
	{
		// $this->form_validation->set_rules('pass_no', 'Pass No already exists Try Again,', 'trim|required|xss_clean|is_unique[visitors.pass_no]');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('purpose', 'Purpose of visit', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('visitor_img', 'Please Take Picture, This', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('range_unit', 'Range / Unit', 'trim|required');
		// $this->form_validation->set_rules('home_district', 'District', 'trim|required');
		// $this->form_validation->set_rules('joining_date', 'Joining date', 'trim|required');
		// $this->form_validation->set_rules('present_posting', 'Present posting', 'trim|required');
		// $this->form_validation->set_rules('contact_no', 'Contact No', 'trim|required|min_length[12]|xss_clean');
		// $this->form_validation->set_rules('reference', 'Phone / Office Reference', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('dept', 'Departement', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('perm_address', 'Permanent address', 'trim|required');
		// $this->form_validation->set_rules('grade', 'Pay Scale', 'trim|required');
		// $this->form_validation->set_rules('id', 'id', 'trim'); 
		$this->form_validation->set_error_delimiters('<span class="col-md-12 text-danger badge badge-danger text-center">', '</span>');
	}
}
