//function to confirm deletion of users.
function confirmUserDelete(uid){
	if(confirm("Are you sure you want to delete this user? \n PLEASE NOTE THAT ALL POSTS AND COMMENTS MADE BY THIS USER WILL ALSO BE DELETED")){
		window.location = "index.php?action=admintools&tool=manageusers&do=delete&uid="+uid;
	}
}

function confirmPageDelete(pageid){
	if(confirm("Are you sure you wish to delete this page?\nIt is an irreversable process...")){
		window.location = "index.php?action=deletepage&pageid="+pageid;
	}
}