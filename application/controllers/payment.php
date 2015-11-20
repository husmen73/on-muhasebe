<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	public function index()
	{
		$this->template->view('payment/dashboard_view');
	}
	
	
	public function new_payment()
	{
		$this->template->view('payment/new_payment_view');	
	}
	
	public function payment_list()
	{
		$this->template->view('payment/payment_list_view');	
	}
	
	public function view($payment_id)
	{
		$data['invoice_id'] = $payment_id;
		$this->template->view('payment/payment_view',$data);	
	}
	
	
}
