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
	function check_share_links_form(frm)
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
	<form id="share_links_form" enctype="multipart/form-data" class="te" method="post" action="<?php echo htmlspecialchars($postURL); ?>" onsubmit="return check_share_links_form( this );" >
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

			<?php if( $this->write_error_msg != '' ){ ?><div class="error"><?php echo htmlspecialchars( $this->write_error_msg ); ?></div><?php } ?>

			<dl class="te">
				<dt class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<label class="te <?php echo $this->get_field_type( 'id' ); ?> id" for="id">ID</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_id'; ?>" class="te <?php echo $this->get_field_type( 'id' ); ?> id" >
					<?php $this->draw_input_field( 'id', $this->db['id'], 'edit', 'id' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
					<label class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" for="sort_order">Sort Order</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_sort_order'; ?>" class="te <?php echo $this->get_field_type( 'sort_order' ); ?> sort_order" >
					<?php $this->draw_input_field( 'sort_order', $this->db['sort_order'], 'edit', 'sort_order' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<label class="te <?php echo $this->get_field_type( 'name' ); ?> name" for="name">Name</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_name'; ?>" class="te <?php echo $this->get_field_type( 'name' ); ?> name" >
					<?php $this->draw_input_field( 'name', $this->db['name'], 'edit', 'name' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'description' ); ?> description" >
					<label class="te <?php echo $this->get_field_type( 'description' ); ?> description" for="description">Description</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_description'; ?>" class="te <?php echo $this->get_field_type( 'description' ); ?> description" >
					<?php $this->draw_input_field( 'description', $this->db['description'], 'edit', 'description' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'url' ); ?> url" >
					<label class="te <?php echo $this->get_field_type( 'url' ); ?> url" for="url">URL</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_url'; ?>" class="te <?php echo $this->get_field_type( 'url' ); ?> url" >
					<?php $this->draw_input_field( 'url', $this->db['url'], 'edit', 'url' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'file_name' ); ?> file_name" >
					<label class="te <?php echo $this->get_field_type( 'file_name' ); ?> file_name" for="file_name">File Name</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_file_name'; ?>" class="te <?php echo $this->get_field_type( 'file_name' ); ?> file_name" >
					<?php $this->draw_input_field( 'file_name', $this->db['file_name'], 'edit', 'file_name' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'img_url' ); ?> img_url" >
					<label class="te <?php echo $this->get_field_type( 'img_url' ); ?> img_url" for="img_url">Image Url</label>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_img_url'; ?>" class="te <?php echo $this->get_field_type( 'img_url' ); ?> img_url" >
					<?php $this->draw_input_field( 'img_url', $this->db['img_url'], 'edit', 'img_url' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'requires_success_line' ); ?> requires_success_line" >
					<label class="te <?php echo $this->get_field_type( 'requires_success_line' ); ?> requires_success_line" for="requires_success_line">Requires Success Line</label> <small>(Do not display if user is not in success line)</small>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_requires_success_line'; ?>" class="te <?php echo $this->get_field_type( 'requires_success_line' ); ?> requires_success_line" >
					<?php $this->draw_input_field( 'requires_success_line', $this->db['requires_success_line'], 'edit', 'requires_success_line' ); ?>
				</dd>
				<dt class="te <?php echo $this->get_field_type( 'postal_parrot_var_name' ); ?> postal_parrot_var_name" >
					<label class="te <?php echo $this->get_field_type( 'postal_parrot_var_name' ); ?> postal_parrot_var_name" for="postal_parrot_var_name">Postal Parrot Var Name <small>(Merge Code)</small>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_postal_parrot_var_name'; ?>" class="te <?php echo $this->get_field_type( 'postal_parrot_var_name' ); ?> postal_parrot_var_name" >
					<?php $this->draw_input_field( 'postal_parrot_var_name', $this->db['postal_parrot_var_name'], 'edit', 'postal_parrot_var_name' ); ?>
				</dd>

				<?php $this->draw_input_field( 'page_name_source', $this->db['page_name_source'], 'edit', 'page_name_source' ); ?>

				<dt class="te <?php echo $this->get_field_type( 'is_pixel' ); ?> is_pixel" >
					<label class="te <?php echo $this->get_field_type( 'is_pixel' ); ?> is_pixel" for="is_pixel">Enable Pixel?
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . 'is_pixel'; ?>" class="te <?php echo $this->get_field_type( 'is_pixel' ); ?> is_pixel" >
					<?php $this->draw_input_field( 'is_pixel', $this->db['is_pixel'], 'edit', 'is_pixel' ); ?>
				</dd>
				
				<?php if($AI->get_access_group_perm('Website Developers')) { ?>
				<dt class="te <?php echo $this->get_field_type( 'is_public' ); ?> is_public" >
					<label class="te <?php echo $this->get_field_type( 'is_public' ); ?> is_public" for="is_public">Is Public?<small>(Should Non-Webdev see this entry)</small>
				</dt>
				<dd id="<?php echo 'value_field_container_' . $this->te_key . '_is_public'; ?>" class="te <?php echo $this->get_field_type( 'is_public' ); ?> is_public" >
					<?php $this->draw_input_field( 'is_public', $this->db['is_public'], 'edit', 'is_public' ); ?>
				</dd>
				<?php } ?>

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
