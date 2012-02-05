<h4>Navigation</h4>
<ul>
<li><a href="index.php">View All Posts</a></li>
<?php if(!loggedIn()){?>
<li><a href="?action=login">Login</a></li>
<li><a href="?action=register">Register</a></li>
<?php }else{
	?>
	<li><a href="?action=logout">Logout</a></li>
<?php }?>
</ul>
<?php 

$pageTitleArray = pageTitleArray();
if(count($pageTitleArray)>0){
	echo "<h4>Pages</h4>";
	echo "<ul class=\"pagesmenu\">";
	foreach($pageTitleArray as $page){
		echo "<li><a href=\"?page=".$page['pageid']."\">".$page['pagetitle']."</a></li>";
	}
	echo "</ul>";
}
?>

<?php if(isAdmin()){?>
	<h4>Admin Tools</h4>
	<ul class="adminmenu">
	<li><a href="?action=admintools">Admin Tools</a></li>
	<li><a href="?action=addPost">Add Post</a></li>
	<li><a href="?action=addPage">Add Page</a></li>
	</ul>
<?php } ?>

<?php
echo "<h4>Categories</h4>";
echo "<ul>";
foreach(categoryArray() as $category){
	echo "<li><a href=\"?cid=".$category['cid']."\">".$category['catname']."</a></li>";
}
echo "</ul>";

?>

<a href="rss.php"><img style="display: block; width: 40px; height: 40px; margin-left: auto; margin-right: auto;" src="img/rss.png" /><!--http://en.wikipedia.org/wiki/File:Feed-icon.svg--></a>