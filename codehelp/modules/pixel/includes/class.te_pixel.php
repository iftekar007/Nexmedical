<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id

	require_once( ai_cascadepath( 'includes/core/classes/tableedit_base.php' ) );

	class C_te_share_links extends C_tableedit_base
	{
		var $upload_dir = 'uploads/share_links/';
		
		//(configure) parameters
		var $_dbTableName = 'share_links';
		var $_keyFieldName = 'id';
		var $_numeric_key = true;
		var $unique_id = 'share_links';
		var $_tableTitle = ''; //default in constructor
		var $draw_qsearch = false;
		var $use_te_fields_system = false; // need to remove DB & DESC array definitions. Consider defaulting all draw files to the default tableedit/ draw files
		var $draw_table_menu_not_buttons = false; //displays TE_field options, multidelete, etc under one button
		var $draw_enum_as_select = true;	//don't draw radio buttons
		var $init_backbone_js = true;
		//(configure) Draw Code
		var $view_include_file = 'includes/modules/share_links/includes/draw.share_links.view.php';
		var $edit_include_file = 'includes/modules/share_links/includes/draw.share_links.edit.php';
		var $table_include_file = 'includes/modules/share_links/includes/draw.share_links.table.php';
		var $qsearch_include_file = 'includes/modules/share_links/includes/draw.share_links.qsearch.php';
		var $asearch_include_file = 'includes/modules/share_links/includes/draw.share_links.asearch.php';
		var $viewnav_include_file = 'includes/modules/share_links/includes/draw.share_links.viewnav.php';
		var $noresults_include_file = 'includes/modules/share_links/includes/draw.share_links.noresults.php';
		var $ajax_include_file = 'includes/modules/share_links/includes/handler.share_links.ajax.php';

		//(configure) ob stands for "order by" members
		var $_obFieldDefault = ''; //default in constructor
		var $_obDirDefault = "ASC";
		var $_pgSizeDefault = 20;
		var $_te_modeDefault = 'table';
		var $_default_mode_after_save = 'table';
		var $_draw_paging_for_more_than_n_results = 2;
		var $_max_results_2_select_pg_num = 200; //0 to disable
		var $_paging_size_options = array( 5, 10, 20, 50, 100, 200 ); //empty to disable
		var $_unit_label = 'Results';
		var $_table_controls_side = 'left'; // ( left, right )

		// Drag-n-Drop jQuery Sorting
		var $sort_index_field = 'sort_order';  // If not blank, sorting is enabled using this field as the index
		var $sort_drag_handle_class = ''; // Optional class name of an element to use as handle for sorting (rather than entire row)

		function C_te_share_links( $param_dbWhere = '' )
		{
			$this->dbWhere = $param_dbWhere;
			
			//INITIALIZE DATABASE VARS
			//$this->db;

			//INITIALIZE SEARCH VARS
			//these should NOT conflict with database fields above
			$this->search_vars['example_of_a_special_search_var'] = '';

			//INITIALIZE DATABASE DESCRIPTION
			//$this->desc;

			//CALL PARENT CLASS CONSTRUCTOR ( creates permissions "$this->perm", etc... )
			parent::C_tableedit_base();

			//SPECIFY MODES ALLOWED FOR INLINE-EDITIBLE FIELDS
			//the value may be 'all', 'table', 'view', or 'none'
			$this->inline_edit_db_field['id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['name'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['description'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['url'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['img_url'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['requires_success_line'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['sort_order'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['is_public'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['drip_id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );
			$this->inline_edit_db_field['template_id'] = 'none';//( $this->perm->get('ajax_cmd_inline_edit') ? 'all' : 'none' );

			//Don't inline edit the primary key
			$this->inline_edit_db_field[ $this->_keyFieldName ] = 'none';
			
			$this->te_permit['insert_share_link'] = $this->perm->get('insert_share_link');
			$this->te_permit['adminstrate'] = $this->perm->get('administrate');
			$this->te_permit['landing_page_manager'] = $this->perm->get('landing_page_manager');
		}

		function validate_write()
		{
			global $AI;
			
			//OCCURS BEFORE DATABASE INSERT OR UPDATE ( te_modes : insert, copy, update, ajax )

			//write errors occur if $this->write_error_msg != '' ( this will allow the user to modify their input )
			$this->write_error_msg = '';

			if( $this->_numeric_key || ( $this->te_mode != 'update' && $this->te_mode != 'insert' ) )
			{
				$this->writable_db_field[ $this->_keyFieldName ] = false;  //don't allow the primary key to be overwritten
			}
			else
			{
				$this->writable_db_field[ $this->_keyFieldName ] = true;
			}
			
			if(isset($_POST['url_sub_2']) && empty($_POST['url_sub_2'])) {
				$this->write_error_msg = 'URL must not be blank!'; return false;
			}
			
			// Sanitize URL
			if(isset($_POST['url_sub_2'])) {
				$this->db['url'] = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $this->db['url']);
			}
			
			$count = (int) db_lookup_scalar("SELECT count(*) FROM share_links WHERE id <> '" . (int) db_in($this->db['id']) . "' AND url = '" . db_in($this->db['url']) . "'; ");
			if(!empty($count)) {
				$this->write_error_msg = 'URL not available'; return false;
			}
			
			// Create a new drip campaign, only on insert of copy
			if( $this->te_mode == 'insert' || $this->te_mode == 'copy' ) {
				// Define postal parrot client
				require_once(ai_cascadepath('includes/plugins/postalparrot_client/class.postalparrot_client.php'));
				$pp = new C_postalparrot_client();

				$users_definition_exploded = explode("/",$this->db['url']);
				$users_definition = $users_definition_exploded[count($users_definition_exploded)-1]; 

				$settings = unserialize(@$AI->MODS_INDEX['landing_pages']['raw_settings']);
				$lp_page_name = (isset($settings['_lp_page_name']) ? $settings['_lp_page_name'] : "/l/");

				$list_name = "Drip Campaign  -> " . str_replace($lp_page_name,"",$users_definition);
				$response = $pp->add_drip_campaign($list_name);
				
				if(isset($response[0]['drip_id'])) {
					$this->writable_db_field['drip_id'] = true;
					$this->db['drip_id'] = $response[0]['drip_id'];
				}
				
				// Set page source
				$this->writable_db_field['page_name_source'] = true;
				$this->db['page_name_source'] = db_in(AI_PHP_SELF);
				
			}
			
			if( isset( $_FILES['file_upload']['name'] ) && trim( $_FILES['file_upload']['name'] ) != '' )
			{
				$fname = util_cleanup_string( stripslashes($_FILES['file_upload']['name']), '_', 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.' );
				if( file_exists( $this->upload_dir.$fname ) )
				{
					$this->db['file_name'] = $fname;
					//$this->write_error_msg = 'That filename already exists, please rename your file before uploading.';
				}
			}

			
			
			// Automatically generate the sorting index
			if( $this->te_mode == 'insert' || $this->te_mode == 'copy' ) {
				if(trim($this->sort_index_field) != '') {
					$this->writable_db_field[$this->sort_index_field] = true;
					$this->db[$this->sort_index_field] = floor($this->get_max_sort_index()) + 1;
				}
			}
		}

		function finalize_write()
		{
			
			require_once( ai_cascadepath('includes/core/classes/file.php') );
			$f = new C_file();

			
			if( isset( $_FILES['file_upload'] ) && $f->receive('file_upload') )
			{
				$fname = util_cleanup_string( $f->name, '_', 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-' );
				// chmod( $this->upload_dir . $fname, 0644 );
				
				if($f->make_dir( $this->upload_dir ) && $f->save(  $this->upload_dir.$fname )) {
					$AI->db->Update('share_links', array( 'file_name' => $fname ), "id=" . (int)db_in( $this->te_key ) );
				}
				else {
					$this->write_error_msg = "File not saved. " . $f->errorText;
				}
			}
			
			//OCCURS AFTER SUCCESSFUL DATABASE INSERT OR UPDATE ( te_modes : insert, copy, update, ajax )
			$ai_sid_key = ai_sid_keygen();
			$ai_sid = ai_sid_save_sessionid( $ai_sid_key );
			$core_set = (isset($_SESSION['using_ai_core']) && $_SESSION['using_ai_core']!='default')? '&ai_core='.$_SESSION['using_ai_core']:'';

			if ( isset($this->gets['te_row']) && is_numeric($this->gets['te_row']) )
			{
				$this->_row_i = (int) $this->gets['te_row'];
			}
			
			if(!empty($this->db['template_id'])) {
				$value = self::interpret_url_v2($this->db['url'],$this->db['domain_id'],$this->db['sub_domain_id']);
				util_redirect($value . "?edit=true&te_row=".$this->_row_i."&ai_sid=".$ai_sid."&ai_sid_key=".$ai_sid_key.$core_set);
			}
		}

		function validate_delete( $delKey )
		{
			//BEFORE DELETE -- return false to abort delete
			return true;
		}

		function finalize_delete( $delKey )
		{
			//AFTER DELETE
		}

		function calcSqlQuery_ASearch()
		{
			$asearch_sql = '';

			//ADD SEARCHES FOR DB FIELDS
			if( $this->search_vars['id'] != '' ){ $asearch_sql .= "AND this.id = '" . db_in( $this->search_vars['id'] ) . "' "; }
			if( $this->search_vars['name'] != '' ){ $asearch_sql .= "AND this.name LIKE '%" . db_in( $this->search_vars['name'] ) . "%' "; }
			if( $this->search_vars['description'] != '' ){ $asearch_sql .= "AND this.description LIKE '%" . db_in( $this->search_vars['description'] ) . "%' "; }
			if( $this->search_vars['url'] != '' ){ $asearch_sql .= "AND this.url LIKE '%" . db_in( $this->search_vars['url'] ) . "%' "; }
			if( $this->search_vars['img_url'] != '' ){ $asearch_sql .= "AND this.img_url LIKE '%" . db_in( $this->search_vars['img_url'] ) . "%' "; }
			if( $this->search_vars['requires_success_line'] != '' ){ $asearch_sql .= "AND this.requires_success_line = '" . db_in( $this->search_vars['img_url'] ) . "' "; }
			if( $this->search_vars['postal_parrot_var_name'] != '' ){ $asearch_sql .= "AND this.postal_parrot_var_name = '" . db_in( $this->search_vars['postal_parrot_var_name'] ) . "' "; }
			if( $this->search_vars['sort_order'] != '' ){ $asearch_sql .= "AND this.sort_order = '" . db_in( $this->search_vars['img_url'] ) . "' "; }
			if( $this->search_vars['is_public'] != '' ){ $asearch_sql .= "AND this.is_public = '" . db_in( $this->search_vars['img_url'] ) . "' "; }

			//example: if( $this->search_vars['example_of_a_special_search_var'] != '' ){ $asearch_sql .= "AND example_of_a_special_search_var LIKE '%" . db_in( $this->search_vars['example_of_a_special_search_var'] ) . "%' "; }

			return $asearch_sql;
		}

		/**
		 * DRAW INPUT FIELDS
		 * $mode : asearch, edit, inline
		 * $element_id : this will default to $fieldname if left blank
		 */
		function draw_input_field( $fieldname, $value, $mode, $element_id = '' )
		{
			global $AI;
			if( $element_id == '' ){ $element_id = $fieldname; }
			switch( $fieldname )
			{
				//DRAW THE INPUT FIELD BASED UPON THE DATABASE'S DESCRIBE RESULTS
				case 'description':
				{
					if ( $mode == 'asearch' )
					{
						echo '<input type="text" id="' . $element_id . '" name="' . $fieldname . '" value="' . h($value) . '" />';
					}
					else
					{
						echo '<textarea id="' . $element_id . '" name="' . $fieldname . '" cols="100" rows="5">' . h($value) . '</textarea>';
					}
				}
				break;

				case 'url':
				{
					if((empty($this->db['template_id']) && @$_GET['te_mode'] == "insert") || !empty($this->db['template_id']))
					{
						
						$is_admin = ($AI->get_access_group_perm('Administrators') || $AI->perm->is_user_in_group('Administrators', '', '', $AI->user->userID) || $AI->get_access_group_perm('Website Developers'));

						$users_definition_exploded = explode("/",$value);
						$users_definition = $users_definition_exploded[count($users_definition_exploded)-1]; 
						
						$settings = unserialize(@$AI->MODS_INDEX['landing_pages']['raw_settings']);
						$lp_page_name = (isset($settings['_lp_page_name']) ? $settings['_lp_page_name'] : "/l/");
						?>
						<table width="1000">
							<tr>
								<td align="right" style="text-align: right" valign="middle">Name Your Landing Page</td>
								<td width="10"></td>
								<td align="left" style="text-align: left">
									<input type="text" name="url_sub_1" id="url_sub_1" onkeyup="update_url()" autocomplete="off" value="<?=$users_definition?>" />
								</td>
							</tr>
							<?
								$domain_html = "";
								$count_domain_html = 0;
								$domains = $AI->db->getAll("SELECT * FROM `multiple_domains` WHERE owner_id = 0 OR owner_id = " . (int) db_in($AI->user->userID) . ";");
								
								if(!empty($domains)) {
									$domain_html .= '<td align="right" style="text-align: right" valign="middle">Select a Domain</td>';
									$domain_html .= '<td width="10"></td>';
									$domain_html .= '<td align="left" style="text-align: left">';
									
									$domain_html .= '<select class="span14" name="url_sub_2" id="url_sub_2" onchange="update_url()">';
									foreach($domains as $d) {
										if(strpos($d['http_url'], "*.") !== false) {
											$subdomains = $AI->db->getAll("SELECT * FROM `multiple_domains_sub` WHERE owner_id = " . (int) db_in($AI->user->userID) . " AND domain_id = " . (int) db_in($d['id']) . "; ");
											if(!empty($subdomains)) {
												foreach($subdomains as $sd) {
													$domain_html .= '<option ';
													if(($this->db['domain_id'] . "`" . $this->db['sub_domain_id'] == $d['id'] . '`' . $sd['sub_domain_id']) || (!$is_admin)) {
														$domain_html .= ' selected="selected"';
													}
													if(AI_HTTPS_ON == "true") {
														$domain_html .= 'value="' . $d['id'] . '`' . $sd['sub_domain_id'] . '">https://' .  str_replace("*.",$sd['sub_domain'] . ".",$d['https_url']) . '</option>';
													} else {
														$domain_html .= 'value="' . $d['id'] . '`' . $sd['sub_domain_id'] . '">http://' .  str_replace("*.",$sd['sub_domain'] . ".",$d['http_url']) . '</option>';
													}
													$count_domain_html++;
												}
											}
										} else {
											if($is_admin) {
												$domain_html .= '<option ';
												if($this->db['domain_id'] . "`0" == $d['id'] . '`0') {
													$domain_html .= ' selected="selected"';
												}
												if(AI_HTTPS_ON == "true") {
													$domain_html .= 'value="' . $d['id'] . '`0">https://' .  $d['https_url'] . '</option>';
												} else {
													$domain_html .= 'value="' . $d['id'] . '`0">http://' .  $d['http_url'] . '</option>';
												}
												$count_domain_html++;
											}
										}
									}
									$domain_html .= '</select>';
									$domain_html .= '</td>';
								} else {
									$count_domain_html = 1;
									$domain_html = "<td colspan=\"3\"><select name=\"url_sub_2\" id=\"url_sub_2\"><option value=\"0`0\">" . AI_CURRENT_URL . "</option></select></td>";
								}
								echo "<tr ";
									if($count_domain_html < 2) {
										echo " style='display: none;'";
									}
								echo ">";
								echo $domain_html;
								echo "</tr>";
							?>
							<tr>
								<td align="right" style="text-align: right" valign="middle">Your Landing Page Address</td>
								<td width="10"></td>
								<td align="left" style="text-align: left">
									<input type="hidden" name="url_sub_3" id="url_sub_3" value="<?=$lp_page_name;?>" />
									<input type="hidden" name="domain_id" id="domain_id" value="<?=$this->db['domain_id'];?>" />
									<input type="hidden" name="sub_domain_id" id="sub_domain_id" value="<?=$this->db['sub_domain_id'];?>" />
									<input type="hidden" name="url" id="url" class="span14" value="<?=$value?>" />
									<span class="url_preview"></span>
								</td>
							</tr>
						</table>
						<?
						
					} else {
						
						$this->draw_input_field_by_desc( $fieldname, $value, $mode, $this->desc[ $fieldname ], $element_id );
						echo '<div style="margin-left:5em;">';
						echo '<p><strong>Available merge codes:</strong></p>';
						echo '<dl>';
						echo '<dt>[sub_domain]</dt><dd>The subdomain, typically the same as the username.</dd>';
						echo '<dt>[lead_id]</dt><dd>The unique ID assigned to their lead record. (This is not their member/user ID.)</dd>';
						if(util_module_status('success_line'))
						{
							echo '<dt>[tour_id]</dt><dd>If they are a part of the success line tour, this is their unique URL ID</dd>';
						}
						echo '</dl>';
						echo '</div>';

					}
				}
				break;

				case 'img_url':
					{
						$this->draw_input_field_by_desc( $fieldname, $value, $mode, $this->desc[ $fieldname ], $element_id );
						
					}
					break;
				case 'file_name':
					{
						$AI->skin->js('includes/modules/share_links/share_links.js');
						echo '<input type="hidden" name="file_name" id="file_name" value="' . htmlspecialchars( $value ) . '">';
						if( $value != '' )
						{
							echo 'Keep this file: ' . htmlspecialchars($value) . '?<br>Or select new file: ';
						}
						echo '<input type="file" name="file_upload" id="file_upload" value="">';
						echo '<p style="font-weight:bold">The images should be a square aspect ratio and will be shrunk to 250x250</p>';
					}
					break;
				case 'page_name_source':
					{
						echo '<input type="hidden" name="'.$fieldname.'" id="'.$element_id.'" value="'.AI_PHP_SELF.'" />';
					}
					break;
				default: { $this->draw_input_field_by_desc( $fieldname, $value, $mode, $this->desc[ $fieldname ], $element_id ); } break;
			}

		}

		/**
		 * DRAW VALUE FIELDS
		 */
		function draw_value_field( $fieldname, $value, $key, $mode )
		{
			global $AI;

			//IF THEY CAN "INLINE-EDIT" THEN SET IT UP
			if( $this->perm->get('ajax') && ( $this->inline_edit_db_field[ $fieldname ] == $mode || $this->inline_edit_db_field[ $fieldname ] == 'all' ) )
			{
				echo '<div class="te_inline_edit_cell" onclick="javascript:ajax_get_request( \'' . htmlspecialchars($this->ajax_url( 'inline_edit', 'te_key=' . $key . '&fieldname=' . $fieldname . '&view_mode=' . $mode )) . '\', ajax_handler_default );" >';
			}

			//DRAW THE VALUES
			if( $mode == 'table' )
			{
				switch( $fieldname )
				{
					case 'name': {
						$value = str_replace("Custom Landing Page for User:","Landing Page:",$value);
						echo '<h3>' . h($value);
						if($this->db['is_public']=='0') echo ' <span style="color:red;">(Not Public)</span>';
						
						if(!empty($this->db['template_id'])) {
							$settings = unserialize(@$AI->MODS_INDEX['landing_pages']['raw_settings']);
							$lp_page_name = (isset($settings['_lp_page_name']) ? $settings['_lp_page_name'] : "/l/");

							echo " - " . str_replace($lp_page_name,"",$this->db['url']);
						}
						
						echo '</h3>';
					} break;

					case 'description': { 
						$value = str_replace("Custom Landing Page for User:","Landing Page:",$value);
						echo '<p>' . nl2br(h($value)) . '</p>';
					} break;	

					case 'url':
					{
						
						$AI->skin->js('includes/modules/share_links/share_links.js');
						if(empty($this->db['template_id']) && $this->te_mode != "insert") {
							$value = self::interpret_url($value);
						} else {
							$value = self::interpret_url_v2($value,$this->db['domain_id'],$this->db['sub_domain_id']);
						}
						
						$def = 'This SubID will be appended to the link below.<br>For reporting purposes, all traffic from this source will be<br>tracked independently from the rest of this campaign.';
						$tipval=preg_replace('/<!--[^-]*-->/','',$AI->get_defaulted_dynamic_area('sharelink_subID',$def));
						echo '<p><label>SubID</label><input type="text" class="source"  style="width:125px;" value="" placeholder="Enter Your SubID" data-key="'.$key.'" />'.util_qtip($tipval).'</p>';
						
						echo '<p><input type="text" id="visual_link_'.$key.'" value="' . nl2br(h(util_tracking_url($value, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, null, util_pretty('share_link_'.$this->db["name"])))) . '" readonly="readonly" class="span12"  style="float:left; width:425px" /></p>';
						//global $AI; $AI->skin->js('includes/core/js/zero_clipboard/ZeroClipboard.js');
						$var = 'clip_'.util_rand_string(8);

						if(!defined('ZERO_CLIPBOARD_DEFINED')) {
							define('ZERO_CLIPBOARD_DEFINED',1);
							echo '<script type="text/javascript" src="includes/core/js/zero_clipboard/ZeroClipboard.js"></script>';
							echo '<script>
								function init_clip() {
								$("button.clip_button").each(function(){
									$(this).fadeIn(100);
									var clip = new ZeroClipboard.Client();
									clip.setHandCursor( true );
									clip.glue(this);
									clip.setText( $(this).attr("href_txt") );
									clip.addEventListener( "complete", function(client,text){
										var tekey = $(client.domElement).attr("tekey");
										$("#link_"+tekey+"_copy_success").fadeIn(50).delay(1000).fadeOut(200);
									});
								});
							}
							//we have to wait until everything is drawn because we cant have the objects moving once the flash is drawn
							$(window).load(function(){
						    t=setTimeout("init_clip()",1000);
							});
					
							
						</script>';
						}

						echo '<br style="clear:both;">';

						echo '<table width="350" style="margin: 10px 0" cellpadding="0" cellspacing="0">';
						echo '<tr>';
						echo '<td align="left">';
						$this->draw_sharing($value,$key);
						echo '</td>';
						echo '<td align="right">';
						echo '<button class="info clip_button" id="clip_button_'.$key.'" style="display:none; height:30px; padding-top: 5px;" href_txt="'.util_tracking_url($value, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, null, util_pretty('share_link_'.$this->db["name"])).'" tekey="'.$key.'">Copy Link</button>';
						echo '</td>';
						echo '</tr>';
						echo '</table>';

						echo '<div id="link_'.$key.'_copy_success" style="display:none; background: none repeat scroll 0 0 #E6EFC2; border: 2px solid #C6D880; color: #264409; float: left; font-size: 14px; height: 14px; margin-left: 10px; padding: 3px 6px 9px;" class="success"><img src="images/icons/tick.png">&nbsp;Copied</div>';
					}
					break;


					case 'img_url': {

						$no_cache = (isset($_GET['no_cache']) ? "no_cache&" : "");
						$core_set = (isset($_SESSION['using_ai_core']) && $_SESSION['using_ai_core']!='default')? '&ai_core='.$_SESSION['using_ai_core']:'';
						
						if(strstr($value,"template")) {
							$template_info = explode(":",$value);
							if(isset($template_info[0]) && isset($template_info[1])) {
								if(is_numeric($template_info[1]) && $template_info[0] == "template") {

									$webshots_url = "//webshots.apogeevault.com/webshots/?" . $no_cache . "w=250&url=" . urlencode(AI_CURRENT_URL . "screenshot?s" . $core_set . "&selected_template=" . $template_info[1]);
									echo '<img src="' . h($webshots_url) . '" title="'.h($this->db['name']).'" class="drip_thumbnail" width="250" />&nbsp;';
								}
							}
						} else {
							
								echo '<img src="' . h($value) . '" title="'.h($this->db['name']).'" class="drip_thumbnail" width="250" />&nbsp;';
							
							
						}
					} break;

					case 'requires_success_line':
					{
						if ( empty($value) )
						{
							echo '<span class="te-disabled-24"><span>No</span></span>';
						}
						else
						{
							echo '<span class="te-enabled-24"><span>Yes</span></span>';
						}
					}
					break;

					default: { echo util_trim_string( htmlspecialchars( $value ), 25, '..' ) . '&nbsp;'; } break;
				}
			}
			elseif( $mode == 'view' )
			{
				switch( $fieldname )
				{
					case 'requires_success_line':
					{
						if ( empty($value) )
						{
							echo '<span class="te-disabled"><span>No</span></span>';
						}
						else
						{
							echo '<span class="te-enabled"><span>Yes</span></span>';
						}
					}
					break;
					case 'img_url':
					{
						
								 echo htmlspecialchars( $value ) . '&nbsp;';
							
					}
					break;
					case 'url':
					{
						
						$AI->skin->js('includes/modules/share_links/share_links.js');
						if(empty($this->db['template_id']) && $this->te_mode != "insert") {
							$value = self::interpret_url($value);
						} else {
							$value = self::interpret_url_v2($value,$this->db['domain_id'],$this->db['sub_domain_id']);
						}
						
						echo '<input type="text" id="visual_link_'.$key.'" value="' . h( $this->get_track_url() ) . '" readonly="readonly" class="span12"  style="float:left; width:425px" />';
					}
					break;
					default: { echo htmlspecialchars( $value ) . '&nbsp;'; } break;
				}
			}
			else
			{
				echo 'Error: Invalid view mode specified.';
			}

			//IF THEY CAN "INLINE-EDIT" THEN FINISH IT UP
			if( $this->perm->get('ajax') && ( $this->inline_edit_db_field[ $fieldname ] == $mode || $this->inline_edit_db_field[ $fieldname ] == 'all' ) )
			{
				echo '</div>';
			}

		}
		
		function get_track_url() {
			$keystr = $this->get_track_campaign_keystr();
			return util_tracking_url($this->db["url"], $keystr, $GLOBALS['AI']->user->userID, null, util_pretty('share_link_'.$this->db["name"]));
		}
		function get_track_campaign_id() {
			$keystr = $this->get_track_campaign_keystr();
			return util_tracking_get_campaign_id($this->db["url"], $keystr, $GLOBALS['AI']->user->userID, null, util_pretty('share_link_'.$this->db["name"]));
		}
		function get_track_campaign_keystr() {
			return util_cleanup_keystr('share_link_'.$this->db["name"]);
		}

		static public function interpret_url( $url_with_merge_codes, $prospect_lead_id = null )
		{
			global $AI;
			
			$interpreted_url = trim($url_with_merge_codes . '');
			if ( strpos($interpreted_url, '[sub_domain]') !== false )
			{
				$sub_domain = db_lookup_value('multiple_domains_sub', 'owner_id', $AI->user->userID, 'sub_domain');
				if ( empty($sub_domain) )
				{
					$sub_domain = $AI->user->username;
				}
				$interpreted_url = str_replace('[sub_domain]', $sub_domain, $interpreted_url);
			}
			$lead_id = (int) ($prospect_lead_id === null ? db_lookup_value('users', 'userID', $AI->user->userID, 'lead_id') : $prospect_lead_id);
			if ( strpos($interpreted_url, '[lead_id]') !== false )
			{
				if ( empty($lead_id) )
				{
					$lead_id = '';
				}
				$interpreted_url = str_replace('[lead_id]', $lead_id, $interpreted_url);
			}
			if ( strpos($interpreted_url, '[tour_id]') !== false )
			{
				$tour_id = db_lookup_value('success_line', 'lead_id', (int) $lead_id, 'url');
				$interpreted_url = str_replace('[tour_id]', $tour_id, $interpreted_url);
			}
			return $interpreted_url;
		}

		static public function interpret_url_v2($url, $domain_id = 0, $sub_domain_id = 0 )
		{
			global $AI;
			
			$domain = "";
			if(empty($domain_id)) {
				$domain = AI_CURRENT_URL;
			} else {
				if( AI_HTTPS_ON == 'true' ) {
					$domain = "https://" . db_lookup_scalar("SELECT https_url FROM multiple_domains WHERE id = " . (int) db_in($domain_id) . "; ");
				} else {
					$domain = "http://" . db_lookup_scalar("SELECT http_url FROM multiple_domains WHERE id = " . (int) db_in($domain_id) . "; ");
				}
				
				// Now, check to see if this domain is a subdomain domain
				if(strpos($domain, "*.") !== false) {
					$sub_domain = (empty($sub_domain_id) ? "www" : db_lookup_scalar("SELECT sub_domain FROM multiple_domains_sub WHERE domain_id = " . (int) db_in($domain_id) . " AND sub_domain_id = " . (int) db_in($sub_domain_id) . "; "));
					$domain = str_replace("*",$sub_domain,$domain);
				}
			}
			
			$domain = rtrim($domain,"/");
			
			return $domain . $url;
		}

		function draw_sharing($url,$key)
		{
			global $AI;

			// ABORT IF HTTPS, OR IF SHOW-SHARING IS OFF
			//if( $AI->get_setting('HTTPS_ON').'' == 'true' ) return;

			if(!defined('SHARETHIS_INCLUDE_ONCE')) {
				define('SHARETHIS_INCLUDE_ONCE',true);
				$sharethis_publisher = $AI->get_setting('sharethis_publisher'); // APOGEES : '0e2b3531-4d7f-4706-b278-80daebc8b22f';
				?>
					<!--<script type="text/javascript" src="//w.sharethis.com/button/sharethis.js#publisher=<?=$sharethis_publisher?>&amp;type=website&amp;button=false"></script>-->
					<!--<script type="text/javascript" src="//w.sharethis.com/button/sharethis.js#publisher=<?=$sharethis_publisher?>&amp;type=website&amp;post_services=email%2Cfacebook%2Ctwitter%2Cdigg%2Cdelicious%2Clinkedin%2Cybuzz%2Cwordpress&amp;button=false"></script>-->
					<?php 
					if( $AI->get_setting('HTTPS_ON').'' == 'true' )
					{
					?>
						<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="//ws.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'<?=$sharethis_publisher?>'});</script>
					<?php 
					}
					else 
					{
					?>
						<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="//w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'<?=$sharethis_publisher?>'});</script>
					<?php 
					}
					?>
				<?php
			}

			// SHARETHIS API : http://blog.sharethis.com/faq/developers-faq/sharethis-api/

			if ( strpos($url, 'http://') === false )
			{
				$url = $AI->get_setting('HTTP_URL') . $url;
				
				
			}

			?>
				<span class='st_email_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'email', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
				<span class='st_facebook_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'facebook', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
				<span class='st_twitter_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'twitter', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
				<span class='st_linkedin_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'linkedin', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
				<span class='st_googleplus_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'googleplus', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
				<span class='st_sharethis_large' st_url="<?= util_tracking_url($url, util_cleanup_keystr('share_link_'.$this->db["name"]), $AI->user->userID, 'sharethis', util_pretty('share_link_'.$this->db["name"])) ?>"></span>
			<?php
		}


	}//~class C_te_share_links extends C_tableedit_base
?>