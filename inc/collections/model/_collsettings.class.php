<?php
/**
 * This file implements the CollectionSettings class which handles
 * coll_ID/name/value triplets for collections/blogs.
 *
 * This file is part of the evoCore framework - {@link http://evocore.net/}
 * See also {@link https://github.com/b2evolution/b2evolution}.
 *
 * @license GNU GPL v2 - {@link http://b2evolution.net/about/gnu-gpl-license}
 *
 * @copyright (c)2003-2018 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evocore
 *
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

load_class( 'settings/model/_abstractsettings.class.php', 'AbstractSettings' );

/**
 * Class to handle the settings for collections/blogs
 *
 * @package evocore
 */
class CollectionSettings extends AbstractSettings
{
	/**
	 * The default settings to use, when a setting is not defined in the database.
	 *
	 * @access protected
	 */
	var $_defaults = array(
		// Home page settings:
			'front_disp'             => 'posts',
			'default_noindex'         => 0, // META NOINDEX on Default/Front collection page
			'canonical_homepage'      => 1, // Redirect front page to its canonical Url?
			'self_canonical_homepage' => 1, // Use self-referencing rel="canonical" tag
			'relcanonical_homepage'   => 1, // If no 301, fall back to rel="canoncial" ?

		// Posts list settings:
			'what_to_show'           => 'posts',      // posts, days
			'main_content'           => 'normal',
			'posts_per_page'         => '5',
			'disp_featured_above_list' => 0,				// Don't display a featured post above the list by default
			'canonical_posts'        => 1,				// Redirect disp=posts to its canonical Url?
			'self_canonical_posts'   => 1,				// Use self-referencing rel="canonical" tag
			'relcanonical_posts'     => 1,				// If no 301, fall back to rel="canoncial" ?
			'posts_firstpage_noindex'=> 0,				// META NOINDEX on First page on disp=posts
			'orderby'         => 'datestart',
			'orderdir'        => 'DESC',
			'title_link_type' => 'permalink',
			'permalinks'      => 'single',				// single, archive, subchap

		// Page 2,3,4..; settings:
			'paged_noindex' => '1',							// META NOINDEX on following blog pages
			'paged_nofollowto' => '0',						// NOFOLLOW on links to following blog pages

		// Single post settings:
			'canonical_item_urls' => 1,					// Redirect posts to their canonical Url?
			'self_canonical_item_urls' => 1,		// Use self-referencing rel="canonical" tag
			'allow_crosspost_urls' => 1,					// For cross-posted Items, allow non-canonical URL
			'relcanonical_item_urls' => 1,				// If no 301, fall back to rel="canoncial" ?
			'single_links'   => 'short',
			'single_item_footer_text' => '',
			'slug_limit' => 5,
			'tags_meta_keywords' => 1,
			'tags_open_graph' => 1,
			'tags_twitter_card' => 1,
			// 'post_moderation_statuses' => NULL,			// Possible values are a list of statuses from: 'community', 'protected', 'review', 'draft', but we don't specify a general default because it depends from the blog type ( see @Blog::get_setting() )

		// Item voting settings:
			'voting_positive' => 1, // Allow Positive vote
			'voting_neutral'  => 0, // Allow Neutral vote
			'voting_negative' => 0, // Allow Negative vote

		// Comment settings:
			'new_feedback_status' => 'review',			// Default status for new anonymous comments: 'published', 'community', 'protected', 'private', 'review', 'draft' or 'deprecated'. We don't specify a general default because it depends from the blog type ( see @Blog::get_setting() )
			// 'moderation_statuses' => NULL,			// Possible values are a list of statuses from: 'community', 'protected', 'review', 'draft', but we don't specify a general default because it depends from the blog type ( see @Blog::get_setting() )
			// 'comment_inskin_statuses' => NULL,       // Possible value is a set of statuses wihtout the 'trash' status, but we don't specify a general default because it depends from the blog type ( see @Blog::get_setting() )
			// 'post_inskin_statuses' => NULL,          // Same as in case of comments
			'allow_comments' => 'any',
			'allow_view_comments' => 'any',				// 'any', 'registered', 'member', 'moderator'
			'require_anon_name' => 1,
			'require_anon_email' => 1,
			'allow_anon_url' => 0,
			'allow_attachments' => 'registered',
			'max_attachments' => '',
			'display_rating_summary' => '1', // Display a summary of star ratings above the comments
			'allow_rating_items' => 'never',
			'allow_rating_comment_helpfulness' => '0',
			'comments_orderdir' => 'ASC',
			'threaded_comments' => '0',
			'paged_comments' => '0',
			'comments_per_page' => 1000,
			'comments_avatars' => '1',
			'comments_latest' => '1',
			'comments_detect_email' => 1,
			'comments_register' => 1,
			'comment_quick_moderation' => 'expire',		// Comment quick moderation can be 'never', 'expire' - Links expire on first edit action, and 'always'
			'autocomplete_usernames' => 1,
			'meta_comments_frontoffice' => 1, // Display meta comments in front-office
			'webmentions' => 1, // Allow to accept webmentions from other sites

		// Archive settings:
			'arcdir_noindex' => '1',					// META NOINDEX on Archive directory
			'archive_mode'   => 'monthly',				// monthly, weekly, daily, postbypost
			'archive_links'  => 'extrapath',			// param, extrapath
			'canonical_archive_urls' => 1,				// Redirect archives to their canonical URL?
			'self_canonical_archive_urls' => 1,		// Use self-referencing rel="canonical" tag
			'relcanonical_archive_urls' => 1,			// If no 301, fall back to rel="canoncial" ?
			'archive_content'   => 'excerpt',
			'archive_posts_per_page' => '100',
			'archive_noindex' => '1',					// META NOINDEX on Archive pages
			'archive_nofollowto' => '0',				// NOFOLLOW on links to archive pages
			'archives_sort_order' => 'date',

		// Chapter/Category settings:
			'catdir_noindex' => '1',					// META NOINDEX on Category directory
			'chapter_links'  => 'chapters',				// 'param_num', 'subchap', 'chapters'
			'canonical_cat_urls' => 1,					// Redirect categories to their canonical URL?
			'self_canonical_cat_urls' => 1,		// Use self-referencing rel="canonical" tag
			'relcanonical_cat_urls' => 1,				// If no 301, fall back to rel="canoncial" ?
			'chapter_content'   => 'normal',
			'chapter_posts_per_page' => 100,
			'chapter_noindex'   => '1',					// META NOINDEX on Category pages
			'category_prefix'   => '',
			'categories_meta_description' => 1,
			'category_ordering' => 'alpha',             // Ordering of categories

		// Tag page settings:
			'tag_links'  => 'colon',					// 'param', 'semicolon' -- fp> we want this changed to prefix only for new blogs only
			'canonical_tag_urls' => 1,					// Redirect tag pages to their canonical Url?
			'self_canonical_tag_urls' => 1,			// Use self-referencing rel="canonical" tag
			'relcanonical_tag_urls' => 1,				// If no 301, fall back to rel="canoncial" ?
			'tag_content'       => 'excerpt',
			'tag_posts_per_page' => 100,
			'tag_noindex' => '1',				      	// META NOINDEX on Tag pages
			'tag_prefix' => '',							// fp> fp> we want this changed to prefix only for new blogs only
			'tag_rel_attrib' => 1,						// rel="tag" attribute for tag links (http://microformats.org/wiki/rel-tag) -- valid only in prefix-only mode

		// Other filtered pages:
			'filtered_noindex' => '1',					// META NOINDEX on other filtered pages
			'filtered_content'  => 'excerpt',

		// Other pages:
			'feedback-popup_noindex' => '1',			// META NOINDEX on Feedback popups
			'msgform_noindex' => '1',					// META NOINDEX on Message forms
			'special_noindex' => '1',					// META NOINDEX on other special pages
			'404_response' => '404',
			'help_link' => 'slug',

		// Feed settings: (should probably be duplicated for comment feed, category feeds, etc...)
			'atom_redirect' => '',
			'rss2_redirect' => '',
			'feed_content'   => 'normal',
			'posts_per_feed' => '8',
			'xml_item_footer_text' => '<p><small><a href="$perm_url$">Original post</a> blogged on <a href="http://b2evolution.net/">b2evolution</a>.</small></p>',
			'image_size'	=> 'fit-320x320',

		// Comment feed settings:
			'comment_feed_content' => 'normal',
			'comments_per_feed' => '8',

		// Sitemaps settings:
			'enable_sitemaps' => 1,

		// General settings:
			'ajax_form_enabled' => 1,					// Comment, Contact & Quick registration forms will be fetched by javascript
			'ajax_form_loggedin_enabled' => 0,			// Also use JS forms for logged in users
			'cache_enabled' => 0,
			'cache_enabled_widgets' => 0,
			'in_skin_login' => 0,						// Use in skin login form every time it's possible
			'in_skin_editing' => 0,
			'in_skin_change_proposal' => 1,
			'in_skin_editing_renderers' => 1,
			'in_skin_editing_category' => 1,
			'in_skin_editing_category_order' => 1,
			'default_cat_ID' => NULL,					// Default Cat for new posts
			'ping_plugins' => 'ping_pingomatic,ping_b2evonet,evo_twitter,webmention', // ping plugin codes, separated by comma
			'allow_subscriptions' => 1,         // Allow email subscriptions for new post by default
			'allow_comment_subscriptions' => 1, // Allow email subscriptions for new comment by default
			'allow_item_subscriptions' => 1,    // Allow email subscriptions for a specific post by default
			'allow_anon_subscriptions' => 1,    // Allow email subscriptions for replies to anonymous users comments
			'default_anon_comment_notify' => 0, // Default option to subscribe anonymous users for replies notification
			'anon_notification_email_limit' => 3, // Max # of email notifications an anonymous user may receive per day
			'use_workflow' => 0,						// Don't use workflow by default
			'use_deadline' => 1,						// Use deadline for workflow by default
			'aggregate_coll_IDs' => '',
			'blog_footer_text' => 'This collection &copy;$year$ by $owner$',
			'max_footer_credits' => 3,
			'enable_goto_blog' => 'blog',  // 'no' - No redirect, 'blog' - Go to blog after publishing post, 'post' - Redirect to permanent post url
			'editing_goto_blog' => 'post', // 'no' - No redirect, 'blog' - Go to blog after editing post, 'post' - Redirect to permanent post url
			'default_post_type' => '1', // Default type for new posts, value is ID of post type from table T_items__type
			'post_anonymous' => 0, // Allow to create new posts by anonymous users
			// 'default_post_status' => 'draft',		// Default status for new posts in backoffice ("published", "community", "protected", "private", "review", "draft", "deprecated", "redirected"). We don't specify a general default because it depends from the blog type ( see @Blog::get_setting() )
			'default_post_status_anon' => 'review', // Default status for new posts from anonymous user ("published", "community", "protected", "private", "review", "draft", "deprecated", "redirected").
			'post_categories' => 'main_extra_cat_post', // Post category setting
			'post_navigation' => 'same_blog',           // Default post by post navigation should stay in the same blog, category, author or tag
			'blog_head_includes' => '',
			'blog_body_includes' => '',
			'blog_footer_includes' => '',
			'allow_html_comment' => 1, // Allow HTML in comments
			'track_unread_content' => 0, // Should we track unread content on the specific blog. It can be modified on the Features/Other settings form.
			'allow_access' => 'public', // Allow access to blog; Values: 'public' - Everyone (Public Blog), 'users' - Logged in users, 'members' - Members of the blog
			'http_protocol' => 'allow_both', // SSL; Values: 'always_http' - Always use http, 'always_https' - Always use https, 'allow_both' - Allow both http and https as valid URLs.
			// Assets URLs:
			'rsc_assets_url_type' => 'relative', // Load generic /rsc/ assets from: 'basic', 'relative', 'absolute'
			'rsc_assets_absolute_url' => '', // Absolute URL for setting 'rsc_assets_url_type' with selected option 'absolute'
			'media_assets_url_type' => 'relative', // Load generic /media/ assets from: 'basic', 'relative', 'absolute'
			'media_assets_absolute_url' => '', // Absolute URL for setting 'media_assets_url_type' with selected option 'absolute'
			'skins_assets_url_type' => 'relative', // Load generic /skins/ assets from: 'basic', 'relative', 'absolute'
			'skins_assets_absolute_url' => '', // Absolute URL for setting 'skins_assets_url_type' with selected option 'absolute'
			'plugins_assets_url_type' => 'relative', // Load generic /plugins/ assets from: 'basic', 'relative', 'absolute'
			'plugins_assets_absolute_url' => '', // Absolute URL for setting 'plugins_assets_url_type' with selected option 'absolute'
			'htsrv_assets_url_type' => 'relative', // Link to /htsrv/ through: 'basic', 'relative', 'absolute'
			'htsrv_assets_absolute_url' => '', // Absolute URL for setting 'htsrv_assets_url_type' with selected option 'absolute'
			'locale_source' => 'blog', // Source of the locale for navigation/widget: 'blog', 'user'
			'post_locale_source' => 'post', // Source of the locale for post content: 'post', 'blog'
			'new_item_locale_source' => 'select_coll', // Source of the locale for new items: 'use_coll', 'select_coll', 'select_user'
			// Cookie settings:
			'cookie_domain_type' => 'auto', // Cookie domain type: 'auto', 'custom'
			'cookie_path_type' => 'auto', // Cookie path type: 'auto', 'custom'

		// Contact form settings (disp=msgform):
			'msgform_display_recipient' => 1, // Display a "Message to:" line
			'msgform_user_name' => 'none', // Name input for logged in users
			'msgform_display_avatar' => 1, // Display recipient avatar
			'msgform_avatar_size' => 'crop-top-48x48', // Recipient avatar size
			'msgform_require_name' => 1, // Require name
			'msgform_display_subject' => 1, // Display subject
			'msgform_require_subject' => 1, // Require subject
			'msgform_contact_method' => 1, // Require a preferred contact method
			'msgform_display_message' => 1, // Display message
			'msgform_require_message' => 1, // Require message

		// User directory:
			'userdir_filter_gender' => 1,
			'userdir_filter_level' => 1,
			'userdir_filter_org' => 1,
			'userdir_filter_criteria' => 1,
			'userdir_filter_lastseen' => 1,
			'userdir_picture' => 1,
			'image_size_user_list' => 'crop-top-48x48',
			'userdir_login' => 1,
			'userdir_firstname' => 0,
			'userdir_lastname' => 0,
			'userdir_nickname' => 0,
			'userdir_fullname' => 1,
			'userdir_country' => 0,
			'userdir_country_type' => 'both',
			'userdir_region' => 0,
			'userdir_subregion' => 0,
			'userdir_city' => 1,
			'userdir_phone' => 0,
			'userdir_soclinks' => 0,
			'userdir_lastseen' => 0,

		// Other settings:
			'image_size_messaging' => 'crop-top-32x32', // Used in disp = threads
			'search_per_page'      => 20, // Number of results per page on disp=search
			'search_sort_by'       => 'score', // Sort type of results on disp=search ('score', 'date')
			'search_include_cats'  => 1, // Include categories to results on disp=search
			'search_include_posts' => 1, // Include posts to results on disp=search
			'search_include_cmnts' => 1, // Include comments to results on disp=search
			'search_include_tags'  => 1, // Include tags to results on disp=search
			'search_include_files' => 1, // Include files to results on disp=search
			'search_score_post_title'          => 5, // weight multiplier for keywords found in post title
			'search_score_post_content'        => 1, // weight multiplier for keywords found in post content
			'search_score_post_tags'           => 4, // weight multiplier for keywords found in post tags
			'search_score_post_excerpt'        => 1, // weight multiplier for keywords found in post excerpt
			'search_score_post_titletag'       => 4, // weight multiplier for keywords found in post <title> tag
			'search_score_post_author'         => 5, // weight multiplier for keywords found in post author login
			'search_score_post_date_future'    => 0, // weight multiplier for posts from future
			'search_score_post_date_moremonth' => 0, // weight multiplier for posts older month
			'search_score_post_date_lastmonth' => 1, // weight multiplier for posts from the last month
			'search_score_post_date_twoweeks'  => 2, // weight multiplier for posts from the last two weeks
			'search_score_post_date_lastweek'  => 8, // weight multiplier for posts from the last week
			'search_score_cmnt_post_title'     => 1, // weight multiplier for keywords found in title of the comment's post
			'search_score_cmnt_content'        => 1, // weight multiplier for keywords found in comment content
			'search_score_cmnt_author'         => 5, // weight multiplier for keywords found in comment author name
			'search_score_cmnt_date_future'    => 0, // weight multiplier for comments from future
			'search_score_cmnt_date_moremonth' => 0, // weight multiplier for comments older month
			'search_score_cmnt_date_lastmonth' => 1, // weight multiplier for comments from the last month
			'search_score_cmnt_date_twoweeks'  => 2, // weight multiplier for comments from the last two weeks
			'search_score_cmnt_date_lastweek'  => 8, // weight multiplier for comments from the last week
			'search_score_file_name'           => 3, // weight multiplier for keywords found in file name
			'search_score_file_path'           => 1, // weight multiplier for keywords found in file path
			'search_score_file_title'          => 3, // weight multiplier for keywords found in file long title
			'search_score_file_alt'            => 1, // weight multiplier for keywords found in file alternative text
			'search_score_file_description'    => 1, // weight multiplier for keywords found in file caption/description
			'search_score_cat_name'            => 3, // weight multiplier for keywords found in category name
			'search_score_cat_desc'            => 1, // weight multiplier for keywords found in category description
			'search_score_tag_name'            => 3, // weight multiplier for keywords found in tag name
			'latest_comments_num'  => 20, // Number of the shown comments on disp=comments

		// Time frame settings:
			'timestamp_min' => 'yes',
			'timestamp_max' => 'no',

		// Back-end settings, these can't be modified by the users, it will be modified from code:
			'last_invalidation_timestamp' => 0,

		// Download settings:
			'download_delay' => 5,
			'download_noindex' => 1,
			'download_nofollowto' => 1,
		);

	/**
	 *  Configurable default settings
	 *
	 *  These settings default is defined in general settings
	 *
	 *  Skin settings:
	 *  'normal_skin_ID' => NULL,
	 *  'mobile_skin_ID' => NULL,
	 *  'tablet_skin_ID' => NULL,
	 */


	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct( 'T_coll_settings', array( 'cset_coll_ID', 'cset_name' ), 'cset_value', 1 );
	}


	/**
	 * Loads the settings. Not meant to be called directly, but gets called
	 * when needed.
	 *
	 * @param string First column key
	 * @param string Second column key
	 * @param string NOT USED (provided for compatibility with parent class)
	 * @return boolean
	 */
	function _load( $coll_ID = NULL, $arg = NULL, $arg3 = NULL )
	{
		if( empty( $coll_ID ) || empty( $arg ) )
		{
			return false;
		}

		return parent::_load( $coll_ID, $arg );
	}


	/**
	 * Get a setting from the DB settings table.
	 *
	 * @uses get_default()
	 * @param string First column key
	 * @param string Second column key
	 * @return string|false|NULL value as string on success; NULL if not found; false in case of error
	 */
	function get( $col_key1, $col_key2 )
	{
		return parent::getx( $col_key1, $col_key2 );
	}


	/**
	 * Temporarily sets a setting ({@link dbupdate()} writes it to DB).
	 *
	 * @param string First column key
	 * @param string Second column key
	 * @param mixed Value
	 * @return boolean true, if the value has been set, false if it has not changed.
	 */
	function set( $col_key1, $col_key2, $value )
	{
		if( is_array( $value ) )
		{	// Don't crop a serialized value if value is an array,
			// e-g plugin setting with type "checklist":
			if( strlen( serialize( $value  ) ) > 10000 )
			{	// Stop here to avoid DB error on inserting of long value:
				debug_die( 'Impossible to store long data(>10000 chars) of collection setting "'.$col_key2.'"!' );
			}
		}
		else
		{	// Limit value with max possible length:
			$value = utf8_substr( $value, 0, 10000 );
		}

		return parent::setx( $col_key1, $col_key2, $value );
	}
}

?>