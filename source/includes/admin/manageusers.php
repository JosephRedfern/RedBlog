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
<h1>User Management</h1>
<?php

if(isset($_GET['uid'])&&isset($_GET['admin'])&&isset($_GET['changeUserType'])){
	if(isAdmin()){ //once again... you can never be too sure
		changeUserType($_GET['uid'], $_GET['admin']);
	}
}


$userArray = userArray(); //let the variable $userArray = output of the userArray(); function to minimize DB calls
if(count($userArray)>0){ //it always should be... but just in case.
	echo "<table border=1><tr><th>UID</th><th>Username</th><th>Name</th><th>Email</th><th>User Type</th><th>Change Password</th><th>Delete User</th></tr>";
	foreach($userArray as $user){
		echo "<tr><td>".$user['uid']."</td><td>".$user['username']."</td><td>".$user['title'].". ".$user['fname']." ".$user['lname']."</td><td>".$user['email']."</td><td>";
		echo "<form action=\"index.php\" method=\"GET\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$user['uid']."\" />";
		echo "<input type=\"hidden\" name=\"changeUserType\" value=\"True\"/>";
		echo "<input type=\"hidden\" name=\"action\" value=\"admintools\"  />";
		echo "<input type=\"hidden\" value=\"manageusers\" name=\"tool\" />";
		echo "<select name=\"admin\" onchange=\"this.form.submit()\">";
		if(isAdmin($user['uid'])){
			echo "<option selected=\"selected\" value=\"1\">Admin</option>";
			echo "<option value=\"0\">Member</option>";
		}else{
			echo "<option value=\"1\">Admin</option>";
			echo "<option selected=\"selected\" value=\"0\">Member</option>";
		}
		echo "</select></form></td>";
		echo "<td><form method=\"POST\" action=\"index.php?action=admintools&amp;tool=manageusers&amp;do=cpw&amp;uid=".$user['uid']."\"><input type=\"text\" name=\"newpw\" /><input type=\"submit\"/></form>";
		echo "<td><input type=\"button\" value=\"Delete\" onclick=\"confirmUserDelete(".$user['uid'].")\" /></td>";
		
		echo "</tr>";
	}
}

?>

<?php
}
?>