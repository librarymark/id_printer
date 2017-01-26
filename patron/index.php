<?PHP
session_start();

//======================================
// this is the patron card index page:
$_SESSION['card_type'] = 'patron';
//======================================
unset($_SESSION['ID']);
unset($_SESSION['patron_id']);
unset($_SESSION['patron_fname']);
unset($_SESSION['patron_mname']);
unset($_SESSION['patron_lname']);
include_once('/var/www/html/assets/php/index_header.php');

$_SESSION['location_abbr'] = 'wpl';
?>
<html>
<head>
	<title>Willard Library <?PHP echo $_SESSION['card_type']; ?> ID Generator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<link rel="icon" href="/favicon.ico" type="image/x-icon"> 

<?PHP 
// bring in the css and the javascript files here
include_once('/var/www/html/assets/php/css_javascript_includes.php');
?>

</head>
<body>
	<div class="abs" id="wrapper">
		<div class="abs" id="desktop">
			<a class="abs icon" style="left:20px;top:20px;" href="#icon_dock_webcam">
				<img src="/assets/images/icons/webcam.png" /><?PHP echo $_SESSION['card_type']; ?> Data Entry
			</a>
			<a ID="reset_icon" class="abs icon" style="left:20px;top:120px;" href="#">
				<img src="/assets/images/icons/icon_reset_16.png" />Start Over
			</a>
			<!--
			<a ID="reset_icon" class="abs icon" style="left:20px;top:200px;" href="#">
				<img src="/assets/images/icons/file_edit.png" />Edit/Reprint Last Card
			</a>
			-->
			<div id="window_webcam" class="abs window window_camera">
				<div class="abs window_inner">
					<div class="window_top">
						<span class="float_left">
							<img src="/assets/images/icons/webcam_16.png" /><?PHP echo $_SESSION['card_type']; ?> Data Entry
						</span>
						<span class="float_right">
							<a href="#icon_dock_webcam" class="window_close"></a>
						</span>
					</div>
					<div class="abs window_content">
						<div class="window_aside">
							<BUTTON ID="btnTakeSnapshot" style="width:150px; color:white; background-color:green;">Take Picture</BUTTON>
							<BUTTON ID="btnRetake" style="width:150px;"  disabled="disabled" >Re-take</BUTTON>
							<p>&nbsp;</p>
							<div id='mugshot_preview_holder' style='text-align:center; display:none;'>
							Identification Photo Preview
							<canvas 
								style="	border: 10px solid white; 
										text-align:center; 
										width:135px;
										height:180px;
										overflow:hidden;" 
										id="preview">
							</canvas>
							<p>&nbsp</p>
							</div>
							<p>
							
							<div id='divImageAdjust'>
							<div id='brightness'></div>
							<div style='text-align:center;'>Brightness</div>
							<div id='contrast'></div>
							<div style='text-align:center;'>Contrast</div>
							<Button class='image_adjust' style="width:150px;" ID="btnImageReset" name="btnImageReset" >Reset</button>&nbsp;<p>
							</div>
							
							<Button style="width:150px;" ID="btnPreviewAndPrint" name="btnPreviewAndPrint" >Preview & Print</button>&nbsp;<p>
						</div>
						<!-- this is the camera div --->
						<div id='video_holder' class="window_main" style='position:absolute; top:0px; left:4px; width:640px;height:480px;'>
							<video  width="640" height="480" id='video' style='position:absolute; top:0px; left:4px; width:640px;height:480px;'></video>
							<canvas width="640" height="480" id='canvas' ></canvas>
							<img id='snapshot' src='' width="640" height="480" id='canvas' ></canvas>
						</div>
						<div id='divDataEntry' style='position:absolute; top:483px; left:179px;'>
						
						<table id='data_entry_table'>
							<tr>
							<td style=""><input style='width:150px;'  ID="fname"  name="fname"><br></td>
							<td style=""><input style='width:150px;' ID="mname"  name="mname"></td>
							<td style=""><input style='width:150px;' ID="lname"  name="lname"></td>
							<td><input disabled="disabled" style='width:105px;' ID="id_number"  maxlength = '14' name="id_number" value=' (Auto-Generate)'></td>
							<td><small><input type='checkbox' id='chk_override_autogenerate'>Override</small></td>
							</tr>
							<tr>
							<tr>
							<td><small>First Name</small></td><td><small>Middle</small></td><td><small>Last Name</small></td><td><small>ID #</small></td>
							</table>
						
							</div>
					</div>
				</div>
				<span class="abs ui-resizable-handle ui-resizable-se"></span>
			</div>
			
			<div id="window_data_entry" class="abs window window_data_entry">
				<div class="abs window_inner">
					<div class="window_top">
						<span class="float_left">
							<img src="/assets/images/icons/data_entry_16.png" />Name entry
							</span>
							<span class="float_right">
							<a href="#icon_dock_data_entry" class="window_close"></a>
						</span>
					</div>
					<div id="data_entry_form" class="abs window_content">
						<div class="window_aside align_center">
							<button ID="save_data">Save and Continue</button>
							<p>&nbsp;</p>
							<div style='text-align:justify;'>
							<b>You must enter at least the first and last name.</b> If you want to 
							manually specify a number, click the 'Override Auto-Generate ID' checkbox and enter the number you wish to use.							
							</div>
						</div>
						
						<div class="window_main">
							<p>&nbsp;</p>
							<div style='margin:3px;'>
							</div>
						</div>
					</div>
					<div class="abs window_bottom">Enter the requred data and press 'Save and Continue'</div>
				</div>
				<span class="abs ui-resizable-handle ui-resizable-se">
				</span>
			</div>
			<div id="window_print_preview" class="abs window window_print_preview">
				<div class="abs window_inner">
					<div class="window_top">
						<span class="float_left">
							<img src="/assets/images/icons/print_preview_16.png" />Preview & Print
						</span>
						<span class="float_right">
							<a href="#icon_dock_print" class="window_close"></a>
						</span>
					</div>
					<div class="abs window_content">
						<div class="window_aside">
						<!-- this next two lines might look weird, but we had to swap the back for the front
						so that the overlay would print both sides
						-->
						
						<Button style="width:150px;" ID="view_front" name="view_front"  disabled>View Front</button>&nbsp;<p>
						<Button style="width:150px;" ID="view_back" name="view_back" disabled>View Back</button>&nbsp;<p>
							
							<Button style="width:150px; background-color:green" ID="print" name="print"  disabled>Print</button>&nbsp;<p>
							<Button style="width:150px; background-color:yellow" ID="start_over" name="start_over" title="restart camera">Start Over</button>&nbsp;<p>
							
						</div>
						<div class="window_main">&nbsp;
							<img ID="card_preview_image" style="border:1px solid; border-color:000; height:424px; width:674px;" src="/assets/images/no_preview_yet.png">
						</div>
					</div>
					<div class="abs window_bottom" id='id_number_print_message'>
					(ID number will be assigned upon print)
					<!-- <input type="checkbox" ID="call_workflows" >Call Workflows on print -->
					</div>
				</div>
				<span class="abs ui-resizable-handle ui-resizable-se"></span>
			</div>
		</div>
		<div class="abs" id="bar_top"><span class="float_right" id="clock"></span>
			<ul>
				<li>
					<a class="menu_trigger" href="#">File</a>
					<ul class="menu">
						<li>
							<a id='mnuStartOver' >Start Over</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="menu_trigger" href="#">Reprint</a>
					<ul class="menu">
						<li>
							<a id='mnuReprintLastCard' >Reprint Last Card</a>
						</li>
						<li>
							<a id='mnuReprintChooseCard' >Edit/Reprint Card</a>
						</li>

					</ul>
				</li>
				
				<li>
					<a class="menu_trigger" href="#">Config</a>
					<ul class="menu">
						<li><input type="checkbox" ID="chkbxCallWorkflows" >Call Workflows with Print</li>
						<li><input type="checkbox" ID="chkbxPasteID" >Copy User ID to Clipboard on Print</li>
					</ul>
				</li>

				<li>
				<a class="menu_trigger" href="#">Location</a>
					<ul class="menu">
					<?PHP foreach ($locations as $location){ ?>
						<li><input type="radio" name='location_abbr' value='<?PHP echo $location->abbr; ?>' ID="location_abbr" <?PHP if($location->abbr == $_SESSION['location_abbr']) echo "CHECKED"; ?> ><?PHP echo $location->name; ?></li>
					<?PHP } ?>
					</ul>
				</li>
				<li>
					<a class="menu_trigger" href="#">Card type</a>
					<ul class="menu">
					<?PHP foreach ($card_types as $card_type){ ?>
						<li><input type="radio" name='card_type' value='<?PHP echo $card_type->type; ?>' ID="card_type" <?PHP if($card_type->type == $_SESSION['card_type']) echo "CHECKED"; ?>> <?PHP echo $card_type->name ?></li>
					<?PHP } ?>
					</ul>
				</li>

				<li>
					<a class="menu_trigger" href="#">Help</a>
					<ul class="menu">
						<li>
						<a href="" id="ViewSessionVars">View Session vars...</a>
						</li>
						<li>
						<a href="" id="about_id_generator">About ID Generator...</a>
						</li>
						
					</ul>
				</li>
			</ul>
		</div>
		<div class="abs" id="bar_bottom">
			<a class="float_left" href="#" id="show_desktop" title="Show Desktop">
				<img src="/assets/images/icons/icon_22_desktop.png" />
			</a>
			<ul id="dock">
				<li id="icon_dock_webcam">
					<a href="#window_webcam"><img src="/assets/images/icons/webcam_16.png" />Camera</a>
				</li>
				<li id="icon_dock_print">
					<a href="#window_print_preview"><img src="/assets/images/icons/print_preview_16.png" />Preview & Print</a>
				</li>
			</ul>
			<span id='timeout' style="float:left; font-size:10px;"></span> 
			
			<span id='id_number' style="float:right; ">
				<?PHP echo $_SESSION['location_name']; ?> - <?PHP echo $_SESSION['card_type']; ?> - <?PHP echo $_SESSION['station_ip']; ?> - <?PHP echo $_SESSION['station_hostname']; ?> 
			</span>
			
		</div>
	</div>
	</body>
</html>
<div id='divSessionVars'></div>
<div id='divFixTypoForm'></div>
<div id='divChooseCard'></div>
<div id='divReprintList'></div>
</div>


