<div class="spcomment"><h4><?php echo htmlentities($comment['author']); ?></h4>
	<span class="spcommentdate"><em> on <?php echo date("j/n/Y", strtotime($comment['timestamp'])); ?></em>
	<?php
	if(showDeleteCommentButton($comment['uid'])){
		echo "<a href=\"index.php?action=singlePost&pid=3&deleteComment=".$comment['cid']."\">[Delete]</a>";
	}
	?>
	</span>
	<p><?php echo parseLineBreaks($comment['body']); ?></p>
</div>