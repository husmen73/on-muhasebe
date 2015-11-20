<?php include_once('../../header.php'); ?>

<div class="row">
	<div class="four columns">
    	<h4><?php lang('User'); ?></h4>
        <ul class="square">
        	<li><a href="user.php#add_user">add_user()</a></li>
            <li><a href="user.php#update_user">update_user()</a></li>
            <li><a href="user.php#delete_user">delete_user()</a></li>
            <li><a href="user.php#get_user">get_user()</a></li>
            <li><a href="user.php#user">user()</a></li>
            <li><a href="user.php#get_the_user">get_the_user()</a></li>
            <li><a href="user.php#the_user">the_user()</a></li>
            <li><a href="user.php#get_the_current_user">get_the_current_user()</a></li>
            <li><a href="user.php#the_current_user">the_current_user()</a></li>
        </ul>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    	
        <!-- add_user() -->
        <h4 id="add_user">add_user()</h4>
    	<p>If you use to add_user() function , you can add new users.</p>
        <p><code>add_user($user_name, $password, $password_again, $level)</code></p>
        <p>
        	<ul class="circle">
            	<li><strong>$user_name</strong>	: new user name</li>
            	<li><strong>$password</strong>	: new password</li>
            	<li><strong>$password_again</strong>	: new password again</li>
            	<li><strong>$level</strong>	: user level [1,2,3,4,5]</li>
           </ul>
        </p>
        <p>If this function is run correctly it rotates to the ID of the user which latest addition. But if it encounters any errors, the result is "<strong>false</strong>"</p>
        
        <hr />
        <!-- update_user() -->
        <h4 id="update_user">update_user()</h4>
    	<p>You can update to a registered user, using to update_user() function.</p>
        <p><code>update_user($user_id, $status, $user_name, $password, $level)</code></p>
        <p>
        	<ul class="circle">
            	<li><strong>$user_id</strong>	: user ID</li>
                <li><strong>$status</strong>	: user status [publish, delete]</li>
            	<li><strong>$user_name</strong>	: user name</li>
            	<li><strong>$password</strong>	: password</li>
            	<li><strong>$level</strong>	: user level [1,2,3,4,5]</li>
           </ul>
        </p>
        <p>If this fucntion is run correctly, the result is "<strong>true</strong>". If it encounters any errors, the result is "<strong>false</strong>". You can not change your passport if you leave empty to the passport change area. </p>
        
        
        <hr />
        <!-- delete_user() -->
        <h4 id="delete_user">delete_user()</h4>
    	<p>delete_user () function can delete a registered user.</p>
        <p><code>delete_user($user_id)</code></p>
        <p>
        	<ul class="circle">
            	<li><strong>$user_id</strong>	: user ID</li>
           </ul>
        </p>
        <p>This function is operating correctly, "<strong>true</strong>" is returned.</p>
        
        
        <hr />
        <!-- get_user() -->
        <h4 id="get_user">get_user()</h4>
    	<p>You can rotate to the database information of a registered user, using get_user() function. This function rotates to the results to query the values ​​as parameters in the database.</p>
        <p><code>get_user($user_id, $value)</code></p>
        <p>
        	<ul class="circle">
            	<li><strong>$user_id</strong>	: user ID</li>
                <li><strong>$value</strong>	: database / table column name</li>
           </ul>
        </p>
        <p>You can rotate the downstairs values.</p>
        <p>
        $users['id']<br />
		$users['status']<br />
		$users['user_name']<br />
		$users['password']<br />
		$users['level']<br />
        </p>
        
        
        <hr />
        <!-- user() -->
        <h4 id="user">user()</h4>
    	<p>user() function <a href="#get_user">get_user()</a> function then prints the result returned.</p>
        
        
        <hr />
        <!-- get_the_user() -->
        <h4 id="get_the_user">get_the_user()</h4>
    	<p>The function get_the_user() shows the info of the user within the process. In order for this function to run properly, one of the values <strong>$_GET['user_id']</strong> or <strong>$_POST['user_id']</strong> must be active. .</p>
        <p><code>get_the_user($value)</code></p>
        <p>
        	<ul class="circle">
                <li><strong>$value</strong>	: database / table column name</li>
           </ul>
        </p>
        <p>You can rotate the downstairs values.</p>
        <p>
        $users['id']<br />
		$users['status']<br />
		$users['user_name']<br />
		$users['password']<br />
		$users['level']<br />
        </p>
        
        
        <hr />
        <!-- the_user() -->
        <h4 id="the_user">the_user()</h4>
    	<p>the_user() function <a href="#get_the_user">get_the_user()</a> function then prints the result returned.</p>
        
        
        <hr />
        <!-- get_the_user() -->
        <h4 id="get_the_current_user">get_the_current_user()</h4>
    	<p>get the _current_user() function returns the logged in user information.</p>
        <p><code>get_the_current_user($value)</code></p>
        <p>
        	<ul class="circle">
                <li><strong>$value</strong>	: database / table column name</li>
           </ul>
        </p>
        <p>You can rotate the downstairs values.</p>
        <p>
        $users['id']<br />
		$users['status']<br />
		$users['user_name']<br />
		$users['password']<br />
		$users['level']<br />
        </p>

        
        <hr />
        <!-- the_user() -->
        <h4 id="the_current_user">the_current_user()</h4>
    	<p>The function the_current_user() displays the result on the screen that returns from the function <a href="#get_the_current_user">get_the_current_user()</a>.</p>
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>