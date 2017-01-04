<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	
	require_once( ai_cascadepath( dirname(__FILE__) . '/includes/class.te_repmanager.php' ) );

echo '<link href="includes/modules/video_manager/video_manager.css" rel="stylesheet">';
echo '<link href="includes/modules/video_manager/bootstrap.min.css" rel="stylesheet">';

	global $AI;





	$te_video_manager = new C_te_video_manager();

	$te_video_manager->_obFieldDefault = 'time';
	$te_video_manager->_obDirDefault = 'DESC';
	$te_video_manager->set_session( 'te_obField', $te_video_manager->_obFieldDefault );
	$te_video_manager->set_session( 'te_obDir', $te_video_manager->_obDirDefault );
	$te_video_manager->_obField = $te_video_manager->get_session( 'te_obField' );
	$te_video_manager->_obDir = $te_video_manager->get_session( 'te_obDir' );

	$te_video_manager->select($te_video_manager->te_key);

	$te_video_manager->run_TableEdit();
?>
