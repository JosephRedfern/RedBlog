<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

//This file generates an RSS feed of the latest 10 posts. It has been "seperated" from the HTML version of the site, in that it does not do the normal checks for get variables etc. However, all functions are available.
include "settings.php";
include "functions.php";
$db_connect = mysql_connect($mysql_host, $mysql_user, $mysql_password) OR DIE(mysql_error());
$db_select = mysql_select_db($mysql_db, $db_connect) OR DIE(mysql_error());
header("Content-type: text/xml");
?>
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
	<channel>
		<title><?php echo $blogTitle; ?></title>
		<link><?php echo $blogURL; ?></link>
		<description><?php echo $blogDescription ?></description>
		
		<?php
		foreach(postsArray() as $post){
			echo "<item>\n";
			echo "			<title>".$post['Title']."</title>\n";
			echo "			<link>".$blogURL."index.php?pid=".$post['PID']."</link>\n";
			echo "			<description>".substr($post['Content'], 0, 400)."</description>\n";
			echo "			<pubDate>".$post["Timestamp"]."</pubDate>\n";
			echo "		</item>\n";
		}
		?>
	</channel>
</rss>