<?PHP
session_start();
ini_set('memory_limit', '512M');
include_once("/var/www/html/assets/php/id_generator.inc.php");
include_once("/var/www/html/assets/php/id_generator_common_functions.php");
include_once("/var/www/html/assets/php/ezsql/shared/ez_sql_core.php");
include_once("/var/www/html/assets/php/ezsql/mysql/ez_sql_mysqli.php");
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

//$patron_lname = addslashes($patron_lname);
//$patron_fname = addslashes($patron_fname);
//$patron_mname = addslashes($patron_mname);

$generate_new_card_front_image = $card_images_generator . " \"{$patron_lname}\" \"{$patron_fname}\" \"{$patron_mname}\" {$next_id_number} {$station_ip} \"{$card_template_file}\"  \"{$card_images_generator}\" ";

//error_log("command: ".$generate_new_card_front_image);

$result = shell_exec($generate_new_card_front_image);

//=============================================
// get all the images associated with this card
//=============================================
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

$patron_fname = addslashes($patron_fname);
$patron_mname = addslashes($patron_mname);
$patron_lname = addslashes($patron_lname);

// put patron data into patron_records table
$query = "
INSERT INTO patron_records( 
ID, 
patron_id,
patron_fname,
patron_mname,
patron_lname,
mugshot_dimensions,
mugshot_image,
fotd,
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
'$next_id_number',
'$patron_fname',
'$patron_mname',
'$patron_lname',
'$mugshot_image_size[3]',
'$mugshot_imgData',
'$fotd_data',
'$card_front_image_size[3]',
'$card_front_imgData',
'$card_back_image_size[3]',
'$card_back_imgData',
now(),
'$station_ip',
'$station_hostname'
)";

$db->query($query);

//send message to remote paster...
$message = $call_workflows. "@@" . $paste_id . "@@" . $next_id_number . "@@" . $patron_lname . '@@' . $patron_fname . '@@' . $patron_mname;
error_log($message);
send_message($message, $remote_paster_port);

//====================================================================================================
// print the card here... (this is the only place in the project that uses libre office headless printing)
//====================================================================================================
//$print_result = shell_exec('sudo ' . $libre_office . " --headless --pt {$location_printer} {$fotd_file}");
$_SESSION['patron_id'] = $next_id_number;
echo $next_id_number ." " ;

