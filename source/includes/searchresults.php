<h2>Displaying Search Results for "<?php echo htmlentities($_GET['term']); ?>" </h2>
<?php
if(displaySearchResults($_GET['term'])==0){
	print "There where no results found for your query";
}
?>