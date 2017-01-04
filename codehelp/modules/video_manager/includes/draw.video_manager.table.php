<?php

global $AI;

// Merge Codes
$sub_domain = $AI->user->username;

?>

<!--<link href="includes/modules/video_manager/video_manager.css" rel="stylesheet">-->

<script language="javascript" type="text/javascript">
<!--
	function share_links_update_sort_index(table, row)
	{
		// Fix Zebra Stripping
		//$("table.te_main_table tr:even").removeClass("te_odd_row").addClass("te_even_row");
		//$("table.te_main_table tr:odd").removeClass("te_even_row").addClass("te_odd_row");

		var post_str = $(table).tableDnDSerialize();
		//$('#saving').css('display', 'inline');

		// Create a post request
		ajax_post_request('<?= $this->ajax_url('update_sort_index', '') ?>', post_str, ajax_handler_default);
	}
-->
</script>

<?php
$title='';
$type='';
if(isset($_GET['title']) && $_GET['title']!=''){
	$title=$_GET['title'];
}
if(isset($_GET['type'])){
	$type=$_GET['type'];
}

/*if ( @$this->te_permit['insert_share_link'] )
{*/
/*	echo '<button onclick="document.location = \'' . h($this->url('te_mode=insert')) . '\'; return false;">New</button>';*/
/*}*/

//$share_arr = $AI->db->getAll("SELECT * FROM `share_links` WHERE id = " . (int) db_in($_GET['te_share_link_id']));

//$share_link_name = @$share_arr[0]['name'];

echo "<h2>Video Manager</h2>";
echo '<form id="share_links_asearch_form" class="te formsearch" method="get" action="'.htmlspecialchars($this->url( '' )).'">';
echo '<input type="hidden" name="te_class" value="'.$this->unique_id.'" />';
echo '<input type="hidden" name="te_mode" value="table" />';
echo '<input type="hidden" name="te_asearch" value="true" />';
echo '<input type="text" name="title" id="title" placeholder="Search by title" value="'.$title.'">';
echo '<select id="type"  name="type"><option value="" selected="selected">All</option><option value="0" '.(($type == 0 && strlen($type))?'selected="selected"':'').'>Youtube Video</option><option value="1" '.(($type == 1 && strlen($type))?'selected="selected"':'').' >MP4 Video</option></select>';
echo '<input class="search_button" type="button" value="Show all" onclick="document.location = \'video_manager\'; return false;" />';
echo '<input class="te te_buttons search_button" type="submit" name="btnSearch" value="Search" />';
echo '</form>';


echo '<button class="search_button"  onclick="document.location = \'' . h($this->url('te_mode=insert')) . '\'; return false;">New</button>';
echo '<p>&nbsp;</p><!--spacer-->';

$lead_id = (int) db_lookup_value('users', 'userID', (int) $AI->user->userID, 'lead_id');

echo '<table class="te_main_table pixel_main_table" id="pixel_main_table">';


echo "<tr>";
echo "<th>Title</th>";
echo "<th>Description</th>";
echo "<th>Type</th>";
echo "<th>Video</th>";
echo "<th>Status</th>";
echo "<th>Priority</th>";
echo "<th>Added on</th>";
echo "<th>Action</th>";
echo "</tr>";


//var_dump($table_result);

$table_row = db_fetch_assoc($table_result);

for ( $table_i = 0; $table_i < $this->_pgSize && $table_row; $table_i++ )
{


	if (true) {

		$ai_sid_key = ai_sid_keygen();
		$ai_sid = ai_sid_save_sessionid( $ai_sid_key );
		$core_set = (isset($_SESSION['using_ai_core']) && $_SESSION['using_ai_core']!='default')? '&ai_core='.$_SESSION['using_ai_core']:'';
		
		echo '<tr class="te_data_row ' . ( $table_i % 2 == 1 ? 'te_even_row' : 'te_odd_row' ) . '" id="'.$this->db[$this->_keyFieldName].'" data-row-i="' . $this->_row_i . '">';
		
		echo "<td>";
			$this->draw_value_field('title', $table_row['title'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td>";
			$this->draw_value_field('description', $table_row['description'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('type', $table_row['type'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_file_table_list($table_row['file'], $table_row['type'],$table_row['title']);
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('status', $table_row['status'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('priority', $table_row['priority'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center'>";
		$this->draw_value_field('time', $table_row['time'], $this->db[$this->_keyFieldName], 'table');
		echo "</td>";
		echo "<td align='center' class='addbtn'>";

		echo '<button  class="icon_button_16 share_link_buttons editbtn" onclick="document.location = \'' . h($this->url('te_mode=update&te_key=' . $table_row['id'])) . '&te_row=' . $this->_row_i.'\'; return false;">';
		echo '<img src="images/dynamic_edit.14.transparent.png">';
		echo '<span>Edit</span>';
		echo '</button>';

		echo '<button  class="icon_button_16 share_link_buttons deletebtn" onclick="document.location = \'' . h($this->url('te_mode=delete&te_key=' . $table_row['id'])) . '&te_row=' . $this->_row_i.'\'; return false;">';
		echo '<img src="images/drop.png">';
		echo '<span>Delete</span>';
		echo '</button>';

		echo "</td>";
		echo "</tr>";
	}
	//--
	$this->_row_i++;
	$table_row = db_fetch_assoc($table_result);
}

echo '</table>';

?>

<div id="videopopupModal" class="modal fade bs-example-modal-md pop" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Select video</h4>
			</div>
			<div class="modal-body" id="videoopenpopup" >

			</div>