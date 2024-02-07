<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Place_Model extends CI_Model
{

	public function add_new_place($data){
		$this->db->insert('places', $data);
		return true;
	}

	public function get_all_places()
	{
		$query = $this->db->get('places');
		return $query->result();
	}

	public function edit_place($place_id){
		// $query = $this->db->where('id',$dept_id)->get();

		// $this->db->update('tasks', $data);
		// return true;

		$this->db->where('place_id', $place_id);
		$get_data = $this->db->get('places');

		return $get_data->row();

	// return $query->row();
	}

	public function update_place($place_id, $data){
		$this->db->where('place_id', $place_id);
		$this->db->update('places', $data);
		return true;
	}

	public function delete_place($place_id){
		$this->db->where('place_id', $place_id);
		$this->db->delete('places');
		return true;
	}
	// gate---

	public function add_new_gate($data){
		$this->db->insert('gates', $data);
		return true;
	}

	public function get_all_gates()
	{
		$query = $this->db->get('gates');
		return $query->result();
	}

	public function get_all_gatesbyid($place_id)
	{
		$query = $this->db->get('gates');
		$this->db->where('place_id',$place_id);
		return $query->result();
	}

	public function edit_gate($gate_id){
		// $query = $this->db->where('id',$dept_id)->get();

		// $this->db->update('tasks', $data);
		// return true;

		$this->db->where('gate_id', $gate_id);
		$get_data = $this->db->get('gates');

		return $get_data->row();

	// return $query->row();
	}

	public function update_gate($gate_id, $data){
		$this->db->where('gate_id', $gate_id);
		$this->db->update('gates', $data);
		return true;
	}

	public function delete_gate($gate_id){
		$this->db->where('gate_id', $gate_id);
		$this->db->delete('gates');
		return true;
	}
	// ---- gate -------

}
