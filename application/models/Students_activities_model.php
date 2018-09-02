<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Students_activities_model extends CI_Model {


	// GET STUDENTS PROFILE DETAILS //
	public function get_students_profile_record($stu_id,$stu_role)
	{
		$this->db->select('*');
		$this->db->from('cms_students');
		$this->db->where('cms_id',$stu_id);
		$this->db->where('roles_id',$stu_role);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->row();
	}
	// GET STUDENTS CLASS SECTION //
	public function get_student_class_section_by_master_id($stu_id)
	{
		$this->db->select('stud_id,roles_id,cls_id,sect_id');
		$this->db->from('cms_students');
		$this->db->where('cms_id',$stu_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->row();
	}

	// GET MENU LIST BASED ON ROLE PERMISSION
	public function get_menu_role_permissions($stu_role)
	{
		$this->db->select('mn.menu_name, mr.*');
		$this->db->from('cms_menu_role_permission mr');
		$this->db->join('cms_menu mn','mr.menu_id=mn.menu_id','left');
		$this->db->where('roles_id', $stu_role);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	// GET ALL UNREAD NOTIFICATION //
	// public function get_all_unread_notification($unread)
	// {
	// 	$this->db->select('COUNT(ntfn_notification_read_status) as message');		
	// 	$query = $this->db->get('cms_notifications');
	// 	$this->db->where('ntfn_notification_read_status',$unread);
	// 	$count = $query->row_array();
	// 	return $count;
	// }
	// GET ALL UNREAD NOTIFICATION //
	public function get_student_unread_notification_list($stdid,$roleid,$clsid,$sectid)
	{
		$this->db->select('COUNT(ntfn_notification_read_status) as message');		
		$query = $this->db->get('cms_notifications');
		$this->db->where('stud_id',$stdid);
		$this->db->where('roles_id',$roleid);
		$this->db->where('cls_id',$clsid);
		$this->db->where('sect_id',$sectid);
		$this->db->where('ntfn_notification_read_status','0');
		$count = $query->row_array();
		return $count;
	}

}
