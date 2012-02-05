<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(isset($_GET['pid'])&&validPID($_GET['pid'])){
	$post = singlePostArray($_GET['pid']);

?>
<div class="singlepost">
<h2><?php echo $post["Title"]; ?></h2>
<span class="postinfo">Posted by  <em><?php echo $post["Author"]; ?></em> on <?php echo date("j/n/Y", strtotime($post['Timestamp'])); ?> in <a href="index.php?cid=<?php echo $post['CID']; ?>"><?php echo $post['Category']; ?> </a></span>

<p class="singlepostcontent"><?php echo parseLineBreaks($post["Content"]); ?></p>
<?php if(isAdmin()){
	echo "<span class=\"postadmin\">";
	echo "<br />[";
	echo "<a href=\"?action=checkDelete&amp;dpid=".$post['PID']."\">Delete</a>";
	echo "|<a href=\"?action=editPost&amp;pid=".$post['PID']."\">Edit</a>";
	echo "]";
	echo "</span>";
	}
?>

<div class="spcomments"><h3>Comments</h3>
	<?php if(displayComments($_GET['pid'])==False){
		echo "There are no comments! Why not add your opinion in the form below?";
	} ?>
</div>
<div class="addcomment">
	<?php if(loggedIn()){
		?>
		<form action="" method="post"><label for="cbody">Comment:<br /></label><textarea id="cbody" rows="8" cols="60" name="cbody"></textarea><br /><input type="submit">
		<?php
	}else{
		echo "Why not log in and post a comment?";
	}?>
</div>
</div>
<?php
}else{
	echo "<div class=\"warning\">Invalid PID specified.</div>";
	include "includes/home.php";
}
?>