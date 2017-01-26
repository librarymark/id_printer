<?PHP
session_start();
ini_set('memory_limit', '512M');
include_once "/var/www/html/assets/php/id_generator.inc.php";

$fotd_file = $_SESSION['var_folder'] . '/' . $_SESSION['station_ip'] .'.fotd';

if (file_exists($fotd_file)){
    $print_result = shell_exec('sudo ' . $libre_office . " --headless --pt {$_SESSION['location_printer']} {$fotd_file}");
    echo "Card reprinted. Check the printer.";
}else{
    echo "No card was found to reprint. You must print it first!";
}


