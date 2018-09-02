<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

	// LOAD CONSTRUCTOR FUNCTION TO INITIALIZE CLASS OBJECTS //
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Notification_model', 'ntm');

	}
	// ADD AND VIEW NOTIFICATION TEMPLATES //
	public function templates()
	{
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}
		$data['templates'] = $this->ntm->get_all_notification_templates();

	 	$this->load->view('super/includes/header');
	 	$this->load->view('super/includes/sidebar');
	 	$this->load->view('super/includes/top_header');
	 	$this->load->view('super/addNotificationTemplates',$data);
	 	$this->load->view('super/includes/footer');
	}
	// ADD TEMPLATES //
	public function addTemplates()
	{
		$data = $this->input->post();
		if(!empty($data)){
			$templateArray = array(
				'tmpl_name' => $this->input->post('template_name'),
				'tmpl_descriptions' => $this->input->post('template_description'),
				'tmpl_status' => '0',
				'tmpl_created' => date('Y-m-d H:i:s'),
			);

			$result = $this->ntm->save_notification_template($templateArray);
			if($result){
				$this->session->set_flashdata('message','<span class="text-success pull-right" style="font-weight:bold">Templates created successfully.</span>');
				redirect('super/notifications/templates');
			}else{
				$this->session->set_flashdata('message','<span class="text-danger pull-right" style="font-weight:bold">Templates created Failed.</span>');
				redirect('super/notifications/templates');
			}
		}else{
			$this->session->set_flashdata('message','<span class="text-danger pull-right" style="font-weight:bold">Blank Fields.</span>');
			redirect('super/notifications/templates');
		}
	}
	// DELETE TEMPLATES //
	public function deleteTemplate()
	{
		$tmpl_id = $this->uri->segment(4);
		$result = $this->ntm->delete_template($tmpl_id);
		if($result){
			$this->session->set_flashdata('message','<span class="text-success pull-right" style="font-weight:bold">Templates Deleted successfully.</span>');
			redirect('super/notifications/templates');
		}else{
			$this->session->set_flashdata('message','<span class="text-danger pull-right" style="font-weight:bold">Templates deletion Failed.</span>');
			redirect('super/notifications/templates');
		}

	}
	// LOAD ALL NOTIFICATION  LIST HERE //
	public function index()
	{
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}

	 	$this->load->view('super/includes/header');
	 	$this->load->view('super/includes/sidebar');
	 	$this->load->view('super/includes/top_header');
	 	$this->load->view('super/notificationLog');
	 	$this->load->view('super/includes/footer');
	}

	// LOAD SEND NOTIFICATION PAGE TO PREPARE SEND NOTIFICATION //
	public function send()
	{
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}
		$session_id = $reference['cms_id'];
		$data['schools'] = $this->ntm->get_all_school_list();
		$data['roles'] = $this->ntm->get_all_users_list();
		$data['templates'] = $this->ntm->get_all_templates();

		$this->load->view('super/includes/header');
		$this->load->view('super/includes/sidebar');
		$this->load->view('super/includes/top_header');
		$this->load->view('super/sendNotification', $data);
		$this->load->view('super/includes/footer');
	}
	// GET TEMPLATE CONTENT BY SELECTION //
	public function get_templates_content()
	{
		$template_id = $this->input->post('tmplate_id');
		$result['description'] = $this->ntm->get_template_content_by_id($template_id);
		echo json_encode($result);
	}
	// GET NO OF RECIPIENTS //
	public function get_recipient()
	{
		$roleid = $this->input->post('role_id');
		$result = $this->ntm->get_recipient_by_role_id($roleid);

		$matches = [];
		$string = $result->cms_ref_id;

		if (preg_match("/[a-zA-Z]{3}/", $string, $matches)) 
		{

			if($matches[0]=="THR"){
				$teacher_role = $result->cms_role;
				$record['recipients'] = $this->ntm->get_teachers_by_role($teacher_role);
				//print_r($record);die;
				echo json_encode($record);
			}else if($matches[0]=="PRT"){
				$parents_role = $result->cms_role;
				$record['recipients'] = $this->ntm->get_parents_by_role($parents_role);
				echo json_encode($record);
			}else if($matches[0]=="STU"){
				$students_id = $result->cms_role;
				//$record['recipients'] = $this->ntm->get_students_by_role($students_id);
				$record['recipients'] = $this->ntm->get_all_class_list();
				echo json_encode($record);
			}else{
				$record['recipients'] = "0";
				echo json_encode($record);
			}
			
		}
		
	}
	// GET SECTION LIST BY CLASS ID //
	public function get_sections_content()
	{
		$cls_id = $this->input->post('cls_id');
		$record['sections'] = $this->ntm->get_all_sections_list_by_id($cls_id);
		echo json_encode($record);
	}
	// GET STUDENTS LIST BY SECTION ID //
	public function get_students_list()
	{
		$sect_id = $this->input->post('sect_id');
		$record['students'] = $this->ntm->get_all_students_by_section_by($sect_id);
		echo json_encode($record);
	}
	// CREATE PRIVATE NOTIFICATION //

	public function send_notifications()
	{
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}
		$sender_id = $reference['cms_id'];
		$sender_ref_id = $reference['cms_ref_id'];

		$data = $this->input->post();

		echo "<pre>";
		print_r($data);die;

		if(!empty($data)){

			$receipent_id = $this->input->post('recipient_id');
			$receiverArray = array();
			for ($i=0; $i < count($receipent_id); $i++) {

	        $receiverArray[] = array(
						'ntfn_sender_id' => $sender_id,
						'ntfn_sender_ref_id' => $sender_ref_id,
						'schl_id' => $this->input->post('school_name_id'),
						'roles_id' => $this->input->post('role_id'),
						'ntfn_receiver_id' => $receipent_id[$i],
						'ntfn_notification_type' => $this->input->post('notification_type'),
						'ntfn_notification_message' => $this->input->post('notification_content'),
						'ntfn_status' => '0',
						'ntfn_created' => date('Y-m-d H:i:s')
	        );
	    }
			// echo "<pre>";
			// print_r($receiverArray);die;

			$result = $this->ntm->save_notification_message($receiverArray);
			if($result){
				$this->session->set_flashdata('message','<span class="text-success pull-right" style="font-weight:bold">Notification Send successfully.</span>');
				redirect('super/notifications/send');
			}else{
				$this->session->set_flashdata('message','<span class="text-danger pull-right" style="font-weight:bold">Notification Send Failed.</span>');
				redirect('super/notifications/send');
			}
		}else{
			$this->session->set_flashdata('message','<span class="text-danger pull-right" style="font-weight:bold">Blank Fields.</span>');
			redirect('super/notifications/send');
		}
	}

	// GET NOTIFICATION DETAIL //
	public function detail()
	{
		$reference = $this->session->userdata('logged_in');
		if(empty($reference)){
			redirect('','refresh');
		}

		$this->load->view('super/includes/header');
		$this->load->view('super/includes/sidebar');
		$this->load->view('super/includes/top_header');
		$this->load->view('super/notificationDetails');
		$this->load->view('super/includes/footer');
	}
}
