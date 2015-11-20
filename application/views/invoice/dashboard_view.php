<?php $accounts = get_account_list_for_array(); ?>


<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li class="active"><?php lang('Buying-Selling'); ?></li>
</ol>



<div class="row">
<div class="col-md-8">

<div class="row">
    <div class="col-md-3">
        <a href="<?php echo site_url('invoice/new_invoice/?sell'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_green none">
                <div class="details">
                    <div class="number"><i class="glyphicon glyphicon-log-out fs-14 mr9"></i><?php lang('new'); ?></div>
                    <div class="desc"><?php lang('sell products'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
        </a>
    </div> <!-- /.col-md-3 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('invoice/new_invoice/?buy'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_green none">
                <div class="details">
                    <div class="number"><i class="glyphicon glyphicon-log-in fs-14 mr9"></i><?php lang('new'); ?></div>
                    <div class="desc"><?php lang('buy products'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('invoice/invoice_list'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat yellow none">
                <div class="details">
                    <div class="number"><?php lang('invoices'); ?></div>
                    <div class="desc"><?php lang('invoice list'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <?php if(get_the_current_user('role') < 3): ?>
    <div class="col-md-3">
        <a href="<?php echo site_url('invoice/options'); ?>" class="link-dashboard-stat">
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
	<div class="box_title dark_turq"><span class="glyphicon glyphicon-list-alt mr9"></span><?php lang('Invoice List'); ?></div>
	<?php
    $this->db->where('status', 1);
    $this->db->where('type','invoice');
    $this->db->order_by('date', 'DESC');
    $this->db->limit(10);
    $query = $this->db->get('invoices')->result_array();
    ?>
    <?php if($query) : ?>
    <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
            	<th width="60"><?php lang('ID'); ?></th>
                <th><?php lang('Account'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($query as $q): ?>
           <tr>
           		<td><a href="<?php echo site_url('invoice/view/'.$q['id']); ?>">#<?php echo $q['id']; ?></a></td>
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