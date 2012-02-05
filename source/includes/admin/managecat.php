<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(!DEFINED("INCLUDED")||!isAdmin()){die("DONT BE SNEAKY");}?>
<h1>Manage Categories</h2>
	<?php
	if(isset($_POST['submit'])&&isset($_POST['catname'])){
		if(isAdmin()){ //never be too sure...
			if($_POST['catname']!==""){
				addCat($_POST['catname']);
			}else{
				echo "You need to have provide a name for the category!!";
			}
		}
	}
		
	if(isset($_GET['cid'])&&isset($_GET['swapcid'])){
		if(validCat($_GET['cid'])&&validCat($_GET['swapcid'])&&$_GET['cid']!=$_GET['swapcid']){
			delCat($_GET['cid'], $_GET['swapcid']);
		}else{
			echo "Something funny's going on - The cid and/or swapcid you provided is/are invalid.";
			echo validCat($_GET['cid']);
		}	
	}

	?>
	<h3>Add Category</h3>
	<form action="?action=admintools&tool=managecat" method="POST">
		<fieldset>
			<label>Category Name: </label><input type="text" name="catname" /><br/>
			<input type="submit" name="submit"/>
		</fieldset>
	</form>
	
	<h3>Delete Categories</h3>
	<p><h4>READ ME:</h4>
	There are one or two things you need to know about the "delete category" function. <br />
	In order to make sure that ever post has a category, you need to choose a "swap category". If any posts have the category that you are trying to delete assigned to them, then the category will be swapped to the category you provided in the drop down menu below.
	<br />
	If there is only one category left, then it is not possible to delete it. 
	</p>
	<?php
	$categories = categoryArray(True);
	echo "<table border=1><tr><th>CID</th><th>Category Name</th><th>Swap Category</th><th>Delete</th></tr>";
	foreach($categories as $category){
		echo "<tr><td>".$category["cid"]."</td><td>".$category["catname"]."</td><td>";
		if(count($categories)-1>0){
			echo "<form action=\"?admintools&tool=mangagecat&do=delete\" method=\"GET\">";
			echo "<input name=\"action\" type=\"hidden\" value=\"admintools\" />";
			echo "<input name=\"tool\" type=\"hidden\" value=\"managecat\" />";
			echo "<input name=\"cid\" type=\"hidden\" value=\"".$category['cid']."\"/>";
			echo "<select name=\"swapcid\">";
			foreach($categories as $catitem){
				if($catitem['cid']==$category['cid']){
					//do nothing
				}else{
					echo "<option value=\"".$catitem['cid']."\">".$catitem['catname']."</option>";
				}
			}
			echo "</td><td>";
			echo "<input type=\"submit\" value=\"Delete\"/>";
			echo "</select>";
			echo "</form>";
			echo "</tr>";
		}else{
			echo "</td><td>";
			echo "</select>";
			echo "</form>";
			echo "</tr>";
		}
	}
	echo "</table>";
	?>