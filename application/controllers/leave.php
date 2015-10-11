<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class leave extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Leave_Model');
        $this->load->model('Year_Model');
        $this->load->model('Teacher_Model');
        $this->load->model('News_Model');
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login', 'refresh');
        }

        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Management";
        $data['first_name'] = $this->session->userdata('first_name');
        $userid = $this->session->userdata['id'];

        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_leave_types();

        //Getting Values from Leaves DB
        $data['casual_leaves'] = $this->Leave_Model->get_max_leave_count("Casual");
        $data['medical_leaves'] = $this->Leave_Model->get_max_leave_count("Medical");
        $data['duty_leaves'] = $this->Leave_Model->get_max_leave_count("Duty");
        $data['other_leaves'] = $this->Leave_Model->get_max_leave_count("Other");
        $data['maternity_leaves'] = $this->Leave_Model->get_max_leave_count("Maternity");

        //Getting List of Applied Leaves
        $data['applied_leaves'] = $this->Leave_Model->get_applied_leaves_list($userid);

        //Get Separate leaves count according to the type
        $data['applied_casual_leaves'] = $this->Leave_Model->get_no_leaves('1', $userid);
        $data['applied_medical_leaves'] = $this->Leave_Model->get_no_leaves('2', $userid);
        $data['applied_duty_leaves'] = $this->Leave_Model->get_no_leaves('3', $userid);
        $data['applied_other_leaves'] = $this->Leave_Model->get_no_leaves('4', $userid);
        $data['applied_maternity_leaves'] = $this->Leave_Model->get_no_leaves('5', $userid);

        //total leaves
        $data['total_leaves'] = $data['applied_casual_leaves'] + $data['applied_medical_leaves'] + $data['applied_duty_leaves'] + $data['applied_other_leaves'] + $data['applied_maternity_leaves'];

        //Getting user type
        $data['user_type'] = $this->session->userdata['user_type'];

        //For Admin Views
        if ($data['user_type'] == 'A') {
            //Get Pending Leaves List
            $data['admin_pending_list'] = $this->Leave_Model->get_list_of_pending_leaves();
            $data['admin_pending_short_list'] = $this->Leave_Model->get_list_of_pending_short_leaves();

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/admin_leave', $data);
            $this->load->view('/templates/footer');
        } elseif ($data['user_type'] == 'T') {


            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');
        } else {
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Main function to apply leaves
    public function apply_leave() {
        $data['navbar'] = "leave";

        //Basic data to be loaded
        $data['user_type'] = $this->session->userdata['user_type'];
        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_leave_types();
        date_default_timezone_set('Asia/Kolkata');
        $userid = $this->session->userdata['id'];

        //Getting Values from Leaves DB
        $data['casual_leaves'] = $this->Leave_Model->get_max_leave_count("Casual");
        $data['medical_leaves'] = $this->Leave_Model->get_max_leave_count("Medical");
        $data['duty_leaves'] = $this->Leave_Model->get_max_leave_count("Duty");
        $data['other_leaves'] = $this->Leave_Model->get_max_leave_count("Other");
        $data['maternity_leaves'] = $this->Leave_Model->get_max_leave_count("Maternity");

        //Getting List of Applied Leaves
        $data['applied_leaves'] = $this->Leave_Model->get_applied_leaves_list($this->session->userdata['id']);

        //Get Separate leaves count according to the type
        $data['applied_casual_leaves'] = $this->Leave_Model->get_no_leaves('1', $userid);
        $data['applied_medical_leaves'] = $this->Leave_Model->get_no_leaves('2', $userid);
        $data['applied_duty_leaves'] = $this->Leave_Model->get_no_leaves('3', $userid);
        $data['applied_other_leaves'] = $this->Leave_Model->get_no_leaves('4', $userid);
        $data['applied_maternity_leaves'] = $this->Leave_Model->get_no_leaves('5', $userid);

        //total leaves
        $data['total_leaves'] = $data['applied_casual_leaves'] + $data['applied_medical_leaves'] + $data['applied_duty_leaves'] + $data['applied_other_leaves'] + $data['applied_maternity_leaves'];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_reason', 'Reason', "required|xss_clean");
        $this->form_validation->set_rules('txt_startdate', 'Start Date', "required|xss_clean|callback_check_date_for_current_year");
        $this->form_validation->set_rules('txt_enddate', 'End Date', "required|xss_clean|callback_check_date_for_current_year");

        $data['page_title'] = "Leave Management";

        if ($this->form_validation->run() == FALSE) {

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');
        } else {
            //Get Post Data
            $leavetype = $this->input->post('cmb_leavetype');
            $startdate = $this->input->post('txt_startdate');
            $enddate = $this->input->post('txt_enddate');
            $reason = $this->input->post('txt_reason');
            $applieddate = date("Y-m-d");
            $teacherid = $this->Leave_Model->get_teacher_id($userid);

            $noofdates = date_diff(date_create($startdate), date_create($enddate));
            $sdate = $noofdates->format("%a");

            $dateold = date_diff(date_create($applieddate), date_create($startdate));
            $dateoldc = $dateold->format("%R%a");

            //Get info from the Academic Year
            $academic_year = $this->Year_Model->get_academic_year_details();
            foreach ($academic_year as $row) {
                $year_structure = $row->structure;

                //Building the Array from the Database
                $string = $year_structure;
                $partial = explode(', ', $string);
                $final = array();
                array_walk($partial, function($val, $key) use(&$final) {
                    list($key, $value) = explode('=', $val);
                    $final[$key] = $value;
                });

                //Array customized with Year Planner
                $dataset = array();

                $enddate_var = $enddate;
                $enddate_var = date('Y-m-d', strtotime('-1 day', strtotime($enddate_var)));
                $days = date_diff(date_create($startdate), date_create($enddate_var));
                //No of days in between Term 1 start and end 
                $t1days = $days->format("%a");
                $newdate = $startdate;

                //Iterating days of Start date to end date
                for ($i = 0; $i <= $t1days; $i++) {
                    //Iterating Year Structure
                    foreach ($final as $key => $value) {
                        if ($key == $newdate) {
                            $dataset[$newdate] = $value;
                        }
                    }
                    $newdate = strtotime($newdate);
                    $newdate = strtotime("+1 day", $newdate);
                    $newdate = date('Y-m-d', $newdate);
                }
            }

            //No of days for Medical and Casual
            $no_of_days_mc = 0;

            //Checking Leave type for Medical and Casual
            if ($leavetype == 1 || $leavetype == 2 || $leavetype == 3 || $leavetype == 4) {
                foreach ($dataset as $key => $value) {
                    if ($value == 0 || $value == 5) {
                        $no_of_days_mc++;
                    }
                }
            } else {
                $noofdates = date_diff(date_create($startdate), date_create($enddate_var));
                $sdate = $noofdates->format("%a");
                $no_of_days_mc = $sdate;
            }

            //validation for dates
            if ($sdate == '0') {
                $data['error_message'] = "Start date cannot be the End date of the leaves";
            } elseif ($dateoldc < 0) {
                $data['error_message'] = "Start Date cannot be a previous date";
            } elseif ($enddate < $startdate) {
                $data['error_message'] = "End Date cannot be a previous date";
            }
            //Commented because No need of validations
            // //bit buggy here
            // elseif($leavetype =='1' && $data['casual_leaves'] == $data['applied_casual_leaves']){
            //     $data['error_message'] = "No Casual leaves left to apply";
            // } elseif($leavetype =='2' && $data['medical_leaves'] == $data['applied_medical_leaves']){
            //     $data['error_message'] = "No Medical leaves left to apply";
            // }
            // //Need to apply some more logic here when it comes to maternity leaves. But not right now
            // elseif($leavetype =='5' && $data['maternity_leaves'] >= $data['applied_maternity_leaves']){
            //     $data['error_message'] = "No Maternity leaves left to apply";
            // }
            else {


                if ($this->Leave_Model->apply_for_leave($userid, $teacherid, $leavetype, $applieddate, $startdate, $enddate, $reason, $no_of_days_mc) == TRUE) {
                    $data['succ_message'] = "Leave Applied Successfully for " . $no_of_days_mc . " days";


                    //loading values again
                    //Getting Values from Leaves DB
                    $data['casual_leaves'] = $this->Leave_Model->get_max_leave_count("Casual");
                    $data['medical_leaves'] = $this->Leave_Model->get_max_leave_count("Medical");
                    $data['duty_leaves'] = $this->Leave_Model->get_max_leave_count("Duty");
                    $data['other_leaves'] = $this->Leave_Model->get_max_leave_count("Other");
                    $data['maternity_leaves'] = $this->Leave_Model->get_max_leave_count("Maternity");

                    //Getting List of Applied Leaves
                    $data['applied_leaves'] = $this->Leave_Model->get_applied_leaves_list($this->session->userdata['id']);

                    //Get Separate leaves count according to the type
                    $data['applied_casual_leaves'] = $this->Leave_Model->get_no_leaves('1', $userid);
                    $data['applied_medical_leaves'] = $this->Leave_Model->get_no_leaves('2', $userid);
                    $data['applied_duty_leaves'] = $this->Leave_Model->get_no_leaves('3', $userid);
                    $data['applied_other_leaves'] = $this->Leave_Model->get_no_leaves('4', $userid);
                    $data['applied_maternity_leaves'] = $this->Leave_Model->get_no_leaves('5', $userid);

                    //total leaves
                    $data['total_leaves'] = $data['applied_casual_leaves'] + $data['applied_medical_leaves'] + $data['applied_duty_leaves'] + $data['applied_other_leaves'] + $data['applied_maternity_leaves'];
                } else {
                    $data['error_message'] = "Failed to save data to the Database";
                }
            }


            //For news field
            $tech_id = $this->session->userdata('id');
            $tech_details = $this->Teacher_Model->user_details($tech_id);
            $this->News_Model->insert_action_details($tech_id, "Apply a leave", $tech_details->photo_file_name, $tech_details->full_name);
            //////
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Get One Leave details
    public function get_leave_details($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Leave Details
        $data['leave_details'] = $this->Leave_Model->get_leave_details($id);

        //Passing it to the View
        $this->load->view('templates/header', $data);
        $this->load->view('navbar_main', $data);
        $this->load->view('navbar_sub', $data);
        $this->load->view('/leave/view_leave', $data);
        $this->load->view('/templates/footer');
    }

    //Approve Leave
    public function approve_leave($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->approve_leave($id);

        $data['user_type'] = $this->session->userdata['user_type'];

        if ($data['leave_approve_status'] == TRUE) {

            $data['succ_message'] = "Successfully Approved the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);
            //For news field
            $tech_id = $this->session->userdata('id');
            $tech_details = $this->Teacher_Model->user_details($tech_id);
            $this->News_Model->insert_action_details($tech_id, "Approve leave", $tech_details->photo_file_name, $tech_details->full_name);
            //////
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        } else {
            $data['error_message'] = "Failed to Approved the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Approve Short Leave
    public function approve_short_leave($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->approve_short_leave($id);

        $data['user_type'] = $this->session->userdata['user_type'];

        if ($data['leave_approve_status'] == TRUE) {

            $data['succ_message'] = "Successfully Approved the Short Leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_short_leave_details($id);

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_short_leave', $data);
            $this->load->view('/templates/footer');
        } else {
            $data['error_message'] = "Failed to Approved the Short Leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_short_leave_details($id);
            //For news field
            $tech_id = $this->session->userdata('id');
            $tech_details = $this->Teacher_Model->user_details($tech_id);
            $this->News_Model->insert_action_details($tech_id, "Approve short leave", $tech_details->photo_file_name, $tech_details->full_name);
            //////
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_short_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Rejected Leave
    public function reject_leave($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->reject_leave($id);

        if ($data['leave_approve_status'] == TRUE) {

            $data['succ_message'] = "Successfully Rejected the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);
            
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        } else {
            $data['error_message'] = "Failed to Reject the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);
            //For news field
            $tech_id = $this->session->userdata('id');
            $tech_details = $this->Teacher_Model->user_details($tech_id);
            $this->News_Model->insert_action_details($tech_id, "Reject leave", $tech_details->photo_file_name, $tech_details->full_name);
            //////
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Rejected Leave
    public function reject_short_leave($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->reject_short_leave($id);

        if ($data['leave_approve_status'] == TRUE) {

            $data['succ_message'] = "Successfully Rejected the Short Leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_short_leave_details($id);
            //For news field
            $tech_id = $this->session->userdata('id');
            $tech_details = $this->Teacher_Model->user_details($tech_id);
            $this->News_Model->insert_action_details($tech_id, "Reject short leave", $tech_details->photo_file_name, $tech_details->full_name);
            //////
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_short_leave', $data);
            $this->load->view('/templates/footer');
        } else {
            $data['error_message'] = "Failed to Reject the Short Leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_short_leave_details($id);

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_short_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //View All Leaves
    public function get_all_leaves() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "All Leaves";

        $data['user_type'] = $this->session->userdata['user_type'];

        $data['teachers'] = $this->Leave_Model->get_teachers();

        $data['all_leaves'] = $this->Leave_Model->get_all_leaves();

        //Passing it to the View
        $this->load->view('templates/header', $data);
        $this->load->view('navbar_main', $data);
        $this->load->view('navbar_sub', $data);
        $this->load->view('/leave/all_leaves', $data);
        $this->load->view('/templates/footer');
    }

    //View Leaves Report
    public function leaves_report() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "Leaves Report";

        $data['user_type'] = $this->session->userdata['user_type'];

        //Values
        $startdate = $this->input->post('txt_startdate');
        $enddate = $this->input->post('txt_enddate');
        $userid = $this->input->post('cmb_status');

        $data['teachers'] = $this->Leave_Model->get_teachers();

        if (empty($startdate) || empty($enddate) || $userid == 0) {

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leaves_report', $data);
            $this->load->view('/templates/footer');
        } else {

            //Get all leaves in a period
            $data['applied_leaves'] = $this->Leave_Model->get_leaves_for_report($userid, $startdate, $enddate);

            $data['teacher_details'] = $this->Leave_Model->get_teacher_by_id($userid);

            if (empty($data['applied_leaves'])) {
                $var = TRUE;
            } else {
                //Setting Values
                $data['report_results'] = "Not Empty";
            }

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leaves_report', $data);
            $this->load->view('/templates/footer');
        }
    }

    //View Leaves Report
    public function all_teacher_leave() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "Apply Teacher Leave";

        $data['user_type'] = $this->session->userdata['user_type'];

        //Values
        $startdate = $this->input->post('txt_startdate');
        $enddate = $this->input->post('txt_enddate');
        $userid = $this->input->post('cmb_status');

        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_leave_types();
        //Load teachers
        $data['teachers'] = $this->Leave_Model->get_teachers();

        if (empty($startdate) || empty($enddate) || $userid == 0) {

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/apply_teacher_leave', $data);
            $this->load->view('/templates/footer');
        } else {

            //Get all leaves in a period
            $data['applied_leaves'] = $this->Leave_Model->get_leaves_for_report($userid, $startdate, $enddate);

            $data['teacher_details'] = $this->Leave_Model->get_teacher_by_id($userid);

            if (empty($data['applied_leaves'])) {
                $var = TRUE;
            } else {
                //Setting Values
                $data['report_results'] = "Not Empty";
            }

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/apply_teacher_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Apply any teacher leave function
    public function apply_teacher_leave() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "Apply Teacher Leave";

        $data['user_type'] = $this->session->userdata['user_type'];

        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_leave_types();
        //Load teachers
        $data['teachers'] = $this->Leave_Model->get_teachers();


        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_reason', 'Reason', "required|xss_clean");
        $this->form_validation->set_rules('txt_startdate', 'Start Date', "required|xss_clean|callback_check_date_for_current_year");
        $this->form_validation->set_rules('txt_enddate', 'End Date', "required|xss_clean|callback_check_date_for_current_year");


        if ($this->form_validation->run() == FALSE) {
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/apply_teacher_leave', $data);
            $this->load->view('/templates/footer');
        } else {
            //Values
            $startdate = $this->input->post('txt_startdate');
            $enddate = $this->input->post('txt_enddate');
            $reason = $this->input->post('txt_reason');
            $leavetype = $this->input->post('cmb_leavetype');

            //Get teacher id
            $teacherid = $this->input->post('cmb_teacher');

            //Other essential data
            $applieddate = date("Y-m-d");
            $noofdates = date_diff(date_create($startdate), date_create($enddate));
            $sdate = $noofdates->format("%a");

            $dateold = date_diff(date_create($applieddate), date_create($startdate));
            $dateoldc = $dateold->format("%R%a");

            //checkin for combo boxes
            if ($teacherid == 0) {
                //Error Message
                $data['error_message'] = "Please Select a teacher";
            } elseif ($leavetype == 0) {
                //Error Message
                $data['error_message'] = "Please select a leave type";
            } //validation for dates
            elseif ($sdate == '0') {
                $data['error_message'] = "Start date cannot be the End date of the leaves";
            } elseif ($dateoldc < 0) {
                $data['error_message'] = "Start Date cannot be a previous date";
            } elseif ($enddate < $startdate) {
                $data['error_message'] = "End Date cannot be a previous date";
            } else {
                //get user id
                $userid = $this->Leave_Model->get_user_id($teacherid);

                //Get info from the Academic Year
                $academic_year = $this->Year_Model->get_academic_year_details();
                foreach ($academic_year as $row) {
                    $year_structure = $row->structure;

                    //Building the Array from the Database
                    $string = $year_structure;
                    $partial = explode(', ', $string);
                    $final = array();
                    array_walk($partial, function($val, $key) use(&$final) {
                        list($key, $value) = explode('=', $val);
                        $final[$key] = $value;
                    });

                    //Array customized with Year Planner
                    $dataset = array();

                    $enddate_var = $enddate;
                    $enddate_var = date('Y-m-d', strtotime('-1 day', strtotime($enddate_var)));
                    $days = date_diff(date_create($startdate), date_create($enddate_var));
                    //No of days in between Term 1 start and end 
                    $t1days = $days->format("%a");
                    $newdate = $startdate;

                    //Iterating days of Start date to end date
                    for ($i = 0; $i <= $t1days; $i++) {
                        //Iterating Year Structure
                        foreach ($final as $key => $value) {
                            if ($key == $newdate) {
                                $dataset[$newdate] = $value;
                            }
                        }
                        $newdate = strtotime($newdate);
                        $newdate = strtotime("+1 day", $newdate);
                        $newdate = date('Y-m-d', $newdate);
                    }
                }

                //No of days for Medical and Casual
                $no_of_days_mc = 0;

                //Checking Leave type for Medical and Casual
                if ($leavetype == 1 || $leavetype == 2 || $leavetype == 3 || $leavetype == 4) {
                    foreach ($dataset as $key => $value) {
                        if ($value == 0 || $value == 5) {
                            $no_of_days_mc++;
                        }
                    }
                } else {
                    $noofdates = date_diff(date_create($startdate), date_create($enddate_var));
                    $sdate = $noofdates->format("%a");
                    $no_of_days_mc = $sdate;
                }

                if ($this->Leave_Model->apply_for_leave($userid, $teacherid, $leavetype, $applieddate, $startdate, $enddate, $reason, $no_of_days_mc) == TRUE) {
                    $data['succ_message'] = "Leave Applied Successfully for " . $no_of_days_mc . " days";
                } else {
                    $data['error_message'] = "Failed to save data to the Database";
                }
            }

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/apply_teacher_leave', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Short leave function
    public function short_leave() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "Apply Teacher Leave";

        $data['user_type'] = $this->session->userdata['user_type'];
        $userid = $this->session->userdata['id'];
        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_short_leave_types();
        //Getting List of Applied Leaves
        $data['applied_leaves'] = $this->Leave_Model->get_applied_short_leaves_list($userid);
        $data['recent_applied_leaves'] = $this->Leave_Model->get_recent_applied_short_leaves_list($userid);

        //get current applied short leaves this month
        $data['short_leave_count'] = $this->Leave_Model->get_applied_short_leaves_count($userid);

        //Passing it to the View
        $this->load->view('templates/header', $data);
        $this->load->view('navbar_main', $data);
        $this->load->view('navbar_sub', $data);
        $this->load->view('/leave/short_leaves', $data);
        $this->load->view('/templates/footer');
    }

    //Apply Short leave function
    public function apply_short_leave() {
        $data['navbar'] = "leave";

        //other
        $data['page_title'] = "Apply Teacher Leave";

        $data['user_type'] = $this->session->userdata['user_type'];
        $userid = $this->session->userdata['id'];
        //Load form combo
        $data['leave_types'] = $this->Leave_Model->get_short_leave_types();
        //Getting List of Applied Leaves
        $data['applied_leaves'] = $this->Leave_Model->get_applied_short_leaves_list($userid);
        $data['recent_applied_leaves'] = $this->Leave_Model->get_recent_applied_short_leaves_list($userid);

        //get current applied short leaves this month
        $data['short_leave_count'] = $this->Leave_Model->get_applied_short_leaves_count($userid);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_reason', 'Reason', "required|xss_clean");
        $this->form_validation->set_rules('txt_date', 'Date', "required|xss_clean|callback_check_date_validations");
        $this->form_validation->set_rules('cmb_leavetype', 'Leave Type', "required|xss_clean|callback_check_combo_box");

        if ($this->form_validation->run() == FALSE) {
            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/short_leaves', $data);
            $this->load->view('/templates/footer');
        } else {
            //Values for DB
            $applieddate = date("Y-m-d");
            $date = $this->input->post('txt_date');
            $leavetype = $this->input->post('cmb_leavetype');
            $reason = $this->input->post('txt_reason');
            //Getting teacher id and user id
            $userid = $this->session->userdata['id'];
            $teacherid = $this->Leave_Model->get_teacher_id($userid);

            if ($leavetype == 1 && $data['short_leave_count'] >= 2) {
                //Apply a regular short leave
                $this->Leave_Model->apply_for_short_leave($userid, $teacherid, $leavetype, $applieddate, $date, $reason);
                //Apply a half day for the extra
                $reason = $reason . " | Half Day for extra short leaves";
                $this->Leave_Model->apply_for_halfday($userid, $teacherid, $applieddate, $date, $reason);

                //re call the data
                //Load form combo
                $data['leave_types'] = $this->Leave_Model->get_short_leave_types();
                //Getting List of Applied Leaves
                $data['applied_leaves'] = $this->Leave_Model->get_applied_short_leaves_list($userid);
                $data['recent_applied_leaves'] = $this->Leave_Model->get_recent_applied_short_leaves_list($userid);

                //get current applied short leaves this month
                $data['short_leave_count'] = $this->Leave_Model->get_applied_short_leaves_count($userid);

                $data['succ_message'] = "Short Leave Applied Successfully. It will mark as a Half day";
            } else {
                if ($leavetype == 2) {
                    $reason = $reason . " | Half Day";
                    if ($this->Leave_Model->apply_for_halfday($userid, $teacherid, $applieddate, $date, $reason) == TRUE) {
                        $data['succ_message'] = "Half Day Applied Successfully";

                        //Getting List of Applied Leaves
                        $data['applied_leaves'] = $this->Leave_Model->get_applied_short_leaves_list($userid);
                        $data['recent_applied_leaves'] = $this->Leave_Model->get_recent_applied_short_leaves_list($userid);
                    } else {
                        $data['error_message'] = "Failed to save data to the Database";
                    }
                } else {
                    if ($this->Leave_Model->apply_for_short_leave($userid, $teacherid, $leavetype, $applieddate, $date, $reason) == TRUE) {
                        $data['succ_message'] = "Short Leave Applied Successfully";

                        //Getting List of Applied Leaves
                        $data['applied_leaves'] = $this->Leave_Model->get_applied_short_leaves_list($userid);
                        $data['recent_applied_leaves'] = $this->Leave_Model->get_recent_applied_short_leaves_list($userid);
                    } else {
                        $data['error_message'] = "Failed to save data to the Database";
                    }
                }
            }

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/short_leaves', $data);
            $this->load->view('/templates/footer');
        }
    }

    //Get One Short Leave details
    public function get_short_leave_details($id) {
        $data['navbar'] = "leave";

        $data['page_title'] = "Short Leave Details";
        $data['id'] = $id;

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Leave Details
        $data['leave_details'] = $this->Leave_Model->get_short_leave_details($id);

        //Passing it to the View
        $this->load->view('templates/header', $data);
        $this->load->view('navbar_main', $data);
        $this->load->view('navbar_sub', $data);
        $this->load->view('/leave/view_short_leave', $data);
        $this->load->view('/templates/footer');
    }

    // Call back Validations
    //check date for current year
    function check_date_for_current_year($date) {
        $current_year = date('Y');
        $date = date_create($date);
        $year = $date->format("Y");
        if ($current_year != $year) {
            $this->form_validation->set_message('check_date_for_current_year', 'Select a Date from Current Year');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //Checking combo box on short leaves
    function check_combo_box($value) {
        if ($value == 0) {
            $this->form_validation->set_message('check_combo_box', 'Select a Leave Type');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //Date Validation Call back Function
    function check_date_validations($date) {
        //Other essential data
        $applieddate = date("Y-m-d");
        $dateold = date_diff(date_create($applieddate), date_create($date));
        $dateoldc = $dateold->format("%R%a");

        //Getting Year Plan Data
        //Get info from the Academic Year
        //Set conditon bool
        $aca_year_stat = FALSE;
        $academic_year = $this->Year_Model->get_academic_year_details();
        foreach ($academic_year as $row) {
            $year_structure = $row->structure;

            //Building the Array from the Database
            $string = $year_structure;
            $partial = explode(', ', $string);
            $final = array();
            array_walk($partial, function($val, $key) use(&$final) {
                list($key, $value) = explode('=', $val);
                $final[$key] = $value;
            });
        }

        if (isset($final[$date])) {
            if ($final[$date] == '0' || $final[$date] == '5') {
                $aca_year_stat = TRUE;
            }
        }

        if ($dateoldc < 0) {
            $this->form_validation->set_message('check_date_validations', 'Date cannot be a previous date');
            return FALSE;
        } elseif ($aca_year_stat == FALSE) {
            $this->form_validation->set_message('check_date_validations', 'You cannot Apply Short Leaves on School Holidays');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

/* Coded by Udara Karunarathna @P0dda */
/* Location: www.udara.info */