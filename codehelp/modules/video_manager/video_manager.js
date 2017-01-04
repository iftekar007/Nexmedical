$(document).on("click",".button_prev .icon_button",function(event) {
	var step = parseInt($("#landing_page_step").text());
	if(step === 3) {
		// Nothing to do here
	} else {
		event.preventDefault();
		step = step - 1;
		goto_page(step);
	}
});
$(document).on("click",".button_next .icon_button",function(event) {
	var step = parseInt($("#landing_page_step").text());
	if(step < 2) {
		step = 2;
		update_url();
		check_check_url();
		event.preventDefault();
	}
});

$('.file_name').on("change", "#file_upload", function(event){
	link = $(this).val().match('[^/\\\\]+$');
	new_link = link[0].replace( /[^a-zA-Z0-9.-]/, '_');
	$('#url').val('image.php?imgurl=uploads/share_links/'+ ai.urlencode(new_link) );
});

$('.source').on('blur', function(){
	if($('#visual_link_'+$(this).data('key')).val().match(/&aitsub=[0-9a-zA-Z]+/))
	{
		$('#visual_link_'+$(this).data('key')).val($('#visual_link_'+$(this).data('key')).val().replace(/&aitsub=[0-9a-zA-Z]+/,'&aitsub='+$(this).val() ));

	}
	if($('#visual_link_'+$(this).data('key')).val().match(/&aitsub=/))
	{
		$('#visual_link_'+$(this).data('key')).val($('#visual_link_'+$(this).data('key')).val().replace(/&aitsub=/,'&aitsub='+$(this).val() ));

	}
	else
	{
		$('#visual_link_'+$(this).data('key')).val($('#visual_link_'+$(this).data('key')).val()+ '&aitsub='+$(this).val() );
	}

	$('#clip_button_'+$(this).data('key')).attr('href_txt', $('#visual_link_'+$(this).data('key')).val());
	init_clip();
});

function goto_page(step)
{
	if($("#url_sub_1").val() == "" && step > 1) {
		goto_page(1);
		document.location('#1');
	}
	switch(step)
	{
		case 1:
			//check_check_url();
			update_url();
			$(".landing_page_step_1").show(0);
			$(".landing_page_step_2").hide(0);
			$(".landing_page_step_3").hide(0);
			$(".landing_page_preview").hide(0);
			$(".button_prev .icon_button").hide(0);
			$(".button_next .icon_button").show(0);
			$(".button_next .icon_button .small_text").text("Select Template");
			break;

		case 2:
			$(".landing_page_step_1").hide(0);
			$(".landing_page_step_2").show(0);
			$(".landing_page_step_3").hide(0);
			$(".landing_page_preview").show(0);
			$(".button_prev .icon_button").show(0);
			if($("#template_id").val() > 0) {
				$(".button_next .icon_button").show(0);
			} else {
				$(".button_next .icon_button").hide(0);
			}
			$(".button_next .icon_button .small_text").text("Edit Page");
			break;

		case 3:
			$(".landing_page_step_1").hide(0);
			$(".landing_page_step_2").hide(0);
			$(".landing_page_step_3").show(0);
			$(".landing_page_preview").hide(0);
			$(".button_prev .icon_button").show(0);
			$(".button_next .icon_button").show(0);
			break;
	}

	$("#landing_page_step").text(step);

	window.location.hash = step;
	update_height();
}

function load_template(template) {
	var url = "screenshot?selected_template=" + template;
	$(".landing_page_preview").attr('src',url);
	$("#img_url").val('template:' + template);
	$("#template_id").val(template);
	$(".button_next .icon_button").show(0);
	update_height();
}

function update_url()
{
	if(!$("#url_sub_2").length == 0) {
		var user_string = $("#url_sub_1").val().replace(/ /g,"_");
		var domain_string = $("#url_sub_2").val();
		var landing_page_name = $("#url_sub_3").val();
		var domain_parts = domain_string.split("`");
		var domain = domain_parts[0];
		var subdomain = domain_parts[1];

		$("#domain_id").val(domain);
		$("#sub_domain_id").val(subdomain);
		$("#url").val(landing_page_name + user_string);
		$(".url_preview").text($("#url_sub_2 option:selected").text() + landing_page_name.substr(1) + user_string);
	}
}

$("#url_sub_1").keypress(function(event) {
	var key = event.which;
	var keychar = String.fromCharCode(key).toLowerCase();
	// allow control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ) {
		return true;
	}

	if ((("abcdefghijklmnopqrstuvwxyz0123456789_- ").indexOf(keychar) == -1)) {
		event.preventDefault();
		return false;
	}
});

function update_height()
{
	//offset causing errors, removed - Philip 2/11/2014
	//var offset = $(".landing_page_controls").offset();
	var height = $(".landing_page_controls").height();
	$( ".landing_page_control_bar" ).animate({ height: (height+30) }, 125, function() {
		$("BODY").animate({ paddingTop: (height+50) }, 125);
		// Animation complete.
	});
}

/*
 $("#url_sub_1").blur(function() {
 check_check_url();
 });
 */

function check_check_url() {
	if($("#url_sub_1").val() != "") {
		check_url();
	}
}

function keyWordsearch(){

			search_input= $('#youtubevalue').val();
	if(typeof(search_input)=='undefined' || search_input !='' ) {
		url = $('#youtubevalue').val();

		var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		var matches = url.match(p);
		if (matches) {
			search_input1 = matches[1];
			//alert(search_input1);
			addVideo(search_input1);
		}
		else {
			search_input1 = search_input;

//	console.log(search_input);
			//var keyword= encodeURIComponent(search_input);
			//console.log(keyword);
// Youtube API
			var yt_url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' + search_input1 + '&maxResults=10&key=AIzaSyANefU-R8cD3udZvBqbDPqst7jMKvB_Hvo';

			//var yt_url='http://gdata.youtube.com/feeds/api/videos?q='+keyword+'&format=5&max-results=1&v=2&alt=jsonc';
			$.ajax
			({
				type: "GET",
				url: yt_url,
				dataType: "jsonp",
				success: function (response) {
					if (response.items) {
						var video_id = [];
						var final = '';
						var video_frame = '';
						$.each(response.items, function (i, data) {
							//i//f(typeof (data.item.id.videoId) != 'undefined'){
							//video_id=data.item.id.videoId;
							//}
							var video_id = data.id.videoId;
							var video_title = data.snippet.title;
							//var video_viewCount=data.viewCount;
// IFRAME Embed for YouTube
							video_frame += "<iframe width='640' height='385' src='http://www.youtube.com/embed/" + video_id + "' frameborder='0' type='text/html'></iframe><div style='float: left;'><input type='button' class='btn btn-primary add_video_button' onclick=\"addVideo('" + video_id + "')\" value='Add video'></div>";

							//final +="<div id='title'>"+video_title+"</div><div>"+video_frame+"</div>";

							//$("#result").html(video_frame); // Result

						});
						$("#results").html(video_frame);
						$("#myModal").modal('show');
					}
					else {
						$("#results").html("<div id='no'>No Video</div>");
						$("#myModal").modal('show');
					}
				}
			});
		}
	}
		//});
	//});
}

$(function(){
	$('#myModal').modal('hide');

	videotyp=$('#type').val();
	if(videotyp==1){
		$('#youtubevalue').hide();
		$('#search-button').hide();
		$('#file_upload').show();
	}
	else{
		$('#youtubevalue').show();
		$('#search-button').show();
		$('#file_upload').hide();
	}
})
function addVideo(vidoeid){
$('#youtubevalue').val('');
$('#file').val(vidoeid);
	$("#myModal").modal('hide');
	showvideofile();
}
//
function gettype(e){
	$('#filedetails').html('');
	$('#file').val('');
	videotype=$(e).val();
	//alert(videotype);
	if(videotype==1){
		$('#youtubevalue').hide();
		$('#search-button').hide();
		$('#file_upload').show();
	}
	else{
		$('#youtubevalue').show();
		$('#search-button').show();
		$('#file_upload').hide();
	}
}

function openvideo5(vidoeid,videotitle){

		var videohtml = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+vidoeid+'" frameborder="0" allowfullscreen></iframe>';

		$('#videoopenpopup').html(videohtml);
		//$('#videopopupModal').show();
	$("#videopopupModal").modal('show');
	$('.modal-title').html(videotitle);


}
function openvideo51(vidoeid,videotitle){
	var videohtml = '<video width="560" controls>\
			<source src="uploads/video_manager/'+vidoeid+'" type="video/mp4">\
		</video>';

	$('#videoopenpopup').html(videohtml);
	$("#videopopupModal").modal('show');
	$('.modal-title').html(videotitle);

}


$(function () {
	if($('#video_manager_form').length){
		$('#filedetails').html('');
		showvideofile();

	}
});

function showvideofile(){
	var video_type = ($('#video_manager_form #type').val());
	var video_val = ($('#video_manager_form #file').val());
//alert(video_val);
	if(video_type == 0 && video_val !=''){
		var videohtml = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+video_val+'" frameborder="0" allowfullscreen></iframe>';
//alert(videohtml);
		$('#filedetails').html(videohtml);
	}

	if(video_type == 1 && video_val !=''){
		var videohtml = '<video width="560" controls>\
			<source src="uploads/video_manager/'+video_val+'" type="video/mp4">\
		</video>';

		$('#filedetails').html(videohtml);
	}
}