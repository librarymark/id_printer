<?php
session_start();
include_once "/var/www/html/assets/php/id_generator.inc.php";

$side = $_REQUEST['side'];

switch ($side) {
    case 'front':
        $side = '0';
        break;
    case 'back':
        $side = '1';
        break;
}

$image_file = $var_folder . DS . $_SERVER['REMOTE_ADDR'] . '-'.$side . '.png';

$imginfo = getimagesize($image_file);
header("Content-type: {$imginfo['mime']}");
readfile($image_file);
?>
