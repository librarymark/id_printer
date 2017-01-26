<?PHP
session_start();
$var_name = $_REQUEST['varname'];
$var_val = $_REQUEST['varval'];
$_SESSION[$var_name] = $var_val;
error_log ("session_set.php: var_name:{$var_name} var_val:{$var_val}");
echo "done";
