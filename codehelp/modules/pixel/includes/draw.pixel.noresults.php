<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc. 
	//Generated: 2012-05-22 15:24:18 by jon
	//DB Table: share_links, Unique ID: share_links, PK Field: id
	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
?>


<div class="te_table share_links_table">
	<fieldset class="te">
		<legend class="te">
			<?php echo htmlspecialchars( $this->_tableTitle ); ?>
		</legend>
		
		<div class="te_noresults">
			<img class="spacer_top" border="0" src="images/spacer.gif" alt="" />
			<div>
				<?php
					if( $this->te_permit['insert_share_link'] )
					{
						?><a class="te_button te_new_button" href="<?php echo $this->url( 'te_mode=insert_old' ); ?>" title="New"><span class="te_button te_new_button">New</span></a><?php  
					}				
				?>
			</div>
			<p class="to_noresults">No <?php echo htmlspecialchars($this->_unit_label); ?> Found.</p>
			<img class="spacer_bottom" border="0" src="images/spacer.gif" alt="" />
		</div>
		
	</fieldset>
</div>
<?php

if(@$this->settings['enable_landing_page_manager'] != "No" && @$this->te_permit['landing_page_manager'] == 1) {
	echo '<button class="icon_button" style="margin: 25px auto; font-size: 16px; width: 400px; text-align: center;" onclick="document.location = \'' . h($this->url('te_mode=insert')) . '\'; return false;"><img src="images/menu_tree/ao1n_landing_page_leads_48.gif" align="absmiddle"> Add Landing Page</button>';
}
