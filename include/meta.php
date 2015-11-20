<?php
/* ----------------------------------------------
	META
---------------------------------------------- */

	/* ----- ADD META ----- */
	function add_meta($ref_id, $title, $meta_key, $meta_value)
	{
		global $database;
		$ref_id	= safety_filter($ref_id);
		$title 	= safety_filter($title);
		$key 	= safety_filter($meta_key);
		$value 	= safety_filter($meta_value);
		
		mysql_query("INSERT INTO $database->meta
		(ref_id, title, meta_key, meta_value)
		VALUES
		('$ref_id', '$title', '$meta_key', '$meta_value')");
		if(mysql_affected_rows() > 0)
		{
			return true;	
		}
		else
		{
			echo mysql_error();
			return false;	
		}
	}
	
	
	/* ----- UPDATE META ----- */
	function update_meta($id, $ref_id, $title, $meta_key, $meta_value)
	{
		global $database;
		$id				= trim(mysql_real_escape_string(strip_tags($id)));
		$ref_id			= trim(mysql_real_escape_string(strip_tags($ref_id)));
		$title 			= trim(mysql_real_escape_string(strip_tags($title)));
		$meta_key 		= trim(mysql_real_escape_string(strip_tags($meta_key)));
		$meta_value 	= trim(mysql_real_escape_string(strip_tags($meta_value)));
		
		if($id == '')
		{
			if(mysql_num_rows(mysql_query("SELECT * FROM $database->meta WHERE ref_id='$ref_id' AND title='$title' AND meta_key='$meta_key'")) > 0)
			{
				$update = mysql_query("UPDATE $database->meta SET
					ref_id='$ref_id',
					title='$title',
					meta_key='$meta_key',
					meta_value='$meta_value'
					WHERE
					ref_id='$ref_id' AND title='$title' AND meta_key='$meta_key'");
				if(mysql_affected_rows() > 0){ return true;	}
				else{ if($update) { return true;	} else { return false;	}	}
				
				
			}
			else
			{
				return add_meta($ref_id, $title, $meta_key, $meta_value);	
			}
		}
		else
		{
			$update = mysql_query("UPDATE $database->meta SET
				ref_id='$ref_id',
				title='$title',
				meta_key='$meta_key',
				meta_value='$meta_value'
				WHERE id='$id'");
			if(mysql_affected_rows() > 0){ return true;	}
				else{ if($update) { return true;	} else { return false;	}	}
		}
	}
	
	/* ----- DELETE META ----- */
	function delete_meta($id, $title, $meta_key)
	{
		global $database;
		$id				= trim(mysql_real_escape_string(strip_tags($id)));
		$title 			= trim(mysql_real_escape_string(strip_tags($title)));
		$meta_key 		= trim(mysql_real_escape_string(strip_tags($meta_key)));
		
		if($id == '')
		{ 
			mysql_query("DELETE FROM $database->meta WHERE title='$title' AND meta_key='$meta_key'");	
			if(mysql_affected_rows() > 0){ return true;	} else { return false;	}
		}
		else
		{ 
			mysql_query("DELETE FROM $database->meta WHERE id='$id'");	
			if(mysql_affected_rows() > 0){ return true;	} else { return false;	}
		}
	}
	
	/* ----- GET META ----- */
	function get_meta($id, $ref_id, $title, $meta_key)
	{
		global $database;
		$id				= safety_filter($id);
		$ref_id 		= safety_filter($ref_id);
		$title 			= safety_filter($title);
		$meta_key 		= safety_filter($meta_key);
		$meta['meta_value'] = '';
		
		if($id == ''){ $query = mysql_query("SELECT * FROM $database->meta WHERE ref_id='$ref_id' AND title='$title' AND meta_key='$meta_key'");	}
		else{ $query = mysql_query("SELECT * FROM $database->meta WHERE id='$id'");	}
		while($list = mysql_fetch_assoc($query))
		{
			$meta['meta_value'] = $list['meta_value'];
		}
		
		return $meta['meta_value'];
	}

?>