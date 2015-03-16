<?php
class leave_model extends CI_Model {
	//loading database on class creationorderMainAddress
	public function __construct() {
			$this->load->database();
	}
	// Test Function
	public function getTest() {
		$query = $this->db->query("SELECT * FROM `test`.`staff` ");
		return $query->result_array();
	}

    //Get Leave types table
    public function get_leave_types(){
        try{
            $query = $this->db->query("SELECT * FROM `leave_types`");
            return $query->result();
        } catch(Exception $ex) {
            return FALSE;
        }
    }

    //Get a list of applied leaves according to the teacher id
    //Teacher id is not passed yet ;)
    public function get_applied_leaves_list(){
        try{
            $query = $this->db->query("SELECT lt.name,al.applied_date,al.start_date,al.end_date,al.no_of_days,ls.status FROM apply_leaves al,leave_types lt,leave_status ls where (al.id = lt.id) AND al.leave_status = ls.id");
//            $query = $this->db->query("SELECT lt.name,al.applied_date,al.start_date,al.end_date,al.no_of_days,al.leave_status FROM apply_leaves al,leave_types lt where (al.id = lt.id)");
            return $query->result();
        } catch(Exception $ex) {
            return FALSE;
        }
    }
	//Get max leave count according to the name
	public function get_max_leave_count($name){
		try {
			$query = $this->db->query("SELECT max_leave_count FROM `leave_types` WHERE name='$name'");
            $row = $query->row();
            return $row->max_leave_count;
			
		} catch (Exception $ex) {
			return FALSE;
		}
	}

    //Get No of leaves applied by a person
    public function get_no_leaves($leave_type, $tid){
        try {
            $query = $this->db->query("SELECT sum(no_of_days) as days FROM `apply_leaves` WHERE teacher_id = '$tid' AND leave_type_id = '$leave_type'");
            $row = $query->row();
            return $row->days;
            
        } catch (Exception $e) {
            return FALSE;
        }
    }

	//Apply for leave
	public function apply_for_leave($user_id, $teacher_id, $leave_type_id, $is_half_day, $applied_date, $start_date, $end_date, $reason, $remarks, $no_of_days){
		try {
    		if($this->db->query("INSERT INTO `dcsms`.`apply_leaves` ('user_id', 'teacher_id', 'leave_type_id', 'is_half_day','applied_date','start_date',
    			'end_date','reason','remarks','no_of_days')
    			VALUES ('$user_id', '$teacher_id', '$leave_type_id', '$is_half_day','$applied_date', '$start_date', '$end_date', '$reason', '$remarks','$no_of_days');")) {
    			return TRUE;
    		} else {
    			return FALSE;
    		}
    	} catch(Exception $ex) {
    		return FALSE;
    	}
	}
    //Sample Data extraction
    public function get_data(){
        try {
            $query = $this->db->query("SELECT * FROM `staff`");
            return $query->result_array();
        } catch(Exception $ex) {
            return FALSE;
        }
    }
}
?>