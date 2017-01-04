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
	$('#img_url').val('image.php?imgurl=uploads/share_links/'+ ai.urlencode(new_link) );
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