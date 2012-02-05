<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me
require_once("required.php");
?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo pageTitle($blogTitle); ?></title>
	<link rel="stylesheet" href="style.css"/>
	<link rel="alternate" type="application/rss+xml" title="<?php echo $blogTitle ?>" href="<?php echo $blogURL;?>rss.php" />
	<script src="js/comment.js"></script>
	<script src="js/confirmdelete.js"></script>
</head>
<body>
<div class="header">
	<span class="logo"><a href="index.php"><?php echo $blogTitle; ?></a></span><span class="tagline"><?php echo randomtagline(); ?></span>
	<form action="index.php" class="search" method="GET">
		<input type="hidden" name="action" value="search" /><input class="searchbox" type="text" name="term" value="Search" onfocus="if(this.value=='Search') this.value=''"/>
	</form></div>
<div class="sidebar"><?php showSidebar(); ?></div>
<div class="content">
<?php showMessage(); ?>
<?php includeFile(); ?>
</div>
</body>
</html>
<?php include "footer.php" ?> 
