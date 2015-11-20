<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li class="active"><?php lang('Product'); ?></li>
</ol>



<div class="row">
<div class="col-md-8">

<div class="row">
    <div class="col-md-3">
        <a href="<?php echo site_url('product/new_product'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat metro_green none">
                <div class="details">
                    <div class="number"><i class="icon-comments glyphicon glyphicon-plus fs-12 mr5"></i><?php lang('new'); ?></div>
                    <div class="desc"><?php lang('new product card'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
        </a>
    </div> <!-- /.col-md-6 -->
    <div class="col-md-3">
        <a href="<?php echo site_url('product/list_product'); ?>" class="link-dashboard-stat">
            <div class="dashboard-stat yellow none">
                <div class="details">
                    <div class="number"><?php lang('products'); ?></div>
                    <div class="desc"><?php lang('product list'); ?></div>
                 </div>
            </div> <!-- /.dashboard-stat -->
         </a>
    </div> <!-- /.col-md-3 -->
    <?php if(get_the_current_user('role') < 3): ?>
    <div class="col-md-3">
        <a href="<?php echo site_url('product/options'); ?>" class="link-dashboard-stat">
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

</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->