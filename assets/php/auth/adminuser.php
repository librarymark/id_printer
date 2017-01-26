<?php
/***
**** @file: AdminPro Class User Administration
**** @project: AdminPro Class
**** @version: 1.3;
**** @author: Giorgos Tsiledakis;
**** @date: 2004-09-04;
**** @license: GNU GENERAL PUBLIC LICENSE;
***/
include("adminpro_config.php");
include("adminpro_class.php");
include("adminuser_config.php");
$prot=new protect("1","1");
if ($prot->showPage) {
$db=new mysql_dialog("1");
$db->connect($globalConfig['dbhost'],$globalConfig['dbuser'], $globalConfig['dbpass'], $globalConfig['dbase']);
$SQL="SELECT ".$globalConfig['tblUserName']." as userName, ";
$SQL.=$globalConfig['tblIsAdmin']." as isAdmin";
$SQL.=" FROM ".$globalConfig['tbl'];
$db->speak($SQL);
$option=""; //for the select tag normal users
$optionad=""; //for the select tag admins
$action=""; //main navigation
$editName="";
$userName="";
$allusers=0; //count all normal users
$alladmins=0; //count all admins
while ($data=$db->listen()){
$option.="<option value=\"".$data['userName']."\">".$data['userName'];
if ($data['isAdmin']==1){
$alladmins++;
}
else{
$allusers++;
}
}
extract($_POST);
?>
<html>
<head>
<title><?php echo $adminConfig['htmlTitle']; ?></title>
<?php
if ($adminConfig['htmlCharset']!=""){
echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=".$adminConfig['htmlCharset']."\">\n";
}
if ($adminConfig['htmlCss']!=""){
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$adminConfig['htmlCss']."\">";
}
?>
</head>
<body>
<h1 align="center"><?php echo $adminConfig['pageTitle']; ?></h1>
<p align="center"><?php echo $adminConfig['curUserMsg']."<b>".$prot->getUser()."</b>"; ?></p>
<hr></hr>
<table align="center" border="0" cellpadding="2" cellspacing="2">
<tr>
<td align="right" valign="top">
<?php
if ($option!=""){
?>
<form action="" method="POST">
<input type="hidden" name="action" value="edit">
<?php echo $adminConfig['selectMsg']; ?>
</td>
<td align="right" valign="top">
<select name="userSelection"><?php echo $option; ?></select> 
<?php 
}
?>
</td>
<td>
<?php
if ($option!=""){
?>
<input type="submit" id="button" value="<?php echo $adminConfig['editBtn']; ?>"></form>
<?php 
}
?>
</td>
<td>
<form action="" method="POST">
<input type="hidden" name="action" value="addnew">
<input type="submit" id="button" value="<?php echo $adminConfig['newBtn']; ?>">
</form>
</td>
<td>
<form action="" method="POST">
<input type="hidden" name="action" value="showall">
<input type="submit" id="button" value="<?php echo $adminConfig['tableBtn']; ?>">
</form>
</td>
<td>
<form action="" method="POST">
<input type="hidden" name="action" value="logout">
<input type="submit" id="button" value="<?php echo $adminConfig['logoutBtn']; ?>">
</form>
</td>
</tr>
</table>
<hr></hr>
<div align="center">
<?php
if ($action=="" || $action=="login"){
$out="<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">";
$out.="<tr><td align=\"right\">".$adminConfig['allAdminMsg']."</td><td>".$alladmins."</td></tr>\n";
$out.="<tr><td align=\"right\">".$adminConfig['allUserMsg']."</td><td>".$allusers."</td></tr>\n";
$out.="</table>\n";
echo $out;
}
if ($action=="edit"){
$SQ="SELECT ".$globalConfig['tblUserName']." as userName, ";
$SQ.=$globalConfig['tblUserRemark']." as userRemark, ";
$SQ.=$globalConfig['tblIsAdmin']." as isAdmin, ".$globalConfig['tblUserGroup']." as userGroup";
$SQ.=" FROM ".$globalConfig['tbl'];
$SQ.=" WHERE ".$globalConfig['tblUserName']."='".$userSelection."'";
$db->speak($SQ);
$data=$db->listen();
/*
**  thanks to robert 
*/
$out="<form action=\"\" method=\"POST\">";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['cancelBtn']."\">\n";
$out.="</form>\n";
$out.="<p>".$adminConfig['editUserMsg']."<b>";
$out.=$userSelection." </b></p>\n";
$out.="<p><i>".$adminConfig['editInfoMsg']."</i></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<table align=\"center\" cellpadding=\"4\" cellspacing=\"4\">\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['editPassMsg']."</td>\n";
$out.="<td align=\"left\"><input type=\"password\" name=\"userPass\"></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['editIsAdminMsg']."</td>\n";
$out.="<td align=\"left\"><select name=\"isAdmin\"><option value=\"-1\"";
if ($data['isAdmin']=="-1") {$out.=" selected=\"selected\"";}
$out.=">".$adminConfig['editNo']."<option value=\"1\"";
if ($data['isAdmin']=="1") {$out.=" selected=\"selected\"";}
$out.=">".$adminConfig['editYes']."</select></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['editUserGroupMsg']."</td>\n";
$out.="<td align=\"left\"><select name=\"userGroup\">";
$out.="<option value=\"1\"";
if ($data['userGroup']=="1" || !$data['userGroup']) {$out.=" selected=\"selected\"";}
$out.=">1";
if ($adminConfig['userGroupNum']){
for ($x=2; $x<=$adminConfig['userGroupNum']; $x++){
$out.="<option value=\"".$x."\"";
if ($data['userGroup']==$x) {$out.=" selected=\"selected\"";}
$out.=">".$x;
}
}
$out.="</select></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['editRemarkMsg']."</td>\n";
$out.="<td align=\"left\"><textarea name=\"userRemark\" cols=\"40\" rows=\"4\">".$data['userRemark']."</textarea></td>\n";
$out.="</tr>\n";
$out.="<tr><td></td><td align=\"center\">\n";
$out.="<input type=\"hidden\" name=\"userName\" value=\"".$userSelection."\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"edituser\">\n";
$out.="<input type=\"reset\" id=\"button\" value=\"".$adminConfig['resetBtn']."\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['submitBtn']."\">\n";
$out.="</td></tr>\n";
$out.="</table>\n";
$out.="</form>\n";
$out.="<form action=\"\" method=\"POST\"><p align=\"right\">\n";
$out.="<input type=\"hidden\" name=\"userName\" value=\"".$userSelection."\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"delete\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['deleteBtn']."\">\n";
$out.="</p></form>\n";
echo $out;
}
if ($action=="edituser"){
$SQL="UPDATE ".$globalConfig['tbl']." SET ";
$SQL.=$globalConfig['tblIsAdmin']."=".$isAdmin.", ";
$SQL.=$globalConfig['tblUserGroup']."=".$userGroup.", ";
$SQL.=$globalConfig['tblUserRemark']."='".$userRemark."'";
if ($userPass!=""){
if ($globalConfig['isMd5']=="1"){
$SQL.=", ".$globalConfig['tblUserPass']."='".md5($userPass)."'";
}
else{
$SQL.=", ".$globalConfig['tblUserPass']."='".$userPass."'";
}
}
$SQL.=" WHERE ".$globalConfig['tblUserName']."='".$userName."'";
$db->speak($SQL);
$out="<p><b><font color=\"green\">".$adminConfig['editUpdMsg']."</font>";
$out.=$userName."</b></p>";
$out.="<form action=\"\" method=\"POST\">";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">";
$out.="</form>";
echo $out;
}
if ($action=="delete"){
$out="<form action=\"\" method=\"POST\">";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['cancelBtn']."\">\n";
$out.="</form>\n";
$out.="<p><b><font color=\"red\">".$adminConfig['delConfirmMsg']."</font>";
$out.=$userName."</b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"userName\" value=\"".$userName."\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"deleteuser\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
if ($action=="deleteuser"){
$SQL="DELETE FROM ".$globalConfig['tbl'];
$SQL.=" WHERE ".$globalConfig['tblUserName']."='".$userName."'";
$db->speak($SQL);
$SQ="SELECT ".$globalConfig['tblUserName']." as userName FROM ".$globalConfig['tbl'];
$db->speak($SQ);
$data=$db->listen();
$userNotDel=false;
while ($data=$db->listen()){
if ($userName==$data['userName']) {
$userNotDel=true;
break;
}
}
if ($userNotDel) {
$out="<p><b><font color=\"red\">".$adminConfig['delNotDelMsg']."</font>";
$out.=$userName."</b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
else{
$out="<p><b><font color=\"green\">".$adminConfig['delDelMsg']."</font>";
$out.=$userName."</b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
}
if ($action=="showall"){
$SQL="SELECT * FROM ".$globalConfig['tbl'];
$button="<form action=\"\" method=\"POST\">\n";
$button.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$button.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['cancelBtn']."\">\n";
$button.="</form>\n";
echo $button;
$db->onscreen($SQL);
}
if ($action=="addnew"){
$out="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['cancelBtn']."\">\n";
$out.="</form>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<table align=\"center\" cellpadding=\"4\" cellspacing=\"4\">\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['newUserMsg']."</td>\n";
$out.="<td align=\"left\"><input type=\"text\" name=\"userName\"></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['newPassMsg']."</td>\n";
$out.="<td align=\"left\"><input type=\"password\" name=\"userPass\"></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['newIsAdminMsg']."</td>\n";
$out.="<td align=\"left\"><select name=\"isAdmin\"><option value=\"-1\">".$adminConfig['newNo'];
$out.="<option value=\"1\">".$adminConfig['newYes']."</select></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['newUserGroupMsg']."</td>\n";
$out.="<td align=\"left\"><select name=\"userGroup\"><option value=\"1\">1";
if ($adminConfig['userGroupNum']){
for ($x=2; $x<=$adminConfig['userGroupNum']; $x++){
$out.="<option value=\"".$x."\">".$x;
}
}
$out.="</select></td>\n";
$out.="</tr>\n";
$out.="<tr>\n";
$out.="<td align=\"right\">".$adminConfig['newRemarkMsg']."</td>\n";
$out.="<td align=\"left\"><textarea name=\"userRemark\" cols=\"40\" rows=\"4\"></textarea></td>\n";
$out.="</tr>\n";
$out.="<tr><td></td><td align=\"center\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"newuser\">\n";
$out.=" <input type=\"reset\" id=\"button\" value=\"".$adminConfig['resetBtn']."\">\n";
$out.=" <input type=\"submit\" id=\"button\" value=\"".$adminConfig['submitBtn']."\">\n";
$out.="</td></tr>\n";
$out.="</table>\n";
$out.="</form>\n";
echo $out;
}
if ($action=="newuser"){
if ($userName && $userPass){
$userExist=false;
$SQL="SELECT ".$globalConfig['tblUserName']." as userName FROM ".$globalConfig['tbl'];
$db->speak($SQL);
while ($data=$db->listen()){
if ($userName==$data['userName']) {
$userExist=true;
break;
}
}
if (!$userExist) {
$SQL="INSERT INTO ".$globalConfig['tbl']." ( ";
$SQL.=$globalConfig['tblID'].", ";
$SQL.=$globalConfig['tblUserName'].", ";
$SQL.=$globalConfig['tblUserPass'].", ";
$SQL.=$globalConfig['tblIsAdmin'].", ";
$SQL.=$globalConfig['tblUserGroup'].", ";
$SQL.=$globalConfig['tblSessionID'].", ";
$SQL.=$globalConfig['tblLastLog'].", ";
$SQL.=$globalConfig['tblUserRemark']." ) ";
if ($globalConfig['isMd5']=="1"){
$SQL.=" VALUES ( '', '".$userName."', '".md5($userPass)."', '".$isAdmin."', '".$userGroup."', '', '', '".$userRemark."')";
}
else{
$SQL.=" VALUES ( '', '".$userName."', '".$userPass."', '".$isAdmin."', '".$userGroup."', '', '', '".$userRemark."')";
}
$db->speak($SQL);
$SQ="SELECT ".$globalConfig['tblUserName']." as userName FROM ".$globalConfig['tbl'];
$SQ.=" WHERE ".$globalConfig['tblUserName']."='".$userName."'";
$db->speak($SQ);
$data=$db->listen();
if ($data['userName']){
$out="<p><b><font color=\"green\">".$adminConfig['newAddedMsg']."</font>";
$out.=$data['userName']."</b></p>\n";
$out.="<form action=\"\" method=\"POST\">";
$out.="<input type=\"hidden\" name=\"action\" value=\"\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
}
else {
$out="<p><b><font color=\"red\">".$adminConfig['newExistsMsg']."</font>";
$out.=$userName."</b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"addnew\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
}
elseif (!$userName && $userPass) {
$out="<p><b><font color=\"red\">".$adminConfig['newNoUserMsg']."</font></b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"addnew\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
elseif (!$userPass && $userName) {
$out="<p><b><font color=\"red\">".$adminConfig['newNoPassMsg']."</font></b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"addnew\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
elseif (!$userName && !$userPass) {
$out="<p><b><font color=\"red\">".$adminConfig['newNoUserPassMsg']."</font></b></p>\n";
$out.="<form action=\"\" method=\"POST\">\n";
$out.="<input type=\"hidden\" name=\"action\" value=\"addnew\">\n";
$out.="<input type=\"submit\" id=\"button\" value=\"".$adminConfig['okBtn']."\">\n";
$out.="</form>\n";
echo $out;
}
}
?>
</div>

</body>
</html>
<?php
}
?>