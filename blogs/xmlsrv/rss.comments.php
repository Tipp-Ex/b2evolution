<?php
	/*
	 * This template generates an RSS 0.92 feed for the requested blog's latest comments
	 * (http://backend.userland.com/rss092)
	 */
	$skin = '';								// We don't want this do be displayed in a skin !
	$disp = 'comments';				// What we want is the latest comments
	$show_statuses = array(); // Restrict to published comments
	require dirname(__FILE__).'/../b2evocore/_blog_main.php' ;
	$CommentList = & new CommentList( $blog, "'comment'", $show_statuses );
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\"?".">";
?>
<!-- generator="b2evolution/<?php echo $b2_version ?>" -->
<rss version="0.92">
	<channel>
		<title><?php $Blog->disp( 'name', 'xml' ); last_comments_title( ' : ', 'xml' ) ?></title>
		<link><?php $Blog->disp( 'lastcommentsurl', 'xml' ) ?></link>
		<description></description>
		<language><?php $Blog->disp( 'locale', 'xml' ) ?></language>
		<docs>http://backend.userland.com/rss092</docs>
		<?php while( $Comment = $CommentList->get_next() )
		{ // Loop through comments: ?>
		<item>
			<title><?php echo format_to_output( T_('In response to:'), 'xml' ) ?> <?php $Comment->Item->title( '', '', false, 'xml' ) ?></title>
			<description><?php $Comment->content( 'entityencoded' ) ?></description>
			<link><?php $Comment->permalink() ?></link>
		</item>
		<?php } // End of comment loop. ?>
	</channel>
</rss>
<?php $Hits->log();	// log the hit on this page ?>