<?php include_once('../../header.php'); ?>

<div class="row">
	<div class="four columns">
    	<h4><?php lang('Global'); ?></h4>
        <ul class="square">
        	<li><a href="global.php#get_url">get_url()</a></li>
            <li><a href="global.php#url">url()</a></li>
            <li><a href="global.php#safety_filter">safety_filter()</a></li>
        </ul>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    	
        <!-- get_url() -->
        <h4 id="get_url">get_url()</h4>
    	<p>You can get the system URL info by using the function get_url(). This function returns the system URL address. Besides, it adds the parameter that it took to the end of the URL.</p>
        <p><code>
        echo get_url(''); <br />
        // http://localhost/management
        <br /><br />
        echo get_url('theme'); <br />
        // http://localhost/management/theme
        </code></p>
        
        
        <hr />
        <!-- url() -->
        <h4 id="get_url">url()</h4>
    	<p>url() function <a href="#get_url">get_url()</a> function then prints the result returned.</p>
        <p><code>
        url(''); <br />
        // http://localhost/management
        <br /><br />
        url('theme'); <br />
        // http://localhost/management/theme
        </code></p>
        
        
        <hr />
        <!-- url() -->
        <h4 id="get_url">safety_filter()</h4>
    	<p>The function safety_filter() checks the values that come from outside. This is a security function.</p>
        <p><code>
       	$user_name = safety_filter($_POST['user_name']);
        </code></p>
        
        
        
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>