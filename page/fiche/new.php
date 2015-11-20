<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('New Fiche')); ?>

<?php
if(isset($_GET['input']))		{ $type = 'input'; 		}
else if(isset($_GET['output']))	{ $type = 'output'; 	}
else 							{ alert_box('alert', get_lang('No Type')); exit; }
?>


<?php
if(isset($_POST['btn_next']))
{
	$datetime		=	safety_filter($_POST['datetime']);
	$type			=	safety_filter($_POST['type']);
	$date			=	safety_filter($_POST['date']);
	$current_id		=	safety_filter($_POST['current_id']);
	

	if(!get_log($datetime, get_the_current_user('id'), 'New Fiche', true))
	{
		$fiche_id = add_fiche($type, $date, $current_id);
		if($fiche_id > 0)
		{
			add_log($datetime, '', $current_id, $fiche_id, 'New Fiche', 'Generated new fiche');	
			go_to_page('fiche.php?fiche_id='.$fiche_id.'');
		}
	}
}
?>


<form name="form1" id="form1" action="" method="POST" class="custom">
	<div class="row">
    	<div class="six columns">
        	
            <fieldset>
                <legend><?php lang('Current'); ?></legend>
				
                <div class="row">
                	<div class="six columns">
                    	<label for="type"><?php lang('Type'); ?></label>
                        <select name="type" id="type">
                                <option value="input" <?php if($type=='input'){echo 'selected';} ?> style="width:300px;"><?php lang('INPUT'); ?></option>
                                <option value="output" <?php if($type=='output'){echo 'selected';} ?>><?php lang('OUTPUT'); ?></option>
                        </select>
                    </div>
                    <div class="six columns">
                    	<label for="date"><?php lang('Date'); ?></label>
                        <div class="row collapse">
                            <div class="two mobile-one columns">
                              <span class="prefix"><label for="date" class="has-tip tip-top" title="<?php lang('Date'); ?>"  ><img src="<?php url('theme'); ?>/images/icon/16/calendar_1.png" /></label></span>
                            </div>
                            <div class="ten mobile-three columns">
                              <input type="text" name="date" id="date" class="required date_picker" maxlength="40" minlength="10" value="<?php config('date'); ?>" readonly />
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php box_current_list('current_id', 'current_code'); ?>
                <label for="current_id"><?php lang('Current Code'); ?></label>
                <div class="row collapse">
                    <div class="one mobile-one columns">
                      <span class="prefix"><a href="#" class="has-tip tip-top" title="<?php lang('Current List'); ?>" data-reveal-id="box_current_list" data-animation="fadeAndPop" ><img src="<?php url('theme'); ?>/images/icon/16/list_num.png" /></a></span>
                    </div>
                    <div class="eleven mobile-three columns">
                      <input type="text" name="current_code" id="current_code" value="" maxlength="20" minlength="3" class="required" readonly />
                      <input type="hidden" name="current_id" id="current_id" value="" />
                    </div>
                </div>
                	                    
            </fieldset>
            
        </div> <!-- /.six columns -->
        <div class="six columns">
        </div> <!-- /.six columns -->
    </div> <!-- /.row -->
    
    <div class="row">
    	<div class="twelve columns">
        	<input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
        	<input type="submit" name="btn_next" id="btn_next" class="button" value="<?php lang('Next'); ?> &raquo;" />
        </div>
    </div>
</form>

<?php include_once('../../footer.php'); ?>