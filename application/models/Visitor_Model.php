<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_Model extends CI_Model
{

    public $order = 'DESC';
    public $vtable = 'visitors';
    public $vid = 'id';
    public $leavetype_id = 'leave_type_id';
    public $leavetype_tbl = 'tbl_leave_type';
    public $leave_id = 'leave_order_id';
    public $leave_tbl = 'tbl_leave_orders';
    public $products ='products';
    // public $ia_table = 'tbl_issuing_authority'; 
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.user_name, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        // if(!empty($searchText)) { $this->db->or_like('BaseTbl.user_name', $searchText); $this->db->or_like('BaseTbl.name', $searchText); $this->db->or_like('BaseTbl.mobile', $searchText); }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();

        return count($query->result());
    }



    /**
     * This function is used to get the user listing count
     *
     * @return array $result : This is result
     */
    function userListing(/*$searchText = '', $page, $segment*/)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.user_name, BaseTbl.name,BaseTbl.designation,BaseTbl.cnic_no, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        if (!empty($searchText)) {
            $this->db->or_like('BaseTbl.user_name', $searchText);
            $this->db->or_like('BaseTbl.name', $searchText);
            $this->db->or_like('BaseTbl.mobile', $searchText);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        // $this->db->limit($page, $segment);
        $query = $this->db->get();

        return $query->result();
    }

    function user_report(/*$searchText = '', $page, $segment*/)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.user_name, BaseTbl.name, BaseTbl.mobile, BaseTbl.place_id, BaseTbl.gate_id, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        // if (!empty($searchText)) {
        //     $this->db->or_like('BaseTbl.user_name', $searchText);
        //     $this->db->or_like('BaseTbl.name', $searchText);
        //     $this->db->or_like('BaseTbl.mobile', $searchText);
        // }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        // $this->db->limit($page, $segment);
        $query = $this->db->get();

        return $query->result();

        // $this->db->select('*');
        // $this->db->from('tbl_users');
        // $query = $this->db->get();
        // return $query->result();
    }
    function count_user_dentry($userId)
    {
        $this->db->where('added_by', $userId);

        $result = $this->db->get('visitors')->num_rows();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    function getUsers()
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        // $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    function getUserPlace()
    {
        $this->db->select('place_id, place_name, place_address');
        $this->db->from('places');
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, name, cnic_no, designation, user_name, mobile, place_id, gate_id, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        // var_dump($query);

        return $query->result();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return TRUE;
    }



    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId');
        $this->db->where('userId', $userId);
        $this->db->where('password', $oldPassword);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

        return $query->result();
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }

    // get all employees


    function get_all_employees()
    {
        $this->db->order_by($this->eid, $this->order);
        return $this->db->get($this->etable)->result();
    }

    function getDistricts()
    {
        $this->db->select('dst_id, dst_name');
        $this->db->from('tbl_district');
        // $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    function getDesignation()
    {
        $this->db->select('designation_id, designation_name, rank_type');
        $this->db->from('tbl_designations');
        // $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

   


    // ================= insert visitor =====================


    public function totalvisitor()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $query = $this->db->get();
        return count($query->result());
        // $query= count($query->result());
        // return json_encode($query);
    }

    public function totalv_checkout()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('status',1);
        $query = $this->db->get();
        return count($query->result());
    }
    public function totalsuspect()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('read_status', 0);
        $this->db->or_where('read_status', 1);
        $query = $this->db->get();
        return count($query->result());
    }

    public function todaysuspect()
    {
        $today = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('read_status', 0);
        $this->db->or_where('read_status', 1);
        $this->db->where('date(visitors.added_date)', $today);
        $query = $this->db->get();
        return count($query->result());
    }


    public function pie_chart()
    {
        // $query =  $this->db->query('SELECT DATE_FORMAT(added_date, "%Y-%m-%d") AS `date`, COUNT(`id`) as count FROM visitors WHERE MONTH(`added_date`) = "' . date('m') . '" AND YEAR(`added_date`) = "' . date('Y') . '" GROUP BY DATE(`added_date`)');
        // $records = $query->result_array();
        // $query =  $this->db->query('SELECT DATE_FORMAT(added_date, "%Y-%m-%d") AS `date`, COUNT(`id`) as count FROM visitors WHERE MONTH(`added_date`) = "' . date('m') . '" AND YEAR(`added_date`) = "' . date('Y') . '" GROUP BY DATE(`added_date`)');
        // $records = $query->result_array();

        $query = $this->db->select('date(visitors.added_date) AS DATE, sum(case when hav_veh = 1 then 1 else 0 end ) As Vehicles, sum(case when hav_veh != 1 then 1 else 0 end ) As Visitors',FALSE)
        ->where('date(visitors.added_date) BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()')  
        ->from("visitors")
        ->group_by('DATE')
        ->get();

        $abc = $query->result_array();

        // $this->db->select('date(visitors.added_date) AS DATE, count(visitors.id) AS VISITORS');
        // $this->db->from('visitors');
        // $this->db->where('hav_veh',1);
        // $this->db->where('date(visitors.added_date) BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()');
        // $this->db->group_by('date(visitors.added_date)');
        // $this->db->order_by('date(visitors.added_date)');
        // $records=$this->db->get()->result_array();
        
        // echo "<pre>";
        // print_r($records);
        // echo "</pre>"; 
        // die();

        $data = [];
        $data['chart_data'] = $abc;
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>"; 
        // die();
        $this->load->view('dash-chartgoogle', $data);
        // $this->load->view('dashboard', $data);

    }

    public function line_chart()
    {
        // $query = $this->db->query("SELECT monthname(added_date), COUNT('id') FROM visitors WHERE YEAR('added_date') = date('Y') GROUP BY monthname('added_date')");

        // $this->db->select('monthname(visitors.added_date), count(visitors.id)');

        // $this->db->select('monthname(visitors.added_date) AS MONTH, count(visitors.id) AS Suspect');
        $this->db->select('date(visitors.added_date) AS DATE, count(visitors.id) AS Suspect');
        $this->db->from('visitors');
        $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        // $this->db->where('year(visitors.added_date) = date("Y")');
        $this->db->where('date(visitors.added_date) BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()');
        // $this->db->where('visitors.added_date >= DATE(NOW())- INTERVAL 15 DAY)');
        $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
        $this->db->group_by('date(visitors.added_date)');
        $this->db->order_by('date(visitors.added_date)');

        // $query = $this->db->query('SELECT MONTHNAME(added_date,"%m") AS `month`, COUNT(`id`) as count FROM visitors WHERE MONTH(`added_date`) = "'.date('m') ,'" AND YEAR(`added_date`) ="'. date('Y').'" GROUP BY DATE_FORMAT(added_date, "%m")');
        // $records = $query->result_array();

        // $this->db->distinct();
        // $this->db->select('visitors.*');
        // $this->db->from('visitors');
        // $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        // $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        // $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
        // $records = $this->db->last_query();

        $records = $this->db->get()->result_array();
        // var_dump($records);
        // die;

        $data = [];
        $data['chart_data'] = $records;

        // $this->load->view('dash-chartline',$data);
        $this->load->view('dash-chartline', $data);

        //      echo "<pre>";
        //  print_r($data['chart_data']);
        //  echo "</pre>"; die;
        //     $data = [];
        //     $data['chart_data'] = $records;
        //     $this->load->view('dash-chartgoogle',$data);
        // $this->load->view('dashboard', $data);

    }


    // ==== Users Dashboard Users Data  START ====

    public function totalvisitor_user($id)
    {
        $this->db->select('*');
        $this->db->where('added_by', $id);
        $this->db->from('visitors');
        $query = $this->db->get();
        return count($query->result());
    }

    public function todayvisitor_user($id)
    {
        $this->db->select('*');
        $this->db->where('added_by', $id);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $this->db->where('is_pre_appointed!=', 2);
        // $this->db->where('is_pre_appointed', 0);
        $this->db->from('visitors');
        $query = $this->db->get();
        return count($query->result());
    }

    public function todayvehicle_user($id)
    {
        $this->db->select('*');
        $this->db->where('added_by', $id);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $this->db->where('hav_veh', 1);
        $this->db->from('visitors');
        $query = $this->db->get();
        return count($query->result());
    }

    public function totalvehicle_user($id)
    {
        $this->db->select('*');
        $this->db->where('added_by', $id);
        $this->db->where('hav_veh', 1);
        $this->db->from('visitors');
        $query = $this->db->get();
        return count($query->result());
    }


    function get_last_entry_user($id)
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $this->db->select("*");
        $this->db->where('added_by', $id);
        $this->db->from("visitors");
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(5);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();
        return $query;
    }

    public function pie_chart_user($id)
    {
        $query =  $this->db->query('SELECT DATE_FORMAT(added_date, "%Y-%m-%d") AS `date`, COUNT(`id`) as count FROM visitors WHERE MONTH(`added_date`) = "' . date('m') . '" AND YEAR(`added_date`) = "' . date('Y') . '" AND added_by ="' . $id . '"  GROUP BY DATE(`added_date`)');
        // $query = $this->db->where('added_by',$id);
        $records = $query->result_array();
        // echo "<pre>";
        // print_r($records);
        // echo "</pre>"; die;
        $data = [];
        $data['chart_data'] = $records;
        $this->load->view('dash-chartgoogle', $data);
        // $this->load->view('dashboard', $data);

    }


    // ==== Users Dashboard Users Data  END ====


    // public function monthlydatas()
    // {
    // $sql = "SELECT COUNT(id) as totalv, DATE(added_date) as dayss
    // FROM visitors
    // WHERE MONTH(added_date) = MONTH(CURDATE())
    // AND YEAR(added_date) = YEAR(CURDATE())
    // GROUP BY DATE(added_date)";

    // $query = $this->db->query($sql);

    // return $query;
    // }

    // public function monthlydatas()
    // {
    //  $this->db->select('SELECT COUNT(id) as count, DATE(added_date) as Day
    //  FROM visitors
    //  WHERE MONTH(added_date) = MONTH(CURDATE())
    //  AND YEAR(added_date) = YEAR(CURDATE())
    //  GROUP BY DATE(added_date)');
    // $result1 = $this->db->get();
    // return $result1;
    // }

    public function todayvisitor()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('hav_veh',0);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $query = $this->db->get();
        return count($query->result());
    }
    public function todayv_checkout()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('status', 1);
        $this->db->where('hav_veh',0);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $query = $this->db->get();
        return count($query->result());
    }

    public function totalvehicle()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('hav_veh', 1);
        $query = $this->db->get();
        return count($query->result());
    }

    public function totalveh_checkout()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('hav_veh', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return count($query->result());
    }

    public function todayvehicle()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('hav_veh', 1);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $query = $this->db->get();
        return count($query->result());
    }

    public function todayvehicles()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        
        $this->db->where('date(added_date)', date('Y-m-d'));
        $this->db->where('hav_veh', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function todayveh_checkout()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('hav_veh', 1);
        $this->db->where('status', 1);
        $this->db->where('date(added_date)', date('Y-m-d'));
        $query = $this->db->get();
        return count($query->result());
    }

    public function visitor_counter()
    {
        $today = date('Y-m-d');
        $this->db->select('dept AS Department, count(visitors.id) AS visitors');
        $this->db->from('visitors');

        $this->db->join('department', 'department.name = visitors.dept', 'left');
        $this->db->where('date(visitors.added_date)', $today);
        $this->db->group_by('visitors.dept');
        $data = $this->db->get()->result_array();
        return $data;
        // return count($data);
        // return json_encode($data);
        // $today = date('Y-m-d');
        // $this->db->select('department.name AS Department, count(department.id) AS visitors');
        // $this->db->from('department');
        // $this->db->join('visitors', 'visitors.dept = department.id','left');
        // $this->db->where('date(visitors.added_date)', $today);
        // $this->db->where('visitors.dept !=', null);
        // $this->db->group_by('Department');
        // $data = $this->db->get()->result();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>"; die;

    }


    function insert_new_visitor($data)
    {
       
        $this->db->trans_start();
        $this->db->insert('visitors', $data);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    // function get_emp_by_id($emp_id)
    // {
    //     $this->db->where($this->eid, $emp_id);
    //     return $this->db->get($this->etable)->row();
    // }
    // update emp 

    // function update_emp($emp_id, $data)
    // {
    //     $this->db->where($this->eid, $emp_id);
    //     $this->db->update($this->etable, $data);
    // }

    // delete emp 

    // function delete_emp($emp_id)
    // {
    //     $this->db->where($this->eid, $emp_id);
    //     $this->db->delete($this->etable);
    // }
    // get all visitors


    function get_all_visitors()
    {
        // $this->db->select('*');
        // $this->db->from('visitors');
        // $this->db->join('companion', 'companion.visitor_id = visitors.id', 'left');
        $this->db->where('hav_veh',0);
        $this->db->order_by($this->vid, $this->order);
        return $this->db->get('visitors')->result();
    }
    function get_allVisitors($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        // $visitor_type = $postData['visitor_type'];
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            // $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or 
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  =" . $searchValue . " or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%'  or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " visitors.name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept ='" . $searchdept . "' ";
        }
        if ($searchsubb != '') {
            $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        }

        // if ($visitor_type != '') {
        //     $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('hav_veh',0);
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('visitors.*, department.name as v_dept, department.floor as d_floor,b.name as s_branch');

        // -------- join left dept---------
        $this->db->join('department', 'department.id = visitors.dept', 'left');
        $this->db->join('department as b', 'b.id = visitors.sub_branch', 'left');
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        if($rowperpage != '-1'){
            $this->db->limit($rowperpage, $start);
        }
        
        $this->db->from('visitors');
        
        $records = $this->db->get()->result();

        $data = array();
        $serial_no = 1;
        
        foreach ($records as $record) {
            $depts=$this->getDepts();

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "v_dept" => $record->v_dept,
                "s_branch" => $record->s_branch,
                "floor" => $record->floor,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("d-m-Y", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),
                "actions" => '<a href="'.base_url().'Visitor_Controller/view_pass/' . $record->id.'"><i class="fa fa-id-badge"></i></a><a class="btn_edit" href="'.base_url().'Visitor_Controller/update_visitor/'.$record->id.'"><span class="fa fa-pencil"></span> </a>
                <!-- <a href="'.base_url("Visitor_Controller/delete_visitor/") . $record->id.'" onclick="return confirm("Do you want to delete this record?");"><span class="fa fa-trash"></span></a>-->',
            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    function get_all_pre_Visitors($postData = null)
    {
        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        // $visitor_type = $postData['visitor_type'];
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            // $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or 
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  =" . $searchValue . " or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%'  or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " visitors.name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept ='" . $searchdept . "' ";
        }
        if ($searchsubb != '') {
            $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        }

        // if ($visitor_type != '') {
        //     $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('is_pre_appointed', 1);
        $this->db->where('hav_veh',0);
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('is_pre_appointed', 1);
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

       ## Fetch records
	   $this->db->select('visitors.*, department.name as v_dept, department.floor as d_floor,b.name as s_branch');

	   // -------- join left dept---------
	   $this->db->join('department', 'department.id = visitors.dept', 'left');
	   $this->db->join('department as b', 'b.id = visitors.sub_branch', 'left');
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('is_pre_appointed', 1);    
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->from('visitors');
        
        $records = $this->db->get()->result();

        $data = array();
        $serial_no = 1;
        
        foreach ($records as $record) {
            $depts=$this->getDepts();

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "v_dept" => $record->v_dept,
                "s_branch" => $record->s_branch,
                "floor" => $record->floor,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("y-m-d", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),
                "actions" => '<a href="'.base_url().'Visitor_Controller/view_pass/' . $record->id.'"><i class="fa fa-id-badge"></i></a><a class="btn_edit" href="'.base_url().'Visitor_Controller/update_visitor/'.$record->id.'"><span class="fa fa-pencil"></span> </a>
                <!-- <a href="'.base_url("Visitor_Controller/delete_visitor/") . $record->id.'" onclick="return confirm("Do you want to delete this record?");"><span class="fa fa-trash"></span></a>-->',

            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }


    // END PRE APPOINTED VISITORS

    function get_all_public_Visitors($postData = null)
    {
        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        // $visitor_type = $postData['visitor_type'];
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            // $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or 
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  =" . $searchValue . " or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%'  or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " visitors.name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept ='" . $searchdept . "' ";
        }
        if ($searchsubb != '') {
            $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        }

        // if ($visitor_type != '') {
        //     $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('is_pre_appointed', 0);
        $this->db->where('hav_veh',0);
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('is_pre_appointed', 0);
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('visitors.*, department.name as v_dept, department.floor as d_floor,b.name as s_branch');

        // -------- join left dept---------
        $this->db->join('department', 'department.id = visitors.dept', 'left');
        $this->db->join('department as b', 'b.id = visitors.sub_branch', 'left');
        $this->db->where('hav_veh',0);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('is_pre_appointed', 0);    
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->from('visitors');
        
        $records = $this->db->get()->result();

        $data = array();
        $serial_no = 1;
        
        foreach ($records as $record) {
            $depts=$this->getDepts();

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "v_dept" => $record->v_dept,
                "s_branch" => $record->s_branch,
                "floor" => $record->floor,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("y-m-d", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),
                "actions" => '<a href="'.base_url().'Visitor_Controller/view_pass/' . $record->id.'"><i class="fa fa-id-badge"></i></a><a class="btn_edit" href="'.base_url().'Visitor_Controller/update_visitor/'.$record->id.'"><span class="fa fa-pencil"></span> </a>
                <!-- <a href="'.base_url("Visitor_Controller/delete_visitor/") . $record->id.'" onclick="return confirm("Do you want to delete this record?");"><span class="fa fa-trash"></span></a>-->',

            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }


    // END Public APPOINTED VISITORS
    

      function get_products() {		
		//columns
		$columns = array(
            'id',
            'name',
            'cnic',
            'sale_price',
            'sales_count',
            'sale_date');
		
		//index column
		$indexColumn = 'id';
		
		//total records
		$sqlCount = 'SELECT COUNT(' . $indexColumn . ') AS row_count FROM ' . $this->products;
		$totalRecords = $this->db->query($sqlCount)->row()->row_count;
		
		//pagination
		$limit = '';
		$displayStart = $this->input->get_post('start', true);
		$displayLength = $this->input->get_post('length', true);
		
		if (isset($displayStart) && $displayLength != '-1') {
            $limit = ' LIMIT ' . intval($displayStart) . ', ' . intval($displayLength);
        }
		
		$uri_string = $_SERVER['QUERY_STRING'];
        $uri_string = preg_replace("/%5B/", '[', $uri_string);
        $uri_string = preg_replace("/%5D/", ']', $uri_string);

        $get_param_array = explode('&', $uri_string);
        $arr = array();
        foreach ($get_param_array as $value) {
            $v = $value;
            $explode = explode('=', $v);
            $arr[$explode[0]] = $explode[1];
        }
		
		$index_of_columns = strpos($uri_string, 'columns', 1);
        $index_of_start = strpos($uri_string, 'start');
        $uri_columns = substr($uri_string, 7, ($index_of_start - $index_of_columns - 1));
        $columns_array = explode('&', $uri_columns);
        $arr_columns = array();
		
		foreach ($columns_array as $value) {
            $v = $value;
            $explode = explode('=', $v);
            if (count($explode) == 2) {
                $arr_columns[$explode[0]] = $explode[1];
            } else {
                $arr_columns[$explode[0]] = '';
            }
        }
		
		//sort order
		$order = ' ORDER BY ';
        $orderIndex = $arr['order[0][column]'];
        $orderDir = $arr['order[0][dir]'];
        $bSortable_ = $arr_columns['columns[' . $orderIndex . '][orderable]'];
        if ($bSortable_ == 'true') {
            $order .= $columns[$orderIndex] . ($orderDir === 'asc' ? ' asc' : ' desc');
        }
		
		//filter
		$where = '';
        $searchVal = $arr['search[value]'];
        if (isset($searchVal) && $searchVal != '') {
            $where = " WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                $where .= $columns[$i] . " LIKE '%" . $this->db->escape_like_str($searchVal) . "%' OR ";
            }
            $where = substr_replace($where, "", -3);
            $where .= ')';
        }
		
		//individual column filtering
        $searchReg = $arr['search[regex]'];
        for ($i = 0; $i < count($columns); $i++) {
            $searchable = $arr['columns[' . $i . '][searchable]'];
            if (isset($searchable) && $searchable == 'true' && $searchReg != 'false') {
                $search_val = $arr['columns[' . $i . '][search][value]'];
                if ($where == '') {
                    $where = ' WHERE ';
                } else {
                    $where .= ' AND ';
                }
                $where .= $columns[$i] . " LIKE '%" . $this->db->escape_like_str($search_val) . "%' ";
            }
        }
		
		//final records
		$sql = 'SELECT SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $columns)) . ' FROM ' . $this->products . $where . $order . $limit;
        $result = $this->db->query($sql);
		
		//total rows
		$sql = "SELECT FOUND_ROWS() AS length_count";
        $totalFilteredRows = $this->db->query($sql)->row()->length_count;
		
		//display structure
		$echo = $this->input->get_post('draw', true);
        $output = array(
            "draw" => intval($echo),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFilteredRows,
            "data" => array()
        );
		
		//put into 'data' array
		foreach ($result->result_array() as $cols) {
            $row = array();
            foreach ($columns as $col) {
                $row[] = $cols[$col];
            }
			array_push($row, '<button class=\'edit\'>Edit</button>&nbsp;&nbsp;<button class=\'delete\' id='. $cols[$indexColumn] .'>Delete</button>');
            $output['data'][] = $row;
        }
		
		return $output;
	}
    function call_dt(){
// $this->db->select('*');
// $this->db->from('visitors');
$result = $this->db->get('visitors');
// var_dump($result);
return $result->result();

    }
   
    function get_today_visitors()
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        $date_ = date('Y-m-d');

        $this->db->where('date(added_date) =', $date_);
        $this->db->where('hav_veh', 0);
        // $data=$this->db->get('visitors')->result();
        // var_dump($data);
        // die();
        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        $this->db->order_by($this->vid, 'DESC');
        $r= $this->db->get($this->vtable)->result();
       return $r;
    }
    function get_today_emps()
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        $date_ = date('Y-m-d');

        $this->db->where('date(added_date) =', $date_);
        $this->db->where('hav_veh', 0);
        $this->db->where('is_employee', 1);
       
        $this->db->order_by($this->vid, 'DESC');
        $r= $this->db->get($this->vtable)->result();
       return $r;
    }
    function get_todayall_visitors()
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        $date_ = date('Y-m-d');

        $this->db->where('date(added_date) =', $date_);
        // $this->db->where('hav_veh', 0);
        // $data=$this->db->get('visitors')->result();
        // var_dump($data);
        // die();
        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        $this->db->order_by($this->vid, 'DESC');
        $r= $this->db->get($this->vtable)->result();
       return $r;
    }

   

    public function visitor_search($get_cnic)
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');
        $today = date('Y-m-d');
        // $this->db->where('added_date =', $date_);
        $this->db->select('visitors.*,department.name as v_dept');
        $this->db->from('visitors');
        $this->db->where('date(visitors.added_date)', $today);
        $this->db->where('visitors.cnic =', $get_cnic);
        $this->db->where('status =', 0);
        $this->db->join('department','department.id = visitors.sub_branch','left');
        // $this->db->where("(cnic=$get_cnic OR contact_no=$get_cnic OR pass_no=$get_cnic)", NULL, true);
        // $data=$this->db->get('visitors')->result();

        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        // $this->db->order_by($this->vid, 'DESC');
        $data = $this->db->get()->row();

        // var_dump($data);
        // die();
        return $data;
        // return json_encode($data);
    }
    public function vehicle_checkout_search($get_vno)
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');
        $today = date('Y-m-d');
        // $this->db->where('added_date =', $date_);
        $this->db->select('visitors.*,department.name as v_dept');
        $this->db->from('visitors');
        $this->db->where('date(visitors.added_date)', $today);
        $this->db->where('visitors.veh_no =', $get_vno);
        $this->db->where('status =', 0);
        $this->db->join('department','department.id = visitors.sub_branch','left');
        // $this->db->where("(cnic=$get_cnic OR contact_no=$get_cnic OR pass_no=$get_cnic)", NULL, true);
        // $data=$this->db->get('visitors')->result();

        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        // $this->db->order_by($this->vid, 'DESC');
        $data = $this->db->get()->row();

        // var_dump($data);
        // die();
        return $data;
        // return json_encode($data);
    }

    public function visitor_search_checkin($get_cnic)
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');
        $today = date('Y-m-d');
        // $this->db->where('added_date =', $date_);
        $this->db->where('date(visitors.added_date)', $today);
        $this->db->where('cnic =', $get_cnic);
        $this->db->where('status =', 0);
        // $this->db->where("(cnic=$get_cnic OR contact_no=$get_cnic OR pass_no=$get_cnic)", NULL, true);
        // $data=$this->db->get('visitors')->result();

        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        // $this->db->order_by($this->vid, 'DESC');
        $data = $this->db->get($this->vtable)->row();

        // var_dump($data);
        // die();
        return $data;
        // return json_encode($data);
    }

    public function get_today_vequipments($id)
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');
        $today = date('Y-m-d');
        // $this->db->where('added_date =', $date_);
        $this->db->select('*');
        $this->db->from('visitorsequipment');

        $this->db->where('date(visitorsequipment.added_date)', $today);
        $this->db->where('visitor_id =', $id);
        // $this->db->where('status =', 0);
        // $data=$this->db->get('visitors')->result();
        // var_dump($data);
        // die();
        // $this->db->where('added_date >=', $curr_date);
        // $this->db->where('added_date',  $current_date);
        // $this->db->order_by($this->vid, 'DESC');
        $data = $this->db->get()->result();
        return $data;
        // return json_encode($data);
    }


    function get_preappointed_visitors()
    {
        $this->db->order_by($this->vid, $this->order);
        $this->db->where('is_pre_appointed', 1);
        $this->db->order_by($this->vid, 'DESC');
        return $this->db->get($this->vtable)->result();
    }
    function get_public_visitors()
    {
        $this->db->order_by($this->vid, $this->order);
        $this->db->where('is_pre_appointed', 0);
        $this->db->order_by($this->vid, 'DESC');
        return $this->db->get($this->vtable)->result();
    }
    function get_visitor_by_id($id)
    {
        $this->db->where($this->vid, $id);
        return $this->db->get($this->vtable)->row();
    }
    // Visitor History

    public function visitor_history($cnic){
        $this->db->select('visitors.*,D.name as v_dept, V.name as branch');
        $this->db->from('visitors');
        $this->db->where('visitors.cnic', $cnic);
        $this->db->join('department as D','D.id = visitors.dept','left');
        $this->db->join('department as V','V.id = visitors.sub_branch','left');
        $this->db->order_by('visitors.pass_no', 'DESC');
        $data = $this->db->get()->result();
        return $data;
    }
    public function veh_history($veh_no){
        $this->db->select('visitors.*,D.name as v_dept, V.name as branch');
        $this->db->from('visitors');
        $this->db->where('visitors.veh_no', $veh_no);
        $this->db->join('department as D','D.id = visitors.dept','left');
        $this->db->join('department as V','V.id = visitors.sub_branch','left');
        $this->db->order_by('pass_no', 'DESC');
        $data = $this->db->get()->result();
        return $data;
    }


    // update visitor 


    function update_visitor($id, $data)
    {
        $this->db->where($this->vid, $id);
        $this->db->update($this->vtable, $data);
    }

    function update_checkout($id, $data)
    {
        $this->db->where($this->vid, $id);
        $this->db->update($this->vtable, $data);
    }

    function update_checkin($id, $data)
    {
        $this->db->where($this->vid, $id);
        $this->db->update($this->vtable, $data);
    }

    // delete visitor 

    function delete_visitor($id)
    {
        $this->db->where($this->vid, $id);
        $this->db->delete($this->vtable);
    }

    // ===================== // End visitor ===================----

    // get visitors
    function getvisitors()
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $query = $this->db->get();

        return $query->result();
    }

    function get_last_entry()
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $this->db->select("*");
        $this->db->from("visitors");
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(5);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();
        return $query;
    }

    function get_last_fivedected()
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
       
        $this->db->select("visitors.*");
        $this->db->distinct('apipsrms.psrms_cnic');
        $this->db->from("visitors");
        $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(5);
        $this->db->order_by("visitors.id", "DESC");
        $query = $this->db->get();
        return $query;
    }
    function get_last_pass()
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $this->db->select("*");
        $this->db->from("visitors");
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(1);
        $this->db->order_by("pass_no", "DESC");
        $query = $this->db->get();
        return $query;
    }

    function get_last_id()
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $this->db->select("*");
        $this->db->from("visitors");
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(1);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();
        return $query;
    }

    function get_last_companion()
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $this->db->select("*");
        $this->db->from("companion");
        // $this->db->where('year(leave_order_date)', date('Y'));
        $this->db->limit(1);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();
        return $query;
    }

    public function check_cro($cnic)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://202.83.168.198/crodashboard/api/SindhAPI/GetDataByCnic?cnic=' . $cnic,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Key: S1NDH90KARACH1BAD33N'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        curl_close($curl);
        // echo $response;
        // var_dump($response);
        // die();
        return $response;
    }

    public function save_cro($cnic)
    {

        // $this->db->where('cnic', $cnic);
        //  = $cnic;
        $string = $cnic;
        //php string replace
        $nic_wd = str_replace("-", "", $string);

        $cro = $this->db->get_where('apicro', array('cnic_no' => $cnic))->result_array();
        // var_dump($cro);
        // die;
        if (empty($cro)) {
            $cro = json_decode($this->check_cro($nic_wd), true);
            //    var_dump($cro);
            //    die;
            if ($cro == '') {
            } else {
                foreach ($cro as $row) {
                    $data = array(
                        'cnic_no' => $cnic,
                        'cro_no' => $row['cro_no'],
                        //    'cnic' => $row['cnic'],
                        'cro_full_name' => $row['cro_full_name'],
                        'cro_father_name' => $row['cro_father_name'],
                        'cro_age' => $row['cro_age'],
                        'category_desc' => $row['category_desc'],
                        'record_district' => $row['record_district'],
                        'cro_photo_front' => $row['cro_photo_front']
                    );

                    $this->db->insert('apicro', $data);

                    //    var_dump($data);
                    //    die();

                }
            }
        }
    }



    public function check_fir($cnic)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://202.83.168.251/FIR/criminal_api/checkperson?cnic=',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('cnic' => $cnic, 'X_API_KEY' => 'F528BF1B36P2A8K7ICSETA1A2F3N5FD7FA61SINDHPOLICE'),
            CURLOPT_HTTPHEADER => array(
                'X_API_KEY: F528BF1B36P2A8K7ICSETA1A2F3N5FD7FA61SINDHPOLICE',
                'Cookie: d1777482_1a65x448=eU2rIOuT1XMqhX2UFBz1eXsDg1f1kKCG0KAhltYRwI9jYzwJQsXPcDhQpzJHJsGKkTcrDEcPeIaaUk6f4ChtPDbtP7GTJa5%2FRzfChUtH5HlgmQ5uMIKHfYLoCgjiIX7l3g6R5LG1v4CCnQLs3lpIQjSk12pmpqA7gF7xtMiWaizrqMLmW9qw7aWWs7z%2FWXTFisuskSMaBoXpRt3YS8pvc96ZvlSmttGiy7EVZruZDQaYZ2ek8cbilfwODt1NWdECRRw53cNxH99JV15xXY0XcKizycP9NXgCXdty6BmyhXygmp%2BktRuN2eLAtN7cTpBWRzK8bMd%2FzlGSUAGGwzvGGw%3D%3D150bb8d453b6cc13ec513527fbf3082f5cd5a32a'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        curl_close($curl);
        echo $response;

        $result = json_decode($response);

        return $result;
    }
    public function save_fir($cnic)
    {

        $this->db->where('psrms_cnic', $cnic);
        $record = $this->db->get('apipsrms')->result_array();
        // var_dump($record);
        // die;
        if (empty($record)) {
            $record = $this->check_fir($cnic);

            //  var_dump($record);
            // die;
            foreach ($record as $row) {
                $data = array(
                    'psrms_cnic' => $cnic,
                    'fir_district' => $row->fir_district,
                    'fir_ps' => $row->fir_district,
                    'fir_no' => $row->fir_no,
                    'fir_year' => $row->fir_year,
                    'fir_offence_date' => $row->fir_offence_date,
                    'fir_offecnce' => $row->fir_offecnce,
                    'fir_status' => $row->fir_status,
                    'sus_name' => $row->sus_name,
                    'sus_parent_name' => $row->sus_parent_name,
                    'sus_gender' => $row->sus_gender,
                    'sus_cast' => $row->sus_cast,
                    'sus_address' => $row->sus_address,
                    'sus_phone' => $row->sus_phone
                );

                $this->db->insert('apipsrms', $data);
            }
        }
    }

    public function insert_companion($pass_id)
    {

        $data = array();
        // $this->input->post('person_cnic')
        // echo "<pre>";
        // print_r([
        //     $this->input->post('person_cnic')
        // ]);
        // exit;

        $temp = count($this->input->post('person_cnic'));
        // $id       =   $this->input->post('id');
        $purpose = $this->input->post('purpose', TRUE);
        $contact_no = $this->input->post('contact_no', TRUE);
        $reference = $this->input->post('reference', TRUE);
        $dept = $this->input->post('dept', TRUE);
        $floor = $this->input->post('floor', TRUE);
        $appoin_date = date('Y-m-d H:i:s', strtotime($this->input->post('appoin_date', TRUE)));
        $date_from = date('Y-m-d H:i:s', strtotime($this->input->post('date_from', TRUE)));
        $date_to = date('Y-m-d H:i:s', strtotime($this->input->post('date_to', TRUE)));

        $id = $pass_id;
        $new_pass_no = 0;
        $pass_no = $this->get_last_id()->result();
        foreach ($pass_no as $pass) {
            $new_pass_no = $pass->pass_no;
        }
        // var_dump($id);
        // die;

        for ($i = 0; $i < $temp; $i++) {

            $pass = ++$new_pass_no;
            $cnic  =   $this->input->post('person_cnic');
            $name        =   $this->input->post('person_name');
            $father_name = $this->input->post('person_fname');
            $address = $this->input->post('person_address');
            $hr_img = $this->input->post('companion_hr_img');
            $cro_verified = $this->input->post('cro_verified');
            $hrm_verified = $this->input->post('hrm_verified');
            $nadra_verified = $this->input->post('nadra_verified');
            $read_status = $this->input->post('read_status');
            // $img_nadra = $this->input->post('companion_img');

            // $visitor_id       =    $id;



            if ($cnic[$i] != '') {

                $data[$i] = array(



                    // 'cnic' => $cnic[$i],
                    // 'name' => $name[$i],
                    // 'father_name' => $father_name[$i],
                    // 'address' => $address[$i],
                    // // 'nadra_image'=>$nadra_image,
                    // 'companion_hr_img'=>$hr_img[$i],
                    // 'visitor_id' => $id,
                    // 'added_date' => date('Y-m-d H:i:s'),

                    'cnic' => $cnic[$i],
                    'name' => $name[$i],
                    'fname' => $father_name[$i],
                    'address' => $address[$i],
                    // 'nadra_image'=>$nadra_image,
                    'visitor_image' => $hr_img[$i],
                    'cro_verified' => $cro_verified[$i],
                    'hrm_verified' => $hrm_verified[$i],
                    'nadra_verified' => $nadra_verified[$i],
                    'read_status' => $read_status[$i],
                    'source_id' => $id,
                    'pass_no' => $pass,

                    'purpose' => $purpose,
                    'contact_no' => $contact_no,

                    'reference' => $reference,
                    'dept' => $dept,
                    'floor' => $floor,
                    'appoin_date' => $appoin_date,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'check_in' => date('Y-m-d H:i:s'),
                    'added_date' => date('Y-m-d H:i:s'),


                );
                // $this->save_cro($cnic[$i]);
                // $this->save_fir($cnic[$i]);
            }
        }
        // echo "<pre>";
        // print_r($data);
        // exit;

        $insert = count($data);

        if ($insert) {
            // $this->db->insert_batch('companion', $data);
            $this->db->insert_batch('visitors', $data);
        }

        return $insert;
    }
    function get_companion($id)
    {
        $this->db->select('*');
        $this->db->from('visitors');
        // $this->db->order_by("leave_order_date", "desc");

        $this->db->where('source_id', $id);
        $query = $this->db->get();
        // , array('emp_id' => $emp_id));
        return $query;
    }
    public function update_companion()
    {
        $id       =   $this->input->post('id');
        $this->db->where('source_id', $id);
        $this->db->delete('visitors');
        $data = array();

        $temp = count($this->input->post('person_cnic'));

        $purpose = $this->input->post('purpose', TRUE);
        $contact_no = $this->input->post('contact_no', TRUE);
        $reference = $this->input->post('reference', TRUE);
        $dept = $this->input->post('dept', TRUE);
        $floor = $this->input->post('floor', TRUE);
        $appoin_date = date('Y-m-d H:i:s', strtotime($this->input->post('appoin_date', TRUE)));
        $date_from = date('Y-m-d H:i:s', strtotime($this->input->post('date_from', TRUE)));
        $date_to = date('Y-m-d H:i:s', strtotime($this->input->post('date_to', TRUE)));

        // $id =$pass_id;
        $new_pass_no = 0;
        $pass_no = $this->get_last_id()->result();
        foreach ($pass_no as $pass) {
            $new_pass_no = $pass->pass_no;
        }
        for ($i = 0; $i < $temp; $i++) {
            $new_id = 0;


            $pass = ++$new_pass_no;
            $cnic  =   $this->input->post('person_cnic');
            $name        =   $this->input->post('person_name');
            $father_name = $this->input->post('person_fname');
            $address = $this->input->post('person_address');
            $hr_img = $this->input->post('companion_hr_img');
            $cro_verified = $this->input->post('cro_verified');
            $hrm_verified = $this->input->post('hrm_verified');
            $nadra_verified = $this->input->post('nadra_verified');
            $read_status = $this->input->post('read_status');

            // $visitor_id       =    $id;


            if ($cnic[$i] != '') {
                $data[$i] = array(
                    'cnic' => $cnic[$i],
                    'name' => $name[$i],
                    'fname' => $father_name[$i],
                    'address' => $address[$i],
                    // 'nadra_image'=>$nadra_image,
                    'visitor_image' => $hr_img[$i],
                    'cro_verified' => $cro_verified[$i],
                    'hrm_verified' => $hrm_verified[$i],
                    'nadra_verified' => $nadra_verified[$i],
                    'read_status' => $read_status[$i],
                    'source_id' => $id,
                    'pass_no' => $pass,

                    'purpose' => $purpose,
                    'contact_no' => $contact_no,

                    'reference' => $reference,
                    'dept' => $dept,
                    'floor' => $floor,
                    'appoin_date' => $appoin_date,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'check_in' => date('Y-m-d H:i:s'),
                    'updated_on' => date('Y-m-d H:i:s'),


                );
                // $this->save_cro($cnic[$i]);
                // $this->save_fir($cnic[$i]);
            }
        }
        // echo "<pre>";
        // print_r($data);
        // exit;

        $insert = count($data);

        if ($insert) {
            $this->db->insert_batch('visitors', $data);
        }

        return $insert;
    }
    public function insert_v_equipment()
    {

        $data = array();
        // $this->input->post('person_cnic')
        // echo "<pre>";
        // print_r([
        //     $this->input->post('person_cnic')
        // ]);
        // exit;

        $temp = count($this->input->post('equipment'));
        $id       =   $this->input->post('id');


        for ($i = 0; $i < $temp; $i++) {

            $eq_name  =   $this->input->post('equipment');
            $qty        =   $this->input->post('qty');
            $serial        =   $this->input->post('serial');
            // $visitor_id       =   $id;


            if ($eq_name[$i] != '') {
                $data[$i] = array(
                    'eq_name' => $eq_name[$i],
                    'qty' => $qty[$i],
                    'visitor_id' => $id,
                    'serial' => $serial[$i],
                    'added_date' => date('Y-m-d H:i:s'),

                );
            }
        }
        // echo "<pre>";
        // print_r($data);
        // exit;

        $insert = count($data);

        if ($insert) {
            $this->db->insert_batch('visitorsequipment', $data);
        }

        return $insert;
    }
    function get_v_equipment($id)
    {
        $this->db->select('*');
        $this->db->from('visitorsequipment');
        // $this->db->order_by("leave_order_date", "desc");

        $this->db->where('visitor_id', $id);
        $query = $this->db->get();
        // , array('emp_id' => $emp_id));
        return $query;
    }

    function get_counteq_byid($id)
    {
        $this->db->select('count(*) as allcount');
        $this->db->where('visitor_id', $id);

        $records = $this->db->get('visitorsequipment')->result();
        $query = $records[0]->allcount;

        // $this->db->count('*');
        // $this->db->from('visitorsequipment');
        // $this->db->order_by("leave_order_date", "desc");

        // $query = $this->db->count_all_results();
        // , array('emp_id' => $emp_id));
        // var_dump($query);
        return $query;
    }
    function get_equipment_type()
    {
        $this->db->select('*');
        $this->db->from('equipments');
        $this->db->order_by("id", "desc");

        $query = $this->db->get();
        // , array('emp_id' => $emp_id));
        return $query;
    }
    public function update_v_equipment()
    {
        $id       =   $this->input->post('id');
        $this->db->where('visitor_id', $id);
        $this->db->delete('visitorsequipment');

        $data = array();
        // $this->input->post('person_cnic')
        // echo "<pre>";
        // print_r([
        //     $this->input->post('person_cnic')
        // ]);
        // exit;

        $temp = count($this->input->post('equipment'));



        for ($i = 0; $i < $temp; $i++) {

            $eq_name  =   $this->input->post('equipment');
            $qty        =   $this->input->post('qty');
            $serial        =   $this->input->post('serial');
            // $visitor_id       =   $id;


            if ($eq_name[$i] != '') {
                $data[$i] = array(
                    'eq_name' => $eq_name[$i],
                    'qty' => $qty[$i],
                    'serial' => $serial[$i],
                    'visitor_id' => $id,
                    'added_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s'),

                );
            }
        }
        // echo "<pre>";
        // print_r($data);
        // exit;

        $insert = count($data);

        if ($insert) {
            $this->db->insert_batch('visitorsequipment', $data);
        }

        return $insert;
    }

    // ================ get department with floor==============

    function get_department()
    {
        $query = $this->db->get('department');
        return $query;
    }
    function get_floor_data($department)
    {
        $query = $this->db->get_where('department', array('id' => $department));
        return $query;
    }
    function get_sub_branches($department_id)
    {
        $query = $this->db->get_where('department', array('parent_id' => $department_id));
        return $query;
    }

    function get_companion_with_id($id)
    {
        // $this->db->where('visitor_id', $id);
        // $query = $this->db->get('companion');
        $this->db->where('source_id', $id);
        $query = $this->db->get('visitors');
        return $query;
    }



    //  ================/// Get department with floor =========


    // --------- report with filter ------------------------

    // Get DataTable data
    function get_Visitors($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        $visitor_type = $postData['visitor_type'];
        
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];
		
        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept like '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%' or is_pre_appointed like '%" . $searchValue . "%' or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " visitors.name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept like '%" . $searchdept . "%' ";
        }
		if ($searchsubb != '') {
            $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        }

        if ($visitor_type != '') {
            $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        }
        // if ($visitor_type == 2) {
        //     $search_arr[] = " hav_veh like '%" . $hav_veh . "%' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('visitors.*, department.name as v_dept, branches.name as branch, department.floor as d_floor');

        // -------- join left dept---------
        $this->db->join('department', 'department.id = visitors.dept', 'left');
		$this->db->join('department as branches', 'branches.id = visitors.sub_branch', 'left');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->from('visitors');
        
        $records = $this->db->get()->result();

        $data = array();
        $serial_no = 1;
        
        foreach ($records as $record) {
            $depts=$this->getDepts();

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "v_dept" => $record->v_dept,
                "sub_branch" => $record->branch,
                "floor" => $record->floor,
                "officer_called" => $record->officer_called,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("d-m-Y", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),

            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

    // NOTIFICATION

    public function fetch_data($v)
    {

        if ($v != '') {
            $this->db->set('read_status', '1');
            $this->db->where('read_status', '0');
            $this->db->update('visitors');
            echo '0';
        }
        $this->db->select();
        $this->db->from("visitors");
        // $this->db->where("read_status", "");
        $this->db->where("read_status !=", NULL);
        $this->db->where("read_status !=", 2);
        // $this->db->where("read_status !=", NULL);
        $this->db->limit(5);
        $this->db->order_by("id", "DESC");
        $result = $this->db->get();
        $output = '';

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $user_image = '';
                if ($row->visitor_img == 'noimage.jpg' || $row->nadra_image == 'noimage.jpg') {
                    $user_image = 'user.png';
                } else if ($row->nadra_image != null || $row->nadra_image != '') {
                    $user_image = 'nadra_images/' . $row->nadra_image;
                } else if ($row->visitor_img != null || $row->visitor_img != '' || $row->visitor_img != 'noimage.jpg') {
                    $user_image = 'visitor_images/' . $row->visitor_img;
                }
                // var_dump($user_image);
                // die;
                //   $output .='<li><a href="#">
                //   <strong>Name: ' . $row->name . ' </strong><br />
                //   <small><em>CNIC: '.$row->cnic.'</em></small>
                //   </a> </li>';

                $output .= '
                            
                            <div class="notification_desc">
                                <a class="" href="' . base_url('Visitor_Controller/visitor_detail/') . $row->id . '">
                                
                                <img class="user_img" style="border: 1px solid #ccc; padding:2px;" src="' . base_url('assets/images/') . $user_image . '" alt="" width="35px"> 
                                
                               
                                
                                <span>Name:<strong> ' . $row->name . ' </strong> </span><br>
                                <span class="cnic" style="margin-left: 40px;margin-top: -9px; position:absolute;"><small><em>CNIC: ' . $row->cnic . '</em></small></span>
                                <span class="float-right" style="margin-top: -9px; position:relative;">' . date("M-d-Y H:i", strtotime($row->added_date));
                '</span>
                                
                                
                                
                                </a>
                                </div>
                        </a>';
            }
        } else {
            // $output .= '<li><a href="#" class="text-bold text-italic"> No Notification Found </a></li>'; 
            $output .= 'Notification not Found';
        }

        $this->db->select();
        $this->db->from("visitors");
        $this->db->where("read_status", "0");
        $result1 = $this->db->get();
        $count = $result1->num_rows();

        // $data= array('v_name'=>$row->name,
        //              'v_cnic'=>$row->cnic,
        //              'unseen_notification'=>$count);

        $data = array('notification' => $output, 'unseen_notification' => $count);
        // $data= array('unseen_notification'=>$count);
        return json_encode($data);
    }


    public function user_notification()
    {
        $this->db->select();
        $this->db->from("visitors");
        $this->db->where("read_status", "0");
        $this->db->limit(5);
        $this->db->order_by("id", "DESC");
        $result = $this->db->get();
        return $result;
    }

    // public function user_notification(){
    //     $this->db->from('visitors');
    //     $this->db->where('read_status', 0);
    //     $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
    //     $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
    //     // $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
    //     $r = $this->db->get()->num_rows();
    //     // $r = $this->db->last_query();
    //     return $r;
    //     // var_dump($r);
    //     // die();
    // }

    // END NOTIFICATION

    // -----------  get criminal record psrms ------------

    function get_cro($cnic)
    {
        $this->db->select('*');
        $this->db->where('cnic_no', $cnic);
        $query = $this->db->get('apicro');
        return $query;
    }

    function get_fir($cnic)
    {
        $this->db->select('*');
        $this->db->where('psrms_cnic', $cnic);
        $query = $this->db->get('apipsrms');
        return $query;
    }

    function get_cri_Visitors($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        $visitor_type = $postData['visitor_type'];
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept = " . $searchValue . " or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%' or is_pre_appointed like '%" . $searchValue . "%' or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " visitors.cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " visitors.name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept ='" . $searchdept . "' ";
        }
        if ($searchsubb != '') {
            $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        }

        if ($visitor_type != '') {
            $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->distinct('apipsrms.psrms_cnic');
        $this->db->select('count(*) as allcount');
        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic' , 'INNER' );
        
        $this->db->from('visitors');
        $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
        $r = $this->db->get()->result();
        // var_dump($r);
        // die;

        // $records = $this->db->get('visitors')->result();
        $records = $r;
        $totalRecords = $records[0]->allcount;

        // cnic without dashes---


        ## Total number of record with filtering
        $this->db->distinct('apipsrms.psrms_cnic');
        $this->db->select('count(*) as allcount');
        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic' , 'INNER' );
        // $this->db->select('visitors.*');
        $this->db->from('visitors');
        $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
        $r = $this->db->get()->result();

        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $r;
        $totalRecordwithFilter = $records[0]->allcount;

        // -- cri- record--
        // $this->db->select('*');
        // $this->db->from('apipsrms');
        // $psrms=$this->db->get()->result();


        $this->db->select('visitors.*,department.name as v_dept, branches.name as branch, department.floor as d_floor, apipsrms.fir_offecnce as fir_offence, apipsrms.fir_offence_date as fir_offence_date,apipsrms.fir_ps as fir_ps,apipsrms.fir_status as fir_status');
        $this->db->distinct('apipsrms.psrms_cnic');
        $this->db->from('visitors');
        // -------- join left dept---------
        $this->db->join('department', 'department.id = visitors.dept', 'left');
        $this->db->join('department as branches', 'branches.id = visitors.sub_branch', 'left');
        $this->db->join('apipsrms', 'apipsrms.psrms_cnic = visitors.cnic', 'left');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'left');
        
        $this->db->where('apipsrms.psrms_cnic !=', null)->or_where('apicro.cnic_no !=', null);
		
        // $r = $this->db->get()->result();

		
        // echo "<pre>";
        // var_dump($r);
        // echo "</pre>";
        // die();

        ## Fetch records
        // $this->db->select('*');


        // $string="text-with-dashes";
        // $test = str_replace("-", " ", $string);
        // $this->db->from('visitors');


        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic' , 'right' );
        // $this->db->join ( 'apicro', 'apicro.cnic_no = visitors.cnic' , 'left' );

        // $this->db->where('`cnic` in  (select  `psrms_cnic` from `apipsrms`)', NULL, FALSE);
        // $this->db->where('`cnic` in  (select  `cnic_no` from `apicro`)', NULL, FALSE);



        // $this->db->query('Select * from visitors v 
        // where 
        // v.cnic in  (select  psrms_cnic from apipsrms ps)
        // OR 
        // v.cnic in (SELECT cro.cnic_no from apicro cro )');



        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic' ,'inner');
        // $this->db->where('apipsrms.psrms_cnic= visitors.cnic'  );
        // $this->db->join ('apicro', 'apicro.cnic_no = visitors.cnic');
        // $this->db->where('apicro.cnic_no= visitors.cnic'  );


        // $this->db->where('cnic', apipsrms.cnic);
        // $records = $this->db->get()->result();
        // var_dump($records);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        // $records = $r;


        $data = array();
        $serial_no = 1;


        foreach ($records as $record) {

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "fir_offence" => $record->fir_offence,
                "fir_offence_date" => $record->fir_offence_date,
                "fir_ps" => $record->fir_ps,
                "fir_status" => $record->fir_status,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "v_dept" => $record->v_dept,
                "sub_branch" => $record->branch,
                "floor" => $record->d_floor,
                "officer_called" => $record->officer_called,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("d-m-Y", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),

            );
        }


        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    // ---------- // get criminal record psrms-----------

    // -----------  get criminal record cro ------------

    function get_cro_Visitors($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value



        // Custom search filter 
        $searchdept = $postData['searchdept'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchcnic = $postData['searchcnic'];
        $searchname = $postData['searchname'];
        $visitor_type = $postData['visitor_type'];
        $searchpurpose = $postData['searchpurpose'];
        $searchfloor = $postData['searchfloor'];
        // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // $searchdatefrom = date("Y-m-d",strtotime($from));
        $to = $postData['searchdateto'];
        // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $searchname = $postData['searchname'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept like '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or 
        cnic like '%" . $searchValue . "%' or  
        contact_no like'%" . $searchValue . "%' or floor like '%" . $searchValue . "%' or is_pre_appointed like '%" . $searchValue . "%' or 
        hav_veh like '%" . $searchValue . "%' or check_in '%" . $searchValue . "%' ) ";
        }
        if ($searchcontact_no != '') {
            $search_arr[] = " contact_no='" . $searchcontact_no . "' ";
        }
        if ($searchcnic != '') {
            $search_arr[] = " cnic='" . $searchcnic . "' ";
        }
        if ($searchname != '') {
            $search_arr[] = " name like '%" . $searchname . "%' ";
        }
        if ($searchdept != '') {
            $search_arr[] = " dept like '%" . $searchdept . "%' ";
        }

        if ($visitor_type != '') {
            $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        if ($searchpurpose != '') {
            $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        }

        if ($searchfloor != '') {
            $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic' , 'INNER' );

        // $this->db->join ( 'apipsrms', 'apipsrms.psrms_cnic = visitors.cnic','inner' );
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'inner');
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

        // cnic without dashes---


        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'inner');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        // -- cri- record--
        // $this->db->select('*');
        // $this->db->from('apipsrms');
        // $psrms=$this->db->get()->result();


        ## Fetch records
        $this->db->select('*');

        $this->db->join('apicro', 'apicro.cnic_no = visitors.cnic', 'inner');

        // $this->db->where('cnic', apipsrms.cnic);
        // $records = $this->db->get()->result();
        // var_dump($records);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('visitors')->result();


        $data = array();
        $serial_no = 1;


        foreach ($records as $record) {

            $data[] = array(
                "serial_no" => $serial_no++,
                "pass_no" => $record->pass_no,
                "veh_no" => $record->veh_no,
                "name" => '<a href="' . base_url() . 'Visitor_Controller/visitor_detail/' . $record->id . '">' . $record->name . '</a>',
                "cnic" => $record->cnic,
                "purpose" => $record->purpose,
                "contact_no" => $record->contact_no,
                "dept" => $record->dept,
                "floor" => $record->floor,
                "no_of_persons" => '<span class="text-center">' . $record->no_of_persons . '</span>',
                "added_date" => date("d-m-Y", strtotime($record->added_date)),
                "check_in" => date("H:i:s", strtotime($record->check_in)),
                "check_out" => date("H:i:s", strtotime($record->check_out)),

            );
        }


        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    // ---------- // get criminal record cro-----------

    // Get Departments array
    public function getDepts()
    {

        ## Fetch records
        $this->db->distinct();
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $records = $this->db->get('department')->result();

        // $data = array();

        // foreach ($records as $record) {
        //     $data[] = $record->name;
        // }

        return $records;
    }

    // Get Purpose array
    public function getPurpose()
    {

        ## Fetch records
        $this->db->distinct();
        $this->db->select('purpose');
        $this->db->order_by('purpose', 'asc');
        $records = $this->db->get('visitpuropse')->result();

        $data = array();

        foreach ($records as $record) {
            $data[] = $record->purpose;
        }

        return $data;
    }

    // Get floor array
    public function getFloor()
    {

        ## Fetch records
        $this->db->distinct();
        $this->db->select('floor');
        $this->db->order_by('id', 'asc');
        $records = $this->db->get('department')->result();

        $data = array();

        foreach ($records as $record) {
            $data[] = $record->floor;
        }

        return $data;
    }
    // -------- /// report with filter --------------------

    // ============= Use Employee Data ====================
	public  function recent_entry()
	{

		
		$curtime = time();
		$now = date('Y-m-d H:i:s');
		// $a=(int)(($now - $start) / 60
		$seconds = date('H:i:s', strtotime('-2 hour'));
		// echo $date;

		// print_r($date);
		// die;
		$date = new DateTime("now");
		$curr_date = $date->format('Y-m-d ');
		$this->db->select('*');
		$this->db->from('visitors');
		// $this->db->where('time(added_date) >', $seconds);
		// $this->db->where('time(added_date)<', $curtime -1);
		$this->db->where('read_status', 2);
		$this->db->where('date(added_date)', $curr_date);
		$result = $this->db->get()->result();
		// print_r($result);
		// die;
		return $result;
	}
	public function get_employee_fromentry($cnic)
    {
		// $date = new DateTime("now");
		// $curr_month = $date->format('m');
		$this->db->select('name as ofc_name, fname as ofc_fathername,rank as rnk_name, contact_no as ofc_phone, current_posting as ps_name_eng, visitor_image as ofc_picture,address as ofc_address,address as ofc_mailing_address,cnic as ofc_cnic');
        $this->db->where('cnic', $cnic);
        $this->db->where('hrm_verified', 1);
        $this->db->where('MONTH(added_date)', date('m'));
        $records = $this->db->get('visitors')->result();

        // $data = array();

        // foreach ($records as $record) {
        //     $data[] = $record->name;
        // }

        return $records;
	}
    public function get_employee_bycnic($cnic)
    {
        // connect
        $otherdb = $this->load->database('db2', TRUE);
        // echo '<pre>';
        // echo var_dump($otherdb);
        // echo '</pre>';
        // $this->otherdb->query('select * from tbl_officer limit 1');
        // $otherdb.query('select * from tbl_officer limit 1');
        // $otherdb.query('select * from tbl_officer limit 1');
        //   $otherdb->select("*");
        //   $otherdb->from("tbl_officer");
        //   $otherdb->limit("tbl_officer");

        //   $otherdb->where("ofc_cnic", $cnic); 
        //   $otherdb->join('tbl_leave_types','tbl_leave_types.leave_type_id = tbl_leaves.leave_type_id','left');


        // $query = $otherdb->where('ofc_cnic', $cnic);
        // $query = $otherdb->get('tbl_officer');
        // $query = $otherdb->select('tbl_officer.*,Rank.rnk_name,Ps.ps_name_eng,dsg.designation_name,
        // tbl_officer.ofc_dateofbirth,
        // pdst.dst_name,
        // domdst.dst_name AS domicile,
        // tbl_postingtype.ptp_name,
        // tbl_postingtype.ptp_id');
		$query = $otherdb->select('tbl_officer.*,Rank.rnk_name,
        tbl_officer.ofc_dateofbirth,
        ');
        $query = $otherdb->From('tbl_officer');
        $query = $otherdb->where('ofc_cnic', $cnic);
        $query = $otherdb->join('tbl_ranks as Rank', 'tbl_officer.ofc_currentrank = Rank.rnk_id', 'LEFT');
        // $query = $otherdb->join('tbl_postingtype', 'tbl_postingtype.ptp_id = tbl_officer.ofc_potingstatus', 'LEFT');
        
        // $query = $otherdb->join('tbl_district_unit as pdst', 'pdst.dst_id = tbl_officer.current_posting_district', 'LEFT');
        // $query = $otherdb->join('tbl_district_unit as domdst', 'domdst.dst_id = tbl_officer.ofc_domicile', 'LEFT');
        // $query = $otherdb->join('tbl_ranks', 'tbl_ranks.rnk_id = tbl_officer.ofc_currentrank', 'LEFT');
        // $query = $otherdb->join('designations   as dsg', 'dsg.designation_id = tbl_officer.current_posting', 'LEFT');
        // $query = $otherdb->join('police_stations as Ps', 'Ps.ps_id = tbl_officer.posting_police_station_office ', 'LEFT');

        
        // tbl_wings
        // echo json_encode($query->result());

        // return (array('status' => true));
        // var_dump($query);
        $query = $otherdb->get();
        return $query->result();
        // die();

        //   $query =  $otherdb->get();


        //     //  $leaves = $query->result();
        //      return $query;
        //      var_dump($query);
        //      die();

       
    }


	public function get_gul()
    {
        // connect
        $otherdb = $this->load->database('db2', TRUE);
        
        $query = $otherdb->select('tbl_officer.*,Rank.rnk_name,Ps.ps_name_eng,dsg.designation_name,
        tbl_officer.ofc_dateofbirth,
        pdst.dst_name,
        domdst.dst_name AS domicile,
        tbl_postingtype.ptp_name,
        tbl_postingtype.ptp_id');
        $query = $otherdb->From('tbl_officer');
        // $query = $otherdb->where('ofc_name', 'Abdul Rahim');
        $query = $otherdb->where('ofc_belt_no', '278');
        $query = $otherdb->join('tbl_ranks as Rank', 'tbl_officer.ofc_currentrank = Rank.rnk_id', 'LEFT');
        $query = $otherdb->join('tbl_postingtype', 'tbl_postingtype.ptp_id = tbl_officer.ofc_potingstatus', 'LEFT');
        
        $query = $otherdb->join('tbl_district_unit as pdst', 'pdst.dst_id = tbl_officer.current_posting_district', 'LEFT');
        $query = $otherdb->join('tbl_district_unit as domdst', 'domdst.dst_id = tbl_officer.ofc_domicile', 'LEFT');
        // $query = $otherdb->join('tbl_ranks', 'tbl_ranks.rnk_id = tbl_officer.ofc_currentrank', 'LEFT');
        $query = $otherdb->join('designations   as dsg', 'dsg.designation_id = tbl_officer.current_posting', 'LEFT');
        $query = $otherdb->join('police_stations as Ps', 'Ps.ps_id = tbl_officer.posting_police_station_office ', 'LEFT');

        
        $query = $otherdb->get();
        return $query->result();
      
       
    }
    // ============ // use Employee data ===================

	public function save_message($data)
	{
		$this->db->insert('sms', $data);
	}
	public function save_ndr($data)
	{
		$this->db->insert('apinadra', $data);
	}



}
