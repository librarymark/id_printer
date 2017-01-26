<?php
/***
**** @class configuration file for class: protect (in adminpro_class.php)
**** @project: AdminPro Class
**** @version: 1.3;
**** @author: Giorgos Tsiledakis;
**** @date: 2004-09-04;
**** @license: GNU GENERAL PUBLIC LICENSE;
**** PLEASE CONFIGURE THIS FILE FIRST
***/

/*
**MySQL CONFIGURATION*************************************************************
*/
$globalConfig['dbhost']="localhost"; // Your MySQL Server Host URL
$globalConfig['dbuser']="id_cards"; // Your MySQL Username
$globalConfig['dbpass']="id_cards"; // Your MySQL Password
$globalConfig['dbase']="id_cards"; // Your MySQL Database name
$globalConfig['tbl']="users"; // The name of the MySQL table to store the data required
$globalConfig['tblID']="ID"; // The name of the ID field of the MySQL table
$globalConfig['tblUserName']="userName"; // The name of the Username field of the MySQL table
$globalConfig['tblUserPass']="userPass"; // The name of the Userpassword field of the MySQL table
$globalConfig['tblIsAdmin']="isAdmin"; // The name of the Administator field of the MySQL table
$globalConfig['tblUserGroup']="userGroup"; // The name of the User Group field of the MySQL table
$globalConfig['tblSessionID']="sessionID"; // The name of the ID field of the MySQL table
$globalConfig['tblLastLog']="lastLog"; // The name of the Time field of the MySQL table
$globalConfig['tblUserRemark']="userRemark"; // The name of the Remarks field of the MySQL table
/*
**END MySQL CONFIGURATION*********************************************************
*/
/*
**GENERAL CONFIGURATION***********************************************************
*/
/*
$globalConfig['acceptNoCookies']
true = display an error message if the user has deactivated cookies
false = no error message; you should though pass somehow (POST/GET) the session ID on each link!!
e.g. your_next_page.php?PHPSESSID=".session_id(); etc.
**********************************************************************************
*/
$globalConfig['acceptNoCookies']=true;
/*
**********************************************************************************
*/
$globalConfig['inactiveMin']="40"; // The time in minutes to force new login, if account has been inactive
$globalConfig['loginUrl']="addets/php/login.php"; // The URL of the login page
$globalConfig['logoutUrl']="assets/php/logout.php"; // The URL of the logout page
/*
**END GENERAL CONFIGURATION*******************************************************
*/
/*
**REMEMBER LOGIN CONFIGURATION****************************************************
*/
$globalConfig['enblRemember']=false; // set true to enable Remember Me function
$globalConfig['cookieRemName']="AdminPro-RememberMyName"; // name of username cookie
$globalConfig['cookieRemPass']="AdminPro-RememberMyPass"; // name of password
$globalConfig['cookieExpDays']="30"; // num of days, when remember me cookies expire
/*
**END REMEMBER LOGIN CONFIGURATION************************************************
*/
/*
**HASH CONFIGURATION**************************************************************
$globalConfig['isMd5']
1 = passwords will be stored md5 encrypted on database
other number = passwords will be stored as is on database
**********************************************************************************
*/
$globalConfig['isMd5']="1";
/*
**END HASH CONFIGURATION**********************************************************
*/
/*
**ERROR PAGE CONFIGURATION********************************************************
*/
/*
$globalConfig['errorCssUrl']
the url of the external stylesheet file for the error pages
please leave it blank: $globalConfig['errorCssUrl']=""; if you do not want to use one
**********************************************************************************
*/
$globalConfig['errorCssUrl']="adminpro.css";
/*
**********************************************************************************
*/
/*
$globalConfig['errorCharset']
the Charset for the error pages, default: iso-8859-1
please leave it blank: $globalConfig['errorCharset']=""; if you do not want to use one
**********************************************************************************
*/
$globalConfig['errorCharset']="iso-8859-1";
/*
**********************************************************************************
*/
$globalConfig['errorPageTitle']="";
$globalConfig['errorPageH1']="";
$globalConfig['errorPageLink']="";
$globalConfig['errorNoCookies']="YOU MUST ACCEPT COOKIES TO PROCCED!";
$globalConfig['errorNoLogin']="";
$globalConfig['errorInvalid']="";
$globalConfig['errorDelay']="YOUR ACCOUNT HAS BEEN INACTIVE FOR TOO LONG <br>";
$globalConfig['errorDelay'].="OR YOU HAVE USED THE LOGIN MORE THAN ONCE!<br>";
$globalConfig['errorDelay'].="THIS SESSION IS NO LONGER ACTIVE!";
$globalConfig['errorNoAdmin']="YOU NEED ADMINISTRATOR RIGHTS TO VIEW THIS PAGE!";
$globalConfig['errorNoGroup']="YOU DO NOT BELONG TO THE USER GROUP REQUIRED TO VIEW THIS PAGE!";
/*
**END ERROR PAGE CONFIGURATION****************************************************
*/
?>
