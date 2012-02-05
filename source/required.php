<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

include "settings.php";
include "functions.php";
session_start();
define("INCLUDED", True);
$db_connect = mysql_connect($mysql_host, $mysql_user, $mysql_password) OR DIE(mysql_error());
$db_select = mysql_select_db($mysql_db, $db_connect) OR DIE(mysql_error());

handleGetRequests();
handleRedirects();
?>