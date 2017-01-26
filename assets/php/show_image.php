<?php
include_once "/var/www/html/assets/php/ezsql/shared/ez_sql_core.php";
include_once "/var/www/html/assets/php/ezsql/mysqli/ez_sql_mysqli.php";
include_once "/var/www/html/assets/php/id_generator.inc.php";
$db = new ezSQL_mysqli($user,$pass,$db,$host);
$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

$query = "SELECT front_image from patron_records WHERE ID = '{$id}' LIMIT 1";

$image = $db->get_var($query);

header('Content-Type: image/jpeg');
echo $image;
?>
