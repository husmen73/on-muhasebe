<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Car extends CI_Controller {
	
	public function index()
	{
		$this->template->view('car/dashboard_view');
	}
	
	public function add_car()
	{
		$this->template->view('car/add_car_view');
	}
	
	public function list_cars()
	{
		$this->template->view('car/list_cars_view');	
	}
	
	public function view($car_id)
	{
		$data['car_id'] = $car_id;
		$this->template->view('car/car_view',$data);	
	}
	
	
}
