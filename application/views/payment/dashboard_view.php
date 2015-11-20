<?php $accounts = get_account_list_for_array(); ?>


<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li class="active"><?php lang('Payment'); ?></li>
</ol>


<div class="row">
<div class="col-md-8">

<div class="row">
    <div class="col-md-3">
        <a href="<?php echo site_url('payment/new_payment/?give_money'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_green none">
                <div class="details">
                    <div class="number"><i class="glyphicon glyphicon-usd fs-14 mr9"></i><?php lang('new'); ?></div>
                    <div class="desc"><?php lang('give payment'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
        </a>
    </div> <!-- /.col-md-3 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('payment/new_payment/?get_money'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_green none">
                <div class="details">
                    <div class="number"><i class="glyphicon glyphicon-usd fs-14 mr9"></i><?php lang('new'); ?></div>
                    <div class="desc"><?php lang('receive payment'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('payment/payment_list'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat yellow none">
                <div class="details">
                    <div class="number"><?php lang('payments'); ?></div>
                    <div class="desc"><?php lang('payment list'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('payment/payment_list/?val_1=checks'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat yellow none">
                <div class="details">
                    <div class="number"><?php lang('Checks'); ?></div>
                    <div class="desc"><?php lang('checks list'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <?php if(get_the_current_user('role') < 3): ?>
    <div class="col-md-3">
        <a href="<?php echo site_url('payment/options'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_brown none">
                <div class="details">
                    <div class="number"><?php lang('options'); ?></div>
                    <div class="desc"><?php lang('management settings'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <?php endif; ?>
    
    
</div> <!-- /.row -->


</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<?php if(get_the_current_user('role') < 3): ?>
	<div class="box_title dark_turq"><span class="glyphicon glyphicon-calendar mr9"></span><?php lang('The Checks'); ?></div>
	<?php
    $this->db->where('status', 1);
    $this->db->where('type','payment');
    $this->db->where('val_1', 'checks');
	$this->db->where('in_out', '0');	
	$this->db->where('val_2 >', date('Y-m-d'));	
    $this->db->order_by('val_2', 'ASC');
    $this->db->limit(10);
    $query = $this->db->get('invoices')->result_array();
    ?>
    <?php if($query) : ?>
    <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
            	<th width="100"><?php lang('Fall Due On'); ?></th>
                <th><?php lang('Account'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($query as $q): ?>
           <tr>
           		<td><?php echo $q['val_2']; ?></td>
				<td><a href="<?php echo site_url('account/get_account/'.$q['account_id']); ?>" target="_blank"><?php echo $accounts[$q['account_id']]['name']; ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <?php alertbox('alert-info', get_lang('Value not found.'), '', false); ?>
    <?php endif; ?>
    
    
    <div class="box_title dark_turq"><span class="glyphicon glyphicon-calendar mr9"></span><?php lang('Outgoing Checks'); ?></div>
	<?php
    $this->db->where('status', 1);
    $this->db->where('type','payment');
    $this->db->where('val_1', 'checks');
	$this->db->where('in_out', '1');	
	$this->db->where('val_2 >', date('Y-m-d'));	
    $this->db->order_by('val_2', 'ASC');
    $this->db->limit(10);
    $query = $this->db->get('invoices')->result_array();
    ?>
    <?php if($query) : ?>
    <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
            	<th width="100"><?php lang('Fall Due On'); ?></th>
                <th><?php lang('Account'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($query as $q): ?>
           <tr>
           		<td><?php echo $q['val_2']; ?></td>
				<td><a href="<?php echo site_url('account/get_account/'.$q['account_id']); ?>" target="_blank"><?php echo $accounts[$q['account_id']]['name']; ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <?php alertbox('alert-info', get_lang('Value not found.'), '', false); ?>
    <?php endif; ?>
    <?php endif; // role control(2) ?> 
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->