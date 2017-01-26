<?PHP
session_start();
ini_set('memory_limit', '512M');
include_once("/var/www/html/assets/php/id_generator.inc.php");
include_once("/var/www/html/assets/php/id_generator_common_functions.php");

$db = new ezSQL_mysqli($user,$pass,$db,$host);

$card_template_file = $db->get_var("SELECT template_file FROM card_types WHERE type = 'patron'");
$card_images_generator = $db->get_var("SELECT card_images_generator FROM card_types WHERE type = 'patron'");



$fotd_file = $_SESSION['var_folder'] . '/' . $_SESSION['station_ip'] .'.fotd';

if (file_exists($fotd_file)){
// DISABLED FOR TESTING
$print_result = shell_exec('sudo ' . $libre_office . " --headless --pt {$_SESSION['location_printer']} {$fotd_file}");
	echo "Card reprinted. Check the printer.";
}else{
	echo "No card was found to reprint. You must print it first!";
}


