<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me
if(isAdmin()){
$pageid = mysql_real_escape_string($_GET['pageid']);
$query = "SELECT * FROM `pages` WHERE `pageid`=$pageid";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
define("EDITPAGE", True);
?>
<h2>Edit Post</h2>
<form action="?action=processEditPage&pageid=<?php echo $pageid; ?>" method="POST">
	<fieldset>
		<label for="title">Title: </label><input type="text" name="pagetitle" id='title' value="<?php echo $row["pagetitle"]; ?>"/><br />
		<label for="content">Page Body:<br /></label><textarea cols="100" rows="10" name="pagebody"><?php echo $row["pagebody"]; ?></textarea><br />
		
		<input type="submit" name="submit" value="Edit Page" />
	</fieldset>
</form>
<?php }else{
	$_SESSION['include'] = "loginform.php";
}?>