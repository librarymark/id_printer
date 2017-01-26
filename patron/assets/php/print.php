<?PHP
session_start();
//ini_set('memory_limit', '512M');
ini_set('memory_limit', '-1');
include_once("/var/www/html/assets/php/id_generator.inc.php");
include_once("/var/www/html/assets/php/id_generator_common_functions.php");
include_once("/var/www/html/assets/php/ezsql/shared/ez_sql_core.php");
include_once("/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php");
$db = new ezSQL_mysqli($user,$pass,$db,$host);

$card_template_file = $db->get_var("SELECT template_file FROM card_types WHERE type = 'patron'");
$card_images_generator = $db->get_var("SELECT card_images_generator FROM card_types WHERE type = 'patron'");

if ($_POST['call_workflows'] == 'Y'){
	$call_workflows = $_POST['call_workflows'];
}else{
	$call_workflows = "N";
}

if ($_POST['paste_id'] == 'Y'){
	$paste_id = $_POST['paste_id'];
}else{
	$paste_id = "N";
}

$session_id = $_SESSION['session_id'];
$station_ip = $_SERVER['REMOTE_ADDR'];
$station_hostname = shell_exec("nmblookup -A {$station_ip} | grep '<00>' | grep -v '<GROUP' |  awk -F ' ' '{print $1}'");
$location_printer = $_POST['location_printer'];

$patron_fname = ucfirst($_POST['patron_fname']);
$patron_mname = ucfirst($_POST['patron_mname']);
$patron_lname = ucfirst($_POST['patron_lname']);
$patron_id = trim($_POST['patron_id']);

$selected_width = $_POST['selected_width'];
$selected_height = $_POST['selected_height'];
$selected_x1 = $_POST['selected_x1'];
$selected_x2 = $_POST['selected_x2'];
$selected_y1 = $_POST['selected_y1'];
$selected_y2 = $_POST['selected_y2'];
$contrast = $_POST['contrast'];
$brightness = $_POST['brightness'];


// decide what number to use here...
if ($patron_id == 'auto_generate'){
	$next_id_number = get_next_id_number($db);
}else{
	$next_id_number = $patron_id;
}

// A new front image needs to be generated at this point with a correct ID number.
// first, delete the old files
unlink($var_folder . '/'. $station_ip . ".pdf");
unlink($var_folder . '/'. $station_ip . "-0.png");
unlink($var_folder . '/'. $station_ip . "-1.png");

$generate_new_card_front_image = $card_images_generator . " \"{$patron_lname}\" \"{$patron_fname}\" \"{$patron_mname}\" {$next_id_number} {$station_ip} \"{$card_template_file}\"  \"{$card_images_generator}\" ";

error_log("print.php|generate_new_card_front_image: ".$generate_new_card_front_image);

$result = shell_exec($generate_new_card_front_image);

//=============================================
// get all the images associated with this card
//=============================================

// grab the snapshot
$snapshot_file = $var_folder . DS .$station_ip.'-snapshot.jpg';
$snapshot_file_size = getimagesize($snapshot_file);
$fp = fopen($snapshot_file, 'r');
$snapshotimgData  = fread($fp, filesize($snapshot_file));
$snapshotimgData = addslashes($snapshotimgData);
fclose($fp);


// grab the patron mug shot
$mugshot_file = $var_folder . DS .$station_ip.'-cropped.jpg';
$mugshot_image_size = getimagesize($mugshot_file);
$fp = fopen($mugshot_file, 'r');
$mugshot_imgData  = fread($fp, filesize($mugshot_file));
$mugshot_imgData = addslashes($mugshot_imgData);
fclose($fp);

// Grab the card front image
$card_front_image_file = $var_folder . DS .$station_ip.'-0.png';
$card_front_image_size = getimagesize($card_front_image_file);
$fp = fopen($card_front_image_file, 'r');
$card_front_imgData  = fread($fp, filesize($card_front_image_file));
$card_front_imgData= addslashes($card_front_imgData);
fclose($fp);

// Grab the card back image
$card_back_image_file = $var_folder . DS .$station_ip.'-1.png';
$card_back_image_size = getimagesize($card_back_image_file );
$fp = fopen($card_back_image_file, 'r');
$card_back_imgData = fread($fp, filesize($card_back_image_file));
$card_back_imgData = addslashes($card_back_imgData);
fclose($fp);

// grab the FOTD file
$fotd_file = $var_folder . DS .$station_ip.'.fotd';
$fp = fopen($fotd_file, 'r');
$fotd_file_size = getimagesize($fotd_file);
$fotd_data  = fread($fp, filesize($fotd_file));
$fotd_data = addslashes($fotd_data);
fclose($fp);

// grab the PDF file
$pdf_file = "{$var_folder}/{$station_ip}.pdf";
$fp = fopen($pdf_file, 'r');
$pdf_file_size = getimagesize($pdf_file);
$pdf_data  = fread($fp, filesize($pdf_file));
$pdf_data = addslashes($pdf_data);
fclose($fp);

$patron_fname = addslashes($patron_fname);
$patron_mname = addslashes($patron_mname);
$patron_lname = addslashes($patron_lname);


// if this is a reprint, look for the old number and overwrite, otherwise start a new record

$find_query = "SELECT ID from patron_records where patron_id = '{$_SESSION['patron_id']}' LIMIT 1";

error_log("print.php find_query: {$find_query}");

$reprint_check = $db->get_var($find_query);

if ($reprint_check){
//reprint query
$query = "
UPDATE patron_records SET 
patron_fname = '{$patron_fname}',
patron_mname = '{$patron_mname}',
patron_lname = '{$patron_lname}',
mugshot_dimensions = '{$mugshot_image_size[3]}',
mugshot_image = '{$mugshot_imgData}',
snapshot_image = '{$snapshotimgData}',
selected_width = {$selected_width},
selected_height = {$selected_height},
selected_x1 = {$selected_x1},
selected_x2 = {$selected_x2},
selected_y1 = {$selected_y1},
selected_y2 = {$selected_y2},
brightness = {$brightness},
contrast = {$contrast},
fotd = '{$fotd_data}',
pdf = '{$pdf_data}',
front_image_dimensions = '{$card_front_image_size[3]}',
front_image = '{$card_front_imgData}',
back_image_dimensions = '{$card_back_image_size[3]}',
back_image = '{$card_back_imgData}',
timestamp = now(),
station_ip = '$station_ip',
station_hostname = '{$station_hostname}'
WHERE ID = '{$_SESSION['ID']}' AND patron_id = '{$_SESSION['patron_id']}' LIMIT 1
";

}else{

// new card query
$query = "
INSERT INTO patron_records( 
ID, 
patron_id,
patron_fname,
patron_mname,
patron_lname,
mugshot_dimensions,
mugshot_image,
snapshot_image,
selected_width,
selected_height,
selected_x1,
selected_x2,
selected_y1,
selected_y2,
brightness,
contrast,
fotd,
pdf,
front_image_dimensions,
front_image,
back_image_dimensions,
back_image,
timestamp,
station_ip,
station_hostname
)
VALUES(
NULL,
'{$next_id_number}',
'{$patron_fname}',
'{$patron_mname}',
'{$patron_lname}',
'{$mugshot_image_size[3]}',
'{$mugshot_imgData}',
'{$snapshotimgData}',
{$selected_width},
{$selected_height},
{$selected_x1},
{$selected_x2},
{$selected_y1},
{$selected_y2},
{$brightness},
{$contrast},
'{$fotd_data}',
'{$pdf_data}',
'{$card_front_image_size[3]}',
'{$card_front_imgData}',
'{$card_back_image_size[3]}',
'{$card_back_imgData}',
now(),
'{$station_ip}',
'{$station_hostname}'
)";
}

$db->query($query);

//send message to remote paster...
$message = $call_workflows. "@@" . $paste_id . "@@" . $next_id_number . "@@" . $patron_lname . '@@' . $patron_fname . '@@' . $patron_mname;
send_message($message, $remote_paster_port);

//====================================================================================================
// print the card here... (this is the only place in the project that uses libre office headless printing
// If you want to test without printing, comment out the shell_execmc line)
// 
//====================================================================================================
$print_command = 'sudo ' . $libre_office . " --headless --pt {$location_printer} {$fotd_file}";
error_log("print.php print command: ". $print_command);
$print_result = shell_exec($print_command);
$_SESSION['patron_id'] = $next_id_number;
echo $next_id_number ." " ;