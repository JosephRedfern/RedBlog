<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(!defined("INCLUDED")){
die("No direct access, M'kay?");
}?>
<h1>Login</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=processLogin" method="POST">
	<fieldset>
		<label for="username">Username:</label><input type="text" name="username" id="username" /><br />
		<label for="password">Password:</label><input type="password" name="password" id="password" /><br />
		<input type="submit" value="Login">
		</fieldset>
</form>