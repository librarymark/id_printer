<?PHP

define('DS', DIRECTORY_SEPARATOR);

// MySQL stuff
$user = "id_cards";
$pass = "id_cards";
$db   = "id_cards";
$host = "localhost";

$remote_paster_port = 33891;

// default printer (for headless printing to file images the correct size)
$default_printer = "Zebra-ZXPS32-Ethernet-Printer-IP=192.168.1.18";

// paths
@$var_folder = "/var/www/html/var/".$_SERVER['REMOTE_ADDR'];
@$http_var_folder = "/var/".$_SERVER['REMOTE_ADDR'];

if (!file_exists($var_folder)) {
	$old = umask(0);
    mkdir($var_folder, 0777, true);
	umask($old);
}

// programs
$barcode_generator = '/var/www/html/assets/scripts/generate_barcode';
$card_images_generator = '/var/www/html/assets/scripts/generate_card_images';
$libre_office = '/usr/lib/libreoffice/program/soffice.bin';
$convert = "/usr/bin/convert";
$unoconv = '/usr/bin/unoconv';
$sudo =  '/usr/bin/sudo';


// $mugshot image manipulation
$mugshot_brightness_amount = 15; // set to 0 for none
$mugshot_contrast_amount = -15; // set to 0 for none

$mugshot_sharpen_mask = array(
	array(-1.2, -1, -1.2),
	array(-1, 20, -1),
	array(-1.2, -1, -1.2)
); 
