<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(!defined("INCLUDED")){
	die("No direct access.");
}else{?><div class="post">
	<h2><a href="index.php?action=singlePost&amp;pid=<?php echo $post["PID"]; ?>"><?php echo $post["Title"]; ?></a></h2>
	<span class="postinfo">Posted by  <em><?php echo $post["Author"]; ?></em> on <?php echo date("j/n/Y", strtotime($post['Timestamp'])); ?> in <a href="index.php?cid=<?php echo $post['CID']; ?>"><?php echo $post['Category']; ?> </a></span>
	<p class="postcontent">
		<?php echo parseLineBreaks($post["Content"]); ?>
	</p>
	<span class="bottominfo"><small>There are <?php echo numberOfComments($post["PID"]); ?> <a href="?action=singlePost&amp;pid=<?php echo $post["PID"]; ?>">Comment(s)</a> on this post
	<?php if(isAdmin()){
		echo "<br />[";
		echo "<a href=\"?action=checkDelete&amp;dpid=".$post['PID']."\">Delete</a>";
		echo "|<a href=\"?action=editPost&amp;pid=".$post['PID']."\">Edit</a>";
		echo "]";
		}
	?></small></span>
</div><?php } ?>