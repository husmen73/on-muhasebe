<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function index()
	{
		$this->template->view('account/dashboard_view');
	}
	
	public function new_account()
	{
		$this->template->view('account/new_account_view');
	}
	
	public function list_account()
	{
		$this->template->view('account/list_account_view');	
	}
	
	public function get_account($account_id)
	{
		$data['account_id'] = $account_id;
		$this->template->view('account/account_view', $data);	
	}
	
	
	public function telephone_directory()
	{
		$this->template->view('account/telephone_directory_view');	
	}
	
	public function options()
	{
		$this->template->view('account/options_view');	
	}
	
	public function address_print($account_id)
	{
		$data['account_id'] = $account_id;
		$this->load->view('account/address_print_view', $data);	
	}
	
}
