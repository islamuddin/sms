<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals
{
    // private static $authenticatedMemberId = null;
    // private static $initialized = false;

    // private static function initialize()
    // {
    //     if (self::$initialized)
    //         return;

    //     self::$authenticatedMemberId = null;
    //     self::$initialized = true;
    // }

    // public static function setAuthenticatedMemeberId($memberId)
    // {
    //     self::initialize();
    //     self::$authenticatedMemberId = $memberId;
    // }


    // public static function authenticatedMemeberId()
    // {
    //     self::initialize();
    //     return self::$authenticatedMemberId;
    // }

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
			$this->global['pageTitle'] = 'SMS Management System';
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

	function isOperator()
	{
		if ($this->role != ROLE_OPERATOR) {
			return true;
		} else {
			return false;
		}
	}
	function isUser()
	{
		if ($this->role != ROLE_USER) {
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
		if ($this->role = ROLE_ADMIN || $this->role = ROLE_OPERATOR || $this->role = ROLE_USER) {
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
		$this->global['pageTitle'] = 'SMS Management System.... : Access Denied';

		$this->load->view('include/header', $this->global);
		$this->load->view('access');
		$this->load->view('include/footer');
	}


}
