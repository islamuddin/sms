<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_Model extends CI_Model
{

    public $order = 'DESC';
    public $vtable = 'visitors';
    public $vid = 'id';
    public $vno = NULL;
    public $leavetype_id = 'leave_type_id';
    public $leavetype_tbl = 'tbl_leave_type';
    public $leave_id = 'leave_order_id';
    public $leave_tbl = 'tbl_leave_orders';
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
        $this->db->select('BaseTbl.userId, BaseTbl.user_name, BaseTbl.name, BaseTbl.mobile, Role.role');
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
        $this->db->select('userId, name, user_name, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();

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



    // ================= insert visitor =====================
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


    function get_all_vehicles()
    {
        $this->db->order_by($this->vid, $this->order);
        $this->db->where('hav_veh', 1);
        return $this->db->get($this->vtable)->result();
    }

    function get_visitor_by_id($id)
    {
        $this->db->where($this->vid, $id);
        $this->db->where('hav_veh', 1);

        return $this->db->get($this->vtable)->row();
    }
    // update visitor 

    function update_visitor($id, $data)
    {
        $this->db->where($this->vid, $id);

        $this->db->update($this->vtable, $data);
        
    }

    function update_vehicle_image($id, $data)
    {
        $this->db->where('id', $id);

        $result=$this->db->update('visitors', $data);
        return $result;
        
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
        $this->db->limit(1);
        $this->db->order_by("id", "DESC");
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

            // $visitor_id       =    $id;


            if ($cnic[$i] != '') {
                $data[$i] = array(
                    'cnic' => $cnic[$i],
                    'name' => $name[$i],
                    'fname' => $father_name[$i],
                    'address' => $address[$i],
                    // 'nadra_image'=>$nadra_image,
                    'visitor_image' => $hr_img[$i],
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


    function get_floor_data($department)
    {
        $query = $this->db->get_where('department', array('name' => $department));
        return $query;
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
                    'serial' => $serial[$i],
                    'visitor_id' => $id,
                    'in_veh' => 1,

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
                    'visitor_id' => $id,
                    'serial' => $serial[$i],

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
    public function vehicle_nosearch($veh_no,$v_date)
    {
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        // $today = date('Y-m-d');
        // $this->db->where('added_date =', $date_);
        $this->db->select('visitors.*,department.name as v_dept');
        $this->db->from('visitors');
        $this->db->where('date(visitors.added_date)', $v_date);
        $this->db->where('visitors.veh_no =', $veh_no);
        $this->db->where('visitors.checkout_image =', null);
        // $this->db->where('status =', 0);
        $this->db->join('department', 'department.id = visitors.sub_branch', 'left');

        $data = $this->db->get()->row();

        // var_dump($data);
        // die();
        return $data;
        // return json_encode($data);
    }

	function get_all_vehicles_tbl($postData = null)
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
        $this->db->where('hav_veh',1);
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        // $this->db->where('is_pre_appointed', 1);    
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
                "veh_type" => $record->veh_type,
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

}
