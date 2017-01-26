<?PHP
session_start();

$var = $_REQUEST['var'];
echo $_SESSION[$var]; 



