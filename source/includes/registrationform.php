<h2>REGISTER</h2>
<form action="?action=processRegistration" method="POST">
	<fieldset>
		<label for="username">Username: </label><input type="text" name="username" id="username" /><br />
		<label for="title">Title: </label><input type="text" name="title" id="title" /><br />
		<label for="fname">First Name: </label><input type="text" name="fname" id="fname" /><br />
		<label for="lname">Last Name: </label><input type="text" name="lname" id="lname" /><br />
		<label for="email">Email Address: </label><input type="text" name="email" id="email" /><br />
		<label for="password">Password: </label><input type="password" name="password" id="password"><br />
		<label for="rpassword">Repeat Password: </label><input type="password" name="rpassword" id="rpassword"><br />
		<input type="submit" value="Register" />
	</fieldset>
</form>