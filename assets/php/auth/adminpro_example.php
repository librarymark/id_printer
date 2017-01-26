<?php
/*
Please, create first the MySQL table and add some users.
To start this example please change the form action value in the default login page to the name of this page:
e.g <form action="adminpro_example.php" method="POST">.
In this example only administrators and normal users of the group '2' will have access.
If only administrators and normal users of the group e.g '5' should have access, set: new protect("0","5").
If only administrators should have access, set: new protect("1").
If all users should have access, set: new protect().
*/
include("adminpro_class.php");
$prot=new protect("0","2");
if ($prot->showPage) {
$curUser=$prot->getUser();
?>
<html>
<head>
<title>AdminPro Class v1.3 Example Page</title>
<style type="text/css">
<!--
body {font-family: Helvetica, Tahoma, Arial, sans-serif; font-size: 12px; text-align: center; font-weight: normal;}
h1 {font-family: Helvetica, Tahoma, Arial, sans-serif; font-size: 28px; text-align: center; font-weight: bold;}
input#button {font-family: Helvetica, Tahoma, Arial, sans-serif; border: 2px; bordercolor:silver; font-size: 11px; font-weight: bold;}
-->
</style>
</head>
<body bgcolor="#e9e9e9">
<center>
<h1>AdminPro Class v1.3 Example Page</h1>
<p><i>This could be the first page in your password protected area.</i></p>
<p><i>Here could follow your PHP and HTML code...</i></p>
<p><?php echo ("You are logged in as: <b>".$curUser)."</b>"?> [using function <b>getUser()</b>]</p>
<p><form action="" method="POST">
<input type="hidden" name="action" value="logout">
<input type="submit" id="button" value="Logout">
</form></p>
</body>
</html>
<?php
}
?>