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

	public function failedRequest($data) {
		$this->db->insert('failedRequests', $data);
		return $this->db->insert_id();
    }

	public function saveIpLocation($data) {
		$this->db->insert('iplocations', $data);
		return $this->db->insert_id();
    }

	public function fetchByIP($ip) {
		$this->db->select('*');
		$this->db->from('iplocations');
		$this->db->where('ip', $ip);
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}
	


	public function getAllRecords($filter_type = "all") {
		$this->db->select('otp.*, p.name as project_name');
		$this->db->join('projects p', 'p.id = otp.project_id', 'inner');
		$this->db->from('otp');
	
		if ($filter_type == "today") {
			$this->db->where('DATE(otp.created_date)', date('Y-m-d'));
		} else if ($filter_type == "sent") {
			$this->db->where('otp.status', 1);
		} else if ($filter_type == "failed") {
			$this->db->where('otp.status', 0);
		}	
		$this->db->group_by('otp.id');
		$this->db->order_by('date(otp.created_date)', 'desc');
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return array(); // Return an empty array if no OTP found
	}
	

	public function invalidRequests() {
		$this->db->select('failedrequests.*');
		$this->db->from('failedrequests');
		$this->db->order_by('date(failedrequests.created_date)', 'desc');
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return array(); // Return an empty array if no OTP found
	}
	

	public function iplocations() {
		$this->db->select('iplocations.*');
		$this->db->from('iplocations');
		$this->db->order_by('date(iplocations.created_date)', 'desc');
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return array(); // Return an empty array if no OTP found
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

public function getReportData() {
	$this->db->select('p.name as project_name, COUNT(otp.project_id) as otp_sent, COUNT(DISTINCT otp.number) as unique_numbers_used');
    $this->db->from('projects as p');
    $this->db->join('otp', 'p.id = otp.project_id', 'inner');
    $this->db->group_by('p.id');

    $query = $this->db->get();
    $result = $query->result();
  
    return $result;
}

public function getDateWiseData() {
    $this->db->select('p.name as project_name, date(otp.created_date) as date, COUNT(otp.project_id) as otp_sent, COUNT(DISTINCT otp.number) as unique_numbers_used');
    $this->db->from('projects as p');
    $this->db->join('otp', 'p.id = otp.project_id', 'inner');
    $this->db->group_by('p.id, date(otp.created_date)');
	$this->db->order_by('date(otp.created_date)', 'desc');

    $query = $this->db->get();
    $result = $query->result();

    return $result;
}

public function getMonthWiseData() {
    $this->db->select('p.name as project_name, DATE_FORMAT(otp.created_date, "%Y-%m") as month_year, COUNT(otp.project_id) as otp_sent, COUNT(DISTINCT otp.number) as unique_numbers_used');
    $this->db->from('projects as p');
    $this->db->join('otp', 'p.id = otp.project_id', 'inner');
    $this->db->group_by('p.id, month_year');
    $this->db->order_by('month_year', 'desc');

    $query = $this->db->get();
    $result = $query->result();

    return $result;
}




}
