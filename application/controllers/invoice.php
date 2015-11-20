<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function index()
	{
		$this->template->view('invoice/dashboard_view');
	}
	
	public function new_invoice()
	{
		$this->template->view('invoice/new_invoice_view');
	}
	
	public function view($invoice_id)
	{
		$data['invoice_id'] = $invoice_id;
		$this->template->view('invoice/invoice_view', $data);
	}
	
	public function invoice_list()
	{
		$this->template->view('invoice/invoice_list_view');
	}
	
	
	
}
