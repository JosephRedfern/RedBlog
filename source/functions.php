<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

//Function to check if the user is logged in. Returns true if the uid session is set, false if not. Allows for elegant usage in the form of if(loggedIn()){dosomething;}. 
function loggedIn(){ //function to check if logged in
	if(isset($_SESSION['uid'])){
		return true; //return true is username session is set
	}else{
		return false; //return false if not.
	}
}

//function to return a random tagline.
//ideally these would be stored in a db and have a nice add/delete interface... but no time!
function randomTagline(){
	$taglines = array("ironically in monochrome", "all up in your interwebs", "a security nightmare since 2011", "this page uses HOW MANY sql queries!?", "why cheezoid exist<a href=\"http://www.youtube.com/watch?v=B_m17HK97M8\">?</a>", "haters gonna hate", "at least its not VB..<a href=\"http://www.youtube.com/watch?v=hkDD03yeLnU\">.</a>", "nyan nyan nyan nyan nyan nyan <a href=\"http://www.youtube.com/watch?v=QH2-TGUlwu4\">nyan</a>", "<a href=\"http://www.youtube.com/watch?v=dQw4w9WgXcQ\">Click here for free beer</a>" );
	return $taglines[array_rand($taglines)];
}

//this function has one optional argument. 
//if a uid is specified, it will search the users table for an admin with that UID, and return true if a result is found.
//if no uid is specified, then the current user session is checked.
function isAdmin($uid=NULL){
	if($uid==NULL){
		if(loggedIn()){
			if(isset($_SESSION['admin'])&&$_SESSION['admin']==TRUE){
				return True;
			}else{
				return False;
			}
		}else{
			return False;
		}
	}else{
		if(validUID($uid)){
			$uid = mysql_real_escape_string($uid);
			$query = "SELECT * FROM `users` WHERE `admin`=1 AND `uid`=$uid";
			if($result = mysql_query($query)){
				if(mysql_num_rows($result)>0){
					return True;
				}else{
					return False;
				}
			}else{
				return False;
			}
		}else{
			return False;
		}
	}
}

function changePassword($newpw=NULL, $uid=NULL){
	if(loggedIn()){
		if(isset($_POST['newpw'])){
			if($uid==NULL){
				$uid = $_SESSION['uid'];
				if(validUID($uid)){
					if(isset($_POST['newpw2'])){ //require password confirmation if id isn't specified... i.e. if it's password is being changed by a user
						if($_POST['newpw2']==$_POST['newpw']){
							if($_POST['newpw']!=""){
							$password = md5(mysql_real_escape_string($_POST['newpw']).SALT);
							$query = "UPDATE `users` SET `password`='$password' WHERE `uid`=$uid";
							if(mysql_query($query)){
								setMessage("success", "Your password has been changed - great success!");
								$_SESSION['include'] = "cpw.php";
							}else{
								setMessage("error", "An error occured while changing your password. Oh heck!");
							}
						}else{
							setMessage("warning", "Your password can't be blank, you crazy fool!");
							$_SESSION['include'] = "cpw.php";
						}
						}else{
							//passwords dont match
							setMessage("warning", "Your passwords don't match. Please try again");
							$_SESSION['include'] = "cpw.php";
						}
					}
				}else{
					setMessage("error", "An error has occured. Your UID is not valid - maybe your account was deleted and you've not logged in/out since?");
				}
				//assume current session
			}else{
				if(isAdmin()){
					if($newpw!=NULL){
						if(validUID($uid)){
							$password = md5(mysql_real_escape_string($newpw).SALT);
							$uid = mysql_real_escape_string($uid);
							$query = "UPDATE `users` SET `password`='$password' WHERE `uid`=$uid";
							if(mysql_query($query)){
								setMessage("success", "Great success. Password changed");
							}else{
								//error: pw not changed
								setMessage("error", "An error has occured... the password hasn't been changed.");
								echo "<pre>$query</pre>";
							}
						}else{
							//invalid uid
							setMessage("warning", "Invalid UID has been specified");
						}
					}else{
						setMessage("warning", "Passwords cannot be blank... That would be dangerous!");
					}
				}else{
					//not admin
					setMessage("warning", "Excuse me? What are you trying to do here. You can't change other peoples passwords. Incident logged.");
				}
			}
		}else{
			//not logged in
			setMessage("warning", "You can't change your password if you're not logged in.");
		}
	}
}

//this function outputs an array of comments for a given Post ID
function commentsArray($pid){
	$pid = mysql_real_escape_string($pid);
	if(validPID($pid)){
		$query = "SELECT C.cid, C.uid, C.pid, C.cbody, C.ctimestamp, U.fname, U.lname FROM comments C, users U WHERE `pid` = $pid AND U.uid=C.uid ORDER BY `cid` DESC";
		if($result = mysql_query($query)){
			$comments = array();
			while($comment = mysql_fetch_array($result)){
				$comments[$comment['cid']]=array("cid"=>$comment['cid'], "uid"=>$comment['uid'], "pid"=>$comment['pid'], "timestamp"=>$comment['ctimestamp'], "author"=>$comment['fname']." ".$comment['lname'], "body"=>$comment['cbody']);
			}
			return $comments;
		}else{
			die(mysql_error());
		}
	}else{
		return False;
	}
}

function displayComments($pid){
	$pid = mysql_real_escape_string($pid);
	$commentsarray = commentsArray($pid);
	if(count($commentsarray>0)){
		foreach($commentsarray as $comment){
			include "includes/commenttemplate.php";
		}
		return True;
	}else{
		return False;
	}
}

function addComment(){
	if(loggedIn()){
		$pid = mysql_real_escape_string($_GET['pid']);
		if(validPID($pid)){
			if(isset($_POST['cbody'])){
				$cbody = mysql_real_escape_string(htmlentities($_POST['cbody']));
				$query = "INSERT INTO `comments`(`pid`, `uid`, `cbody`) VALUES($pid, ".$_SESSION['uid'].", '$cbody')";
				if(mysql_query($query)){
					setMessage("success", "Comment Successfully Added");
				}else{
					setMessage("error", "Something bad's gone down... we have a problem!");
				}
			}else{
				setMessage("warning", "This particular page cannot be accessed directly - it needs to contain comment POST data.");
			}
		}else{
			setMessage("warning", "Invalid PID specified");
		}
	}else{
		setMessage("warning", "You need to be logged in to make comments.");
	}
}

//This function takes a category id, and outputs a category name
function cidToCategoryName($cid){
	$cid = mysql_real_escape_string($cid);
	$query = "SELECT `catname` FROM `categories` WHERE `cid`=".$cid; 
	$result = mysql_query($query) or die(mysql_error());
	
	if($result=mysql_query($query)){
		if(mysql_num_rows($result)>0){ //if there is a non-zero number of rows, return the name
			$row = mysql_fetch_assoc($result);
			return $row["catname"];
		}else{
			setMessage("warning", "Invalid Category");
		}
	}else{
		setMessage("warning", "Some kind of error occured!");
	}
}

//This function is in chage of authenticating users. I decided against having a "loggedin" session, and opted for relying on the existance of the uid session to check if the user is logged in.
function processLogin(){
	if(!isset($_SESSION['uid'])){
		if(isset($_POST['username'])&&isset($_POST['password'])){
			$username = mysql_real_escape_string($_POST['username']);
			$password = md5(mysql_real_escape_string($_POST['password']).SALT);
			$query = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
			$result = mysql_query($query) OR die(mysql_error());
			if(mysql_num_rows($result)==0){
				setMessage("warning", "Your account could not be found... Are you sure your username & password where entered correctly?"); //set error session to "accountnotfound" for display error function to handle
				$_SESSION['include'] = "loginform.php";
			}else{
				$row = mysql_fetch_assoc($result);
				$_SESSION['uid'] = $row["uid"];//saving all these DB values into session variables helps minimise database calls.
				$_SESSION['username'] = $row["username"];
				$_SESSION['title'] = $row["title"];
				$_SESSION['fname'] = $row["fname"];
				$_SESSION['lname'] = $row["lname"];
				$_SESSION['email'] = $row["email"];
				if($row['admin']==1){
					$_SESSION["admin"] = true;
				}else{
					$_SESSION["admin"] = false;
				}
				setMessage("success", "Logged in successfully!");
				$_SESSION['include'] = "home.php";
			}
		}else{
			setMessage("warning", "The form didn't seem to be submitted... Please try again.");//set error to "form not submitted"
			$_SESSION['include'] = "loginform.php"; //include login form again
		}
	}else{
		setMessage("warning", "You are already logged in!"); 
		$_SESSION['include'] = "home.php";
	}
}

function pidToTitle($pid){
	$pid = mysql_real_escape_string($pid);
	$query = "SELECT `title` FROM `posts` WHERE `pid`='$pid'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result); //call it row since pid is unique
	return $row["title"];
}

function usernameExists($username){
	$username = mysql_real_escape_string($username);
	$query = "SELECT * FROM `users` WHERE username='$username'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return true;
	}
}

function emailExists($email){
	$email = mysql_real_escape_string($email);
	$query = "SELECT * FROM `users` WHERE email='$email'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return true;
	}
}

function deleteUser($uid){
	$uid = mysql_real_escape_string($uid);
	if(isAdmin()){
		if($uid!=$_SESSION['uid']){
			$delposts = "DELETE FROM `posts` WHERE `uid`=".$uid;
			$delcomments = "DELETE FROM `comments` WHERE `uid` = ".$uid;
			$deluser = "DELETE FROM `users` WHERE `uid`=".$uid;
			if(mysql_query($delposts)&&mysql_query($delcomments)&&mysql_query($deluser)){
				setMessage("success", "Great Success - The user is no more.");
			}
		}else{
			setMessage("warning", "You can't delete yourself. That would be like eating your own head");
		}
	}else{
		setMessage("warning","Sneaky sneaky... you're not an admin. Nice try.");
	}
}

function setDeletePostSession($pid){
	$_SESSION['dpid'] = $pid; //set dpid (delete post id) session = the pid provided. this "primes" the delete function
}

function deletePostGET(){ //Delete Post based on GET URL and SESSION
	if(isset($_SESSION['dpid'])&&isset($_GET['dpid'])&&$_GET['dpid']==$_SESSION['dpid']){
		$dpid = mysql_real_escape_string($_SESSION['dpid']); //escape just incase
		$query = "DELETE FROM `posts` WHERE `pid`='$dpid'";
		if(mysql_query($query)){
			setMessage("success", "Post successfully deleted... Please Wait<script>setTimeout('window.location=\"index.php\"', 1500);</script>");
		}else{
			setMessage("warning", "Error deleting post");
		}
	}else{
		setMessage("warning", "Error deleting post. Please try again.");
	}
}


//This relativley large function is used to process user registration.
//Some server side validation is done to ensure that usernames and passwords etc aren't blank.
//Passwords are salted, so that in the event of a database breach, password digests would be much harder to crack.
function processRegistration(){
	if(isset($_POST['username'])&&isset($_POST['fname'])&&isset($_POST['title'])&&isset($_POST['lname'])&&isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['rpassword'])){
		if($_POST['username']!=""&&$_POST['fname']!=""&&$_POST['lname']!=""&&$_POST['title']!=""&&$_POST['email']!=""&&$_POST['password']!=""){
			if($_POST['rpassword']!=$_POST['password']){
				setMessage("warning", "Your passwords to not match...!");
				$_SESSION['include'] = "registrationform.php";
			}else{
				if(!emailExists($_POST['email'])){
					if(!usernameExists($_POST['username'])){
						$username = mysql_real_escape_string($_POST['username']);
						$title = htmlentities(mysql_real_escape_string($_POST['title']));
						$fname = htmlentities(mysql_real_escape_string($_POST['fname']));
						$lname = htmlentities(mysql_real_escape_string($_POST['lname']));
						$email = htmlentities(mysql_real_escape_string($_POST['email']));
						$password = md5(mysql_real_escape_string($_POST['password']).SALT);
						$query = "INSERT INTO users(username, title, fname, lname, email, password) VALUES('$username', '$title', '$fname', '$lname', '$email', '$password')";
						if(mysql_query($query)){
							setMessage("success", "You have successfully registered! Please log in.");
							$_SESSION['include'] = "loginform.php";
						}else{
							setMessage("error", "An error has occurred... oh heck. ");
							$_SESSION['include'] = "registrationform.php";
						}
					}else{
						setMessage("warning", "The username you selected has already been taken. Please try again with another username.");
						$_SESSION['include'] = "registrationform.php";
					}
				}else{
					setMessage("warning", "A user has already registered with that email address.");
					$_SESSION['include'] = "registrationform.php";
				}
		}
	}else{
		setMessage("warning", "You need to fill in all of the text boxes - there are no optional inputs");
		$_SESSION['include'] = "registrationform.php";
	}
	}else{
		setMessage("warning", "You didn't seem to access this page from the registration page. It has been included below for your convenience. Don't mention it!");
		$_SESSION['include'] = "registrationform.php";
		
		
	}
}

//This function displays the add post form if the user is an admin. if the user is not an admin, then a message is displayed. If the user is not logged in, then the login form is displayed.
function addPost(){
	if(!loggedIn()){
			setMessage("warning", "You need to be logged in to make a blog post.");
			$_SESSION["include"] = "loginform.php";
	}else{
		if(!isAdmin()){
			setMessage("warning", "You do not have the appropriate access rights to make a post");
		}else{
			$_SESSION["include"] = "addpostform.php";
		}
	}
}

//This function displays the add page form if the user is an admin. if the user is not an admin, then a message is displayed. If the user is not logged in, then the login form is displayed.
function addPage(){
	if(!loggedIn()){
		setMessage("warning", "You need to be logged in to add a page");
		$_SESSION['include'] = "loginform.php";
	}else{
		if(!isAdmin()){
			setMessage("warning", "You do not have the appropriate access rights to add a page.");
		}else{
			$_SESSION['include'] = "addpageform.php";
		}
	}
}

function pageIDToTitle($pid){
	$pid = mysql_real_escape_string($pid);
	$query = "SELECT pagetitle FROM `pages` WHERE `pageid`=$pid";
	if($result = mysql_query($query)){
		if(mysql_num_rows($result)>0){
			$post = mysql_fetch_assoc($result);
			return $post['pagetitle'];
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function pageTitleArray(){ //output array of page names & id's (primarily for sidebar)
	$query = "SELECT `pageid`, `pagetitle` FROM `pages` ORDER BY `pagetitle` ASC";
	$pages = array();
	if($result = mysql_query($query)){
		while($page = mysql_fetch_assoc($result)){
			$pages[$page['pageid']]=array("pageid"=>$page['pageid'], "pagetitle"=>$page['pagetitle']);
		}
		return $pages;
	}else{
		return -1;
	}
}

//This function takes in a user ID, and a boolean value for $admin. The User ID specified will either be demoted or promoted to admin, based on $admin being either 0 or 1.
function changeUserType($uid, $admin){
	if(isAdmin()){
		$uid = mysql_real_escape_string($uid);
		$admin = mysql_real_escape_string($admin);
		if($uid != $_SESSION['uid']){
			if($admin==1||$admin==0){ //stop users from editing their own privs.
				$query = "UPDATE `users` SET admin=$admin WHERE `uid`=".$uid;
				if(mysql_query($query)){
					echo "<div class=\"success\">Great success. The user's admin status has been changed.</div>";
				}
			}else{
			echo "<div class=\"error\">Something strange has happened. The value you submitted was not a boolean.</div>";
			}
		}else{
			echo "<div class=\"warning\">You cannot demote yourself... that would be dangerous.</div>";
		}
	}else{
		echo "<div class=\"warning\">I'm afraid I can't do that Dave.</div>";
	}
}

//This function outputs an array of all of the users within the users table. It is mainly used for the "Manage Users" section of the site.
function userArray(){
	$query = "SELECT * FROM `users`";
	$users = array();
	if($result = mysql_query($query)){
		while($user = mysql_fetch_assoc($result)){
			$users[$user["uid"]] = array("uid"=>$user['uid'], "username"=>$user['username'], "title"=>$user['title'], "fname"=>$user['fname'], "lname"=>$user['lname'], "email"=>$user['email'], "admin"=>$user['admin']);
		}
		return $users;
	}else{
		return -1;
	}
}

//This function is similar to the processAddPost() function, but uses UPDATE rather than INSERT.
function processEditPost(){
	if(isAdmin()){
		if(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['cid'])){
			$pid = mysql_real_escape_string($_GET['pid']);
			$title = mysql_real_escape_string($_POST['title']);
			$content = mysql_real_escape_string($_POST['content']);
			$cid = mysql_real_escape_string($_POST['cid']);
			$query = "UPDATE `posts` SET `title`='$title', `content`='$content', `cid`=$cid WHERE pid=$pid";
			if(mysql_query($query)){
				setMessage("success", "Great Success. Please wait while you are redirected to your masterpiece.<script>setTimeout('window.location=\"index.php?action=singlePost&pid=".$pid."\"', 1500);</script>"); 
			}else{
				setMessage("error", "An unexpected error has occured while attempting to update your post. All being well, the post will not have been effected.");
			}
		}
	}
}

//This function is similar to the processAddPost() function, but uses UPDATE rather than INSERT.
function processEditPage(){
	if(isAdmin()){
		if(isset($_POST['pagetitle'])&&isset($_POST['pagebody'])){
			$pageid = mysql_real_escape_string($_GET['pageid']);
			$pagetitle = mysql_real_escape_string($_POST['pagetitle']);
			$pagebody = mysql_real_escape_string($_POST['pagebody']);
			$query = "UPDATE `pages` SET `pagetitle`='$pagetitle', `pagebody`='$pagebody' WHERE pageid=$pageid";
			if(mysql_query($query)){
				setMessage("success", "Great Success. Please wait while you are redirected to your masterpiece.<script>setTimeout('window.location=\"index.php?page=".$pageid."\"', 1500);</script>"); 
			}else{
				setMessage("error", "An unexpected error has occured while attempting to update your page. All being well, the post will not have been destroyed.");
			}
		}
	}
}

function deletePage(){
	if(isAdmin()){
		if(validPage($_GET['pageid'])){
			$pageid = mysql_real_escape_string($_GET['pageid']);
			$query = "DELETE FROM `pages` WHERE `pageid`=$pageid";
			if(mysql_query($query)){
				setMessage("success", "Page Deleted Successfully!");
				$_SESSION['include'] = "home.php";
			}
		}else{
			setMessage("warning", "Invalid page specified. Maybe it's already been deleted?");
			$_SESSION['include'] = "home.php";
		}
	}else{
		setMessage("warning", "You don't have the rights to do that. Don't be sneaky!");
		$_SESSION['include'] = "home.php";
	}
}

//This function is used to add pages.
//Pages are essentially static posts, and are permanently positioned in the sidebar.
function processAddPage(){
	if(isAdmin()){
		if(isset($_POST['title'])&&isset($_POST['body'])){
			$title = mysql_real_escape_string($_POST['title']);
			$body = mysql_real_escape_string($_POST['body']);
			$query = "INSERT INTO `pages` (pagetitle, pagebody) VALUES('$title', '$body')";
			if(mysql_query($query)){
				setMessage("success", "Page has been added");
				$_SESSION['include'] = "home.php"; //TODO: Take the user to the page that they have just added. 
			}else{
				setMessage("error", "A fatal error has occured...");
			}
		}else{
			setMessage("warning", "This form did not seem to be submitted from the appropriate page");	
		}
	}else{
		setMessage("warning", "You need to be an admin to log in.");
	}
}


//this function processes the adding of a post. 
//This is only possible if the user is an admin.
//Some basic validation is done.
function processAddPost(){
if(isAdmin()){
		if(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['cid'])){
		
			$title = mysql_real_escape_string($_POST['title']);
			$content = mysql_real_escape_string($_POST['content']);
			$cid = mysql_real_escape_string($_POST['cid']);
			$uid = $_SESSION['uid'];
			$query = "INSERT INTO `posts` (title, content, cid, uid) VALUES(\"$title\", \"$content\", $cid, $uid)";
			if(mysql_query($query)){
				setMessage("success", "Post Added Successfully");
				$_SESSION['include'] = "home.php";
			}else{
				setMessage("error", "An error has occured...".mysql_error());
			}
		}else{
			setMessage("warning", "No post variables where sent to this URL...");
			$_SESSION['include'] = "adpostform.php";
		}
	}else{
		setMessage("warning", "You do not have the privileges to post... ");
	}
}
//This function destroys all session variables, which logs out the user. Perhaps error/warning/success/information session variables should be maintained... this needs consideration.
function logout(){
if(isset($_SESSION['uid'])){
	unset($_SESSION['uid']);//can't just do session_destroy(); for some reason... seems to destroy sesion at the very end of the page. unset "fixes"" this. googling yields this confirmation: http://www.php.net/manual/en/function.session-destroy.php#85743
	session_destroy();
	setMessage("success", "You have succesfully been logged out. Come back soon!");
	$_SESSION['include'] = "home.php";
}else{
	session_destroy();
	setMessage("success", "We think that you where already logged out... but the process has been re-done, just to make sure!"); //sneakily use the setMessage function to cause a javascript redirect
	$_SESSION['include'] = "home.php";
}
}

//this function takes a category name and adds it. 
function addCat($name){
	if(isAdmin()){
		$name = mysql_real_escape_string($name);
		$query = "INSERT INTO `categories` (`catname`) VALUES ('$name')";
		if(mysql_query($query)){
			echo "Sucesfully Added the category \"".$name."\"";
		}else{
			die(mysql_error());
		}
	}
}

//this function deletes categories.
//To avoid posts having blank/invalid categories, $swapcid must be specified - before deleting the category $cid, all posts who's cid is set to $cid have their cid changed to $swapcid. 
function delCat($cid, $swapcid){
	$cid = mysql_real_escape_string($cid);
	$swapcid = mysql_real_escape_string($swapcid);
	$swapq = "UPDATE `posts` SET `cid`=$swapcid WHERE `cid`=$cid";
	if(mysql_query($swapq)){
		$delq = "DELETE FROM `categories` WHERE `cid`=".$cid;
		if(mysql_query($delq)){
			echo "Great Success. Deleted!";
		}
	}
}


//this function deletes a comment based on the GET parameter of the URL
function deleteComment(){
	$cid = mysql_real_escape_string($_GET['deleteComment']);
	$uid = mysql_real_escape_string($_SESSION['uid']);
	if(!isAdmin()){
		$query = "SELECT * FROM `comments` WHERE `cid`=$cid AND `uid`=$uid";
		if(mysql_num_rows(mysql_query($query))>0){
			$query = "DELETE FROM `comments` WHERE `cid`=$cid AND `uid`=$uid";
			if(mysql_query($query)){
				setMessage("success", "Comment Deleted");
			}else{
				setMessage("error", "An error has occured!");
			}
		}else{
			setMessage("warning", "Either that comment doesn't exist, or it's not yours to delete. Don't be sneaky!");
		}
	}else{
		if(validComment($cid)){
			$query = "DELETE FROM `comments` WHERE `cid`=$cid";
			if(mysql_query($query)){
				setMessage("success", "Great Success. Comment Deleted!");
			}
		}else{
			setMessage("warning", "Invalid Comment ID. Perhaps it has already been deleted?");
		}
	}
}


//this function checks to see if the provided $cid matches a row within the comments table.
//it does not ACTUALLY check the contents of the comment to see if the user has formed a valid argument... If it did, then I'd probably be a millionaire.
function validComment($cid){
	$cid = mysql_real_escape_string($cid);
	$query = "SELECT * FROM `comments` WHERE `cid`=$cid";
	if($result = mysql_query($query)){
		if(mysql_num_rows($result)>0){
			return True;
		}else{
			return False;
		}
	}else{
		return False;
	}
}

//This function is called every time a comment is displayed, in order to determin whether or not to show a delete comment button.
//If the current user is the user that posted the specific comment, or if the user is an admin, then the button is shown.
//Otherwise, this function outputs nothing.
function showDeleteCommentButton($uid){ 
	if(loggedIn()){
		if(isAdmin()){
			return True;
		}else{
			if($_SESSION['uid']==$uid){
				return True;
			}else{
				return False;
			}
		}
	}else{
		return False;
	}
}

function validUID($uid){
	$uid = mysql_real_escape_string($uid);
	if(is_numeric($uid)){
		$query = "SELECT * FROM `users` WHERE `uid`=$uid";
		if($result = mysql_query($query)){
			if(mysql_num_rows($result)>0){
				return True;
			}else{
				return False;
			}
		}else{
			return False;
		}
	}else{
		return False;
	}
}

//this function checks if the provided $cid matches a valid category
function validCat($cid){
	if(is_numeric($cid)){
		$cid = mysql_real_escape_string($cid);
		$query = "SELECT * FROM `categories` WHERE `cid`=$cid";
		if($result = mysql_query($query)){
			if(mysql_num_rows($result)==0){
				return False;
			}else{
				return True;
			}
		}else{
			return False;
		}
	}else{
		return False;
	}
}

//This function takes a page id, and queries the database to see if a row with that page ID exists
function validPage($pageid){
	$pageid = mysql_real_escape_string($pageid);
	if(is_numeric($pageid)){ //not going to be valid if the page ID is non-numeric, so there is no point in even trying.
		$query = "SELECT pageid FROM `pages` WHERE `pageid`=$pageid";
		if($result = mysql_query($query)){
			if(mysql_num_rows($result)>0){
				return True;
			}else{
				return False;
			}
		}else{
			return False;
		}
	}else{
		return False;
	}
}


//This function returns an array of post data. 
function singlePostArray($pid){
	$pid = mysql_real_escape_string($pid);
	$query = "SELECT DISTINCT P.pid, P.uid, U.fname, U.lname, P.cid, C.catname, P.title, P.content, P.timestamp FROM posts P, categories C, users U WHERE P.cid=C.cid AND U.uid=P.uid AND P.pid = $pid LIMIT 1";
	if($result = mysql_query($query)){
		$post = array();
		while($row = mysql_fetch_assoc($result)){
			$post = array("Title"=>$row["title"], "Content"=>$row["content"], "Author"=>$row["fname"]." ".$row["lname"], "UID"=>$row["uid"], "Timestamp"=>$row["timestamp"], "Category"=>$row["catname"], "CID"=>$row["cid"], "PID"=>$row["pid"]);
		}
		return $post;
	}else{
		return False;
	}
}

//This function takes a PID from , checks that it is valid, and then get's the post data array using the singlePostArray() function. This data is then applied to a template and displayed to the user.
function singlePost($pid=NULL){
	if($pid==NULL){
		if(isset($_GET['pid'])){
			$pid = mysql_real_escape_string($_GET['pid']);
		}else{
			setMessage("warning", "No PID specified");
		}
	}
	
	if(validPID($pid)){
		$post = singlePostArray($pid);
		if(count($post)>0){
			include "includes/singleposttemplate.php";
		}
	}else{
		setMessage("warning", "No can do... The post ID you specified is not valid.");
	}

}

//Function similar to the above - takes PID (POST ID) and checks to see if a row with that PID exists in the database.
function validPID($pid){
	$pid = mysql_real_escape_string($pid);
	if(is_numeric($pid)){ //not going to be valid if the page ID is non-numeric, so there is no point in even trying.
		$query = "SELECT pid FROM `posts` WHERE `pid`=$pid";
		if($result = mysql_query($query)){
			if(mysql_num_rows($result)>0){
				return True;
			}else{
				return False;
			}
		}else{
			return False;
		}
	}else{
		return False;
	}
}

//This function handles GET requests when ?action=admin
function handleAdminGetRequests(){
	if(isAdmin()){
		if(isset($_GET['tool'])){
			switch($_GET['tool']){
				case "managecat":
					$_SESSION['include'] = "admin/managecat.php";
				break;
				case "manageposts":
					$_SESSION['include'] = "admin/manageposts.php";
				break;
				case "manageusers":
					if(isset($_GET['do'])&&$_GET['do']=="delete"&&isset($_GET['uid'])){
						deleteUser($_GET['uid']);
					}
					if(isset($_GET['do'])&&$_GET['do']=="cpw"&&isset($_GET['uid'])&&isset($_POST['newpw'])){
						changePassword($_POST['newpw'], $_GET['uid']);
					}
					
					$_SESSION['include'] = "admin/manageusers.php";
				break;
				default:
					$_SESSION['include'] = "admin/adminpage.php";
			}
		}
	}
}

//This function is in charge of handling the get requests. It will call functions and initalise sessions based on the GET parameter(s) of the URL.
//It's started to get a bit crowded... Ideally it would be a bit more seperated. 
function handleGetRequests(){
if(isset($_POST['cbody'])){
	addComment();
}

if(isset($_GET['delete'])){
	deletePostGET();
}else{
	if(isset($_GET['page'])){
		$_SESSION['include'] = "pagetemplate.php";
	}else{
		if(isset($_GET['action'])){
			switch($_GET['action']){
				case "logout":
					logout();
				break;
				case "search":
				if(isset($_GET['term'])){
					$_SESSION['include'] = "searchresults.php";
				}else{
					$_SESSION['include'] = "home.php";
				}
				break;
				break;
				case "addPost":
					addPost();
				break;
				case "deletepage":
					deletePage();
				break;	
				case "processAddPost":
					processAddPost();
				break;
				case "addPage":
					addPage();
				break;
				case "cpw":
					if(isset($_POST['newpw'])){
						changePassword();
					}
					$_SESSION['include'] = "cpw.php";
				break;
				case "singlePost":
					if(isset($_GET['deleteComment'])){
						deleteComment();
					}
					$_SESSION['include'] = "singlepost.php";
				break;
				case "processAddPage":
					processAddPage();
				break;
				case "editPost":
					$_SESSION['include'] = "editpostform.php";
				break;
				case "editPage":
					$_SESSION['include'] = "editpageform.php";
				break;
				case "processEditPost":
					processEditPost();
				break;
				case "processEditPage":
					processEditPage();
				break;
				case "checkDelete":
					$_SESSION['include'] = "checkdelete.php";
				break;
				case "login":
					if(!loggedIn()){
						$_SESSION['include'] = "loginform.php";
					}else{
						setMessage("warning", "You are already logged in...!");
						$_SESSION['include'] = "home.php";
					}
				break;
				case "admintools":
					if(!isAdmin()){
						setMessage("warning", "Access to this page is not allowed.");
						$_SESSION['include'] = "home.php";
					}else{
						if(!isset($_GET['tool'])){
							$_SESSION['include'] = "adminpage.php";
						}else{
							handleAdminGetRequests();
						}
					}
				break;
				case "register":
					if(!loggedIn()){
						$_SESSION['include'] = "registrationform.php";
					}else{
						setMessage("warning", "You must already be registered, since you are logged in!");
						$_SESSION['include'] = "home.php";
					}
				break;
				case "processRegistration":
					processRegistration();
				break;
				case "processLogin":
					processLogin();
				break;
				default:
					$_SESSION['include'] = "home.php"; //if action not valid, display home.php
				}
			}else{
			$_SESSION['include'] = "home.php"; //if no get request, display home.php
		}
}
}
}

//function outputs array of cat id's, names and post counts. It is used in the sidebar and in the "Create post"/"Edit Post" pages.
function categoryArray($showEmpty=False){
	if($showEmpty==False){
		$query = "SELECT DISTINCT C.cid, C.catname FROM categories C, posts P WHERE P.cid=C.cid";
	}else{
		$query = "SELECT C.cid, C.catname FROM categories C";
	}
	$result = mysql_query($query);
	$categoryArray = array();
	while($row = mysql_fetch_assoc($result)){
		$tempcat = array("cid"=>$row['cid'], "catname"=>$row['catname']);
		$categoryArray[$row['cid']] = $tempcat;
	}
	return $categoryArray;
}

//This function returns an array of comments for a given post.
//IT IS NOT FINISHED
function commentArray($pid){
	$pid = mysql_real_escape_string($pid);
	$query = "SELECT * FROM `comments` WHERE `pid`=$pid ORDER BY `ctimestamp` DESC LIMIT 10";
	if($result = mysql_query($query)){
		
	}else{
		die(mysql_error());
	}
}

//This function takes in a string with line breaks, and replaces with html <br />'s.
function parseLineBreaks($input){
	return str_replace("\n", "<br />", $input);
}

//This function outputs any array of posts which match the value provided in $search. This should be expanded so that pages are searched... perhaps.
function searchResultsArray($search, $n=10, $s=0){
	$search = mysql_real_escape_string($search);
	$n = mysql_real_escape_string($n);
	$s = mysql_real_escape_string($s);
	$query = "SELECT P.pid, P.uid, U.fname, U.lname, P.cid, C.catname, P.title, P.content, P.timestamp FROM posts P, categories C, users U WHERE P.cid=C.cid AND U.uid=P.uid AND P.content LIKE '%$search%' ORDER BY P.pid DESC LIMIT $s, $n";
	$result = mysql_query($query) OR DIE("<pre>".$query."</pre>");
	$output = array();
	while($row = mysql_fetch_assoc($result)){
		$postarray = array("Title"=>$row["title"], "Content"=>$row["content"], "Author"=>$row["fname"]." ".$row["lname"], "UID"=>$row["uid"], "Timestamp"=>$row["timestamp"], "Category"=>$row["catname"], "CID"=>$row["cid"], "PID"=>$row["pid"]);
		$output[$row['pid']] = $postarray;
	}
	return $output;
}

//this function uses searchResultsArray to list each post (from post $s to post $s+$n)
function displaySearchResults($search, $n=10, $s=0){
	$search = mysql_real_escape_string($search);
	$results = searchResultsArray($search);
	if(count($results>0)){
		foreach($results as $post){
			if(count($post>0)){
				include "includes/posttemplate.php";
			}
		}
		return count($results);
	}else{
		return count($results);
	}
}

//Function to return an array of posts in descending order of time, starting from $s
function postsArray($n=10, $s=0, $cat=NULL){
	$n = mysql_real_escape_string($n); //escape because we can't trust the get parameter of the URL, which is probably where n comes from.
	$s = mysql_real_escape_string($s); //as above, but with s.
	if($cat==NULL){
			$query = "SELECT P.pid, P.uid, U.fname, U.lname, P.cid, C.catname, P.title, P.content, P.timestamp FROM posts P, categories C, users U WHERE P.cid=C.cid AND U.uid=P.uid ORDER BY P.pid DESC LIMIT $s, $n";
	}else{
		if($cat!=NULL){
			$cat = mysql_real_escape_string($cat);
			$query = "SELECT P.pid, P.uid, U.fname, U.lname, P.cid, C.catname, P.title, P.content, P.timestamp FROM posts P, categories C, users U WHERE P.cid=C.cid AND U.uid=P.uid AND P.cid=$cat ORDER BY P.pid DESC LIMIT $s, $n";
		}
	}
	
	if($n==0){ //if $n is set to 0, return ALL the posts
		$query = "SELECT P.pid, P.uid, U.fname, U.lname, P.cid, C.catname, P.title, P.content, P.timestamp FROM posts P, categories C, users U WHERE P.cid=C.cid AND U.uid=P.uid ORDER BY P.pid DESC";
	}
	
	$result = mysql_query($query) OR DIE(mysql_error());
	$output = array();
	while($row = mysql_fetch_assoc($result)){
		
		$postarray = array("Title"=>$row["title"], "Content"=>$row["content"], "Author"=>$row["fname"]." ".$row["lname"], "UID"=>$row["uid"], "Timestamp"=>$row["timestamp"], "Category"=>$row["catname"], "CID"=>$row["cid"], "PID"=>$row["pid"]);
		$output[$row['pid']] = $postarray;
	}
	return $output;
}

//this function will call postsArray and apply a template to the data within the array
function displayPosts($n=10, $s=0, $cat=NULL){
	//this handles "pages". if page is set
	if(isset($_GET['pagenumber'])){
		$page = $_GET['pagenumber']-1;
		if(isset($_SESSION['ppp'])){ //if there is a "ppp" session, use it's value to calculate the appropriate value of $n and $s.
			$ppp = mysql_real_escape_string($_SESSION['ppp']);
			$n = $ppp;
			$s = $page*$ppp;
		}else{
			$ppp = 10;
			$n = $ppp;
			$s = $page*$ppp;
		}
	}
	DEFINE("fromDisplayPosts", TRUE);
	foreach(postsArray($n, $s, $cat) as $post){
		if(count($post)>0){
			include "includes/posttemplate.php";
		}
	}
	include "includes/pagination.php";
}


//this function simply includes the file "includes/sidebar.php". It is probably not all that nessicary, but it's nice to be able to use functions rather than raw incldude()'s.
function showSidebar(){
	include "includes/sidebar.php";
}

//This function counts the number of rows in the comments table with a PID of $pid.
function numberOfComments($pid){
	$pid = mysql_real_escape_string($pid);
	if(is_numeric($pid)){
		$query = "SELECT * FROM `comments` WHERE `pid`=$pid";
		if($result = mysql_query($query)){
			return mysql_num_rows($result);
		}else{
			return -1;
		}
	}else{
		return -1;
	}
}

//This function attempts to make a sensicle header based on the contents of the URL.
function pageTitle($blogTitle){
	if(count($_GET)==0){
		return "$blogTitle - All Posts";
	}else{
		if(isset($_GET['action'])){
			switch($_GET['action']){
				case "singlePost":
					if(isset($_GET['pid'])){
						return $blogTitle." - ".pidToTitle($_GET['pid']);
					}
				break;
				
				case "login":
					return $blogTitle." - "."Log In";
				break;
				
				case "register":
					return $blogTitle." - "."Register";
				break;
				
				default:
				return $blogTitle;
			}
		}else{
			if(isset($_GET['page'])){
				return $blogTitle." - ".pageIDToTitle($_GET['page']);
			}else{
				if(isset($_GET['cid'])){
					if(validCat($_GET['cid'])){
					return $blogTitle." - Displaying Posts in ".cidToCategoryName($_GET['cid']);
				}else{
				}
				}
			}
		}
	}
	return $blogTitle;
}

//This function sets a session which is used to carry errors. 
function setMessage($type, $body){
	$_SESSION['message'] = array("type"=>$type, "body"=>$body, "url"=>$_SERVER['REQUEST_URI']);
}

//executed at the foot of every page. if the current page URL doesn't match the URL of the page on which the session was set, kill it.
function killMessages(){
	if((isset($_SESSION['message']))&&($_SESSION['message']['url']!==$_SERVER['PHP_SELF'])){
		unset($_SESSION['message']);
	}
}

//This function displays messages set withing the "message" session
function showMessage(){
	if(isset($_SESSION['message'])){
		echo "<div class=\"".$_SESSION['message']['type']."\">".$_SESSION['message']['body']."</div>";
		unset($_SESSION['message']);
	}
}

//if "include" session is initialised, include file. should be secure since only the server can set session variables.
function includeFile(){
	if(isset($_SESSION['include'])){
		include "includes/".$_SESSION['include'];
		unset($_SESSION['include']);
	}
}

//If the headerredirect session is set, this function will redirect to the URL specified within that session.
function handleRedirects(){
	if(isset($_SESSION['headerredirect'])){
		header("Location: ".$_SESSION['headerredirect']);
		unset($_SESSION['headerredirect']);
	}
}
?>