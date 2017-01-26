<?PHP
session_start();
error_log("starting generate_card.php");

include_once "id_generator.inc.php";
ini_set('memory_limit', '64M');

$session_id = $_SESSION['sessionID'];//session_id();
$patron_lname = ucfirst($_POST['patron_lname']);
$patron_fname = ucfirst($_POST['patron_fname']);
$patron_mname = ucfirst($_POST['patron_mname']);
$barcode_text = $_POST['barcode'];
$imageData = $_POST['patron_mugshot'];

$img = str_replace('data:image/jpeg;base64,', '', $imageData);
$imageData = str_replace(' ', '+', $img);
$mugshot_data = base64_decode($imageData);

$mugshot_64 = $imageData;
$mugshot_file = $var_folder . DS .$session_id.'-cropped.jpg';

if (file_exists($mugshot_file)){
	unlink($mugshot_file);
}

$result = file_put_contents($mugshot_file, $mugshot_data);

if ($barcode_text == 'auto_generate'){
	$barcode_text = '00000000000000';
}

$_SESSION['patron_lname'] = $patron_lname;
$_SESSION['patron_fname'] = $patron_fname;
$_SESSION['patron_mname'] = $patron_mname;
$_SESSION['patron_id'] = $barcode_text;

$patron_mi = strtoupper(substr($patron_mname, 0,1));

$barcode_image = shell_exec($barcode_generator . ' ' . $barcode_text);
$barcode_image_64 = base64_encode($barcode_image);

// update the contents of the template file with the patron data
$xml_data = file_get_contents($card_template_filename);
$xml_data = str_replace("@@MUGSHOT_IMAGE_B64@@", $mugshot_64, $xml_data);
$xml_data = str_replace("@@BARCODE_IMAGE_64@@",  $barcode_image_64,   $xml_data);
$xml_data = str_replace("@@LAST_NAME@@",         $patron_lname,      $xml_data);
$xml_data = str_replace("@@FIRST_NAME@@",        $patron_fname,     $xml_data);
$xml_data = str_replace("@@MIDDLE_INITIAL@@",    $patron_mi, $xml_data);
$xml_data = str_replace("@@BARCODE_TEXT@@",      $barcode_text,   $xml_data);

$fodt_file = $var_folder . '/'. $session_id . ".fotd";
$fodt_file_handle = fopen($fodt_file, 'w');
fwrite($fodt_file_handle,$xml_data);
fclose($fodt_file_handle);

// generate the preview files
$generate_preview_pdf_cmd = "{$sudo} {$unoconv} -d document -o {$var_folder}/{$session_id}.pdf {$fodt_file}";
error_log($generate_preview_pdf_cmd);
exec($generate_preview_pdf_cmd, $result_generate_pdf);

$generate_preview_png_cmd = "{$sudo} {$convert} -density 300 {$var_folder}/{$session_id}.pdf {$var_folder}/{$session_id}.png";
exec($generate_preview_png_cmd, $result_generate_png);

$chmod_result = exec("{$sudo} chmod 777 " . $var_folder . '/*');
$chown_result = exec("{$sudo} chown www-data.www-data " . $var_folder.'/*');

