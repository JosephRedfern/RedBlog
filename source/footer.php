<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me


//killMessages();
if(isset($_SESSION['dpid'])&&!isset($_GET['dpid'])){
	unset($_SESSION['dpid']); //once you leave the delete post page, get rid of the session to prevent "accidents"
}
?>