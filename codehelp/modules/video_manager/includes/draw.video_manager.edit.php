<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
	global $AI;
?>
<script type="text/javascript" language="javascript">
	<!--
	function trim( str )
	{
	   str = str.replace(/^\s+/, '');
	   str = str.replace(/\s+$/, '');
	   return str;
	}
	//check if an object has a value
	function check_share_links_obj(obj, msg)
	{
		if(trim(obj.value) == "")
		{
			alert( msg );
			obj.focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	//check if an objects value matches a regular expression
	function regex_share_links_obj(obj, reg, msg)
	{
		if( !trim(obj.value).match(reg) )
		{
			alert( msg );
			obj.focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	function check_video_manager_form(frm)
	{
		//todo: uncomment required fields...
		//note: these requirments need to be reinforced in php function validate_write()
		//You may also use the RegEx Checker
    //Example: if(!regex_share_links_obj(frm.email, /^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3}|info|arpa|aero|coop|name|museum)$/i, "Please enter a valid value for: Email"))return false;
		//if(!check_share_links_obj(frm.id, "Please enter a valid value for: Id"))return false;
		//if(!check_share_links_obj(frm.name, "Please enter a valid value for: Name"))return false;
		//if(!check_share_links_obj(frm.description, "Please enter a valid value for: Description"))return false;
		//if(!check_share_links_obj(frm.url, "Please enter a valid value for: Url"))return false;
		//if(!check_share_links_obj(frm.img_url, "Please enter a valid value for: Img Url"))return false;

		return true;
	}
	//-->
</script>


<div class="te_edit share_links_edit">
	<form id="video_manager_form" enctype="multipart/form-data" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_video_manager_form( this );" >
		<fieldset class="te">
			<legend class="te">
				<a class="te" href="<?php echo ( $this->te_permit['table'] ? htmlspecialchars($this->url('te_mode=table')) :'#'); ?>"><?php echo htmlspecialchars( $this->_tableTitle ); ?></a>
				:
				<?php
					switch( $this->te_mode )
					{
						case 'copy': echo 'Copy'; break;
						case 'insert': echo 'New'; break;
						default: echo 'Edit'; break;
					}
				?>
			</legend>

			<?php if( $this->write_error_msg != '' ){ ?><div class="error"><?php echo htmlspecialchars( $this->write_error_msg ); ?></div><?php }

			if($this->te_mode == 'insert'){
				echo '<input type="hidden" name="share_link_id" value="'.@$_GET['te_share_link_id'].'" />';
			}

			?>

			<dl class="te">
				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">ID</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id'; ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<?php $this->draw_input_field( 'id', $this->db['id'], 'edit', 'id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'title' ); ?> title" >
					<label class="te <?php echo $this->get_field_type( 'title' ); ?> title" for="title">Title</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_title'; ?>" class="te <?php echo $this->get_field_type( 'title' ); ?> title" >
					<?php $this->draw_input_field( 'title', $this->db['title'], 'edit', 'title' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'description' ); ?> description" >
					<label class="te <?php echo $this->get_field_type( 'description' ); ?> description" for="description">Description</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_url'; ?>" class="te <?php echo $this->get_field_type( 'description' ); ?> description" >
					<?php $this->draw_input_field( 'description', $this->db['description'], 'edit', 'description' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'type' ); ?> type" >
					<label class="te <?php echo $this->get_field_type( 'type' ); ?> type" for="type">Type</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_type'; ?>" class="te <?php echo $this->get_field_type( 'type' ); ?> type" >
					<?php $this->draw_input_field( 'type', $this->db['type'], 'edit', 'type' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'file' ); ?> file" >
					<label class="te <?php echo $this->get_field_type( 'file' ); ?> file" for="file">File</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_file'; ?>" class="te <?php echo $this->get_field_type( 'file' ); ?> file" >
					<?php $this->draw_input_field( 'file', $this->db['file'], 'edit', 'file' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'status' ); ?> status" >
					<label class="te <?php echo $this->get_field_type( 'status' ); ?> status" for="status">Make this video active?
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_status'; ?>" class="te <?php echo $this->get_field_type( 'status' ); ?> status" >
					<?php $this->draw_input_field( 'status', $this->db['status'], 'edit', 'status' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'priority' ); ?> priority" >
					<label class="te <?php echo $this->get_field_type( 'priority' ); ?> priority" for="priority">Priority</label>
				</dt>

				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_priority'; ?>" class="te <?php echo $this->get_field_type( 'priority' ); ?> priority" >
					<?php $this->draw_input_field( 'priority', $this->db['priority'], 'edit', 'priority' ); ?>
				</dd>

				<dt class="te <?php echo $this->get_field_type( 'live_commentry	' ); ?> live_commentry	" >
					<label class="te <?php echo $this->get_field_type( 'live_commentry' ); ?> live_commentry" for="live_commentry">Live Commentry</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_live_commentry'; ?>" class="te <?php echo $this->get_field_type( 'live_commentry' ); ?> live_commentry" >
					<?php $this->draw_input_field( 'live_commentry', $this->db['live_commentry'], 'edit', 'live_commentry' ); ?>
				</dd>



			</dl>

			<div class="te_buttons">
			<input class="te te_buttons save_button" type="submit" name="btnSave" value="Save" />
			<?php
				if( $this->is_valid_key( $this->te_key ) && $this->_default_mode_after_save != '' && $this->te_permit[ $this->_default_mode_after_save ] )
				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_default_mode_after_save . '&te_key=' . $this->te_key )); ?>';" /><?php
				}
				elseif( $this->te_permit[ $this->_te_modeDefault ] )

				{
					?><input class="te te_buttons cancle_button" type="button" name="btnCancel" value="Cancel" onclick="document.location='<?php echo htmlspecialchars($this->url( 'te_mode=' . $this->_te_modeDefault )); ?>';" /><?php
				}
			?>
			</div>

		</fieldset>
	</form>
</div>
