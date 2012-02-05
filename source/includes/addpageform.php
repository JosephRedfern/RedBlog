<h2>Add Page</h2>
<form action="?action=processAddPage" method="POST">
	<fieldset>
		<label for="title">Title: </label><input type="text" name="title" id='title' /><br />
		<label for="content">Page Body:<br /></label><textarea cols="100" rows="10" name="body"></textarea><br />
		<input type="submit" name="submit" value="Post!" />
	</fieldset>
</form>