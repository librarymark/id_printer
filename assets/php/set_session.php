<?PHP
session_start();

$var = $_REQUEST['var'];
$val = $_REQUEST['val'];

$_SESSION[$var] = $val;

