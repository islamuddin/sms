<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Department_Model extends CI_Model
{

	public function add_new_department($data){
		$this->db->insert('department', $data);
		return true;
	}

	public function get_all_dept()
	{
		$query = $this->db->get('department');
		return $query->result();
	}
	public function get_all_dept_hds()
	{
		$this->db->where('parent_id',0);
		$query = $this->db->get('department');
		return $query->result();
	}

	public function get_all_subbranch($parent_id = "")
	{
		if(!empty($parent_id)){
			$this->db->where('parent_id =', $parent_id);
		}else{
			$this->db->where('parent_id !=', 0);
		}
		$query = $this->db->get('department');
		return $query->result();
		
	}

	public function get_main_branches(){
		$this->db->where('parent_id',0);
		$query = $this->db->get('department');
		return $query->result();
	}

	public function edit_dept($dept_id){
		// $query = $this->db->where('id',$dept_id)->get();

		// $this->db->update('tasks', $data);
		// return true;

		$this->db->where('id', $dept_id);
		$get_data = $this->db->get('department');

		return $get_data->row();

	// return $query->row();
	}

	public function update_dept($id, $data){
		$this->db->where('id', $id);
		$this->db->update('department', $data);
		return true;
	}

	public function delete_dept($dept_id){
		$this->db->where('id', $dept_id);
		$this->db->delete('department');
		return true;
	}

}
