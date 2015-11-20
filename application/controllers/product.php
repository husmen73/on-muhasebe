<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {

	public function index()
	{
		$this->template->view('product/dashboard_view');
	}
	
	public function new_product()
	{
		$this->template->view('product/new_product_view');
	}
	
	public function list_product()
	{
		$this->template->view('product/list_product_view');	
	}
	
	public function get_product($product_id)
	{
		$data['product_id'] = $product_id;
		$this->template->view('product/product_view', $data);	
	}
	
	public function options()
	{
		$this->template->view('product/options_view');	
	}
	
}
