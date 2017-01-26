<?PHP
session_start();
ini_set('memory_limit', '512M');
include_once("/var/www/html/assets/php/id_generator.inc.php");
include_once("/var/www/html/assets/php/id_generator_common_functions.php");
include_once("/var/www/html/assets/php/ezsql/shared/ez_sql_core.php");
include_once("/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php");
$db = new ezSQL_mysqli($user,$pass,$db,$host);
$card_id = $_POST['card_id'];
// write out the snapshot file

$snapshot_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'-snapshot.jpg';
$query = "SELECT snapshot_image FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$snapshot_image_data = $db->get_var($query);
unlink($snapshot_filename);
file_put_contents($snapshot_filename, $snapshot_image_data);
unset($snapshot_image_data );

$mugshot_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'-cropped.jpg';
$query = "SELECT mugshot_image FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$mugshot_image_data = $db->get_var($query);
unlink($mugshot_filename);
file_put_contents($mugshot_filename, $mugshot_image_data );
unset($mugshot_image_data);

$fodt_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'.fotd';
$query = "SELECT fotd FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$fotd_data = $db->get_var($query);
unlink($fodt_filename);
file_put_contents($fodt_filename , $fotd_data);
unset($fotd_data);

$front_image_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'-0.png';
$query = "SELECT front_image FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$front_image_data = $db->get_var($query);
unlink($front_image_filename);
file_put_contents($front_image_filename, $front_image_data);
unset($front_image_data);

$back_image_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'-1.png';
$query = "SELECT back_image FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$back_image_data = $db->get_var($query);
unlink($back_image_filename);
file_put_contents($back_image_filename, $back_image_data);
unset($back_image_data);

$pdf_filename = $var_folder . DS .$_SERVER['REMOTE_ADDR'].'.pdf';
$query = "SELECT pdf FROM patron_records WHERE ID = '{$card_id}' LIMIT 1";
$pdf_data = $db->get_var($query);
unlink($pdf_filename);
file_put_contents($pdf_filename, $pdf_data);
unset($pdf_data);

$query = "
SELECT 
ID,
patron_id, 
patron_lname, 
patron_fname, 
patron_mname,
selected_width, 
selected_height,
selected_x1,
selected_x2,
selected_y1,
selected_y2,
brightness,
contrast
FROM 
patron_records 
WHERE ID = '{$card_id}' LIMIT 1";
$card_data = $db->get_row($query);
$_SESSION['ID'] = $card_data->ID;
$card_data = json_encode($db->get_row($query));

echo $card_data;
