<?php $car = get_car($car_id); ?>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('plugins'); ?>"><?php lang('Plugins'); ?></a></li>
  <li><a href="<?php echo site_url('car'); ?>"><?php lang('Vehicle Management'); ?></a></li>
  <li><a href="<?php echo site_url('car/list_cars'); ?>"><?php lang('Vehicle List'); ?></a></li>
  <li class="active"><?php lang('New Car'); ?></li>
</ol>



<?php
if(isset($_GET['delete_item']))
{
	$this->db->where('id', $_GET['item_id']);
	$this->db->update('p_car_items', array('status'=>'0'));
	
	alertbox('alert-danger', get_lang('Data Deleted'));
}

if(isset($_GET['status']))
{
	if($_GET['status'] == 0){}else if($_GET['status'] == 1){} else{exit('error! code:fg03mfd');}	
	
	$this->db->where('id', $car['id']);
	$this->db->update('p_cars', array('status'=>$_GET['status']));
	if($this->db->affected_rows() > 0)
	{
		$log['type'] = 'p_car';
		if($_GET['status'] == 0)
		{
			$log['title']	= get_lang('Deletion');
			$log['description'] = get_lang('Data Deleted').' ['.$car['id'].']';
			alertbox('alert-danger', get_lang('Data Deleted'));
		}
		else if($_GET['status'] == 1)
		{
			$log['title']	= get_lang('Activated');
			$log['description'] = get_lang('Data Activated').' ['.$car['id'].']';
			alertbox('alert-warning', get_lang('Data Activated'));
		}
		else { exit('error'); }
		$log['other_id'] = 'p_car:'.$car['id'];
		add_log($log);	
	}
	$car = get_car($car_id);
}
?>

<?php if($car['status'] == 0): ?>
	<?php alertbox('alert-warning', get_lang('Data Deleted'), '', false); ?>
<?php endif; ?>


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



<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#car_card" data-toggle="tab"><?php lang('Car'); ?></a></li>
    <li><a href="#transactions" data-toggle="tab" id="id_transactions"><?php lang('Transactions'); ?></a></li>
    <li><a href="#history" data-toggle="tab"><?php lang('History'); ?></a></li>
</ul>
<div class="h20"></div>



<div id="myTabContent" class="tab-content">


<!-- car_card -->
<div class="tab-pane fade active in" id="car_card">

	
<form name="form_car_card" id="form_car_card" action="" method="POST" class="validation">

<div class="row">
<div class="col-md-8">

<?php
if(isset($_POST['update']) and is_log())
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
			$this->db->where('id', $car['id']);
			$this->db->update('p_cars', $car);


			alertbox('alert-success', get_lang('Operation is Successful'), '');	
			$log['date'] = $this->input->post('log_time');
			$log['type'] = 'p_car';
			$log['title']	= get_lang('Car');
			$log['description'] = get_lang('Data Updated').' ['.$car_id.']';
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
				
			$car = get_car($car_id);
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
                <label for="xx_now_mileage" class="control-label ff-1 fs-16"><?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-dashboard"></span></span>
                    <input type="text" id="xx_now_mileage" name="xx_now_mileage" class="form-control input-lg ff-1 digits" placeholder="<?php lang('Mileage'); ?>" maxlength="30" value="<?php echo $car['now_mileage']; ?>" readonly="readonly">
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
<div class="col-md-4">
    
     <div class="form-group openModal-user_list">
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $car['user_id']; ?>" />
        <label for="display_name" class="control-label ff-1 fs-16"><?php lang('Registered the Staff'); ?></label>
        <?php $user = get_user(array('id'=>$car['user_id']));  ?>
        <div class="input-prepend input-group">
            <span class="input-group-addon pointer"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" id="display_name" name="display_name" class="form-control input-lg ff-1 pointer" placeholder="<?php lang('Registered the Staff'); ?>" minlength="3" maxlength="50" value="<?php echo $user['name']; ?> <?php echo $user['surname']; ?>" readonly="readonly">
        </div>
    </div> <!-- /.form-group -->
    
    <div class="car_detail">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="traffic_insurance" class="control-label ff-1 fs-16"><?php lang('Traffic Insurance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="traffic_insurance" name="traffic_insurance" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo substr($car['traffic_insurance_start_date'],0,10); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-10 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="traffic_insurance_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="traffic_insurance_renovation" name="traffic_insurance_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="<?php echo $car['traffic_insurance_renovation']; ?>">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-2 -->
    </div> <!-- /.row -->
    
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="private_insurance" class="control-label ff-1 fs-16"><?php lang('Private Insurance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="private_insurance" name="private_insurance" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo substr($car['private_insurance_start_date'],0,10); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-7 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="private_insurance_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="private_insurance_renovation" name="private_insurance_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="<?php echo $car['private_insurance_renovation']; ?>">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-5 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="inspection" class="control-label ff-1 fs-16"><?php lang('Date of Inspection'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="inspection" name="inspection" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo substr($car['inspection_start_date'],0,10); ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-7 -->
        <div class="col-md-5">
            <div class="form-group">
                <label for="inspection_renovation" class="control-label ff-1 fs-12"><?php lang('Renovation'); ?>/<?php lang('Month'); ?></label>
                <input type="text" id="inspection_renovation" name="inspection_renovation" class="form-control input-lg ff-1 digits fs-14" min="1" maxlength="2" value="<?php echo $car['inspection_renovation']; ?>">
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-5 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="periodic_maintenance" class="control-label ff-1 fs-13"><?php lang('Periodic Maintenance'); ?>/<?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-compressed"></span></span>
                    <input type="text" id="periodic_maintenance" name="periodic_maintenance" class="form-control input-lg ff-1 digits" maxlength="10" value="<?php echo $car['periodic_maintenance']; ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-6 -->
        <div class="col-md-6">
        	<div class="form-group">
                <label for="maintenance_start_km" class="control-label ff-1 fs-13"><?php lang('Last Maintenance'); ?>/<?php lang('Mileage'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-compressed"></span></span>
                    <input type="text" id="maintenance_start_km" name="maintenance_start_km" class="form-control input-lg ff-1 digits" maxlength="10" value="<?php echo $car['maintenance_start_km']; ?>">
                </div>
            </div> <!-- /.form-group -->	
        </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
    </div> <!-- /.car_detail -->
    

    	<div class="box_title dark_turq"><span class="glyphicon glyphicon-compressed mr9"></span><?php lang('Car Care Statement'); ?> <div class="pull-right fs-12"><a href="javascript:;" class="edit_car_detail" style="color:#fff;" data-status='1'>düzenle</a></div></div>
        <div class="box_border">
        	<table class="table table-bordered table-hover table-condensed" style="margin:0px;">
            	<?php if($car['traffic_insurance_start_date'] > '2000-01-01 00:00:00'): ?>
                    <thead>
                        <tr>
                            <th colspan="3"><?php lang('Traffic Insurance'); ?></th>
                        </tr>
                    </thead>
                    <tr>
                        <td><?php echo substr($car['traffic_insurance_start_date'],0,10); ?></td>
                        <td><?php echo substr($car['traffic_insurance_finish_date'],0,10); ?></td>
                        <td><?php echo days_left($car['traffic_insurance_finish_date']); ?> <?php lang('days left'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if($car['private_insurance_start_date'] > '2000-01-01 00:00:00'): ?>
                    <thead>
                        <tr>
                            <th colspan="3"><?php lang('Private Insurance'); ?></th>
                        </tr>
                    </thead>
                    <tr>
                        <td><?php echo substr($car['private_insurance_start_date'],0,10); ?></td>
                        <td><?php echo substr($car['private_insurance_finish_date'],0,10); ?></td>
                        <td><?php echo days_left($car['private_insurance_finish_date']); ?> <?php lang('days left'); ?></td>
                    </tr>
                <?php endif; ?>
            	<?php if($car['inspection_start_date'] > '2000-01-01 00:00:00'): ?>
                    <thead>
                        <tr>
                            <th colspan="3"><?php lang('Inspection'); ?></th>
                        </tr>
                    </thead>
                    <tr>
                        <td><?php echo substr($car['inspection_start_date'],0,10); ?></td>
                        <td><?php echo substr($car['inspection_finish_date'],0,10); ?></td>
                        <td><?php echo days_left($car['inspection_finish_date']); ?> <?php lang('days left'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if($car['maintenance_start_km']): ?>
                    <thead>
                        <tr>
                            <th colspan="3"><?php lang('Periodic Maintenance'); ?></th>
                        </tr>
                    </thead>
                    <tr>
                        <td><?php echo $car['maintenance_start_km']; ?> <?php lang('km'); ?></td>
                        <td><?php echo $car['maintenance_finish_km']; ?> <?php lang('km'); ?></td>
                        <td><?php echo ($car['maintenance_finish_km'] - $car['now_mileage']); ?> <?php lang('remaining km'); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        
        
        <script>
		$('.car_detail').hide();
		
		$('.edit_car_detail').click(function() {
			if($(this).attr('data-status') == '1')
			{
				$('.car_detail').show('blonde');
			}
			else
			{
				$('.car_detail').hide('blonde');	
			}
			
			if($(this).attr('data-status') == '1')
			{
				$(this).attr('data-status', '0');	
			}
			else
			{
				$(this).attr('data-status', '1');
			}
		});
		</script>
   
    
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


<hr />

<div class="row">
	<div class="col-md-12">
    	<div class="text-right">
        	<?php if($car['status'] == 1): ?>
            	<a href="?status=0" class="btn btn-lg btn-danger"><?php lang('Delete'); ?></a>
            <?php else: ?>
            	<a href="?status=1" class="btn btn-lg btn-warning"><?php lang('Activate'); ?></a>
            <?php endif; ?>
            <input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="update" />
            <button class="btn btn-default btn-lg btn-success"><?php lang('Update'); ?> &raquo;</button>
        </div> <!-- /.text-right -->
    </div> <!-- /.col-md-8 -->
</div> <!-- /.row -->

</form>
</div> <!-- /#car_card -->



<!-- transactions -->
<div class="tab-pane fade in" id="transactions">
<?php
if(isset($_POST['add_item']) and is_log())
{
	$this->form_validation->set_rules('attended_km', get_lang('Attended Km'), 'required');
	
	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$car_item['type'] = 'km';
		$car_item['date'] = $this->input->post('km_date');
		$car_item['description'] = $this->input->post('km_description');
		$car_item['car_id'] = $car['id'];
		$car_item['km'] = $this->input->post('attended_km');
		$car_item['price'] = $this->input->post('km_mpg');
		$car_item['total'] = $car_item['km'] * $car_item['price'];
		
		$item_id = add_car_item($car_item);
		if($item_id > 0)
		{
			alertbox('alert-success', get_lang('Data Added'));
			
			$log['date'] = $this->input->post('log_time');
			$log['type'] = 'p_car_item';
			$log['title']	= get_lang('Car');
			$log['description'] = $car_item['km'].' '.get_lang('Added Mileage').' ['.$car_id.']';
			$log['other_id'] = 'p_car:'.$car_id;
			add_log($log);	
		}
		
		echo '<script>$("#id_transactions").click();</script>';
		
		
	}
}
?>
<form name="form_transactions" id="form_transactions" action="" method="POST" class="validation_2">
	<div class="row">
		<div class="col-md-3">
       		 <div class="form-group">
                <label for="km_date" class="control-label ff-1 fs-16"><?php lang('Date'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="km_date" name="km_date" class="form-control input-lg ff-1 datepicker pointer fs-16" readonly="readonly" maxlength="50" value="<?php echo substr($car['traffic_insurance_start_date'],0,10); ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-3 -->
        <div class="col-md-2">
        	<div class="form-group">
                <label for="km_description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="km_description" name="km_description" class="form-control input-lg ff-1" maxlength="50" value="">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-2 -->
        <div class="col-md-2">
       		 <div class="form-group">
                <label for="attended_km" class="control-label ff-1 fs-16"><?php lang('Attended Km'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-dashboard"></span></span>
                    <input type="text" id="attended_km" name="attended_km" class="form-control input-lg ff-1 digits" maxlength="50" value="" onkeydown="calc_km();" onkeyup="calc_km();" onkeypress="calc_km();">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-2 -->
        <div class="col-md-2">
       		 <div class="form-group">
                <label for="km_mpg" class="control-label ff-1 fs-16"><?php lang('Mpg'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="km_mpg" name="km_mpg" class="form-control input-lg ff-1 number" maxlength="50" value="<?php echo get_money($car['mpg']); ?>" readonly="readonly">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-2 -->
        <div class="col-md-2">
       		 <div class="form-group">
                <label for="km_total" class="control-label ff-1 fs-16"><?php lang('Total'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="km_total" name="km_total" class="form-control input-lg ff-1 number" maxlength="50" value="" readonly="readonly">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-2 -->
        <div class="col-md-1">
        	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="add_item" />
        	<label>&nbsp;</label>
            <br />
        	<button class="btn btn-success"><?php lang('Add'); ?></button>
        </div> <!-- /.col-md-2 -->
    </div> <!-- /.row -->
    
    <script>
	function calc_km()
	{
		var attended_km = $('#attended_km').val();	
		var mpg = $('#km_mpg').val();
		if(attended_km == ''){attended_km = 0;}
		var total = parseFloat(parseInt(attended_km) * parseFloat(mpg)).toFixed(2);	
		$('#km_total').val(total);
	}
	</script>
</form>



<?php
$this->db->where('status', 1);
$this->db->where('car_id', $car['id']);
$this->db->select_sum('total');
$sub_total = $this->db->get('p_car_items')->row_array();

$this->db->where('status', 1);
$this->db->where('type', 'km');
$this->db->where('car_id', $car['id']);
$this->db->select_sum('km');
$km = $this->db->get('p_car_items')->row_array();
?>

<?php
$this->db->where('status', '1');
$this->db->where('car_id', $car['id']);
$this->db->order_by('id', 'DESC');
$query = $this->db->get('p_car_items')->result_array();
?>

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th width="1"></th>
            <th><?php lang('Type'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Description'); ?></th>
            <th><?php lang('Km'); ?></th>
            <th><?php lang('Price'); ?></th>
            <th><?php lang('Total'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($query as $car_item): ?>
        	<tr>
            	<td width="1"><a href="?delete_item&item_id=<?php echo $car_item['id']; ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a></td>
                <td><?php echo $car_item['type']; ?></td>
                <td><?php echo substr($car_item['date'],0,10); ?></td>
                <td><?php echo $car_item['description']; ?></td>
                <td><?php echo $car_item['km']; ?> <?php lang('km'); ?></td>
                <td class="text-right"><?php echo get_money($car_item['price']); ?></td>
                <td class="text-right"><?php echo get_money($car_item['total']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    	<tr>
        	<td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="fs-16"><?php echo $km['km']; ?> <?php lang('km'); ?></td>
            <td></td>
            <td class="text-right fs-16"><?php echo get_money($sub_total['total']); ?></td>
        </tr>
    </tfoot>
</table>

	<?php
	// update mileage
	$this->db->where('id', $car['id']);
	$this->db->update('p_cars', array('now_mileage'=>($car['old_mileage']+$km['km'])));
	?>

</div> <!-- /.transactions -->


<!-- history -->
<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('other_id'=>'p_car:'.$car['id']), 'DESC'); ?>
</div> <!-- /#history -->


</div> <!-- /#myTabContent -->

<div class="h20"></div>


<script>
$('.openModal-user_list').click(function() {
	$('#modal-user_list').click();
});
</script>