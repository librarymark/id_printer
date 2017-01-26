<?PHP
session_start();
include_once("/var/www/html/assets/php/id_generator.inc.php");
include_once("/var/www/html/assets/php/id_generator_common_functions.php");


$fotd_file = $_SESSION['var_folder'].'/'.$_SESSION['station_ip'].'.fotd';

if (file_exists($fotd_file)){
	echo "card_available";
}else{
	echo "card_not_available";
}