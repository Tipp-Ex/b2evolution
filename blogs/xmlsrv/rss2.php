<?php
  /*
   * This template generates an RSS 2.0 feed for the requested blog
   * (http://backend.userland.com/rss)
   */
  $default_to_blog = 1; // This is the default. Should be overidden in url e-g: rss.php?blog=2
  $skin = '';                         // We don't want this do be displayed in a skin !
	$show_statuses = "'published'";     // Restrict to published posts
	$timestamp_min = '';								// Show past
	$timestamp_max = 'now';							// Hide future
  include dirname(__FILE__)."/../b2evocore/_blog_main.php";
  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\"?".">";
?>
<!-- generator="b2evolution/<?php echo $b2_version ?>" -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?php bloginfo( 'name', 'xml' ) ?></title>
    <link><?php bloginfo( 'link', 'xml' ) ?></link>
    <description><?php bloginfo( 'description', 'xml' ) ?></description>
    <language><?php bloginfo( 'lang', 'xml' ) ?></language>
    <docs>http://backend.userland.com/rss</docs>
    <admin:generatorAgent rdf:resource="http://b2evolution.net/?v=<?php echo $b2_version ?>"/>
    <ttl>60</ttl>
    <?php while( $MainList->get_item() ) {  ?>
    <item rdf:about="<?php permalink_single() ?>">
      <title><?php the_title( '', '', false, 'xml' ) ?></title>
      <link><?php permalink_single() ?></link>
      <pubDate><?php the_time('r',1,1); ?></pubDate>
      <author><?php the_author( 'xml' ) /* Should actually be an email adress, but spam... you know... */ ?></author>
      <?php the_categories( false, '<category domain="main">', '</category>', '<category domain="alt">', '</category>', '<category domain="external">', '</category>', "\n", 'xml', 'raw' ) ?>
      <guid isPermaLink="false"><?php echo $id; ?>@<?php echo $baseurl ?></guid>
      <description><?php
        the_link( '', ' ', 'xml' );
        the_content(_('[...] Read more!'), 0, '', '', '', '', 'xml',$rss_excerpt_length );
      ?></description>
      <content:encoded><![CDATA[<?php
        the_link( '<p>', '</p>' );
        the_content()
      ?>]]></content:encoded>
      <comments><?php comments_link( '', 1, 1 ) ?></comments>
    </item>
    <?php } ?>
  </channel>
</rss>
<?php log_hit(); // log the hit on this page ?>