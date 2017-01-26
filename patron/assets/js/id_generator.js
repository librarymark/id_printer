//=============================================================
// DOCUMENT READY
//=============================================================
jQuery(document).ready(function() { 

session_update_period = 600000;
setTimeout(function(){executeQuery(session_update_period);}, session_update_period);
var id_number = 'auto_generate';
var id_override = false;
	// get the interface ready
	$('#snapshot').hide();
	$('#canvas').hide();
	$('#btnPreviewAndPrint').hide();
	$('#btnRetake').hide();
	$('#divImageAdjust').hide();
	$('#divYesNoBox').hide();
	$("#id_number").numeric()

	// check for location
	if (location_abbr == 'none' ){ // we don't know where we are...
		var $dialog = $("<div><p>&nbsp;</p>Please choose your location:<p>&nbsp;</p><ul><li><input type='radio' name='location' value='wpl' ID='location' >Willard</li><li><input type='radio' name='location' value='hwb' ID='location' >Helen Warner Branch</li></ul></div>")
			.dialog({
			modal:true,
			autoOpen: false,
			draggable: false,
			height: 150,
			width: 250,
			title: "Choose your location"});
		$dialog.dialog('open');
	}else{// we do know where we are...
		JQD.go();
		initialize_camera();
	}

	//=============================================================
	// check for call_workflows cookie and set checkbox accordingly
	//=============================================================
	if ( $.cookie('call_workflows') == 'yes'){
		$('#chkbxCallWorkflows').attr('checked', true);
	}else{
		$('#chkbxCallWorkflows').attr('checked', false);
	}	
	// END check for call_workflows cookie and set checkbox accordingly
	

	//=============================================================
	// check for use_paster cookie and set checkbox accordingly
	//=============================================================
	
	if ( $.cookie('paste_id') == 'yes' || $.cookie('paste_id') === null ){
		$('#chkbxPasteID').attr('checked', true);
	}else{
		$('#chkbxPasteID').attr('checked', false);
	}
	// END check for use_paster cookie and set checkbox accordingly

	//=============================================================
	// Checkbox Call Workflows click (in config menu)
	//=============================================================
	$('#chkbxCallWorkflows').click(function(c) {
		if ($('#chkbxCallWorkflows').attr('checked')){
			$.cookie( 'call_workflows', 'yes', { expires: 4000, path: '/' } );
		}else{
			$.cookie( 'call_workflows', 'no', { expires: 4000, path: '/' } );
		}
	});	// Checkbox Call Workflows click (in config menu)

	//=============================================================
	// Checkbox Paste ID click (in config menu)
	//=============================================================
	$('#chkbxPasteID').click(function(c) {
		if ($('#chkbxPasteID').attr('checked')){
			$.cookie( 'paste_id', 'yes', { expires: 4000, path: '/' } );
		}else{
			$.cookie( 'paste_id', 'no', { expires: 4000, path: '/' } );
		}
	});// END Checkbox Paste ID click (in config menu)
	
	$('#btnRetake').click(function(){
			untake_snapshot();
	});

	//=============================================================
	// Image Adjust Buttons
	//=============================================================
	$("#brightness" ).slider({
		min:-50,
		max:50,
		value:-1
	});	

	$("#contrast" ).slider({
		min:-20,
		max:20,
		value:-1
	});	

	/*
	$("#sharpen" ).slider({
		min:0,
		max:50,
		value:0
	});	
*/

	$('#btnImageReset').click(function(e){
		e.preventDefault();
		// from http://stackoverflow.com/questions/15032973/how-can-i-change-out-an-image-using-camanjs
		//$("#preview").removeAttr("data-caman-id");
		$("#contrast").slider('value', "0");
		$("#brightness" ).slider('value', "0");
		//$("#sharpen" ).slider('value', "0");
		
		 return false;
	});

	$('#contrast, #brightness, #sharpen').on( "slidechange", function( event, ui ) {
		applyFilters();
	});

	function applyFilters() {
		var brightness_val =  $("#brightness").slider( "value" );
		var contrast_val = $("#contrast").slider( "value" );
		//var sharpen_val = $("#sharpen").slider( "value" );
		Caman("#preview", function () {
		  this.revert(true);
		  this.brightness(brightness_val);
		  this.contrast(contrast_val);
		  //this.sharpen(sharpen_val );
		  this.render();
		});
	}

	//=============================================================
	// click override auto-generate checkbox
	//=============================================================
	$('#chk_override_autogenerate').click(function(e){
		if($("#chk_override_autogenerate" ).is(":checked")) {
			alert("Please be careful when entering the ID number.\n\nTip: To insure accuracy, copy and paste from Workflows");
			$("#id_number").prop('disabled', false);
			$("#id_number").prop('readonly', false);
			$("#chk_override_autogenerate").prop('disabled', '');
			$("#id_number").val('');
			$("#id_number").focus();
			id_override = true;
		}else{
			$("#id_number").prop('disabled', true);
			$("#id_number").val(' (Auto-Generate)');
			id_override = false;
		}
	})	// END click override auto-generate checkbox
	
	
	//=============================================================
	// Preview and Print button click
	//=============================================================
	$('#btnPreviewAndPrint').click(function(c) {
		
		if( !$('#fname').val() ||  !$('#lname').val()) {
			alert("You need to enter at least the patron's first and last name!");
			return false;
		}		 
		// check for cropped image
		if( selected_width == 0 ||  selected_height == 0) {
			alert("You must crop the image first!");
			return false;
		}
		
		// if auto-number override is on, make sure the number entered is 14 digits long. 
		if( $('#chk_override_autogenerate').is(':checked')){
			var id_num_length =$('#id_number').val().length;
			if (id_num_length != 14){
				alert("The ID number needs to be 14 digits long. You entered " + id_num_length + '.');
				return false;
			}
		}  
		
		
		// check for valid ID number if using override
		// remove the cropper 
		//$('#snapshot').imgAreaSelect({remove:true});
		$('#window_print_preview').css('display', 'inline');
		
		$('#window_print_preview').css('display', 'inline');
		$('#window_print_preview').css('z-index', '210');
		$('#window_print_preview').css('width', preview_width);
		$('#view_front').removeAttr('disabled');
		$('#view_back').removeAttr('disabled');
		$('#print').removeAttr('disabled');

		//JQD.util.window_flat();
		$('#window_print_preview').addClass('window_stack');
		//$('#icon_dock_print').show('fast');
		id_number = update_id();
	
		// Generate the new card based on the above data
		update_card_preview();
	});

	//=============================================================
	// View Front click 
	//=============================================================
	$('#view_front').click(function(c) {
		// this needs to be something like this instead:
		$('#card_preview_image').attr("src","assets/php/show_card_image.php?side=front&session_id="+session_id);
		
	});	// END View Front click 

	//=============================================================
	// View Back click 
	//=============================================================
		$('#view_back').click(function(c) {
		// this needs to be something like this instead:
		$('#card_preview_image').attr("src","assets/php/show_card_image.php?side=back&session_id="+session_id);
	}); // END View Back click 


	//=============================================================
	// About ID Generator Menu click
	//=============================================================
	$('#about_id_generator').click(function(event){
		event.preventDefault();
		alert('Willard web-based ID generator - written by Mark Ehle')
		return false;
	}) // END About ID Generator Menu click

	//=============================================================
	// View Sesion Vars Menu click
	//=============================================================
	$('#ViewSessionVars').click(function(event){
		event.preventDefault();
		$.get("/assets/php/session_vars.php", function( data ) {
		$("<div>"+data+"</div>").dialog({
			height:400,
			width:650,
			modal:true
		});
		//alert( "Data Loaded: " + data );
		return false;
		}
	);

	}) // END About ID Generator Menu click
	
	
	//=============================================================
	// Print button click
	//=============================================================
	$('#print').click(function(c) {
		//$('#print').removeAttr('background_color')
		$('#print').attr('disabled', true)
		var call_workflows = $('#chkbxCallWorkflows').attr('checked'); 
		var paste_id = $('#chkbxPasteID').attr('checked'); 

		if (call_workflows == 'checked'){
			var call_workflows = "Y";
		}else{
			var call_workflows = "N";
		}

		if (paste_id == 'checked'){
			var paste_id = "Y";
		}else{
			var paste_id = "N";
		}

		// just make call paste_id permenant
		var paste_id = "Y";
		
		var patron_fname = $('#fname').val();
		var patron_lname = $('#lname').val();
		var patron_mname = $('#mname').val();

		var brightness_val =  $("#brightness").slider( "value" );
		var contrast_val = $("#contrast").slider( "value" );

		var id_number = update_id()
		$.post("assets/php/print.php", { 
			session_id: session_id, 
			patron_fname: patron_fname,
			patron_lname: patron_lname,
			patron_mname: patron_mname,
			selected_width: selected_width,
			selected_height: selected_height,
			selected_x1: selected_x1,
			selected_x2: selected_x2,
			selected_y1: selected_y1,
			selected_y2: selected_y2,
			brightness:brightness_val,
			contrast:contrast_val,
			patron_id: id_number,
			call_workflows: call_workflows,
			location_printer: location_printer,
			paste_id: paste_id
			},
			function(data) {
				$('#start_over').click();
		}
		);
	}); // end print button click
	
	//=============================================================
	// Reset Icon click
	//=============================================================
	$('#reset_icon').click(function(c) {
		$('#start_over').click();
	}); // END Reset Icon click

	
	//=============================================================
	// Update Card Preview function
	//=============================================================
	function update_card_preview() {
		if (id_override){
			$('#id_number_print_message').html('(ID card will be printed with ID number shown)');
		}else{
			$('#id_number_print_message').html('(ID number will be assigned upon print)');
		}
		//$('#snapshot').imgAreaSelect({remove:true});
		$('#card_preview_image').attr("src","/assets/images/crop_ajax-loader.gif");
		$('#print').hide();
		$('#view_front').hide();
		$('#view_back').hide();
		$('#start_over').hide();
		var canvas = document.getElementById('preview');
		var data_url = canvas.toDataURL("image/jpeg");
		// generate the FOTD, print to image, display front side
		id_number = update_id()
		$.post("assets/php/generate_card.php", { 
			patron_fname: $('#fname').val(),
			patron_lname: $('#lname').val(),
			patron_mname: $('#mname').val(),
			patron_mugshot: data_url,
			barcode: id_number
			},
			function(data) {
				var d = new Date();
				var miliseconds = d.getMilliseconds(); 
				// we need to show the back side first, because we swapped the back and the front in the template 
				// so that the overlay would print on both sides
				$('#card_preview_image').attr("src","assets/php/show_card_image.php?side=front&time=" + miliseconds)
				$('#print').show();
				$('#view_front').show();
				$('#view_back').show();
				$('#start_over').show();
		});
	} // END Update Card Preview function

	
	//============================================================
	// update id number
	//============================================================
	function update_id(){
		if ( $("#chk_override_autogenerate" ).is(":checked")) {
			return $('#id_number').val();
		}else{}
			return 'auto_generate';
	} // END update id number

	
	//=============================================================
	// Log off icon click 
	//============================================================
	$('#logoff_icon').click(function(event){
	$.get( "/assets/php/wipe_session.php", function( data ) {
			window.location='/patron/'
	});
		
	}); // END Log off icon click 
	
	//=============================================================
	// Log off Menu click 
	//============================================================
	$('#mnuLogOut').click(function(event){
		$('#logoff_icon').click();
	});	// END Log off Menu click 

	//=============================================================
	// Start Over button click
	//=============================================================
	$('#start_over').click(function(c) {
		window.location='/patron/';
	}); // END Start Over button click

	//=============================================================
	// Start Over Menu click
	//============================================================
	$('#mnuStartOver').click(function(event){
		$('#start_over').click();
	}); // END Start Over Menu click
	
	
	$('#mnuReprintLastCard').click(function(event){
		event.prevenDefault;
		$.post("/assets/php/reprint_last_card.php", function( data ) {
			alert(data);
		});
		return false;
	});
	/*
	$('#mnuFixTypo').click(function(event){
		event.prevenDefault;
		// check to see if there is a card to reprint
		$.post("/assets/php/check_for_card.php", function( data ) {
			if(data == 'card_available'){
				$('#divReprintList').load('assets/php/edit_data_form.php').dialog({
					modal:true,
					autoOpen: true,
					draggable: false,
					height: 175,
					width: 435,
					title: "Edit info on last card printed and reprint",
					buttons: {
						  'Reprint':  function() {
							  $(this).dialog("close");
							  reprint_card();
						  },
						  Cancel: function() {$(this).dialog("close");},
					   }
					});
			}else{
				alert('Sorry - there is no prior card data available');
			}
		});

		//$.post("/assets/php/reprint_last_card.php", function( data ) {
		//	alert(data);
		//});
		return false;
	});
*/
	function reprint_card(){
		$jsonEditDataForm = $('#frmEditData').serialize();
		$.ajax({
			url: "assets/php/print.php",
			type: "post",
			data: $jsonEditDataForm,
			dataType: "json",
			success: function(d) {
				alert(d);
			}
		});
		alert('reprinted');
		
	}

	//=========================================================================
	// Choose card to reprint
	//=========================================================================
	$('#mnuReprintChooseCard').click(function(event){
		event.preventDefault;
		var reprintChooserDialog = $('#divReprintList').load('/assets/php/show_reprint_list.php').dialog({
					modal:true,
					autoOpen: false,
					draggable: false,
					height: 600,
					width: 330,
					title: "Click on a Card to Reprint",
					buttons: {
						 Cancel: function() {reprintChooserDialog.dialog("close");},
					   }
			});
		reprintChooserDialog.dialog('open');
		return false;
	});

	
	//=========================================================================
	// Load card to reprint Info
	//=========================================================================
	$(document).on('click', '.loadReprintChoice', function(e){
		e.preventDefault;
		take_snapshot();
		//var selection = new Object();
		
		var card_id = this.getAttribute("data-cardID") ;
		var card_info;
		$(".ui-dialog-content").dialog("close");
		$.post( "/assets/php/get_reprint_data.php", { card_id: card_id})
		.done(function( data ) {
			
			card_info = JSON.parse(data)
			// load the name and card # info
			$('#fname').val(card_info.patron_fname);
			$('#mname').val(card_info.patron_mname);
			$('#lname').val(card_info.patron_lname);
			$('#id_number').val(card_info.patron_id);
			// load the image
			$('#snapshot').attr('src',"/assets/php/show_snapshot_image.php?ID="+card_info.ID);
			// copy snapshot to canvas
			
			var canvas = document.getElementById('canvas');
			var ctx = canvas.getContext('2d');
			ctx.drawImage(document.getElementById('snapshot'),0,0);
			// blank out preview initially, so as not to be confusing if a previous crop was shown
			var canvas = document.getElementById('preview');
			var context = canvas.getContext('2d');
			context.clearRect(0, 0, canvas.width, canvas.height);
			
			// recrop
			$('#snapshot').imgAreaSelect({remove:true});
			$('#snapshot').imgAreaSelect({ 
				aspectRatio: '3:4', 
				handles: true,
				zIndex:20,
				onSelectChange: updatePreview,
				onSelectEnd: updatePreview,
				onInit: updatePreview,
				x1:card_info.selected_x1,
				x2:card_info.selected_x2,
				y1:card_info.selected_y1,
				y2:card_info.selected_y2
			});
			$('#chk_override_autogenerate').prop( "checked", true );
			$('#id_number').prop( "readonly", true );
			$("#chk_override_autogenerate").prop('disabled', 'disabled');
			$("#contrast").slider('value', card_info.contrast);
			$("#brightness").slider('value', card_info.brightness);
			applyFilters();
		});
	});	// END Load card to reprint Info
	
	//=========================================================================
	//  take_snapshot function
	//=========================================================================
	function take_snapshot() {
		$.playSound('/assets/sounds/shutter.mp3');
		$('#shutter_sound').remove();
		canvas = document.getElementById("canvas"),
		context = canvas.getContext("2d"),
		context.drawImage(video, 0, 0, 640, 480);
		$('#canvas').show('fast');
		$('#video').hide('fast');
		$('#btnRetake').show();
		
		//clear the thumbnail preview window
		//preview_canvas = document.getElementById("preview");
		//ctx = preview_canvas.getContext("2d");
		//ctx.clearRect(0, 0, preview_canvas.width, preview_canvas.height);
			
		$('#mugshot_preview_holder').css('display','block')
		$('#btnRetake').prop('disabled',false)
		$("#btnTakeSnapshot").css('background-color', '')
		$("#btnTakeSnapshot").css('color', '')
		$("#btnTakeSnapshot").prop('disabled', true)
		$("#btnUseThisShot").prop('disabled', false)
		$("#btnUseThisShot").css('background-color', 'green')
		$("#btnUseThisShot").css('color', 'white')
		$("#btnRetake").css('background-color', 'yellow')
		$("#btnRetake").prop('disabled', false)
		$('#btnPreviewAndPrint').show();
		$('#btnTakeSnapshot').hide();
		$('#divImageAdjust').show();

		var canvas = document.getElementById('canvas');// source canvas
		var context = canvas.getContext("2d");
		var data_url = canvas.toDataURL("image/jpeg");

		 $.ajax({
			type: "POST",
			url: "/assets/php/save_snapshot.php",
			data: {snapshot_image: data_url}
		});

		$('#snapshot').attr('src',data_url);
		$('#video').hide();
		$('#canvas').hide();
		$('#snapshot').show();
		selected_width = 0;
		selected_height = 0;
		$('#snapshot').imgAreaSelect({ 
			aspectRatio: '3:4', 
			handles: true,
			zIndex:20,
			onSelectChange: updatePreview,
			onSelectEnd: updatePreview,
			onInit: updatePreview
		});
	}//  END take_snapshot function

	//=========================================================================
	// untake_snapshot function
	//=========================================================================
	function untake_snapshot(){
	   $('#snapshot').imgAreaSelect({remove:true});
		var canvas = document.getElementById('preview');
		var context = canvas.getContext('2d');
		context.clearRect(0, 0, canvas.width, canvas.height);
		canvas = document.getElementById('canvas');
		context = canvas.getContext('2d');
		context.clearRect(0, 0, canvas.width, canvas.height);
		$('#snapshot').attr('src', '#');
		$("#btnTakeSnapshot").prop('disabled', false)
		$("#btnTakeSnapshot").css('background-color', 'green')
		$("#btnTakeSnapshot").css('color', 'white')
		$("#btnUseThisShot").prop('disabled', true)
		//$("#btnRetake").prop('disabled', true)
		$("#btnUseThisShot").css('background-color', '')
		$("#btnUseThisShot").css('color', '')
		//$("#btnRetake").css('background-color', '')
		$('#btnPreviewAndPrint').hide();
		$('#mugshot_preview_holder').css('display', 'none');
		$('#btnRetake').hide();
		$('#video').show('fast');
		$('#canvas').hide('fast');
		$('#snapshot').hide('fast');
		$('#btnTakeSnapshot').show('fast');
		$('#divImageAdjust').hide();
		$('#btnImageReset').click();
	}// END untake_snapshot function

	//=========================================================================
	//  updatePreview function
	//=========================================================================
	function updatePreview(img, selection){
		// from http://stackoverflow.com/questions/15032973/how-can-i-change-out-an-image-using-camanjs
		$("#preview").removeAttr("data-caman-id");
		var imageObj = $("#snapshot")[0];
		var canvas = $("#preview")[0];
		var context = canvas.getContext("2d");
		if (imageObj != null && selection.x1 != 0 && selection.y1 != 0 && selection.width != 0 && selection.height != 0) {
			// update these vars for the database
			selected_width = selection.width;
			selected_height = selection.height;
			selected_x1 = selection.x1;
			selected_x2 = selection.x2;
			selected_y1 = selection.y1;
			selected_y2 = selection.y2;
			context.drawImage(imageObj, selection.x1, selection.y1, selection.width, selection.height, 0, 0, canvas.width, canvas.height);
		}
	}

	//=========================================================================
	//  initialize_camera function
	//=========================================================================
	function initialize_camera(){
		// Put event listeners into place
		// Grab elements, create settings, etc.
		canvas = document.getElementById("canvas"),
		context = canvas.getContext("2d"),
		video = document.getElementById("video"),
		videoObj = { "video": true },
		errBack = function(error) {
			console.log("Video capture error: ", error.code); 
		};

		// Put video listeners into place
		if(navigator.getUserMedia) { // Standard
			navigator.getUserMedia(videoObj, function(stream) {
				video.src = stream;
				video.play();
			}, errBack);
		} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
			navigator.webkitGetUserMedia(videoObj, function(stream){
				video.src = window.webkitURL.createObjectURL(stream);
				video.play();
			}, errBack);
		}else if(navigator.mozGetUserMedia) { // Firefox-prefixed
			navigator.mozGetUserMedia(videoObj, function(stream){
				video.src = window.URL.createObjectURL(stream);
				video.play();
			}, errBack);
		}

		// Trigger photo take
		document.getElementById("btnTakeSnapshot").addEventListener("click", function() {
			take_snapshot();
		});
	} // end initialize_camera

	// Card type radio input handler
	$('input:radio').change(function(c) {
		c.preventDefault;
		var varname = $(this).attr('id') ;
		var varvalue  = $(this).val();
		
		$.post("/assets/php/session_set.php?varname=" + varname + "&varval=" + varvalue, function( data ) {
			if (varname == 'card_type'){
				window.location.replace("/" + varvalue);
			}else{
				location.reload(true);
			}

		 });
		return false;
	});

function executeQuery(session_update_period) {
	var cachebuster = Math.round(new Date().getTime() / 1000);
	$.ajax({
		url: '/assets/php/session_refresh.php?time='+cachebuster,
		success: function(data) {
		var session = JSON.parse(JSON.stringify(data));
		console.log(session);
		if (session_id != session.session_id){
			alert("Session has timed out - please close this browser and reopen to continue.")
		}

    }
  })
   setTimeout(function(){executeQuery(session_update_period);}, session_update_period); // you could choose not to continue on failure...
}

//=========================================================================
}); // END DOCUMENT READY
//=========================================================================
