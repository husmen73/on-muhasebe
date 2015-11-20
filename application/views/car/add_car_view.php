<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('plugins'); ?>"><?php lang('Plugins'); ?></a></li>
  <li><a href="<?php echo site_url('car'); ?>"><?php lang('Vehicle Management'); ?></a></li>
  <li class="active"><?php lang('New Car'); ?></li>
</ol>


	<!-- Button trigger modal -->
  <a data-toggle="modal" href="#myModal" id="modal-user_list"></a>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php lang('User List'); ?></h4>
        </div>
        <div class="modal-body">
			<?php get_user_list(array('display_name'=>'display_name',
			'user_id'=>'user_id')); ?>
        </div>
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


<form name="form_1" id="form_1" action="" method="POST" class="validation">

<div class="row">
<div class="col-md-8">

<?php
$car['name'] = '';
$car['plate'] = '';
$car['chassis_serial'] = '';
$car['engine_serial'] = '';
$car['brand'] = '';
$car['model'] = '';
$car['old_mileage'] = '';
$car['mpg'] = '';
$car['user_id'] = '';


if(isset($_POST['add']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('name', get_lang('Car Name'), 'required');
	$this->form_validation->set_rules('plate', get_lang('Plate'), 'required');

	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{	
		$car['name'] = $this->input->post('name');
		$car['plate'] = $this->input->post('plate');
		$car['chassis_serial'] = $this->input->post('chassis_serial');
		$car['engine_serial'] = $this->input->post('engine_serial');
		$car['brand'] = $this->input->post('brand');
		$car['model'] = $this->input->post('model');
		$car['old_mileage'] = $this->input->post('old_mileage');
		$car['now_mileage'] = $this->input->post('old_mileage');
		$car['periodic_maintenance'] = $this->input->post('periodic_maintenance');
		$car['mpg'] = $this->input->post('mpg');
		$car['user_id'] = $this->input->post('user_id');
		$car_item['traffic_insurance'] = $this->input->post('traffic_insurance');
		$car_item['traffic_insurance_renovation'] = $this->input->post('traffic_insurance_renovation');
		$car_item['private_insurance'] = $this->input->post('private_insurance');
		$car_item['private_insurance_renovation'] = $this->input->post('private_insurance_renovation');
		$car_item['inspection'] = $this->input->post('inspection');
		$car_item['inspection_renovation'] = $this->input->post('inspection_renovation');
		$car_item['periodic_maintenance'] = $this->input->post('periodic_maintenance');
		$car_item['maintenance_start_km'] = $this->input->post('maintenance_start_km');
	
		if($continue)
		{
			$this->db->insert('p_cars', $car);
			$car_id = $this->db->insert_id();
			if($car_id > 0)
			{
				alertbox('alert-success', get_lang('Operation is Successful'), '');	
				$log['date'] = $this->input->post('log_time');
				$log['type'] = 'p_car';
				$log['title']	= get_lang('Car');
				$log['description'] = get_lang('Data Added').' ['.$car_id.']';
				$log['other_id'] = 'p_car:'.$car_id;
				add_log($log);
				
				
				// item add
				#traffic_insurance
				$car_info['traffic_insurance_start_date'] = $car_item['traffic_insurance'];
				$car_info['traffic_insurance_renovation'] = $car_item['traffic_insurance_renovation'];
				$car_info['traffic_insurance_finish_date'] = add_date_time($car_info['traffic_insurance_start_date'], $car_info['traffic_insurance_renovation'], 'm');  
				
				#private_insurance
				$car_info['private_insurance_start_date'] = $car_item['private_insurance'];
				$car_info['private_insurance_renovation'] = $car_item['private_insurance_renovation'];
				$car_info['private_insurance_finish_date'] = add_date_time($car_info['private_insurance_start_date'], $car_info['private_insurance_renovation'], 'm');  
				
				#inspection
				$car_info['inspection_start_date'] = $car_item['inspection'];
				$car_info['inspection_renovation'] = $car_item['inspection_renovation'];
				$car_info['inspection_finish_date'] = add_date_time($car_info['inspection_start_date'], $car_info['inspection_renovation'], 'm');  
			
				
				
				//Periodic Maintenance
				if($car_item['maintenance_start_km'] > 0)
				{
					$result = $car['old_mileage'] - $car_item['maintenance_start_km'];
					$result = $car_item['periodic_maintenance'] - $result;
					$maintenance_finish_km = $result + $car['old_mileage'];
				}
				else
				{
					$maintenance_finish_km = 0;
				}
				
				$car_info['maintenance_start_km'] = $car_item['maintenance_start_km'];
				$car_info['maintenance_finish_km'] = $maintenance_finish_km;
				
				
				$this->db->where('id', $car_id);
				$this->db->update('p_cars', $car_info);
					
			}
			else
			{
				alertbox('alert-danger', get_lang('Error!'));
			}
		}
	
		
		
		
	}
}
?>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="control-label ff-1 fs-16"><?php lang('Car Name'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name" name="name" class="form-control input-lg ff-1 required" placeholder="<?php lang('Car Name'); ?>" minlength="3" maxlength="30" value="<?php echo $car['name']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="chassis_serial" class="control-label ff-1 fs-16"><?php lang('Chassis Serial'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="chassis_serial" name="chassis_serial" class="form-control input-lg ff-1 " placeholder="<?php lang('Chassis Serial'); ?>" maxlength="30" value="<?php echo $car['chassis_serial']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="brand" class="control-label ff-1 fs-16"><?php lang('Brand'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="brand" name="brand" class="form-control input-lg ff-1" minlength="2" maxlength="50" value="<?php echo $car['brand']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="old_mileage" class="control-label ff-1 fs-16"><?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="old_mileage" name="old_mileage" class="form-control input-lg ff-1 digits" placeholder="<?php lang('Mileage'); ?>" maxlength="30" value="<?php echo $car['old_mileage']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-6 -->
        
        <div class="col-md-6">
        	<div class="form-group">
                <label for="plate" class="control-label ff-1 fs-16"><?php lang('Plate'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="plate" name="plate" class="form-control input-lg ff-1 required" placeholder="<?php lang('Plate'); ?>" minlength="3" maxlength="30" value="<?php echo $car['plate']; ?>">
                </div>
            </div> <!-- /.form-group -->  
            <div class="form-group">
                <label for="engine_serial" class="control-label ff-1 fs-16"><?php lang('Engine Serial'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="engine_serial" name="engine_serial" class="form-control input-lg ff-1" placeholder="<?php lang('Engine Serial'); ?>" value="<?php echo $car['engine_serial']; ?>" maxlength="30">
                </div>
            </div> <!-- /.form-group -->  
            <div class="form-group">
                <label for="model" class="control-label ff-1 fs-16"><?php lang('Model'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="model" name="model" class="form-control input-lg ff-1" minlength="2" maxlength="50" value="<?php echo $car['model']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="mpg" class="control-label ff-1 fs-16"><?php lang('Mpg'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="mpg" name="mpg" class="form-control input-lg ff-1 number" placeholder="0.00" value="<?php echo $car['mpg']; ?>" maxlength="30">
                </div>
            </div> <!-- /.form-group -->  
        </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
    
</div> <!-- /.col-md-8 -->
<div class="col-md-4" style="border-left:1px solid #EBEBEB;">
    
    <div class="form-group openModal-user_list">
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $car['user_id']; ?>" />
        <label for="display_name" class="control-label ff-1 fs-16"><?php lang('Registered the Staff'); ?></label>
        <div class="input-prepend input-group">
            <span class="input-group-addon pointer"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" id="display_name" name="display_name" class="form-control input-lg ff-1 pointer" placeholder="<?php lang('Registered the Staff'); ?>" minlength="3" maxlength="50" value="<?php echo @$user['display_name']; ?>" readonly="readonly">
        </div>
    </div> <!-- /.form-group -->
	
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="traffic_insurance" class="control-label ff-1 fs-16"><?php lang('Traffic Insurance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="traffic_insurance" name="traffic_insurance" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-10 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="traffic_insurance_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="traffic_insurance_renovation" name="traffic_insurance_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="12">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-2 -->
    </div> <!-- /.row -->
    
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="private_insurance" class="control-label ff-1 fs-16"><?php lang('Private Insurance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="private_insurance" name="private_insurance" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-7 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="private_insurance_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="private_insurance_renovation" name="private_insurance_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="12">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-5 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="inspection" class="control-label ff-1 fs-16"><?php lang('Date of Inspection'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="inspection" name="inspection" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-7 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="inspection_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="inspection_renovation" name="inspection_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="12">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-5 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="periodic_maintenance" class="control-label ff-1 fs-13"><?php lang('Periodic Maintenance'); ?>/<?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-compressed"></span></span>
                    <input type="text" id="periodic_maintenance" name="periodic_maintenance" class="form-control input-lg ff-1 digits" maxlength="10" value="">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-6 -->
        <div class="col-md-6">
        	<div class="form-group">
                <label for="maintenance_start_km" class="control-label ff-1 fs-13"><?php lang('Last Maintenance'); ?>/<?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-compressed"></span></span>
                    <input type="text" id="maintenance_start_km" name="maintenance_start_km" class="form-control input-lg ff-1 digits" maxlength="10" value="">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
    
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


<hr />

<div class="row">
	<div class="col-md-12">
    	<div class="text-right">
            <input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="add" />
            <button class="btn btn-default btn-lg btn-success"><?php lang('Save'); ?> &raquo;</button>
        </div> <!-- /.text-right -->
    </div> <!-- /.col-md-8 -->
</div> <!-- /.row -->


</form>

<div class="h20"></div>


<script>
$('.openModal-user_list').click(function() {
	$('#modal-user_list').click();
});
</script>