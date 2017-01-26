<?php
/***
**** @file: AdminPro Class Setup
**** @project: AdminPro Class
**** @version: 1.3;
**** @author: Giorgos Tsiledakis;
**** @date: 2004-09-16;
**** @license: GNU GENERAL PUBLIC LICENSE;
***/
include("adminpro_config.php");
include("mysql_dialog.php");
$action="";
$check=true;
extract($_POST);
?>
<html>
<head>
<title>AdminPro Class v1.3.x MySQL Table Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
input { border-style: none; font-family: Tahoma, Arial, sans-serif; font-size: 11px; background-color: #cecece}
.normal {  font-family: Helvetica, Tahoma, Arial, sans-serif; font-size: 12px; text-align: justify}
.small {  font-family: Helvetica, Tahoma, Arial, sans-serif; font-size: 10px}
.header {  font-family: Helvetica, Tahoma, Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: justify}
.config {  font-family: 'Courier New', Courier, Helvetica, Tahoma, Arial; font-size: 14px; font-weight: normal;}
.code {  font-family: 'Courier New', Courier, mono; font-size: 14px; font-weight: bold; color: #000099; text-align: left}
textarea#patches { border-style: none; font-family: Tahoma, Arial, sans-serif; font-size: 11px; color: #000099; background-color: #dddddd }
textarea#usersql { border-style: none; font-family: 'Courier New', Courier, Tahoma, Arial, sans-serif; font-size: 12px; color: #000099; background-color: #ffffff }
-->
</style>
</head>
<body bgcolor="#e9e9e9">
<p class="header">AdminPro Class MySQL Table Setup<br><span class="small">Author: Giorgos Tsiledakis</span></p>
<div align="justify" class="config">
<?php 
if ($action==""){
echo ("
<p class=\"normal\">Welcome to AdminPro Class MySQL Table Setup.<br> This script reads your configuration 
 in <span class=\"code\">adminpro_config.php</span> file and creates or updates the required table on your database,
 in order to use the AdminPro Class properly. Please make sure that the configuration of the 
 <span class=\"code\">adminpro_config.php</span> is correct, before using this setup script.</p>\n
  <p class=\"normal\"><u>This file is not protected by the AdminPro Class, so please remove it 
  from your Webspace, after your database has been configured.</u></p>\n");
$out="<p class=\"normal\">If you want to use this setup script to configure your database, follow please the following steps:</p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"readconfig\">\n";
$out.="<input type=\"submit\" value=\"1 --> Read congfiguration file\">\n";
$out.="</form>\n";
echo $out;
echo ("
<p class=\"normal\">If you do not want to use this setup script to configure your database,
 please follow these rules to create the MySQL Table [ AdminPro Class v1.3.x ]:\n
<ul class=\"normal\">
<li>Create a standard MySQL table (MyISAM) with 8 fields</li>
<li>Field 1: INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY --> The user's unique ID</li>
<li>Field 2: CHAR( 50 ) BINARY UNIQUE --> The user's unique username</li>
<li>Field 3: CHAR( 50 ) BINARY --> The user's password</li>
<li>Field 4: TINYINT( 2 ) DEFAULT '-1' NOT NULL --> The user's rights: -1 = normal user , 1 = Administrator</li>
<li>Field 5: INT UNSIGNED DEFAULT '1'--> The user's group number: default group number = 1 [ <u>new in v1.3.x</u> ]</li>
<li>Field 6: CHAR( 50 ) --> The user's session ID (value will be created by the AdminPro Class)</li>
<li>Field 7: DATETIME --> The user's last page access time (value will be created by the AdminPro Class)</li>
<li>Field 8: CHAR( 255 ) --> Remarks about the user</li>
<li>Insert the username, password, administrator rights, group number and remarks for each user.
 Please note, that the password should be md5 encrypted. If you cannot insert a md5 encrypted value in
 the user's password field, then set in the <span class=\"code\">adminpro_config.php</span>
  the <span class=\"code\">\$globalConfig['isMd5']=\"2\"</span>, to indicate that the passwords are not md5 encrypted.</li>
<li>Update in the <span class=\"code\">adminpro_config.php</span> the names of the created table and fields
 as well as the MySQL connection data.</li>
</ul>
</p>");
}
/*
Start reading and checking configuration file
*/
if ($action=="readconfig"){
$out="Your are using the following configuration:\n";
$out.="<ul>\n";
$out.="<li>MySQL Host: ";
if (@$globalConfig['dbhost']!=""){
$out.="<font color=\"green\">".$globalConfig['dbhost']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Host indicated!</font>";
}
$out.="</li>\n";
$out.="<li>MySQL User: ";
if (@$globalConfig['dbuser']!=""){
$out.="<font color=\"green\">".$globalConfig['dbuser']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL User indicated!</font>";
}
$out.="</li>\n";
$out.="<li>MySQL Password: ";
$out.="<font color=\"green\">".$globalConfig['dbpass']."</font>";
$out.="</li>\n";
$out.="<li>MySQL Database: ";
if (@$globalConfig['dbase']!=""){
$out.="<font color=\"green\">".$globalConfig['dbase']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Database indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Table: ";
if (@$globalConfig['tbl']!=""){
$out.="<font color=\"green\">".$globalConfig['tbl']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Table Name indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL ID Field: ";
if (@$globalConfig['tblID']!=""){
$out.="<font color=\"green\">".$globalConfig['tblID']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL ID Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Username Field: ";
if (@$globalConfig['tblUserName']!=""){
$out.="<font color=\"green\">".$globalConfig['tblUserName']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Username Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Password Field: ";
if (@$globalConfig['tblUserPass']!=""){
$out.="<font color=\"green\">".$globalConfig['tblUserPass']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Password Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Admin Field: ";
if (@$globalConfig['tblIsAdmin']!=""){
$out.="<font color=\"green\">".$globalConfig['tblIsAdmin']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Admin Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL User Group Field: ";
if (@$globalConfig['tblUserGroup']!=""){
$out.="<font color=\"green\">".$globalConfig['tblUserGroup']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL User Group Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL SessionID Field: ";
if (@$globalConfig['tblSessionID']!=""){
$out.="<font color=\"green\">".$globalConfig['tblSessionID']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL SessionID Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Time Field: ";
if (@$globalConfig['tblLastLog']!=""){
$out.="<font color=\"green\">".$globalConfig['tblLastLog']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Time Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>MySQL Remarks Field: ";
if (@$globalConfig['tblUserRemark']!=""){
$out.="<font color=\"green\">".$globalConfig['tblUserRemark']."</font>";
}
else {
$out.="<font color=\"red\">No MySQL Remarks Field indicated!</font>";
$check=false;
}
$out.="</li>\n";
$out.="<li>Md5 Encryption: ";
if (@$globalConfig['isMd5']=="1"){
$out.="<font color=\"green\">enabled</font>";
}
else {
$out.="<font color=\"green\">disabled</font>";
}
$out.="</li>\n";
$out.="</ul>\n";
$out.="<table border=\"0\"><tr><td>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" value=\"0 --> Cancel Setup\">\n";
$out.="</form>\n";
$out.="</td>\n";
if ($check){
$out.="<td>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"maketable\">\n";
$out.="<input type=\"submit\" value=\"2 --> Create Table \">\n";
$out.="</form>\n";
$out.="</td>\n";
$out.="<td>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"update\">\n";
$out.="<input type=\"submit\" value=\"3 --> Update Table* \">\n";
$out.="</form>\n";
$out.="</td></tr>\n";
$out.="<tr><td colspan=\"3\" class=\"small\">\n";
$out.="<i>* Please use 'Update Table', only if you have already created the AdminPro Class MySQL table
 and you want to update it to a newer version of the AdminPro Class. There is no warranty for this feature.
 Please backup first your existing AdminPro Class MySQL Table before using it.</i>";
$out.="</td>\n";
}
$out.="</tr></table>\n";
echo $out;
}
/*
End reading and checking configuration file
*/
/*
Start creating MySQL table
*/
if ($action=="maketable"){
$db=new mysql_dialog();
$db->connect($globalConfig['dbhost'],$globalConfig['dbuser'], $globalConfig['dbpass'], $globalConfig['dbase']);
$SQLA="SHOW TABLES";
$db->speak($SQLA);
$tblexists=false;
while ($tbls=$db->listen()){
for ($x=0; $x<$db->fields; $x++){
if ($tbls[$x]==$globalConfig['tbl']) {
$tblexists=true;
break;
}
}
}
if (!$tblexists) {
$SQL="CREATE TABLE ".$globalConfig['tbl']." ( ";
$SQL.=$globalConfig['tblID']." INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
$SQL.=$globalConfig['tblUserName']." CHAR( 50 ) BINARY UNIQUE, ";
$SQL.=$globalConfig['tblUserPass']." CHAR( 50 ) BINARY, ";
$SQL.=$globalConfig['tblIsAdmin']." TINYINT( 2 ) DEFAULT '-1' NOT NULL, ";
$SQL.=$globalConfig['tblUserGroup']." INT UNSIGNED DEFAULT '1', ";
$SQL.=$globalConfig['tblSessionID']." CHAR( 50 ), ";
$SQL.=$globalConfig['tblLastLog']." DATETIME, ";
$SQL.=$globalConfig['tblUserRemark']." CHAR( 255 ) ) ";
$SQL.="TYPE=MyISAM COMMENT = 'Created by the AdminPro Class MySQL Setup '";
$SQLI="INSERT INTO ".$globalConfig['tbl']." VALUES (";
if ($globalConfig['isMd5']=="1"){
$admin=md5("admin");
$SQLI.="1, 'admin', '".$admin."', 1, 1, '', '', 'Default Administrator')";
}
else { 
$SQLI.="1, 'admin', 'admin', 1, 1, '', '', 'Default Administrator')";
}
$db->connect($globalConfig['dbhost'],$globalConfig['dbuser'], $globalConfig['dbpass'], $globalConfig['dbase']);
$db->speak($SQL);
$db->speak($SQLI);
$SQ="SHOW TABLES";
$db->speak($SQ);
$res=false;
while ($tbls=$db->listen()){
for ($x=0; $x<$db->fields; $x++){
if ($tbls[$x]==$globalConfig['tbl']) {
$res=true;
break;
}
}
}
if (!$res){
$out="<p><font color=\"red\">The following table could not be created: </font>".$globalConfig['tbl']."</p>\n";
$out.="<p><font color=\"red\">Please check your database connection!</font></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" value=\"0 --> Cancel Setup\">\n";
$out.="</form>\n";
echo $out;
}
else {
$out="<p><font color=\"green\">The following MySQL table has been created: </font>".$globalConfig['tbl']."</p>\n";
$out.="<p><font color=\"green\">A default Administrator has been created: </font>";
$out.="Username: <font color=\"green\">admin</font> - Password: <font color=\"green\">admin</font></p>\n";
$out.="<p><font color=\"green\">You are now ready to start the AdminPro Class! ";
$out.="Please Login to start adding users. First add yourself as Administrator ";
$out.="with your own username and password. Then delete the default Administrator, ";
$out.="this AdminPro Class MySQL Setup has created.</font></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" value=\"OK\">\n";
$out.="</form>\n";
echo $out;
}
}
else{
$out="<p><font color=\"red\">The following table could not be created: </font>".$globalConfig['tbl']."</p>\n";
$out.="<p><font color=\"red\">This table already exists on your database!</font></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" value=\"0 --> Cancel Setup\">\n";
$out.="</form>\n";
echo $out;
}
}
/*
End creating MySQL table
*/
/*
Start preparing to update MySQL table
*/
if ($action=="update"){
$db=new mysql_dialog();
$db->connect($globalConfig['dbhost'],$globalConfig['dbuser'], $globalConfig['dbpass'], $globalConfig['dbase']);
$SQLA="SHOW TABLES";
$db->speak($SQLA);
$tblexists=false;
while ($tbls=$db->listen()){
for ($x=0; $x<$db->fields; $x++){
if ($tbls[$x]==$globalConfig['tbl']) {
$tblexists=true;
break;
}
}
}
if (!$tblexists){
$out="<p><font color=\"red\">The following table cannot be updated: </font>".$globalConfig['tbl']."</p>\n";
$out.="<p><font color=\"red\">This table does not exist on your database or the connection to your database has failed!</font></p>\n";
$out.="<p><font color=\"red\">Please check your configuration file!</font></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" value=\"0 --> Cancel Setup\">\n";
$out.="</form>\n";
echo $out;
}
else{
$out="<p>The AdminPro Class MySQL Table Setup has found the table <u>".$globalConfig['tbl']."</u> on your database.</p>\n";
$out.="<p>Please enter a SQL statement below, to update this table:</p>\n";
$out.="<table border=\"0\">\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<tr><td>";
$out.="<input type=\"hidden\" name=\"action\" value=\"execute\">";
$out.="<textarea id=\"usersql\" name=\"usersql\" rows=\"3\" cols=\"100\"></textarea>";
$out.="</td></tr>\n";
$out.="<tr><td align=\"right\">";
$out.="<input type=\"reset\" value=\"Reset SQL\"> ";
$out.="<input type=\"submit\" value=\"4 --> Execute SQL \">";
$out.="</td></tr>\n";
$out.="</form>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<tr><td align=\"left\">";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">";
$out.="<input type=\"submit\" value=\"0 --> Cancel Setup\">";
$out.="</td></tr></table>\n";
$out.="</form>\n";
$out.="<br><p>\n";
$out.="SQL Patches (cut & paste):<br>\n";
$out.="<textarea id=\"patches\" rows=\"8\" cols=\"100\">\n";
$out.="# Update AdminPro Class v1.2 to v1.3.x\n";
$out.="ALTER TABLE `".$globalConfig['tbl']."` ";
$out.="ADD `".$globalConfig['tblUserGroup']."` INT( 10 ) UNSIGNED DEFAULT '1' ";
$out.="AFTER `".$globalConfig['tblIsAdmin']."`;";
$out.="</textarea></p>\n";
echo $out;
}
}
/*
End preparing to update MySQL table
*/
/*
Update MySQL table
*/
if ($action=="execute"){
$mysql=stripslashes(trim(htmlentities($usersql)));
$db=new mysql_dialog();
$db->connect($globalConfig['dbhost'],$globalConfig['dbuser'], $globalConfig['dbpass'], $globalConfig['dbase']);
$db->speak($mysql);
if ($db->sql_id){
$out="<p><font color=\"green\">Your SQL statement: </font>".$mysql."</p>\n";
$out.="<p><font color=\"green\">The following table was affected: </font>".$globalConfig['tbl']."</p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"update\">\n";
$out.="<input type=\"submit\" value=\"OK\">\n";
$out.="</form>\n";
echo $out;
}
else{
$out="<p><font color=\"red\">The following table cannot be updated: </font>".$globalConfig['tbl']."</p>\n";
$out.="<p><font color=\"red\">This is an invalid SQL statement: </font>".$mysql."</p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"update\">\n";
$out.="<input type=\"submit\" value=\"OK\">\n";
$out.="</form>\n";
echo $out;
}
}
/*
End Update MySQL table
*/
?>
</div>
</body>
</html>