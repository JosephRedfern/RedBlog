<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(!isAdmin()){
	setMessage("warning", "Nice try. Your IP has been logged, fool!");
	logIP("attempted access to adminpage");
}else{
?>
	<h1>Admin Tools</h1>
	
	<ul>
		<li><a href="?action=admintools&tool=managecat">Manage Categories</a></li>
		<li><a href="?action=admintools&tool=manageposts">Manage Posts</a></li>
		<li><a href="?action=admintools&tool=manageusers">Manage Users</a></li>
	<ul>
<?php
}
?>