<?php $users = get_user_array();  ?>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('plugins'); ?>"><?php lang('Plugins'); ?></a></li>
  <li><a href="<?php echo site_url('car'); ?>"><?php lang('Vehicle Management'); ?></a></li>
  <li class="active"><?php lang('Vehicle List'); ?></li>
</ol>

<div class="row">
<div class="col-md-12">


<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th></th>
            <th><?php lang('Name'); ?></th>
            <th><?php lang('Brand'); ?></th>
            <th><?php lang('Plate'); ?></th>
            <th class="hidden-xs hidden-sm"><?php lang('Mileage'); ?></th>
            <th class="hidden-xs hidden-sm"><?php lang('Maintenance Mileage'); ?></th>
            <th class="hidden-xs hidden-sm"><?php lang('Mpg'); ?></th>
            <th class="hidden-xs hidden-sm"><?php lang('Registered the Staff'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
	$this->db->where('status', '1');
	$cars = $this->db->get('p_cars')->result_array();
	?>
    <?php foreach($cars as  $car): ?>
    	<tr>
        	<td><a href="<?php echo site_url('car/view/'.$car['id']); ?>">#<?php echo $car['id']; ?></a></td>
            <td><a href="<?php echo site_url('car/view/'.$car['id']); ?>"><?php echo $car['name']; ?></a></td>
            <td><?php echo $car['brand']; ?>/<?php echo $car['model']; ?></td>
            <td><?php echo $car['plate']; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $car['now_mileage']; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $car['maintenance_finish_km']; ?></td>
            <td class="text-right hidden-xs hidden-sm"><?php echo get_money($car['mpg']); ?></td>
            <td class="hidden-xs hidden-sm"><?php echo @$users[$car['user_id']]['name']; ?> <?php echo @$users[$car['user_id']]['surname']; ?></td>
        </tr>
    <?php endforeach; ?>
    
    </tbody>
</table>

</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<div class="h20"></div>