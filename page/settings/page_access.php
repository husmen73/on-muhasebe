<?php include_once('../../header.php'); ?>
<?php if(! isset($_GET['page_name'])) { alert_box('alert', get_lang('No Page Name'));	exit;	} ?>
<?php $get_page_name = safety_filter($_GET['page_name']); ?>
<div class="row">
	<div class="six columns">
        
        <?php
		if(isset($_POST['btn_update']))
		{
			$level	= safety_filter($_POST['level']);
			
			if(update_meta('', '', 'page_access', $get_page_name, $level))
			{
				echo '<script> window.location = "?page_name='.$get_page_name.'&success"; </script>';
			}
		}
		if(isset($_GET['success']))
		{
			alert_box('success', get_lang('Successful'));	
		}
		?>
        
        <form name="form_login" id="form_login" action="?page_name=<?php echo $get_page_name; ?>" method="POST">
            <fieldset>
                <legend><?php lang('Update Page Access'); ?></legend>
                
                <?php
				$page_level	=	get_meta('', '', 'page_access', $get_page_name);
				?>
                
                <div class="row">
                	<div class="twelve columns">
                    	<h5><?php echo $get_page_name; ?></h5>
                    </div>
                </div>
                <div class="row">
                	<div class="six columns">
                    <label for="level"><?php lang('Level'); ?></label>
                    <select name="level" id="level">
                        <option value="1" <?php if($page_level == '1') { echo 'selected';	}	?>>1</option>
                        <option value="2" <?php if($page_level == '2') { echo 'selected';	}	?>>2</option>
                        <option value="3" <?php if($page_level == '3') { echo 'selected';	}	?>>3</option>
                        <option value="4" <?php if($page_level == '4') { echo 'selected';	}	?>>4</option>
                        <option value="5" <?php if($page_level == '5') { echo 'selected';	}	?>>5</option>
                    </select>
                    </div> 
                </div>
                
                
                <p></p> 
                
                <input type="submit" name="btn_update" id="btn_update" class="button" value="<?php lang('Update'); ?>" />
              
				<p></p>  
              
            </fieldset>
        </form>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    
    	
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>