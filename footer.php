
<div class="row hide-on-print"><div class="twelve columns"><hr /></div></div>

<div class="row hide-on-print">
	<div class="eight columns">
    	<?php if($page_access != false) : ?>
    		<a class="button small secondary" href="<?php url('page'); ?>/settings/page_access.php?page_name=<?php echo $page_name; ?>"><img src="<?php url('theme'); ?>/images/icon/12/key.png" title="<?php lang('Page Access'); ?>" /> <?php lang('Page Access'); ?></a>
        <?php endif; ?>
    </div> <!-- /.eight columns -->
    <div class="four columns text-right">
    <?php lang('Page Load Time'); ?>: <?php echo $page_load_time->stop(); ?>
    </div> <!-- /.four columns -->
</div>  

<p></p>
</div> <!-- /.twelve columns --> </div> <!-- /.row -->
</body>
</html>
