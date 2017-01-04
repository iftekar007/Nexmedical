<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_share_links.php' ) );	
	
	global $AI;
	
	$dbWhere = " (owner_id = 0 OR owner_id = '" . $AI->user->userID . "') ";
	if(!$AI->get_access_group_perm('Website Developers')) {
		$dbWhere .= ' AND is_public=1';
	}

	// Generate the settings, setting to a local variable for now
	if ( isset($AI->MODS_INDEX['share_links']) )
	{
		if ( isset($AI->MODS_INDEX['share_links']['raw_settings']) )
		{
			$settings = unserialize($AI->MODS_INDEX['share_links']['raw_settings']);
		}
	}

	// We need to define the further dbWhere adjustment
	if(@$settings['enable_landing_page_manager'] == "No") {
		$dbWhere .= ' AND template_id = 0';
	}
	
	$dbWhere .= " AND this.page_name_source='".db_in(AI_PHP_SELF)."' ";
	
	$te_share_links = new C_te_share_links($dbWhere);

	// Now that the db where is set, let's set the settings into the class so it can be used elsewhere
	$te_share_links->settings = $settings;
	
	$te_share_links->_obFieldDefault = 'sort_order';
	$te_share_links->_obDirDefault = 'ASC';
	$te_share_links->set_session( 'te_obField', $te_share_links->_obFieldDefault );
	$te_share_links->set_session( 'te_obDir', $te_share_links->_obDirDefault );
	$te_share_links->_obField = $te_share_links->get_session( 'te_obField' );
	$te_share_links->_obDir = $te_share_links->get_session( 'te_obDir' );
	
	$te_share_links->select($te_share_links->te_key);
	
	if(!empty($te_share_links->db['template_id']) || $te_share_links->te_mode == "insert") {
		$te_share_links->edit_include_file = 'includes/modules/share_links/includes/draw.share_links.edit.landing_page.php';
	}
	
	if($te_share_links->te_mode == "insert_old") {
		$te_share_links->te_mode = "insert";
	}
	
	$te_share_links->run_TableEdit();	
?>