<?php
include_once "/var/www/html/assets/php/ezsql/shared/ez_sql_core.php";
include_once "/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php";
include_once "/var/www/html/assets/php/id_generator.inc.php";
$db = new ezSQL_mysqli($user,$pass,$db,$host);

$id = $_REQUEST['ID'];

$query = "SELECT snapshot_image from patron_records WHERE ID = '{$id}' LIMIT 1";
//echo $query;


$image = $db->get_var($query);

header('Content-Type: image/png');
echo $image;
?>
