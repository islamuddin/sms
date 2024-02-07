<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Equipment_Model extends CI_Model
{

	public function add_new_equipment($data){
		
		$this->db->insert('equipments', $data);
		return true;
	}

	public function	edit_equipment($eq_id){
		$this->db->where('id', $eq_id);
		$get_data = $this->db->get('equipments');
		return $get_data->row();
	}

	public function get_all_equipment()
	{
		$query = $this->db->get('equipments');
		return $query->result();
	}

	public function update_equipment($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('equipments', $data);
		return true;
	}

	public function delete_equipment($eq_id)
	{
		$this->db->where('id', $eq_id);
		$this->db->delete('equipments');
		return true;
	}
}
