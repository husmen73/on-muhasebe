<ol class="breadcrumb">
  <li><a href="<?php echo site_url(); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('account'); ?>"><?php lang('Account'); ?></a></li>
  <li class="active"><?php lang('Account List'); ?></li>
</ol>

<div class="row">
<div class="col-md-12">

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
            <th width="200"><?php lang('Account Code'); ?></th>
            <th width="300"><?php lang('Account Name'); ?></th>
            <th><?php lang('Name and Surname'); ?></th>
            <th><?php lang('City'); ?></th>
            <th><?php lang('Phone'); ?></th>
            <th><?php lang('Balance'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
	$this->db->where('status', '1');
	$accounts = $this->db->get('accounts')->result_array();
	?>
    <?php foreach($accounts as $account): ?>
    	<tr>
        	<td class="hide"></td>
            <td><a href="<?php echo site_url('account/get_account/'.$account['id']); ?>"><?php echo $account['code']; ?></a></td>
            <td><?php echo $account['name']; ?></td>
            <td><?php echo $account['name_surname']; ?></td>
            <td><?php echo $account['city']; ?></td>
            <td><?php echo $account['phone']; ?></td>
            <td class="text-right"><?php echo get_account_balance($account['balance']); ?></td>
        </tr>
    <?php endforeach; ?>
    
    </tbody>
</table>

</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<div class="h20"></div>