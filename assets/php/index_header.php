<?php 
include_once "/var/www/html/assets/php/ezsql/shared/ez_sql_core.php";
include_once "/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php";
include_once "/var/www/html/assets/php/id_generator.inc.php";

$db = new ezSQL_mysqli($user,$pass,$db,$host);

// get list of locations for use later
$location_set_query = "SELECT * from locations";
$locations = $db->get_results($location_set_query);

// get list of card typs for use later
$card_type_set_query = "SELECT * from card_types";
$card_types = $db->get_results($card_type_set_query);

if (isset($_SESSION['location_abbr'])){
	$location_abbr = $_SESSION['location_abbr'];
}else{
	$location_abbr = 'none';
}

$preview_width = $db->get_var('SELECT preview_width FROM card_types WHERE name = "'.$_SESSION['card_type'].'"');

$_SESSION['preview_width'] = $preview_width;

$_SESSION['var_folder'] = $var_folder;

// this stuff is always available
$_SESSION['session_id'] = session_id();
$_SESSION['station_ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['station_hostname'] = trim(shell_exec("sudo /usr/bin/nmblookup -A {$_SESSION['station_ip']} | grep '<00>' | grep -v '<GROUP' |  awk -F ' ' '{print $1}'"));
$_SESSION['http_var_folder'] = $http_var_folder;

// this stuff we need to find out from the user
$_SESSION['location_name'] = "none";
//$_SESSION['location_abbr'] = "none";
$_SESSION['location_printer'] = "none";
$_SESSION['location_wallpaper_image'] = "none";
//$_SESSION['http_var_folder'] = "none";

// if we know where we are, let's look up some stuff
if ($location_abbr <> "none"){
	$location_query = "SELECT * from locations where abbr = '{$location_abbr}'";
	$location_info = $db->get_row($location_query);
	$_SESSION['location_name'] = $location_info->name;
	$_SESSION['location_abbr'] = $location_info->abbr;
	$_SESSION['location_printer'] = $location_info->printer;
	$_SESSION['location_wallpaper_image'] = $location_info->wallpaper_image;

}
