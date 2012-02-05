<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

$pageid = $_GET['page'];
if(validPage($pageid)){
	$pageid = mysql_real_escape_string($pageid);
	$query = "SELECT * FROM `pages` WHERE `pageid` = $pageid";
	if($result = mysql_query($query)){
		if(mysql_num_rows($result)>0){
			$row = mysql_fetch_assoc($result);
		}
	}
?>
<div class="page">
	<h2><?php echo $row["pagetitle"]; ?></h2>
	
	<p class="pagebody"><?php echo parseLineBreaks($row["pagebody"]); ?></p>
	<?php if(isAdmin()){
		echo "<span class=\"postadmin\">";
		echo "<br />[";
		echo "<a href=\"#\" onclick=\"confirmPageDelete(".$row['pageid'].")\">Delete</a>";
		echo "|<a href=\"?action=editPage&amp;pageid=".$row['pageid']."\">Edit</a>";
		echo "]";
		echo "</span>";
		}
	?>
</div>
<?php
}else{
	echo "<h2>Invalid Page ID</h2><h6>also inconsistently styled/actuated error messages</h2>";
}
?>
