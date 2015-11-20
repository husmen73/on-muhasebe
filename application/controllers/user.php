<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		
	}
	
	public function login()
	{
		$this->load->view('login');	
	}
	
	public function logout()
	{
		$this->session->set_userdata('login', false);
		redirect(site_url());
	}
	
	public function user_list()
	{
		$this->template->view('user/user_list_view');
	}
	
	
	
	public function new_user()
	{
		$this->template->view('user/new_user_view');
	}
	
	public function get_user($user_id='')
	{
		if($user_id == ''){exit('no access');}
		$data['user_id'] = $user_id;
		$this->template->view('user/user_view',$data);
	}
	
	
	function no_access($role)
	{
		$data['role'] = $role;
		$this->template->view('user/no_access', $data);
	}
	
	
	function new_message()
	{
		$this->template->view('user/messagebox/new_message_view');	
	}
	
	function inbox($message_id='')
	{
		$data['message_id'] = $message_id;
		$this->template->view('user/messagebox/inbox_view',$data);
	}
	
	function outbox()
	{
		$this->template->view('user/messagebox/outbox_view');
	}
	
	function bulk_message()
	{
		$this->template->view('user/messagebox/bulk_view');	
	}
	
	
	function new_task()
	{
		$this->template->view('user/task/new_task_view');	
	}
	
	function task($task_id='')
	{
		$data['task_id'] = $task_id;
		$this->template->view('user/task/task_view',$data);	
	}
	
	function outbound_tasks()
	{
		$this->template->view('user/task/outbound_task_view');	
	}
	
	function profile($user_id='')
	{
		$data['user_id'] = $user_id;
		$this->template->view('user/profile_view',$data);	
	}

	
}
