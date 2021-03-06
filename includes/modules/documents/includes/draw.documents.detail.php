<?php
	//Copyright (c)2006 All Rights Reserved - Apogee Design Inc.
	//Generated: 2008-06-26 17:06:45
	//DB Table: documents, Unique ID: documents, PK Field: id

	global $AI;

	require_once( ai_cascadepath('includes/plugins/ajax/ajax.require_once.php') );
	$file_link = $this->get_document_download_link();

	if($file_link !== false) {
		$tmp = pathinfo($file_link);
		$ext = $tmp['extension'];
		if(in_array($ext, array('doc','xls','xlsx','pdf','ppt','pptx', 'pages', 'ai', 'psd', 'tiff', 'dxf', 'svg', 'eps', 'ps', 'ttr', 'xps', 'zip', 'rar'))) {
			$view_link = "http://docs.google.com/viewer?url=".urlencode(AI_HTTP_URL.$file_link);
		} else {
			$view_link = $file_link;
		}
	}
	elseif(!empty($this->db['url'])) {
		$view_link = $this->db['url'];
	}
?>
<div class="te_table documents_table">
	<h2><?= h( $this->db['title'] ); ?></h2>
	<?php if($this->perm->get('manage_categories')) { ?>
	<div>
		<sup class="admin"></sup>
		<a href="documents?te_mode=update&te_key=<?=$this->te_key?>" target="_parent" class="icon_button" style="width: 220px; margin: 5px auto; float: none;     padding: 7px 7px 6px 7px;">
			<img src="images/icons/circ_edit_32.png">
			<span>Edit Document</span>
		</a>
		<sup class="admin"></sup>
		<a href="documents?te_mode=delete&te_key=<?=$this->te_key?>" target="_parent" class="icon_button" style="width: 220px; margin: 5px auto; float: none;     padding: 7px 7px 6px 7px;">
			<img src="images/icons/circ_delete_32.png">
			<span>Delete Document</span>
		</a>
	</div>
	<?php } ?>
	<div class="resourcespopup_textcon">
<?php

	$sr = exec("file -i -b " . $this->upload_dir.$this->db['file_name']);
	$ftype = explode('/', $sr);
	$id = time();

	$ftype[0] = ($ftype[0] == 'application' && $ftype[1] == 'octet-stream') ? 'video' : $ftype[0];
	if ( $this->db['url'] != '' ) {
		if ( preg_match('/youtube/i', $this->db['url']) ) {
			$ftype[0] = 'video';	
		}
	}

	switch($ftype[0]) {
		case 'image':
			echo '<img src="image.php?imgurl=' . urlencode($this->upload_dir.$this->db['file_name']) . '&h=250" align="left" style="margin-right:15px">';
			break;

		case 'video':
			echo '<div style="width:480px; margin:12px auto;">';
			if ( $this->db['url'] != '' ) {

				echo '<video width="480" height="315"  id="player' . $id . '" preload="none">';
				echo '<source type="video/youtube" src="' . $this->db['url'] . '">';
				echo '</video>';
			} else {
				echo '<video width="480"  height="315"  src="' . $this->upload_dir.$this->db['file_name'] . '" id="player' . $id . '" controls="controls"></video>';
			}

			echo '</echo>';

			echo "<script>$('#player" . $id . "').mediaelementplayer({enableAutosize:true,pauseOtherPlayers:true,plugins:['flash','silverlight'],pluginPath:'includes/core/js/me-js/'})</script><br>";
			break;

		case 'audio':
			echo '<audio width="480" src="' . $this->upload_dir.$this->db['file_name'] . '" id="player' . $id . '" controls="controls"></audio>';
			echo "<script>$('#player" . $id . "').mediaelementplayer()</script><br>";
			
			break;

		case 'application':
			if ( $ftype[1] == 'pdf') {
				
				break;
			}
		case 'text':
		default:
		
	}
?>
		<p><?=($this->db['description']==''?'<em>No Description.</em>':str_replace("\n",'<br>', h($this->db['description'])))?></p>
		<br clear="both">
	</div>
	<div>

		<?php

		$newfilename = $this->db['file_name'];
		$newfileext = '';

		if(!empty($newfilename)){
			$newfileext = pathinfo($newfilename,PATHINFO_EXTENSION);
		}


		?>

		<?php
			if($newfileext == 'pdf'){

				?>


				<a href="javascript:void(0);" onclick="close_jonbox(); open_jonbox('showpdfpreview1?fileurl=<?php echo $this->upload_dir.$this->db['file_name'];?>')" class="icon_button" style="width: 220px; margin: 5px auto; float: none;     padding: 7px 7px 6px 7px;" >
					<img src="images/mimes/browser.png">
					Show Preview
				</a>

				<?php
			}else{
				?>

				<a href="<?=$view_link?>" target="<?=$this->db['target']?>" onclick="close_jonbox();" class="icon_button" style="width: 220px; margin: 5px auto; float: none;     padding: 7px 7px 6px 7px;" >
					<img src="images/mimes/browser.png">
					View Online
				</a>

				<?php
			}
		?>

		<?=($file_link!==false)?'
		<a href="documents_download?te_key='.$this->db['id'].'" onclick="close_jonbox();" class="icon_button" style="margin-left:15px;width:185px;display:none;">
		<img src="images/icons/book_save_48.gif" />
		<span>Download File</span></a>
		':''?></div>
</div>
