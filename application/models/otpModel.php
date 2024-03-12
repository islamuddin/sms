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
public function get_allVisitors($postData = null)
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
        if ($this->session->userdata('role') == 1) {
            $range_id = $postData['range_id'];
            $place_id = $postData['place_id'];
        }
        if ($this->session->userdata('role') == 6) {
            $place_id = $postData['place_id'];
        }
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
        if ($this->session->userdata('role') == 1 && $range_id != '') {
            $search_arr[] = " visitors.range_id='" . $range_id . "' ";
        }
        if ($this->session->userdata('role') == 1 && $place_id != '') {
            $search_arr[] = " visitors.place_id='" . $place_id . "' ";
        }
        if ($this->session->userdata('role') == 6 && $place_id != '') {
            $search_arr[] = " visitors.place_id='" . $place_id . "' ";
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
            $search_arr[] = " date(visitors.added_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('visitors.hav_veh', 0);
        if ($this->session->userdata('role') == 2) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        } else if ($this->session->userdata('role') == 6) {
            $this->db->where('visitors.range_id', $this->session->userdata('range_id'));
        } else if (in_array($this->session->userdata('role'), [3, 4, 5])) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get('visitors')->result();
        $totalRecords = $records[0]->allcount;

## Total number of records with filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('visitors.hav_veh', 0);
        if ($this->session->userdata('role') == 2) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        } else if ($this->session->userdata('role') == 6) {
            $this->db->where('visitors.range_id', $this->session->userdata('range_id'));
        } else if (in_array($this->session->userdata('role'), [3, 4, 5])) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get('visitors')->result();
        $totalRecordwithFilter = $records[0]->allcount;

## Fetch records
        $this->db->select('visitors.*, p.place_name as place_name, department.name as v_dept, department.floor as d_floor, b.name as s_branch');
        $this->db->from('visitors');
        $this->db->join('department', 'department.id = visitors.dept', 'left');
        $this->db->join('department as b', 'b.id = visitors.sub_branch', 'left');
        $this->db->join('places as p', 'p.place_id = visitors.place_id', 'left');
        $this->db->where('visitors.hav_veh', 0);
        if ($this->session->userdata('role') == 2) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        } else if ($this->session->userdata('role') == 6) {
            $this->db->where('visitors.range_id', $this->session->userdata('range_id'));
        } else if (in_array($this->session->userdata('role'), [3, 4, 5])) {
            $this->db->where('visitors.place_id', $this->session->userdata('place_id'));
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowperpage != '-1') {
            $this->db->limit($rowperpage, $start);
        }

        $records = $this->db->get()->result();

        $data = array();
        $serial_no = 1;
        //  var_dump($records);
        //     die;

        foreach ($records as $record) {
            $depts = $this->getDepts();

            $data[] = array(
                "serial_no" => $serial_no++,
                "place_name" => $record->place_name,
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
                "actions" => '<a href="' . base_url() . 'Visitor_Controller/view_pass/' . $record->id . '"><i class="fa fa-id-badge"></i></a><a class="btn_edit" href="' . base_url() . 'Visitor_Controller/update_visitor/' . $record->id . '"><span class="fa fa-pencil"></span> </a>
                <!-- <a href="' . base_url("Visitor_Controller/delete_visitor/") . $record->id . '" onclick="return confirm("Do you want to delete this record?");"><span class="fa fa-trash"></span></a>-->',
            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
        );

        return $response;
    }
    public function get_allotps($postData = null)
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

        // $searchdept = $postData['searchdept'];
        // $searchsubb = $postData['searchsubb'];
        $searchcontact_no = $postData['searchcontact_no'];
        $searchstatus = $postData['searchstatus'];
        $searchname = $postData['searchname'];
        // // $visitor_type = $postData['visitor_type'];
        // $searchpurpose = $postData['searchpurpose'];
        // $searchfloor = $postData['searchfloor'];
        // // $from = $postData['searchdatefrom'];
        $searchdatefrom = $postData['searchdatefrom'];
        // // $searchdatefrom = date("Y-m-d",strtotime($from));
        // $to = $postData['searchdateto'];
        // // $searchdateto = date("Y-m-d",strtotime($to));
        $searchdateto = $postData['searchdateto'];

        // $type = $postData['type'];
        $type = $postData['url'];
        // var_dump($url);
        // die;

        ## Search
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            // $search_arr[] = " (purpose like '%" . $searchValue . "%' or dept  '%" . $searchValue . "%' or  name like '%" . $searchValue . "%' or
            $search_arr[] = " (otp.status like '%" . $searchValue . "%' or p.name  =" . $searchValue . " or  otp.number like '%" . $searchValue . "%' ) ";
        }
        // if ($this->session->userdata('role') == 1 && $range_id != '') {
        //     $search_arr[] = " visitors.range_id='" . $range_id . "' ";
        // }
        // if ($this->session->userdata('role') == 1 && $place_id != '') {
        //     $search_arr[] = " visitors.place_id='" . $place_id . "' ";
        // }
        if ($searchcontact_no != '') {
            $search_arr[] = " otp.number='" . $searchcontact_no . "' ";
        }
        // if ($searchcnic != '') {
        //     $search_arr[] = " cnic='" . $searchcnic . "' ";
        // }
        if ($searchname != '') {
            $search_arr[] = " p.name like '%" . $searchname . "%' ";
        }
        if ($searchstatus != '') {
            $search_arr[] = " otp.status ='" . $searchstatus . "' ";
        }
        // if ($searchsubb != '') {
        //     $search_arr[] = " sub_branch ='" . $searchsubb . "' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " is_pre_appointed like '%" . $visitor_type . "%' ";
        // }

        // if ($visitor_type != '') {
        //     $search_arr[] = " hav_veh like '%" . $visitor_type . "%' ";
        // }

        // if ($searchpurpose != '') {
        //     $search_arr[] = " purpose like '%" . $searchpurpose . "%' ";
        // }

        // if ($searchfloor != '') {
        //     $search_arr[] = " visitors.floor like '%" . $searchfloor . "%' ";
        // }
        if ($searchdatefrom != '' && $searchdateto != '') {
            $search_arr[] = " date(otp.created_date)  BETWEEN '$searchdatefrom'  AND  '$searchdateto' ";
            // $search_arr[] = " date(added_date)  BETWEEN date('Y-m-d', strtotime(date($searchdatefrom))  AND date('Y-m-d', strtotime(date($searchdateto)) ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->join('projects p', 'p.id = otp.project_id', 'inner');
	
	
		if ($type == "today") {
			$this->db->where('DATE(otp.created_date)', date('Y-m-d'));
		} else if ($type == "sent") {
			$this->db->where('otp.status', 1);
		} else if ($type == "failed") {
			$this->db->where('otp.status', 0);
		}	
		// $this->db->group_by('otp.id');
        $records = $this->db->get('otp')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        // $this->db->select('otp.*, p.name as project_name');
		$this->db->join('projects p', 'p.id = otp.project_id', 'inner');
	
	
		if ($type == "today") {
			$this->db->where('DATE(otp.created_date)', date('Y-m-d'));
		} else if ($type == "sent") {
			$this->db->where('otp.status', 1);
		} else if ($type == "failed") {
			$this->db->where('otp.status', 0);
		}	
		// $this->db->group_by('otp.id');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $records = $this->db->get('otp')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('otp.*, p.name as project_name');
		$this->db->join('projects p', 'p.id = otp.project_id', 'inner');

	
		if ($type == "today") {
			$this->db->where('DATE(otp.created_date)', date('Y-m-d'));
		} else if ($type == "sent") {
			$this->db->where('otp.status', 1);
		} else if ($type == "failed") {
			$this->db->where('otp.status', 0);
		}	
		$this->db->group_by('otp.id');
		// $this->db->order_by('date(otp.created_date)', 'desc');
		
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $this->db->order_by($columnName, $columnSortOrder);
        if ($rowperpage != '-1') {
            $this->db->limit($rowperpage, $start);
        }

        // $this->db->from('visitors');
        $this->db->from('otp');

        $records = $this->db->get()->result();
        // $lastquery= $this->db->last_query();
        // var_dump($records);
        // die;

        $data = array();
        $serial_no = 1;

        foreach ($records as $record) {
         
            $data[] = array(
                // "serial_no" => $serial_no++,
                "created_date" => $record->created_date,
                "project_name" => $record->project_name,
                "otp" => $record->otp,
                "ip" => $record->ip,
                "number" => $record->number,
                "created_date" => $record->created_date,
                "message" => $record->message,
               
                "status" => ($record->status == 1) ? 'sent' : 'failed',
                "response" => $record->response,
                
                "actions" => '<a href="' . base_url() . 'otp/view?id=' . $record->id . '">View </a> 
                ',
            );

            // var_dump($data);
            // die;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
        );

        return $response;
    }

}
