<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

$blogTitle = "Blog Title"; //Blog title
$blogURL = "http://localhost/blog/"; //The address you use to access the blog
$blogDescription = "My excellent blog."; //Blog description/tagline.

//Timezone Settings
date_default_timezone_set('Europe/London'); //see http://www.php.net/manual/en/timezones.php for a list of supported timezones.

//SALT Settings
//The salt should be set to something fairly random. The demo account's password has been MD5'ed with this salt. 
//If you want to change this, log in first, the change the salt, then update your password. Otherwise, you'll be locked out.
define("SALT", "thisissometextwithsomerandomstuffafterit2139u123402u348924£**@$!@£"); 


//MySQL Connection Settings - Fairly self explanitory. 
$mysql_host = "localhost";
$mysql_user = "username";
$mysql_password="password";
$mysql_db = "database";
?>