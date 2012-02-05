<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me
if(isAdmin()){
$pid = mysql_real_escape_string($_GET['pid']);
$query = "SELECT * FROM `posts` WHERE `pid`=$pid";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
define("EDITPOST", True);
?>
<h2>Edit Post</h2>
<form action="?action=processEditPost&pid=<?php echo $pid; ?>" method="POST">
	<fieldset>
		<label for="title">Title: </label><input type="text" name="title" id='title' value="<?php echo $row["title"]; ?>"/><br />
		<label for="content">Post Content:<br /></label><textarea cols="100" rows="10" name="content"><?php echo $row["content"]; ?></textarea><br />
		<select name="cid">
			<?php
			$categories = categoryArray(True);
			foreach($categories as $category){
				if($category['cid']==$row['cid']){
					$defaulttext = " selected=\"selected\"";
				}else{
					$defaulttext = "";
				}
				echo "<option value=\"".$category['cid']."\"".$defaulttext.">".$category["catname"]."</option>";
			}
			?>
		</select>
		<input type="submit" name="submit" value="Edit Post" />
	</fieldset>
</form>
<?php }else{
	$_SESSION['include'] = "loginform.php";
}?>