<?php
	//index.php 
	//(c) 2011 Copyright All Rights Reserved: Apogee Design, Inc.
	chdir(dirname(__FILE__));
	require_once('includes/init.php');
	if( AI_PAGE_NAME=='logoff' ) { require( ai_cascadepath('includes/logoff.php') ); die; }
	else $AI->skin->drawpage();

