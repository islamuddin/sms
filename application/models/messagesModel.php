<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class messagesModel extends CI_Model
{

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }



    public function saveRecord($data) {
			$this->db->insert('messages', $data);
			return $this->db->insert_id(); // Return the ID of the inserted record
    }    

    public function getAllRecords() {
        $this->db->select('messages.*');

        $this->db->from('messages');
        // Add more joins and columns as needed
    
        $query = $this->db->get();
		//echo $this->db->last_query(); exit;
    
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    
        return array(); // Return an empty array if no messages found
    }
	
    // Get the count of all messages
	public function getCountAllRecords() {
		$this->db->select('COUNT(*) as count');
		$this->db->from('messages');
	
		$query = $this->db->get();
		$result = $query->result();
	
		if (!empty($result)) {
			return $result[0]->count;
		}
	
		return 0;
	}
	
	
public function fetch_record_by_id($id) {
    $this->db->select('*');
    $this->db->from('messages');
    $this->db->where('id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();
    } else {
        return null;
    }
}

public function messagesByContactId($id) {
    $this->db->select('m.*');
    $this->db->from('messages_contacts mc');
    $this->db->join('messages m', 'm.id = mc.message_id', 'inner');
    $this->db->where('mc.contact_id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return null;
    }
}

public function getRecordById($id)
{
    $this->db->select('messages.*,'); 
    $this->db->from('messages');
    $this->db->where('messages.id', $id);

    $query = $this->db->get();
    return $query->row();
}

public function deleteRecordById($id)
{
	// todo delete recorder messages id: 
	// $this->db->where('id', $id);
    $this->db->select('messages.*,'); 
    $this->db->from('messages');
    $this->db->where('messages.id', $id);
   	$this->db->delete();
    return 'Deleted';
}

public function deleteSelectedRecords($ids) {
	$this->db->where_in('id', $ids);
	$result = $this->db->delete('messages');

	return $result;
}


public function updateRecord($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('messages', $data);
}

public function markMessageAsSent($data) {
	$this->db->insert('messages_contacts', $data);
	return $this->db->insert_id(); // Return the ID of the inserted record
}


}
