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
        if($data['user_type'] == 'A'){
            //Get Pending Leaves List
            $data['admin_pending_list'] = $this->Leave_Model->get_list_of_pending_leaves();

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/admin_leave', $data);
            $this->load->view('/templates/footer');
        } elseif($data['user_type'] == 'T'){


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
        $this->form_validation->set_rules('txt_startdate', 'Start Date', "required|xss_clean");
        $this->form_validation->set_rules('txt_enddate', 'End Date', "required|xss_clean");

        $data['page_title'] = "Leave Management";

        if($this->form_validation->run() == FALSE){

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');

        } else{
            $leavetype = $this->input->post('cmb_leavetype');
            $startdate = $this->input->post('txt_startdate');
            $enddate = $this->input->post('txt_enddate');
            $reason = $this->input->post('txt_reason');
            $applieddate = date("Y-m-d");
            $teacherid = $this->Leave_Model-> get_teacher_id($userid);

            $noofdates=date_diff(date_create($startdate),date_create($enddate));
            $sdate = $noofdates->format("%a");

            $dateold = date_diff(date_create($applieddate),date_create($startdate));
            $dateoldc = $dateold->format("%R%a");

            //Get info from the Academic Year
            $academic_year = $this->Year_Model->get_curret_academic_year();
            foreach ($query->result() as $row)
            {
                $row->structure;
            }


            //validation for dates
            if($sdate == '0'){
                $data['error_message'] = "Start date cannot be the End date of the leaves";
            } elseif($dateoldc < 0) {
                $data['error_message'] = "Start Date cannot be a previous date";
            } elseif($enddate < $startdate){
                $data['error_message'] = "End Date cannot be a previous date";
            }
            //bit buggy here
            elseif($leavetype =='1' && $data['casual_leaves'] == $data['applied_casual_leaves']){
                $data['error_message'] = "No Casual leaves left to apply";
            } elseif($leavetype =='2' && $data['medical_leaves'] == $data['applied_medical_leaves']){
                $data['error_message'] = "No Medical leaves left to apply";
            }
            //Need to apply some more logic here when it comes to maternity leaves. But not right now
            elseif($leavetype =='5' && $data['maternity_leaves'] >= $data['applied_maternity_leaves']){
                $data['error_message'] = "No Maternity leaves left to apply";
            }
            else {

                $ss=TRUE;
                if($ss == TRUE)

                // if($this->Leave_Model->apply_for_leave($userid, $teacherid, $leavetype, $applieddate, $startdate, $enddate, $reason, $sdate) == TRUE)
                {
                    $data['succ_message'] = "Leave Applied Successfully for ". $noofdates->format("%a days");

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

                } else{
                    $data['error_message'] = "Failed to save data to the Database";
                }
            }



            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/leave', $data);
            $this->load->view('/templates/footer');
        }

    }

    //Get One Leave details
    public function get_leave_details($id){
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
    public  function  approve_leave($id){
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->approve_leave($id);

        $data['user_type'] = $this->session->userdata['user_type'];

        if($data['leave_approve_status'] == TRUE){

            $data['succ_message'] = "Successfully Approved the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        } else{
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

    //Rejected Leave
    public  function  reject_leave($id){
        $data['navbar'] = "leave";

        $data['page_title'] = "Leave Details";
        $data['id'] = $id;

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Approve Leave Status
        $data['leave_approve_status'] = $this->Leave_Model->reject_leave($id);

        if($data['leave_approve_status'] == TRUE){

            $data['succ_message'] = "Successfully Rejected the leave";


            //Get Leave Details
            $data['leave_details'] = $this->Leave_Model->get_leave_details($id);

            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/view_leave', $data);
            $this->load->view('/templates/footer');
        } else{
            $data['error_message'] = "Failed to Reject the leave";


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
    //View All Leaves
    public  function  get_all_leaves(){
        $data['navbar'] = "leave";

        //pagination
        $this->load->library('pagination');

        $config['base_url'] =  base_url()."index.php/leave/get_all_leaves";
        $config['per_page'] = 2;
        $config["uri_segment"] = 3;
        $config['total_rows'] = $this->db->get('apply_leaves')->num_rows();



        $this->pagination->initialize($config);

        $this->db->select('*');

        $qry = "SELECT al.id,t.full_name,lt.name,al.applied_date,al.start_date,al.end_date,al.reason,al.no_of_days,ls.status FROM apply_leaves al,leave_status ls,teachers t,leave_types lt WHERE al.leave_status = ls.id AND t.id = al.teacher_id AND lt.id = al.leave_type_id ORDER by al.applied_date desc";
        $limit = 3;
        $offset = ($this->uri->segment(3) != '' ? $this->uri->segment(3):0);

        $qry .= " limit {$limit} offset {$offset} ";

       $data['query'] = $this->db->query($qry);

        $data['pages'] = $this->pagination->create_links();


        //other
        $data['page_title'] = "Leave Details";

        $data['user_type'] = $this->session->userdata['user_type'];

        //Get Approve Leave Status
        $data['all_leaves'] = $this->Leave_Model->get_all_leaves(3);


            //Passing it to the View
            $this->load->view('templates/header', $data);
            $this->load->view('navbar_main', $data);
            $this->load->view('navbar_sub', $data);
            $this->load->view('/leave/all_leaves', $data);
            $this->load->view('/templates/footer');

    }

}

/* Coded by Udara Karunarathna @P0dda */
/* Location: www.udara.info */