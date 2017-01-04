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

	

	

	<div class="row te_table">

	<div class="span-one-third col-sm-4" style="text-align:center;">

		<h3><?=$this->db['title']?></h3>
<?php
if($this->db['type']==1) {
	?>
		<video width="560" controls>
			<source src="uploads/video_manager/<?php echo $this->db['file'] ?>" type="video/mp4">
		</video>
<?php
}
if($this->db['type']==0) {

?>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $this->db['file'] ?>" frameborder="0" allowfullscreen></iframe>
		<?php }?>
		<!--<img src="http://www.epiclyfe.com/image?imgurl=<?/*=$this->db['url']*/?>&w=<?/*=$this->db['width']*/?>&h=<?/*=$this->db['height']*/?>&ar=1&e=0&cr=0" style="border: solid 1px #ccc; display:block; margin:0 auto; max-width: 200px; max-height: 200px;" />-->


	</div>

	</div>

<div class="te_viewnav_top">

		<?php $this->draw_ViewNav(); ?>

	</div>



	



	

</div>

</div>

