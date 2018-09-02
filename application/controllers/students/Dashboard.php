<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	// INITIALIZE CONSTRUCTOR FUNCTION //
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Students_activities_model','sam');
	}
	public function index()
	{   
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}
		$stu_id = $reference['cms_id'];
		$stu_role = $reference['cms_role'];

		$data['profile'] = $this->sam->get_students_profile_record($stu_id,$stu_role);
		$data['menus'] = $this->sam->get_menu_role_permissions($stu_role);
		//print_r($data);die;
		$class_section = $this->sam->get_student_class_section_by_master_id($stu_id);

		// $notifiArray = array(
		// 	'' => $class_section->stud_id,
		// 	'' => $class_section->stud_id,
		// 	'' => $class_section->stud_id,
		// 	'' => $class_section->stud_id,
		// );
		$stdid  = $class_section->stud_id;
		$roleid = $class_section->roles_id;
		$clsid  = $class_section->cls_id;
		$sectid = $class_section->sect_id;

		$data['notify'] = $this->sam->get_student_unread_notification_list($stdid,$roleid,$clsid,$sectid);

		echo "<pre>";
		print_r($data);die;

		$this->load->view('students/includes/header',$data);
		$this->load->view('students/includes/sidebar');
		$this->load->view('students/includes/top_header');
		$this->load->view('students/index');
		$this->load->view('students/includes/footer');
	}
	// GET LOGIN STUDENT DETAIL WITH CLASS AND SECTION //
	//public function 
	// VIEW ALL UNREAD NOTIFICATION //
	public function new_unread_notification()
	{
	    $reference = $this->session->userdata('logged_in');
			if(empty($reference)){
				redirect('','refresh');
			}
	    // $login_id = $reference['cms_id'];
	    // print_r($login_id);
	    $unread = $this->input->post('unread');
	    $result['message'] = $this->sam->get_all_unread_notification($unread);
	    echo json_encode($result);
	}
}
