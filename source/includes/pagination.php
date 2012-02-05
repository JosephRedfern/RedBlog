<?php
//This code is released under the GNU General Public License, or suttin.
//This was my coursework for the CM1102 "Web Applications" module - I manage to achieve 100% (yay!)
//
//It may well be riddled with bugs, security holes, and it cause the apocalypse... I accept NO responsibility for anything that happens because of my code - you've been warned. 
//By Joseph Redfern
//http://redfern.me/
//joseph [at] redfern [dot] me

if(isset($_GET['pagenumber'])){
	if(is_numeric($_GET['pagenumber'])){
		$nextpage = $_GET['pagenumber']+1; //blindly add one to the next page... not ideal...
		if($_GET['pagenumber']==1){ //if we are on the first page, don't go to page 0 (since it doesn't exist!)
			$prevpage = $_GET['pagenumber'];
		}else{
			$prevpage = $_GET['pagenumber']-1;
		}
	}
}else{
	$nextpage = 2;
	$prevpage = 1;
}
?>

<div class="pagination">
	<div class="paginationprev"><a href="?pagenumber=<?php echo $prevpage; ?>">Previous Page</a></div>
	<div class="paginationnext"><a href="?pagenumber=<?php echo $nextpage; ?>">Next Page</a></div>
</div>