<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	
	if(!function_exists('CI')){
		function CI(){
			$CI =& get_instance(); 
			return $CI; 
		}
	}

	if(!function_exists('CheckUserLoginSession')){
		function CheckUserLoginSession(){
		  	$segment= CI()->uri->segment(2); 
		   	$user_id = CI()->session->userdata('user_id');
		  	if(empty($user_id)){
		    	redirect('front','refresh');
		  	}
		  	else{
		    	return 1;
		  	}
		}
	}
	
	function json_output($statusHeader,$response)
	{
		$ci =& get_instance();
		$ci->output->set_content_type('application/json');
		$ci->output->set_status_header($statusHeader);
		$ci->output->set_output(json_encode($response));
	}