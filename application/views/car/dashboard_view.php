<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('plugins'); ?>"><?php lang('Plugins'); ?></a></li>
  <li class="active"><?php lang('Vehicle Management'); ?></li>
</ol>



<div class="row">
	<div class="col-md-8">
        <div class="row">
             <div class="col-md-3">
                <a href="<?php echo site_url('car/add_car'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat metro_green none">
                        <div class="details">
                            <div class="number"><?php lang('new'); ?></div>
                            <div class="desc"><?php lang('adding a new car'); ?></div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-3 -->
            <div class="col-md-3">
                <a href="<?php echo site_url('car/list_cars'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat metro_green none">
                        <div class="details">
                            <div class="number"><?php lang('cars'); ?></div>
                            <div class="desc"><?php lang('vehicle list'); ?></div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-3 -->
        </div> <!-- /.row -->
	</div> <!-- /.col-md-8 -->
</div> <!-- /.row -->