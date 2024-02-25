<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class projectsModel extends CI_Model
{

	public function __construct() {
		parent::__construct();
		$this->load->database(); // Load the database library
	}

	public function saveRecord($data) {
		// todo check by cnic_no if exists then don't save
		$api_key = $data['api_key'];
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('api_key', $api_key);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			// do nothing
			return 0;
		}else{
			$this->db->insert('projects', $data);
			return $this->db->insert_id(); // Return the ID of the inserted record
		}
	}

	public function getAllProjects() {
		$this->db->select('projects.*');

		$this->db->from('projects');
		// Add more joins and columns as needed

		$query = $this->db->get();
		//echo $this->db->last_query(); exit;

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return array(); // Return an empty array if no contacts found
	}
	public function getAllRecords() {
		$this->db->select('p.id,p.name, p.api_key, COUNT(otp.project_id) as otp_count');
		$this->db->from('projects as p');
		$this->db->join('otp', 'p.id = otp.project_id', 'left');
		$this->db->group_by('otp.project_id');
	
		$query = $this->db->get();
		$result = $query->result();
	
		return $result;
	}
	

	// Get the count of all contacts
	public function getCountAllProjects() {
		$this->db->select('COUNT(*) as count');
		$this->db->from('projects');

		$query = $this->db->get();
		$result = $query->result();

		if (!empty($result)) {
			return $result[0]->count;
		}

		return 0;
	}

	public function fetch_record_by_id($id) {
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('id', $id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}
	public function fetch_record_by_api_key($id) {
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('api_key', $id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	public function getRecordById($id)
	{
		$this->db->select('projects.*,'); 
		$this->db->from('projects');
		$this->db->where('projects.id', $id);

		$query = $this->db->get();
		return $query->row();
	}

	public function deleteRecordById($id)
	{
		// todo delete recorder contacts id: 
		// $this->db->where('id', $id);
		$this->db->select('projects.*,'); 
		$this->db->from('projects');
		$this->db->where('projects.id', $id);
		$this->db->delete();
		return 'Deleted';
	}

	public function deleteSelectedRecords($ids) {
		$this->db->where_in('id', $ids);
		$result = $this->db->delete('projects');

		return $result;
	}


	public function updateRecord($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('projects', $data);
	}

	public function messagesByProjectId($id) {
		$this->db->select('otp.*, p.name as project_name');
		$this->db->from('otp');
		$this->db->join('projects p', 'p.id = otp.project_id', 'inner');
		$this->db->where('p.id', $id);
		$this->db->order_by('otp.created_date', 'desc'); 
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


}
