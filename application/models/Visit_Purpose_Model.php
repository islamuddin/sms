<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visit_Purpose_Model extends CI_Model
{

	public function add_new_purpose($data){
		
		$this->db->insert('visitpuropse', $data);
		return true;
	}

	public function get_all_purpose()
	{
		$query = $this->db->get('visitpuropse');
		return $query->result();
	}


	public function	edit_purpose($purpose_id){
		$this->db->where('id', $purpose_id);
		$get_data = $this->db->get('visitpuropse');
		return $get_data->row();
	}


	public function update_purpose($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('visitpuropse', $data);
		return true;
	}

	public function deletepurpose($purpose_id)
	{
		$this->db->where('id', $purpose_id);
		$this->db->delete('visitpuropse');
		return true;
	}
}
