<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // SAVE ALL NOTIFICATION TEMPLATES //
    public function save_notification_template($templateArray)
    {
    	$this->db->insert('cms_notifications_templates', $templateArray);
    	return $this->db->insert_id();
    }
    // GET ALL NOTIFICATION TEMPLATES //
    public function get_all_notification_templates()
    {
    	$this->db->select('*');
  		$this->db->from('cms_notifications_templates');
  		$this->db->where('tmpl_status','0');
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->result();
    }
    // DELETE TEMPLATES //
    public function delete_template($tmpl_id)
    {
    	$this->db->where('tmpl_id', $tmpl_id);
    	$this->db->delete('cms_notifications_templates');
    	return $tmpl_id;
    }

    // GET ALL SCHOOL LIST //
    public function get_all_school_list()
    {
    	$this->db->select('*');
  		$this->db->from('cms_schools');
  		$this->db->where('schl_status','0');
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->result();
    }
    // GET ALL USERS LIST //
    public function get_all_users_list()
    {
    	$this->db->select('*');
  		$this->db->from('cms_roles');
  		$this->db->where('roles_status','0');
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->result();
    }
    // GET ALL NOTIFICATION TEMPLATES //
    public function get_all_templates()
    {
    	$this->db->select('*');
  		$this->db->from('cms_notifications_templates');
  		$this->db->where('tmpl_status','0');
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->result();
    }
    // GET TEMPLATE CONTENT BY ID //
    public function get_template_content_by_id($template_id)
    {
    	$this->db->select('tmpl_descriptions');
  		$this->db->from('cms_notifications_templates');
  		$this->db->where('tmpl_id',$template_id);
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->row();
    }
    // GET ALL RECIPIENT BY ROLE ID //
    public function get_recipient_by_role_id($roleid)
    {
    	$this->db->select('cms_ref_id,cms_role');
  		$this->db->from('cms_users');
  		$this->db->where('cms_role',$roleid);
  		$query = $this->db->get();
  		//echo $this->db->last_query();
  		return $query->row();
    }

    // GET TEACHER LIST BASED ON ROLE //
    public function get_teachers_by_role($teacher_role)
    {
      $this->db->select('tchr_id,roles_id,tchr_name');
      $this->db->from('cms_teachers');
      $this->db->where('roles_id',$teacher_role);
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result();
    }

    // GET PARENTS LIST BASED ON ROLE //
    public function get_parents_by_role($parents_role)
    {
      $this->db->select('prnt_id,roles_id,prnt_gaurdian_name');
      $this->db->from('cms_parents');
      $this->db->where('roles_id',$parents_role);
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result();
    }

    // GET ALL CLASS LIST FOR STUDENTS //
    public function get_all_class_list()
    {
      $this->db->select('cls_id,cls_name');
      $this->db->from('cms_classes');
      $this->db->where('cls_status','0');
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result();
    }
    
    // GET ALL SECTION LIST BY CLASS ID //
    public function get_all_sections_list_by_id($cls_id)
    {
      $this->db->select('sect_id,cls_id,sect_name');
      $this->db->from('cms_sections');
      $this->db->where('cls_id',$cls_id);
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result();
    }

    // GET PARENTS LIST BASED ON ROLE //
    public function get_all_students_by_section_by($sect_id)
    {
      $this->db->select('stud_id,sect_id,stud_name');
      $this->db->from('cms_students');
      $this->db->where('sect_id',$sect_id);
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result();
    }

    // SEND NOTIFICATION //
    public function save_notification_message($receiverArray)
    {
      $this->db->insert_batch('cms_notifications', $receiverArray);
		  return $this->db->insert_id();
    }

}
