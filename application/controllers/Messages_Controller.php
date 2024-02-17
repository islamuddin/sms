<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Messages_Controller extends CI_Controller
{


	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('messagesModel');
		$this->load->model('contactsModel');
		$this->load->helper('date');
        $this->load->helper(array('form', 'url'));
		$this->load->library('curl'); 

		$this->load->library('form_validation');
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


	public function send()
	{

		// Load data for dropdowns from the database.

		// Load the form view with dropdown data.
		$this->load->view('include/header', $this->global);
		$this->load->view('messages/send', []);
		$this->load->view('include/footer');

		if($this->input->server('REQUEST_METHOD') === 'POST'){
		}

	}

	public function save(){
		// Handle form submission and save data to the 'records' table.
		$data = array(
			'message' => $this->input->post('message')			
		);

		// Call the model function to save the record
		$id = $this->messagesModel->saveRecord($data);		
		if ($id) {
			$contactsArray = $this->contactsModel->getAllContacts();

			foreach ($contactsArray as $contact) {
				$status = "";
                try {
                    $status = $this->sendSmsNotification($contact->contact_no, $this->input->post('message'));
                } //catch exception
                catch (Exception $e) {
                    $status = 'Message: ' . $e->getMessage();
                }

				$data = [
					'message_id' => $id,
					'contact_id' => $contact->id,
					'status' =>  $status
				];
				$this->messagesModel->markMessageAsSent($data);
			}


			// Success: Redirect to a success page or show a success message.
			redirect('messages/all');
		} else {
			// Error: Handle the error, possibly show an error message.
			// You may want to use CI's validation library for more robust validation.
			echo "Error sending message.";
		}
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
        return $response . ":" . $urltouse;
        die;
        return 'pending';
    }

	function testsms()
    {
        $msg = "SP testing";
        $contact = '03323967646';

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
        return $response . ":" . $urltouse;
        die;
        return 'pending';
    }
	

	public function view() {
		// var_dump('working');
		// die;
		$id=$this->input->get('id');
		$data['record'] = $this->messagesModel->getRecordById($id);	
		$data['records'] = $this->contactsModel->getAllRecords();

		$this->load->view('include/header', $this->global);
		$this->load->view('messages/view', $data);
		$this->load->view('include/footer');
	}	


	public function delete() {
		// var_dump('working');
		// die;
		$id=$this->input->get('id');
		$data['record'] = $this->messagesModel->deleteRecordById($id);	
		redirect('messages/all'); 
	}	

    public function deleteSelected() {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');

            if (!empty($ids)) {
                $result = $this->messagesModel->deleteSelectedRecords($ids);

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
		$data['records'] = $this->messagesModel->getAllRecords();
	
		// Load the view
		$this->load->view('include/header', $this->global);
		$this->load->view('messages/all', $data);
		$this->load->view('include/footer');
	}

	
}
