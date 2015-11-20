<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('product'); ?>"><?php lang('Product'); ?></a></li>
  <li class="active"><?php lang('Product List'); ?></li>
</ol>

<div class="row">
<div class="col-md-12">

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
            <th width="200"><?php lang('Barcode Code'); ?></th>
            <th width="300"><?php lang('Product Name'); ?></th>
            <th><?php lang('Cost Price'); ?></th>
            <th><?php lang('Sale Price'); ?></th>
            <th><?php lang('Tax Rate'); ?></th>
            <th><?php lang('Tax'); ?></th>
            <th><?php lang('Amount'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
	$this->db->where('status', '1');
	$products = $this->db->get('products')->result_array();
	?>
    <?php foreach($products as $product): ?>
    	<tr>
        	<td class="hide"></td>
            <td><a href="<?php echo site_url('product/get_product/'.$product['id']); ?>"><?php echo $product['code']; ?></a></td>
            <td><?php echo $product['name']; ?></td>
            <td class="text-right"><?php echo get_cost_price($product['cost_price']); ?></td>
            <td class="text-right"><?php echo $product['sale_price']; ?></td>
            <td class="text-right">% <?php echo $product['tax_rate']; ?></td>
            <td class="text-right"><?php echo $product['tax']; ?></td>
            <td class="text-right"><?php echo round($product['amount']); ?></td>
        </tr>
    <?php endforeach; ?>
    
    </tbody>
</table>

</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<div class="h20"></div>