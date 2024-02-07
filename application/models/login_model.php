<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($user_name, $ecryptPassword)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.name,BaseTbl.user_name, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles', 'Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.user_name', $user_name);
        $this->db->where('BaseTbl.password', $ecryptPassword);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        return $query->result();
    }
}
