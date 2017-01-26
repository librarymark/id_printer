<?php
/***
**** @class: mysql_dialog
**** @version: 1.4;
**** @author: Giorgos Tsiledakis;
**** @date: 2004-08-25;
**** @license: GNU GENERAL PUBLIC LICENSE;

***/
class mysql_dialog {
var $msg1="Not connected to MySQL Server! Please check your connection data or call function \"connect()\" first";
var $msg2="Please check your SQL statement or call function \"speak()\" first!";

var $errors=""; // the last error occured;
var $rows="";  // number of rows of the query, created by listen();
var $fields=""; // number fields of the query, created by listen();
var $printerror=false;
var $error_id=false;
var $con=false;
var $sql_id=false;

/*##
#### Call first Class Constructor mysql_dialog() to beginn;
#### If some value!=0 is passed to mysql(),
#### the errors occured, after each function is called, will be printed in the main script 
##*/
function mysql_dialog($mode=false) {
if ($mode){
$this->printerror=true;
}
}

/*##
#### Call then connect("mysqlhost","mysqluser","mysqlpasswd","name of mysql database")
#### it returns some public $con or creates errors;
##*/
function connect($host=false, $user=false, $pass=false, $dbname=false) {
$con=@mysql_connect($host, $user, $pass);
if (!$con) {
$this->makeerror();
return false;
}
$this->con=$con;
$db=@mysql_select_db($dbname, $con);
if (!$db) {
$this->makeerror();
return false;
}
return $this->con;
}

/*##
#### Call speak("SQL STRING") to send some sql query to the database;
#### it returns some public $sql_id, or creates errors;
##*/
function speak($sql=false) {
if (!$this->con) {
$this->error_id=$this->msg1;
$this->makeerror();
return false;
}
if ($this->sql_id) {
@mysql_free_result($this->sql_id);
}
$sql_id=mysql_query($sql, $this->con);
$this->sql_id=$sql_id;
return $this->sql_id;
}

/*##
#### Call listen() to get the result of the query;
#### it returns an array with the results of the query, or creates errors;
#### listen() must be called after speak("SQL STRING") was called;
##*/
function listen() {
if (!$this->con) {
$this->error_id=$this->msg1;
$this->makeerror();
return false;
}
if (!$this->sql_id) {
$this->error_id=$this->msg2;
$this->makeerror();
return false;
}
$data=@mysql_fetch_array($this->sql_id, MYSQL_BOTH);
$this->rows=@mysql_num_rows($this->sql_id);
$this->fields=@mysql_num_fields($this->sql_id);
return $data;
}

/*##
#### Call onscreen("SQL STRING") to print a table with the result of the query;
##*/
function onscreen($sql=false) {
$this->speak($sql);
echo ("<table border=\"1\" cellpadding=\"4\"><tr>");
while ($fields=@mysql_fetch_field($this->sql_id)) {
echo ("<th align=\"left\">$fields->name</th>");
}
echo ("</tr>\n");
while ($rows = $this->listen()) {
echo ("<tr>");
for ($x=0; $x<@mysql_num_fields($this->sql_id); $x++) {
echo ("<td align=\"left\">".htmlentities($rows[$x])."</td>");
}
echo ("</tr>\n");
}
echo ("</table>");
}

/*##
#### Function makeerror() is called whenever some error has occured;
#### If there is any error_id, it returns the user specified messages $msg1, $msg2,
#### else it returns the mysql error number and message;
#### If $printerror is true, the error message will be printed in the main script;
##*/
function makeerror() {
if (!$this->error_id) {
if (mysql_errno()){
$result="<b>" .mysql_errno(). " :<font color=\"red\">" . mysql_error(). "</font></b><br>";
$this->errors=$result;
if ($this->printerror){
echo $result;
}
return $result;
exit;
}
}
else {
$result="<b><font color=\"red\">$this->error_id</font></b><br>";
$this->errors=$result;
if ($this->printerror){
echo $result;
}
return $result;
}
}

}
?>