<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me
if(isset($_GET['dpid'])&&validPID($_GET['dpid'])){
if(!isset($_SESSION['dpid'])||!isset($_GET['delete'])){
	$dpid = mysql_real_escape_string($_GET['dpid']);
	setDeletePostSession($dpid);
?>
	<div class="checkdelete">
	Are you sure you want to delete the post named <b><?php echo pidToTitle($dpid); ?></b>?

	<span class="deletechoice"><a href="?action=checkDelete&dpid=<?php echo $dpid; ?>&delete">YES!</a> <a href="index.php">NO!</a></span>
	</div>
<?php
}else{
	$dpid = mysql_real_escape_string($_GET['dpid']);
	deletePost($dpid);
	echo "SHOULD BE DELETED";
}
}else{
	echo "<div class=\"warning\">Invalid PID specified</div>";
	include "includes/home.php";
}
?>