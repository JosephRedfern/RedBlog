<h2>This is the "add post" form</h2>
<form action="?action=processAddPost" method="POST">
	<fieldset>
		<label for="title">Title: </label><input type="text" name="title" id='title' /><br />
		<label for="content">Post Content:<br /></label><textarea cols="100" rows="10" name="content"></textarea><br />
		<select name="cid">
			<?php
			$categories = categoryArray(True);
			foreach($categories as $category){
				echo "<option value=\"".$category['cid']."\">".$category["catname"]."</option>";
			}
			?>
		</select>
		<input type="submit" name="submit" value="Post!" />
	</fieldset>
</form>