<legend class="ff-1"><span class="glyphicon glyphicon-log-out mr9"></span><?php lang('Outbox'); ?></legend>
<?php $users = get_user_array(); ?>


<div class="row">
	<div class="col-md-12">
    	<table class="table table-hover table-bordered table-condensed table-striped dataTable">
        	<thead>
            	<tr>
                	<th></th>
                    <th><?php lang('Date'); ?></th>
                    <th><?php lang('Sender'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
			$this->db->where('status', 1);
			$this->db->where_in('type', array('message'));
			$this->db->where('inbox_view', '1');
			$this->db->where('sender_id', get_the_current_user('id'));		
			$this->db->order_by('recent_activity', 'DESC');
			$query = $this->db->get('user_mess')->result_array();
			
			foreach($query as $q):	
			?>
                <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                    <td></td>
                    <td><?php echo substr($q['date'],0,16); ?></td>
                    <td><?php echo $users[$q['sender_id']]['name'].' '.$users[$q['sender_id']]['surname']; ?></a></td>
                    <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo $q['title']; ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
