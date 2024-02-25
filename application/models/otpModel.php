<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class otpModel extends CI_Model
{

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }



    public function saveRecord($data) {
		// todo check by cnic_no if exists then don't save
		$this->db->insert('otp', $data);
		return $this->db->insert_id(); // Return the ID of the inserted record

		// $contact_no = $data['contact_no'];
		// $this->db->select('*');
		// $this->db->from('otp');
		// $this->db->where('contact_no', $contact_no);
		// $query = $this->db->get();
	
		// if ($query->num_rows() > 0) {
		// 	// do nothing
		// 	return 0;
		// }else{
		// 	$this->db->insert('otp', $data);
		// 	return $this->db->insert_id(); // Return the ID of the inserted record
		// }

				
    }    
	public function getAllRecords() {
		$this->db->select('otp.*, p.name as project_name');
		$this->db->join('projects p', 'p.id = otp.project_id', 'inner');	
		$this->db->from('otp');
		$this->db->group_by('otp.id');
		// Add more joins and columns as needed
	
		$query = $this->db->get();
		// echo $this->db->last_query(); exit;
	
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return array(); // Return an empty array if no otp found
	}
	

	public function getAllContacts() {
        $this->db->select('otp.*');

        $this->db->from('otp');
        // Add more joins and columns as needed
    
        $query = $this->db->get();
		//echo $this->db->last_query(); exit;
    
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    
        return array(); // Return an empty array if no otp found
    }
	
    // Get the count of all otp
	public function getCountAllRecords() {
		$this->db->select('COUNT(*) as count');
		$this->db->from('otp');
	
		$query = $this->db->get();
		$result = $query->result();
	
		if (!empty($result)) {
			return $result[0]->count;
		}
	
		return 0;
	}
	
	
public function fetch_record_by_id($id) {
    $this->db->select('*');
    $this->db->from('otp');
    $this->db->where('id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();
    } else {
        return null;
    }
}

public function getRecordById($id)
{
    $this->db->select('otp.*, p.name as project_name'); 
    $this->db->from('otp');
	$this->db->join('projects p', 'p.id = otp.project_id', 'inner');

    $this->db->where('otp.id', $id);

    $query = $this->db->get();
    return $query->row();
}


public function getRecordByNumber($number)
{
    $this->db->select('otp.*,'); 
    $this->db->from('otp');
    $this->db->where('otp.id', $number);

    $query = $this->db->get();
    return $query->row();
}

public function deleteRecordById($id)
{
	// todo delete recorder otp id: 
	// $this->db->where('id', $id);
    $this->db->select('otp.*,'); 
    $this->db->from('otp');
    $this->db->where('otp.id', $id);
   	$this->db->delete();
    return 'Deleted';
}

public function deleteSelectedRecords($ids) {
	$this->db->where_in('id', $ids);
	$result = $this->db->delete('otp');

	return $result;
}


public function updateRecord($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('otp', $data);
}

public function messagesByNumber($number) {
    $this->db->select('otp.*, p.name as project_name');
    $this->db->from('otp');
    $this->db->join('projects p', 'p.id = otp.project_id', 'inner');
    $this->db->where('otp.number', $number);
	$this->db->order_by('otp.created_date', 'desc'); 
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return null;
    }
}


}