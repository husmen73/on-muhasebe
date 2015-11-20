<?php
if($this->session->userdata('login') == false)
{
	redirect('user/login');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TilPark! Açık Kaynak Kodlu Stok Takip ve Cari Otomasyonu</title>
<meta name="description" content="Bootstrap">


<!-- Included CSS Files (Compressed) -->

<link href="<?php echo base_url('theme/css/bootstrap-glyphicons.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('theme/css/bootstrap.css'); ?>">

<link rel="stylesheet" href="<?php echo base_url('theme/css/datepicker.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('theme/js/dataTable/css/TableTools.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('theme/css/app.css'); ?>">


<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo base_url('theme/js/jquery.js'); ?>"></script>
<script src="<?php echo base_url('theme/js/bootstrap.js'); ?>"></script> 
<script src="<?php echo base_url('theme/js/jquery.validation.js'); ?>"></script> 

<script src="<?php echo base_url('theme/js/bootstrap-datepicker.js'); ?>"></script> 


<script src="<?php echo base_url('theme/js/jquery.dataTables.js'); ?>"></script> 
<script src="<?php echo base_url('theme/js/dataTable/js/ZeroClipboard.js'); ?>"></script> 
<script src="<?php echo base_url('theme/js/dataTable/js/TableTools.js'); ?>"></script>

<!-- Initialize JS Plugins -->
<script src="<?php echo base_url('theme/js/app.js'); ?>"></script>


<script>
$(document).ready( function() {
	
	/* datatable */
	var exportName = $('.dataTable').attr("exportname");
	if(exportName == null)
	{
		exportName = '<?php echo $this->uri->segment(2); ?>';
	}
	
	exportName = exportName +'_tilpark_'+'<?php echo date('YmdHis'); ?>';
	
	$('.dataTable').dataTable({
		
		"sDom": " <'row'<'col-md-4 hidden-xs hidden-sm'T><'col-md-4 hidden-xs hidden-sm'l><'col-md-4'f>>rt<'row'<'col-md-6 hidden-xs hidden-sm'i><'col-md-6'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers",
        "oTableTools": {
            "sSwfPath": "<?php echo base_url('theme/js/dataTable/swf/copy_csv_xls_pdf.swf'); ?>",
			"aButtons": [
				{
					"sExtends": "xls",
					"sFileName": exportName + "_excel.csv",
					"sButtonText": "Excel"
				},
				{
					"sExtends": "csv",
					"sFileName":  exportName + ".csv",
					"sButtonText": "CSV"
				},
				{
					"sExtends": "pdf",
					"sFileName": exportName + ".pdf",
					"sButtonText": "PDF"
				},
				{
					"sExtends": "copy",
					"sButtonText": "<?php lang('Copy'); ?>"
				},
				{
					"sExtends": "print",
					"sButtonText": "<?php lang('Print'); ?>"
				}
			]
        },
		"oLanguage": {
		  "oPaginate": {
			"sFirst": "<?php lang('First page'); ?>",
			"sLast": "<?php lang('Last page'); ?>",
			"sNext": "<?php lang('Next'); ?>",
			"sPrevious": "<?php lang('Previous'); ?>"
		  }
		}
    });
	
	
	$('.dataTable_noLength').dataTable({
		
		"sDom": " <'row'<'col-md-6 hidden-xs hidden-sm'T><'col-md-6'f>>rt<'row'<'col-md-6 hidden-xs hidden-sm'i><'col-md-6'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers",
        "oTableTools": {
            "sSwfPath": "<?php echo base_url('theme/js/dataTable/swf/copy_csv_xls_pdf.swf'); ?>",
			"aButtons": [
				{
					"sExtends": "xls",
					"sFileName": exportName + "_excel.csv",
					"sButtonText": "Excel"
				},
				{
					"sExtends": "csv",
					"sFileName":  exportName + ".csv",
					"sButtonText": "CSV"
				},
				{
					"sExtends": "pdf",
					"sFileName": exportName + ".pdf",
					"sButtonText": "PDF"
				},
				{
					"sExtends": "copy",
					"sButtonText": "<?php lang('Copy'); ?>"
				},
				{
					"sExtends": "print",
					"sButtonText": "<?php lang('Print'); ?>"
				}
			]
        }
    });
	
	
	
	$('.dataTable_noExcel').dataTable({
		"sDom": " <'row'<'col-md-6 hidden-xs hidden-sm'l><'col-md-6'f>>rt<'row'<'col-md-6 hidden-xs hidden-sm'i><'col-md-6'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers"
    });
	
	$('.dataTable_noExcel_noLength').dataTable({
		"sDom": " <'row'<'col-md-12'f>>rt<'row'<'col-md-6 hidden-xs hidden-sm'i><'col-md-6'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers"
    });
	
	$('.dataTable_noExcel_noLength_noInformation').dataTable({
		"sDom": " <'row'<'col-md-12'f>>rt<'row'<'col-md-12'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers"
    });
	
	$('.dataTable_noExcel_noLength_noSearch').dataTable({
		"sDom": "rt<'row'<'col-md-6 hidden-xs hidden-sm'i><'col-md-6'p>>",
		"bJQueryUI": false,
		"sPaginationType": "full_numbers"
    });
	
});
</script>


</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    	<div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Site Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        	<a class="navbar-brand" href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home" style="font-size:20px; margin-top:-10px;"></span></a>
        </div> <!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">

		<ul class="nav navbar-nav">
			<li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-inbox mr5"></span><?php echo get_lang('Products'); ?> <b class="caret"></b></a>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('product/new_product'); ?>"><span class="glyphicon glyphicon-plus mr9"></span><?php lang('New Product'); ?></a></li>
                    <li><a href="<?php echo site_url('product/list_product'); ?>"><span class="glyphicon glyphicon-align-justify mr9"></span><?php lang('Product List'); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('product'); ?>"><span class="glyphicon glyphicon-inbox mr9"></span><?php lang('Product Management'); ?></a></li>
                    <?php if(get_the_current_user('role') <= 2): ?>
                    	<li class="divider"></li>
                    	<li><a href="<?php echo site_url('product/options'); ?>"><span class="glyphicon glyphicon-wrench mr9"></span><?php lang('Options'); ?></a></li>
               		<?php endif; ?>
                </ul>
            </li>
            
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-stop mr5"></span><?php echo get_lang('Accounts'); ?> <b class="caret"></b></a>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('account/new_account'); ?>"><span class="glyphicon glyphicon-plus mr9"></span><?php lang('New Account'); ?></a></li>
                    <li><a href="<?php echo site_url('account/list_account'); ?>"><span class="glyphicon glyphicon-align-justify mr9"></span><?php lang('Account List'); ?></a></li>
                    <li><a href="<?php echo site_url('account/telephone_directory'); ?>"><span class="glyphicon glyphicon-phone-alt mr9"></span><?php lang('Telephone Directory'); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('account'); ?>"><span class="glyphicon glyphicon-stop mr9"></span><?php lang('Account Management'); ?></a></li>
                    <?php if(get_the_current_user('role') <= 2): ?>
                    	<li class="divider"></li>
                    	<li><a href="<?php echo site_url('account/options'); ?>"><span class="glyphicon glyphicon-wrench mr9"></span><?php lang('Options'); ?></a></li>
               		<?php endif; ?>
                </ul>
            </li>
            
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-shopping-cart mr5"></span><?php echo get_lang('Buying-Selling'); ?> <b class="caret"></b></a>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('invoice/new_invoice/?sell'); ?>"><span class="glyphicon glyphicon-log-out mr9"></span><?php lang('Sell ​​Product'); ?></a></li>
                    <li><a href="<?php echo site_url('invoice/new_invoice/?buy'); ?>"><span class="glyphicon glyphicon-log-in mr9"></span><?php lang('Product Buy'); ?></a></li>
                    <li><a href="<?php echo site_url('invoice/invoice_list'); ?>"><span class="glyphicon glyphicon-list-alt mr9"></span><?php lang('Invoice List'); ?></a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header"><?php echo mb_strtoupper(get_lang('Cash Box'),'utf-8'); ?></li>
                    <li><a href="<?php echo site_url('payment/new_payment/?get_money'); ?>"><span class="glyphicon glyphicon-usd mr9"></span><?php lang('Get Money'); ?></a></li>
                    <li><a href="<?php echo site_url('payment/new_payment/?give_money'); ?>"><span class="glyphicon glyphicon-usd mr9"></span><?php lang('Give Money'); ?></a></li>
                    <li><a href="<?php echo site_url('payment/payment/?cash_box'); ?>"><span class="glyphicon glyphicon-euro mr9"></span><?php lang('Cash Box'); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('invoice'); ?>"><span class="glyphicon glyphicon-shopping-cart mr9"></span><?php lang('Buying-Selling'); ?></a></li>
					<?php if(get_the_current_user('role') <= 2): ?>
                    	<li class="divider"></li>
                    	<li><a href="<?php echo site_url('fiche/options'); ?>"><span class="glyphicon glyphicon-wrench mr9"></span><?php lang('Options'); ?></a></li>
               		<?php endif; ?>
                </ul>
            </li>
        </ul>
	
		<?php $users = get_user_array(); ?>
        
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle icone" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span></a>
				<ul class="sub-menu dropdown-menu">
                	<?php if(get_the_current_user('role') <= 2): ?>
					<li><a href="<?php echo site_url('user/new_user'); ?>"><span class="glyphicon glyphicon-plus-sign mr9"></span><?php lang('New User'); ?></a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo site_url('user/user_list'); ?>"><span class="glyphicon glyphicon-list mr9"></span><?php lang('User List'); ?></a></li>
                </ul>
            </li>
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle icone" data-toggle="dropdown"><span class="glyphicon glyphicon-envelope"></span><span class="info_icone"><?php echo $calc_inbox = calc_inbox(); ?></span></a>
                
                <style>
				.hMessage {
					width:240px;
					padding:2px;
				}
				.hMessage table{
					margin-bottom:2px;
				}
				.hMessage table tr td{
					border:0px;
					border-bottom:1px solid #F1F1F1;
				}
				.hMessage .table-bordered {
					border: 0px solid #dddddd;
				}
			
				.hMessage .link {
					color:#000;
				}
				.hMessage img.avatar{
					width:40px;
					height:40px;
					float:left;
					margin-right:5px;
					border:1px solid #ccc;
					padding:1px;
				}
				.hMessage span.name{
					font-size:13px;
					font-weight:bold;
					color:#616161;
				}
				.hMessage p{
					font-size:12px;
					width:100%;
					height:10px;
					color:#000;
				}
				.hMessage .alert{
					margin-bottom:2px;
					font-size:13px;
					padding:5px 10px;
					border-top:0px;
				}
				.hMessage .sub_button{
					padding:0px 5px;
					margin-top:3px;
				}
				.hMessage .custom_btn{
					width:49%;
					margin:5px 0px;
				}
				</style>
                <div class="sub-menu table-condensed dropdown-menu hMessage">
                	<div class="sub_button">
                    	<a href="<?php echo site_url('user/new_message'); ?>" class="btn btn-default2 btn-sm btn-block"><span class="glyphicon glyphicon-envelope mr5"></span> <?php lang('New Message'); ?></a>
                        <a href="<?php echo site_url('user/inbox'); ?>" class="custom_btn btn btn-default2 btn-sm pull-left"> <span class="glyphicon glyphicon-log-in mr5"></span> <?php lang('Inbox'); ?></a>
                        <a href="<?php echo site_url('user/outbox'); ?>" class="custom_btn btn btn-default2 btn-sm pull-right"><span class="glyphicon glyphicon-log-out mr5"></span> <?php lang('Outbox'); ?></a>
                        
                    </div>
                	<table class="table table-bordered table-hover">
                    	<?php
						$this->db->where('status', 1);
						$this->db->where_in('type', array('message','reply_message'));
						$this->db->where('inbox_view', '1');
						$this->db->where('receiver_id', get_the_current_user('id'));		
						$this->db->order_by('recent_activity', 'DESC');
						$this->db->limit(5);
						$query = $this->db->get('user_mess')->result_array();
						
						foreach($query as $q):	
						?>
                        <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                        	<td>
                            	<a href="<?php echo site_url('user/inbox/'.$q['id']); ?>" class="link">
                                    <img src="<?php echo base_url($users[$q['sender_id']]['avatar']); ?>" class="avatar" />
                                    <span class="name"><?php echo $users[$q['sender_id']]['surname']; ?></span>
                                    <p><?php echo mb_substr($q['title'],0,30,'utf-8'); ?></p>
                                </a>
                            </td>
                        </tr>
						<?php endforeach; ?>
                    </table>
                    
                    
                </div>
                <?php if(10 < 9): ?>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('user/new_message'); ?>"><span class="glyphicon glyphicon-plus-sign mr9"></span><?php lang('New Message'); ?></a></li>
                    <li><a href="<?php echo site_url('user/inbox'); ?>"><span class="glyphicon glyphicon-log-in mr9"></span><?php lang('Inbox'); ?> (<?php echo $calc_inbox; ?>)</a></li>
                    <li><a href="<?php echo site_url('user/outbox'); ?>"><span class="glyphicon glyphicon-log-out mr9"></span><?php lang('Outbox'); ?></a></li>
                    <?php if(get_the_current_user('role') <= 3): ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('user/bulk_message'); ?>"><span class="glyphicon glyphicon-send mr9"></span><?php lang('Bulk Message'); ?></a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </li>
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle icone" data-toggle="dropdown"><span class="glyphicon glyphicon-globe"></span><span class="info_icone turq"><?php echo $calc_task = calc_task(); ?></span></a>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('user/new_task'); ?>"><span class="glyphicon glyphicon-plus-sign mr9"></span><?php lang('New Task'); ?></a></li>
                    <li><a href="<?php echo site_url('user/task'); ?>"><span class="glyphicon glyphicon-log-in mr9"></span><?php lang('The Tasks'); ?> (<?php echo $calc_task; ?>)</a></li>
                    <li><a href="<?php echo site_url('user/outbound_tasks'); ?>"><span class="glyphicon glyphicon-log-out mr9"></span><?php lang('Outbound Tasks'); ?></a></li>
                </ul>
            </li>
            <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user mr5"></span><?php the_current_user('surname'); ?> <b class="caret"></b></a>
				<ul class="sub-menu dropdown-menu">
					<li><a href="<?php echo site_url('user/profile'); ?>"><span class="glyphicon glyphicon-user mr9"></span><?php lang('My Profile'); ?></a></li>
                    <li><a href="<?php echo site_url('user/logout'); ?>"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Logout'); ?></a></li>
                	<li class="divider"></li>
                    <li><a href="<?php echo site_url('general/about'); ?>"><span class="glyphicon glyphicon-bookmark mr9"></span><?php lang('About us'); ?></a></li>
                </ul>
            </li>
        </ul>
        
        
        
        
        <form class="navbar-form navbar-right hidden-sm" role="search">
            <div class="form-group">
                <input type="text" class="form-control ff1" placeholder="arama...">
            </div>
            <button type="submit" class="btn btn-default ff-1">Ara</button>
        </form>
        
        </div><!-- /.navbar-collapse -->
        </div> <!-- /.container -->
    </nav>
<div class="container bg">


