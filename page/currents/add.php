<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('New Current')); ?>

<?php
if(isset($_POST['btn_add']))
{
	$datetime	=	safety_filter($_POST['datetime']);
	$code		=	safety_filter($_POST['code']);
	$name		=	safety_filter($_POST['name']);
	
	if(!get_log($datetime, get_the_current_user('id'), 'New Current', true))
	{
		$current_id = 0;
		$current_id = add_current($code, $name);
		if($current_id > 0)
		{
			add_log($datetime, $current_id, '', '', 'New Current', get_lang('Generated new current card'));	
			go_to_page('current.php?current_id='.$current_id.'');
		}
	}
}
?>

<form name="form1" id="form1" action="" method="POST">
	<div class="row">
    	<div class="six columns">
            <fieldset>
                <legend><?php lang('Current'); ?></legend>

                <label for="code"><?php lang('Current Code'); ?></label>
                <input type="text" name="code" id="code" value="" maxlength="20" minlength="3" class="required" />

                <label for="name"><?php lang('Current Name'); ?></label>
                <input type="text" name="name" id="name" value="" maxlength="20" minlength="3" class="required" />
                	                    
            </fieldset>
            
        </div> <!-- /.six columns -->
        <div class="six columns">
        </div> <!-- /.six columns -->
    </div> <!-- /.row -->
    
    <div class="row">
    	<div class="twelve columns">
        	<input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
        	<input type="submit" name="btn_add" id="btn_add" class="button" value="<?php lang('Add'); ?> &raquo;" />
        </div>
    </div>
</form>

<?php include_once('../../footer.php'); ?>