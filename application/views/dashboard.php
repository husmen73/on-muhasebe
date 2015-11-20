<?php $users = get_user_array(); ?>

<ol class="breadcrumb">
  <li class="active"><?php lang('Dashboard'); ?></li>
</ol>

<div class="row">
	<div class="col-md-8">
        <div class="row">
        	<div class="col-md-3">
            	<a href="<?php echo site_url('invoice/new_invoice/?sell'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat metro_green none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-shopping-cart fs-24"></i>
                        </div>
                        <div class="details">
                            <div class="number"><i class="icon-comments glyphicon glyphicon-plus fs-12 mr5"></i>yeni</div>
                            <div class="desc">ürün satmak</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-6 -->
            <div class="col-md-3">
            	<a href="<?php echo site_url('payment/new_payment/?get_money'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat metro_green none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-usd fs-24"></i>
                        </div>
                        <div class="details">
                            <div class="number"><i class="icon-comments glyphicon glyphicon-plus fs-12 mr5"></i>yeni</div>
                            <div class="desc">para almak</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
               	 </a>
            </div> <!-- /.col-md-3 -->
            
            <div class="col-md-3">
            	<a href="<?php echo site_url('product'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-inbox fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">ürün</div>
                            <div class="desc">ürün yönetimi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
           </div> <!-- /.col-md-3 -->
           
           	<div class="col-md-3">
				<a href="<?php echo site_url('account'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-stop fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">hesap</div>
                            <div class="desc">hesap yönetimi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
			</div> <!-- /.col-md-3 -->  
           
           	<div class="col-md-3">   
            	<a href="<?php echo site_url('invoice'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-shopping-cart fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">alış-veriş</div>
                            <div class="desc">ürün alışı ve satışı</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->  
            	</a>
            </div> <!-- /.col-md-6 -->
            
            <div class="col-md-3"> 
            	<a href="<?php echo site_url('payment'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-euro fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">kasa</div>
                            <div class="desc">ödeme hareketleri ve çek takibi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
             </div> <!-- /.col-md-3 -->
             
             
             <div class="col-md-3">   
             	<a href="<?php echo site_url('plugins'); ?>" class="link-dashboard-stat">
                <div class="dashboard-stat metro_red none">
                	<div class="visual">
                     	<i class="icon-comments glyphicon glyphicon-tags fs-20"></i>
                  	</div>
                	<div class="details">
                    	<div class="number">eklenti</div>
                        <div class="desc">eklenti listesi ve yönetimi</div>
                     </div>
                </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-3 -->
        </div> <!-- /.row -->
        
        
        
        <?php
$this->db->where('option_group', 'plugins');
$plugins = $this->db->get('options')->result_array();
?>



	<hr />
    <div class="row">
        <?php foreach($plugins as $plugin): ?>
        <div class="col-md-3">
            <a href="<?php echo site_url($plugin['option_key']); ?>" class="link-dashboard-stat">
                <div class="dashboard-stat metro_green none">
                    <div class="details">
                        <div class="number"><?php echo $plugin['option_value']; ?></div>
                        <div class="desc"><?php echo get_lang($plugin['option_value2']); ?></div>
                     </div>
                </div> <!-- /.dashboard-stat -->
            </a>
        </div> <!-- /.col-md-3 -->
        <?php endforeach; ?>
    </div> <!-- /.row -->

        
        
    </div> <!-- /.col-md-8 -->
    <div class="col-md-4">
    
    	<div class="notebox">
            <div class="box_title dark_turq dashboard-note-title">
                <span class="glyphicon glyphicon-pencil mr9"></span>Not Defteri 
                <div class="pull-right"><a href="javascript:;" id="note-save" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-check fs-10 mr5"></span>save</a></div>
            </div>
            <textarea class="dashboard-note ff-1" id="dashboard-note"><?php $dashboard_note = get_option(array('option_group'=>'dashboard', 'option_key'=>'dashboard-note')); echo $dashboard_note['option_value']; ?></textarea>
            <div id="hidden-note-value"></div>
            <script>
			$('#note-save').click(function() {
				$( "#hidden-note-value" ).load('<?php echo site_url('general/save_note'); ?>/' + encodeURI($('#dashboard-note').val()));
				$('.dashboard-note').css('backgroundColor', '#dff0d8');
				setTimeout(function(){$('.dashboard-note').css('backgroundColor', '#F6F6F6');},3000);
			});
			</script>
            
        </div>
        
        
        
        
        <div class="box_title red"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('Inbox'); ?></div>
        <?php
		$this->db->where('status', 1);
		$this->db->where_in('type', array('message','reply_message'));
		$this->db->where('inbox_view', '1');
		$this->db->where('receiver_id', get_the_current_user('id'));		
		$this->db->order_by('date DESC, recent_activity DESC');
		$this->db->limit(10);
		$query = $this->db->get('user_mess')->result_array();
		?>
        <?php if($query) : ?>
        <table class="table table-hover table-bordered table-condensed">
            <thead>
                <tr>
                    <th><?php lang('Sender'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($query as $q): ?>
               <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                    <td><?php echo $users[$q['sender_id']]['surname']; ?></td>
                    <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo mb_substr($q['title'],0,30,'utf-8'); ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        	<?php alertbox('alert-info', get_lang('Your inbox is empty.'), '', false); ?>
        <?php endif; ?>
        
        
        
        
        
        <div class="box_title turq"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('Task Manager'); ?></div>
        <?php
		$this->db->where('status', 1);
		$this->db->where_in('type', array('task','reply_task'));
		$this->db->where('inbox_view', '1');
		$this->db->where('receiver_id', get_the_current_user('id'));		
		$this->db->order_by('recent_activity', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get('user_mess')->result_array();
		?>
        <?php if($query) : ?>
        <table class="table table-hover table-bordered table-condensed">
            <thead>
                <tr>
                    <th><?php lang('Sender'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($query as $q): ?>
               <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                    <td><?php echo $users[$q['sender_id']]['surname']; ?></td>
                    <td><a href="<?php echo site_url('user/task/'.$q['id']); ?>"><?php echo $q['title']; ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        	<?php alertbox('alert-info', get_lang('No task.'), '', false); ?>
        <?php endif; ?>
    </div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

