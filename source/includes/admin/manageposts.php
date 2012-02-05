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
}else{
	?>
<h2>Post Management</h2>

<?php

$allPosts = postsArray(0);
if(count($allPosts>0)){
	echo "<table border=\"1\"><tr><th>PID</th><th>Title</th><th>Author</th><th>Date</th><th>Edit</th><th>Delete</th></tr>";
	foreach($allPosts as $post){
		echo "<tr><td>".$post['PID']."</td><td>".$post['Title']."</td><td>".$post['Author']."</td><td>".$post['Timestamp']."</td><td><a href=\"index.php?action=editPost&pid=".$post['PID']."\">[EDIT]</a></td><td><a href=\"index.php?action=checkDelete&dpid=".$post['PID']."\">[DELETE]</a></td></tr>";
	}
}

?>


<?php } ?>