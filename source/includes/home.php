<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(loggedIn()){echo "<em>Welcome, ".$_SESSION['title'].". ".$_SESSION['lname']."</em>"; }?>

<?php 
if(isset($_GET['cid'])){
	if(validCat($_GET['cid'])){
		$cid = mysql_real_escape_string($_GET['cid']);
		echo "<h2>Displaying posts in category \"".cidToCategoryName($cid)."\"</h2>";
		displayPosts(10, 0, $cid);
	}else{
		echo "<div class=\"warning\">Invalid Category specified</div>";
		displayPosts();
	}
}else{
	displayPosts();
}
?>