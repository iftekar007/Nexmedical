<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
	global $AI;
?>
<style>
#slstats hr { margin:10px 0 10px; }
</style>

<div class="row" id="slstats">
<div class="span-full col-sm-12">
	<div class="te_viewnav_top">
		<?php $this->draw_ViewNav(); ?>
	</div>
	<br style="clear:both">

	<hr />
	<div class="row te_table">
	<div class="span-one-third col-sm-4" style="text-align:center; float:left;">
		<?php $this->draw_value_field('img_url', $this->db['img_url'], $this->db[$this->_keyFieldName], 'table'); ?>
	</div>
	<div class="span-two-thirds col-sm-8" style="float:left;">
		<h3><?=$this->db['name']?></h3>
		<p><?php $this->draw_value_field('url', $this->db['url'], $this->db[$this->_keyFieldName], 'view'); ?></p>
	</div>
	</div>
	<hr />

	
	<!-- NOW DRAW THE REPORTS -->
	
	
	<?php
		//SET CAMPAIGN FOR REPORTS
		$cid = $this->get_track_campaign_id(); //don't really need this but it makes sure the campaign is created
		$keystr = $this->get_track_campaign_keystr();
		
		//hack to pass data to the report widgets
		$_POST['campaign'] = $keystr;
		$_POST['as_admin'] = 'false';//admins view only the data for themselves and not for everyone
		
		//admins view only the data for themselves and not for everyone
		
		//SET STARTING PAGE FOR TRAFFIC REPORT
		//this query roughly taken from "includes/plugins/ai_tracking_analytics/modules/traffic_flows.php"
		$pri_page = db_lookup_assoc("SELECT fr.to_page_id AS page_id, p.url, SUM(fr.`count`) AS 'count' FROM ai_tracking_flow_reports AS fr LEFT JOIN ai_tracking_pages AS p ON fr.to_page_id = p.id WHERE EXISTS (SELECT 1 FROM ai_campaigns WHERE key_str = '".db_in($keystr)."' AND id = fr.campaign_id) AND 1 GROUP BY fr.to_page_id ORDER BY SUM(fr.`count`) DESC ;");
		if(@$pri_page['page_id']>0) $_POST['start_page']=$pri_page['page_id'];
		
		//DRAW THE WIDGET REPORTS
		echo $AI->get_dynalist(AI_PAGE_NAME, 'widgets', array('ok_types' => array('stats')));
	?>
	
</div>
</div>
