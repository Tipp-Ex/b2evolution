<?php
/**
 * This file implements the UI view for the user subscriptions.
 *
 * This file is part of the evoCore framework - {@link http://evocore.net/}
 * See also {@link https://github.com/b2evolution/b2evolution}.
 *
 * @license GNU GPL v2 - {@link http://b2evolution.net/about/gnu-gpl-license}
 *
 * @copyright (c)2003-2018 by Francois Planque - {@link http://fplanque.com/}
 * Parts of this file are copyright (c)2004-2006 by Daniel HAHLER - {@link http://thequod.de/contact}.
 *
 * @package admin
 */

if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * @var instance of GeneralSettings class
 */
global $Settings;
/**
 * @var instance of UserSettings class
 */
global $UserSettings;
/**
 * @var instance of User class
 */
global $edited_User;
/**
 * @var current action
 */
global $action;
/**
 * @var user permission, if user is only allowed to edit his profile
 */
global $user_profile_only;
/**
 * @var the action destination of the form (NULL for pagenow)
 */
global $form_action;
/**
 * @var Blog
 */
global $Collection, $Blog;
/**
 * @var DB
 */
global $DB;

// Default params:
$default_params = array(
		'skin_form_params'     => array(),
		'form_class_user_subs' => 'bComment',
	);

if( isset( $params ) )
{	// Merge with default params
	$params = array_merge( $default_params, $params );
}
else
{	// Use a default params
	$params = $default_params;
}

// ------------------- PREV/NEXT USER LINKS -------------------
user_prevnext_links( array(
		'user_tab' => 'subs'
	) );
// ------------- END OF PREV/NEXT USER LINKS -------------------

$Form = new Form( $form_action, 'user_checkchanges' );

$Form->switch_template_parts( $params['skin_form_params'] );

if( !$user_profile_only )
{
	echo_user_actions( $Form, $edited_User, $action );
}

$is_admin_page = is_admin_page();
if( $is_admin_page )
{
	$form_text_title = '<span class="nowrap">'.T_( 'Edit notifications' ).'</span>'.get_manual_link( 'user-notifications-tab' ); // used for js confirmation message on leave the changed form
	$form_title = get_usertab_header( $edited_User, 'subs', $form_text_title );
	$form_class = 'fform';
	$Form->title_fmt = '$title$';
	$checklist_params = array();
}
else
{
	$form_title = '';
	$form_class = $params['form_class_user_subs'];
	$checklist_params = array( 'wide' => true );
}

$Form->begin_form( $form_class, $form_title, array( 'title' => ( isset( $form_text_title ) ? $form_text_title : $form_title ) ) );

	$Form->add_crumb( 'user' );
	$Form->hidden_ctrl();
	$Form->hidden( 'user_tab', 'subs' );
	$Form->hidden( 'subscriptions_form', '1' );

	$Form->hidden( 'user_ID', $edited_User->ID );
	$Form->hidden( 'edited_user_login', $edited_User->login );
	if( isset( $Blog ) )
	{
		$Form->hidden( 'blog', $Blog->ID );
	}

if( $action != 'view' )
{	// We can edit the values:
	$disabled = false;
}
else
{	// display only
	$disabled = true;
}

$has_messaging_perm = $edited_User->check_perm( 'perm_messaging', 'reply', false );

$Form->begin_fieldset( T_('Receiving emails').( is_admin_page() ? get_manual_link( 'user-notifications-email-panel' ) : '' ) );

	$email_fieldnote = '<a href="mailto:'.$edited_User->get('email').'" class="'.button_class().'">'.get_icon( 'email', 'imgtag', array('title'=>T_('Send an email')) ).'</a>';

	if( $action != 'view' )
	{ // We can edit the values:
		$Form->email_input( 'edited_user_email', $edited_User->email, 30, T_('Email address'), array( 'maxlength' => 255, 'required' => true, 'note' => $email_fieldnote ) );
		$Form->radio_input( 'edited_user_email_format', $UserSettings->get( 'email_format',  $edited_User->ID ), array(
					array(
						'value'   => 'auto',
						'label'   => T_('Automatic (HTML + Plain text)') ),
					array(
						'value'   => 'html',
						'label'   => T_('HTML') ),
					array(
						'value'   => 'text',
						'label'   => T_('Plain text') ),
				), T_('Email format'), array( 'lines' => true ) );
	}
	else
	{ // display only
		$Form->info_field( T_('Email'), $edited_User->get('email'), array( 'note' => $email_fieldnote, 'class' => 'info_full_height' ) );
		switch( $UserSettings->get( 'email_format',  $edited_User->ID ) )
		{
			case 'auto':
				$email_format_value_title = T_('Automatic (HTML + Plain text)');
				break;
			case 'html':
				$email_format_value_title = T_('HTML');
				break;
			case 'text':
				$email_format_value_title = T_('Plain text');
				break;
			default:
				$email_format_value_title = $UserSettings->get( 'email_format',  $edited_User->ID );
		}
		$Form->info_field( T_('Email format'), $email_format_value_title );
	}

$Form->end_fieldset();


$Form->begin_fieldset( T_('Receiving private messages').( is_admin_page() ? get_manual_link( 'user-communications-panel' ) : '' ) );

	$has_messaging_perm = $edited_User->check_perm( 'perm_messaging', 'reply', false );
	$messaging_options = array(	array( 'enable_PM', 1, T_( 'private messages on this site.' ), ( ( $UserSettings->get( 'enable_PM', $edited_User->ID ) ) && ( $has_messaging_perm ) ), !$has_messaging_perm || $disabled ) );
	$emails_msgform = $Settings->get( 'emails_msgform' );

	$email_messaging_note = '';
	if( ! $UserSettings->get( 'enable_email', $edited_User->ID ) &&
			( $emails_msgform == 'userset' || $emails_msgform == 'adminset' ) )
	{ // Check if user has own blog and display a red note
		$user_own_blogs_count = $edited_User->get_own_blogs_count();
		if( $user_own_blogs_count > 0 )
		{
			$email_messaging_note = '<span class="red">'.sprintf( T_('You are the owner of %d collections. Visitors of these collections will <b>always</b> be able to contact you through a message form if needed (your email address will NOT be revealed).'),
				$user_own_blogs_count ).'</span>';
		}
	}

	$msgform_checklist_params = $checklist_params;
	if( $emails_msgform == 'userset' )
	{ // user can set
		$messaging_options[] = array( 'enable_email', 1, T_( 'emails through a message form that will NOT reveal my email address.' ), $UserSettings->get( 'enable_email', $edited_User->ID ), $disabled, $email_messaging_note );
	}
	elseif( ( $emails_msgform == 'adminset' ) )
	{ // only administrator users can set and current User is in 'Administrators' group
		$is_disabled_email_method = ( $disabled || ! $current_User->check_perm( 'users', 'edit' ) );
		$messaging_options[] = array( 'enable_email', 1, T_( 'emails through a message form that will NOT reveal my email address.' ).get_admin_badge( 'user' ), $UserSettings->get( 'enable_email', $edited_User->ID ), $is_disabled_email_method, $email_messaging_note );
	}
	elseif( ! empty( $email_messaging_note ) )
	{	// Display red message to inform user when he don't have a permission to edit the setting:
		$msgform_checklist_params['note'] = $email_messaging_note;
	}
	$Form->checklist( $messaging_options, 'edited_user_msgform', T_('Other users can send me'), false, false, $msgform_checklist_params );

$Form->end_fieldset();


$Form->begin_fieldset( T_('List subscriptions').( is_admin_page() ? get_manual_link( 'user-lists-panel' ) : '' ) );

	$allowed_newsletters = $edited_User->get_allowed_newsletters();
	$user_newsletter_subscriptions = $edited_User->get_newsletter_subscriptions();
	$NewsletterCache = & get_NewsletterCache();
	$NewsletterCache->load_all();
	$newsletter_options = array();
	foreach( $NewsletterCache->cache as $Newsletter )
	{
		if( isset( $allowed_newsletters[ $Newsletter->ID ] ) ||
		    in_array( $Newsletter->ID, $user_newsletter_subscriptions ) )
		{	// Display only allowed newsletter and what user is subscribed to:
			$newsletter_options[] = array( 'edited_user_newsletters[]', $Newsletter->ID, '<b>'.$Newsletter->get( 'name' ).':</b> '.$Newsletter->get( 'label' ), in_array( $Newsletter->ID, $user_newsletter_subscriptions ), $disabled );
		}
	}
	if( count( $newsletter_options ) )
	{
		$Form->checklist( $newsletter_options, 'edited_user_newsletter', T_( 'Lists' ), false, false, $checklist_params );
	}

	// Limit newsletters:
	if( $is_admin_page )
	{ // Back office view
		$Form->text_input( 'edited_user_newsletter_limit', $UserSettings->get( 'newsletter_limit',  $edited_User->ID ), 3, T_( 'Never send me more than' ), '', array( 'maxlength' => 3, 'required' => true, 'input_suffix' => ' <b>'.T_('list emails per day, all lists combined.').'</b>' ) );
	}
	else
	{ // Front office view
		$Form->text_input( 'edited_user_newsletter_limit', $UserSettings->get( 'newsletter_limit',  $edited_User->ID ), 3, T_( 'Never send me more than %s list emails per day, all lists combined.' ), '', array( 'maxlength' => 3, 'required' => true, 'inline' => true ) );
	}

$Form->end_fieldset();


$notifications_mode = $Settings->get( 'outbound_notifications_mode' );

if( $notifications_mode != 'off' )
{
	$Form->begin_fieldset( T_('Collection subscriptions').( is_admin_page() ? get_manual_link( 'user-coll-subscriptions-panel' ) : '' ), array( 'id' => 'subs' ) );

			// Get those blogs for which we have already subscriptions (for this user)
			$sql = 'SELECT blog_ID, blog_shortname,
								MAX( IF( sub_items IS NULL, IF( opt.cset_name = "opt_out_subscription", 1, 0 ), sub_items ) ) AS sub_items,
								MAX( IF( sub_items_mod IS NULL, IF( opt.cset_name = "opt_out_items_mod_subscription", 1, 0 ), sub_items_mod ) ) AS sub_items_mod,
								MAX( IF( sub_comments IS NULL, IF( opt.cset_name = "opt_out_comment_subscription", 1, 0 ), sub_comments ) ) AS sub_comments
							FROM T_blogs
							LEFT JOIN T_coll_settings AS sub ON ( sub.cset_coll_ID = blog_ID AND sub.cset_name = "allow_subscriptions" )
							LEFT JOIN T_coll_settings AS subc ON ( subc.cset_coll_ID = blog_ID AND subc.cset_name = "allow_comment_subscriptions" )
							LEFT JOIN T_coll_settings AS opt ON ( opt.cset_coll_ID = blog_ID AND opt.cset_name IN ( "opt_out_subscription", "opt_out_comment_subscription", "opt_out_items_mod_subscription" ) )
							LEFT JOIN T_subscriptions ON ( sub_coll_ID = blog_ID AND sub_user_ID = '.$edited_User->ID.' )
							LEFT JOIN T_coll_group_perms ON (bloggroup_blog_ID = blog_ID AND bloggroup_ismember = 1 AND opt.cset_value = "1" )
							LEFT JOIN T_coll_user_perms ON (bloguser_blog_ID = blog_ID AND bloguser_ismember = 1 AND opt.cset_value = "1" )
							LEFT JOIN T_users ON (user_grp_ID = bloggroup_group_ID AND user_ID = '.$edited_User->ID.' AND opt.cset_value = "1" )
							LEFT JOIN T_users__secondary_user_groups ON (sug_grp_ID = bloggroup_group_ID AND sug_user_ID = '.$edited_User->ID.' AND opt.cset_value = "1" )
							WHERE ( ( sub.cset_value = 1 OR sub.cset_value IS NULL ) OR ( subc.cset_value = 1 OR subc.cset_value IS NULL ) )
								AND ( sug_user_ID = '.$edited_User->ID.' OR bloguser_user_ID = '.$edited_User->ID.' OR user_ID = '.$edited_User->ID.' OR sub_user_ID = '.$edited_User->ID.' )
								AND ( sub_items <> 0 OR sub_items_mod <> 0 OR sub_comments <> 0 OR sub_coll_ID IS NULL )
								AND ( CASE opt.cset_value WHEN 1 THEN blog_advanced_perms = 1 ELSE TRUE END )
							GROUP BY blog_ID, blog_shortname';
			$blog_subs = $DB->get_results( $sql );

			$BlogCache = & get_BlogCache();
			$subs_blog_IDs = array();
			foreach( $blog_subs AS $blog_sub )
			{
				if( ! ( $sub_Blog = & $BlogCache->get_by_ID( $blog_sub->blog_ID, false, false ) ) )
				{	// Skip wrong collection:
					continue;
				}
				if( ! ( $sub_Blog->get_setting( 'allow_subscriptions' ) && $blog_sub->sub_items ) &&
						! ( $sub_Blog->get_setting( 'allow_comment_subscriptions' ) && $blog_sub->sub_comments ) &&
						! ( $sub_Blog->get_setting( 'allow_item_mod_subscriptions' ) && $blog_sub->sub_items_mod ) )
				{	// Skip because the collection doesn't allow any subscription:
					continue;
				}

				$subs_blog_IDs[] = $sub_Blog->ID;

				// Skip because the user no longer has access to the collection - but only after adding the collection ID to the $subs_blog_IDs array.
				// The subscription will be removed from the DB when the user saves the form
				if( ! $sub_Blog->has_access( $edited_User ) )
				{
					continue;
				}

				$subscriptions = array();
				if( $sub_Blog->get_setting( 'allow_subscriptions' ) )
				{	// If subscription is allowed for new posts:
					$subscriptions[] = array( 'sub_items_'.$sub_Blog->ID, '1', T_('Notify me of any new post in this collection'), $blog_sub->sub_items, $disabled );
				}
				if( $sub_Blog->get_setting( 'allow_comment_subscriptions' ) )
				{	// If subscription is allowed for new comments:
					$subscriptions[] = array( 'sub_comments_'.$sub_Blog->ID, '1', T_('Notify me of any new comment in this collection'), $blog_sub->sub_comments, $disabled );
				}
				if( $sub_Blog->get_setting( 'allow_item_mod_subscriptions' ) )
				{	// If subscription is allowed for modified posts:
					$subscriptions[] = array( 'sub_items_mod_'.$sub_Blog->ID, '1', T_('Notify me when:').' '.T_('a post is modified and I have permissions to moderate it.'), $blog_sub->sub_items_mod, $disabled );
				}
				$Form->checklist( $subscriptions, 'subscriptions', $sub_Blog->dget( 'shortname', 'htmlbody' ) );
			}

			$Form->hidden( 'subs_blog_IDs', implode( ',', $subs_blog_IDs ) );

	if( $is_admin_page && $Settings->get( 'subscribe_new_blogs' ) == 'page' )
	{	// To subscribe from blog page only
		$Form->info_field( '', T_('In order to subscribe to a new blog, go to the relevant blog and subscribe from there.'), array( 'class' => 'info_full' ) );
	}
	else
	{	// To subscribe from current list of blogs

		// Load collections which have the enabled settings to subscribe on new posts or comments:
		$BlogCache = new BlogCache();
		$BlogCache->load_subscription_colls( $edited_User, $subs_blog_IDs );

		if( empty( $BlogCache->cache ) )
		{	// No blogs to subscribe
			if( empty( $subs_blog_IDs ) )
			{	// Display this info if really no blogs to subscribe
				$Form->info_field( '', T_('Sorry, no blogs available to subscribe.'), array( 'class' => 'info_full' ) );
			}
		}
		else
		{ // Display a form to subscribe on new blog
			$subscribe_blog_ID = param( 'subscribe_blog' , '', isset( $Blog ) ? $Blog->ID : 0 );
			if( $action != 'view' )
			{	// If current user can edit this user:
				if( empty( $blog_subs ) )
				{
					$Form->begin_line( T_('Subscribe to') );
				}
				else
				{
					$Form->begin_line( T_('Also available') );
				}

					$Form->select_input_object( 'subscribe_blog', $subscribe_blog_ID, $BlogCache, '', array( 'object_callback' => 'get_option_list_parent', 'loop_object_method' => 'get_shortname' ) );
					$Form->button( array(
						'name'  => 'actionArray[subscribe]',
						'value' => T_('Subscribe to this collection'),
						'style' => 'margin-left:10px;'
					) );
				$Form->end_line();
			}

			// Get collection to set proper active checkboxes on page loading:
			if( isset( $BlogCache->cache[ $subscribe_blog_ID ] ) )
			{	// Get selected collection:
				$selected_subscribe_Blog = $BlogCache->cache[ $subscribe_blog_ID ];
			}
			else
			{	// Get first collection from list:
				foreach( $BlogCache->cache as $selected_subscribe_Blog )
				{
					break;
				}
			}
		}
	}
	$Form->end_fieldset();


	$Form->begin_fieldset( T_('Individual post subscriptions').( is_admin_page() ? get_manual_link( 'user-post-subscriptions-panel' ) : '' ) );

		$sql = 'SELECT DISTINCT post_ID, blog_ID, blog_shortname
				FROM T_items__item
				INNER JOIN T_categories ON cat_ID = post_main_cat_ID
				INNER JOIN T_blogs ON blog_ID = cat_blog_ID
				LEFT JOIN T_coll_settings AS sub ON ( sub.cset_coll_ID = blog_ID AND sub.cset_name = "allow_item_subscriptions" )
				LEFT JOIN T_coll_settings AS opt ON ( opt.cset_coll_ID = blog_ID AND opt.cset_name = "opt_out_item_subscription" )
				LEFT JOIN T_items__subscriptions ON ( isub_item_ID = post_ID AND isub_user_ID = '.$edited_User->ID.' )
				LEFT JOIN T_coll_group_perms ON (bloggroup_blog_ID = blog_ID AND bloggroup_ismember = 1 AND opt.cset_value = "1" )
				LEFT JOIN T_coll_user_perms ON (bloguser_blog_ID = blog_ID AND bloguser_ismember = 1 AND opt.cset_value = "1" )
				LEFT JOIN T_users ON (user_grp_ID = bloggroup_group_ID AND user_ID = '.$edited_User->ID.' AND opt.cset_value = "1" )
				LEFT JOIN T_users__secondary_user_groups ON (sug_grp_ID = bloggroup_group_ID AND sug_user_ID = '.$edited_User->ID.' AND opt.cset_value = "1" )
				WHERE ( sug_user_ID = '.$edited_User->ID.' OR bloguser_user_ID = '.$edited_User->ID.' OR user_ID = '.$edited_User->ID.' OR isub_user_ID = '.$edited_User->ID.' )
					AND ( sub.cset_value = "1" OR sub.cset_coll_ID IS NULL )
					AND ( isub_comments <> 0 OR isub_item_ID IS NULL )';
		$individual_posts_subs = $DB->get_results( $sql );
		$subs_item_IDs = array();
		if( empty( $individual_posts_subs ) )
		{
			$Form->info_field( '', T_( 'You are not subscribed to any updates on specific posts yet.' ), array( 'class' => 'info_full' ) );
		}
		else
		{
			global $admin_url;
			$ItemCache = & get_ItemCache();

			$Form->info_field( '', T_( 'You are subscribed to be notified of all new comments on the following posts' ).':', array( 'class' => 'info_full' ) );
			$blog_name = NULL;
			foreach( $individual_posts_subs as $row )
			{
				if( ! ( $Item = $ItemCache->get_by_ID( $row->post_ID, false, false ) ) )
				{ // Item doesn't exist anymore
					continue;
				}
				$subs_item_IDs[] = $row->post_ID;
				if( $blog_name != $row->blog_shortname )
				{
					if( !empty( $blog_name ) )
					{
						$Form->checklist( $post_subs, 'item_subscriptions', $blog_name );
					}
					$blog_name = $row->blog_shortname;
					$post_subs = array();
				}
				if( is_admin_page() && $current_User->check_perm( 'item_post!CURSTATUS', 'view', false, $Item ) )
				{ // Link title to back-office if user has a permission
					$item_title = '<a href="'.$admin_url.'?ctrl=items&amp;blog='.$row->blog_ID.'&amp;p='.$Item->ID.'">'.format_to_output( $Item->get( 'title' ) ).'</a>';
				}
				else
				{ // Link title to front-office
					$item_title = $Item->get_permanent_link( '#title#' );
				}
				$post_subs[] = array( 'item_sub_'.$row->post_ID, 1, $item_title, 1, $disabled );
			}
			// display individual post subscriptions from the last Blog
			$Form->checklist( $post_subs, 'item_subscriptions', $blog_name );
		}
		$Form->hidden( 'subs_item_IDs', implode( ',', $subs_item_IDs ) );
		$Form->info_field( '', T_( 'To subscribe to notifications on a specifc post, go to that post and click "Notify me when someone comments" at the end of the comment list.' ), array( 'class' => 'info_full' ) );

	$Form->end_fieldset();
}

$Form->begin_fieldset( T_('Receiving notifications').( is_admin_page() ? get_manual_link( 'user-notifications-panel' ) : '' ) );

	// User notification options
	$notify_options = array();
	if( $has_messaging_perm )
	{ // show messaging notification settings only if messaging is available for edited user
		$notify_options[ T_('Messaging') ][] = array( 'edited_user_notify_messages', 1, T_('I receive a private message.'),  $UserSettings->get( 'notify_messages', $edited_User->ID ), $disabled );
		$unread_message_reminder_delay = $Settings->get( 'unread_message_reminder_delay' );
		$notify_options[ T_('Messaging') ][] = array( 'edited_user_notify_unread_messages', 1, sprintf( T_('I have unread private messages for more than %s.'), seconds_to_period( $Settings->get( 'unread_message_reminder_threshold' ) ) ),  $UserSettings->get( 'notify_unread_messages', $edited_User->ID ), $disabled, sprintf( T_('This notification is sent only once every %s days.'), array_shift( $unread_message_reminder_delay ) ) );
	}
	$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_comment_mentioned', 1, T_( 'I have been mentioned on a comment.' ), $UserSettings->get( 'notify_comment_mentioned', $edited_User->ID ) );
	if( $edited_User->check_role( 'post_owner' ) )
	{ // user has at least one post or user has right to create new post
		$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_publ_comments', 1, T_('a comment is published on one of <strong>my</strong> posts.'), $UserSettings->get( 'notify_published_comments', $edited_User->ID ), $disabled );
	}
	$is_comment_moderator = $edited_User->check_role( 'comment_moderator' );
	if( $is_comment_moderator || $edited_User->check_role( 'comment_editor' ) )
	{	// edited user has permission to edit other than his own comments at least in one status in one collection:
		$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_cmt_moderation', 1, T_('a comment is posted and I have permissions to moderate it.'), $UserSettings->get( 'notify_comment_moderation', $edited_User->ID ), $disabled );
		$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_edit_cmt_moderation', 1, T_('a comment is modified and I have permissions to moderate it.'), $UserSettings->get( 'notify_edit_cmt_moderation', $edited_User->ID ), $disabled );
		$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_spam_cmt_moderation', 1, T_('a comment is reported as spam and I have permissions to moderate it.'), $UserSettings->get( 'notify_spam_cmt_moderation', $edited_User->ID ), $disabled );
	}
	if( $is_comment_moderator )
	{ // edited user is comment moderator at least in one blog
		$notify_options[ T_('Comments') ][] = array( 'edited_user_send_cmt_moderation_reminder', 1, sprintf( T_('comments are awaiting moderation for more than %s.'), seconds_to_period( $Settings->get( 'comment_moderation_reminder_threshold' ) ) ), $UserSettings->get( 'send_cmt_moderation_reminder', $edited_User->ID ), $disabled );
	}
	if( $edited_User->check_perm( 'admin', 'restricted', false ) )
	{ // edited user has a permission to back-office
		$notify_options[ T_('Comments') ][] = array( 'edited_user_notify_meta_comments', 1, T_('a meta comment is posted.'), $UserSettings->get( 'notify_meta_comments', $edited_User->ID ), $disabled );
	}
	$notify_options[ T_('Posts') ][] = array( 'edited_user_notify_post_mentioned', 1, T_( 'I have been mentioned on a post.' ), $UserSettings->get( 'notify_post_mentioned', $edited_User->ID ) );
	if( $edited_User->check_role( 'post_moderator' ) )
	{ // edited user is post moderator at least in one blog
		$notify_options[ T_('Posts') ][] = array( 'edited_user_notify_post_moderation', 1, T_('a post is created and I have permissions to moderate it.'), $UserSettings->get( 'notify_post_moderation', $edited_User->ID ), $disabled );
		$notify_options[ T_('Posts') ][] = array( 'edited_user_notify_edit_pst_moderation', 1, T_('a post is modified and I have permissions to moderate it.'), $UserSettings->get( 'notify_edit_pst_moderation', $edited_User->ID ), $disabled );
		$notify_options[ T_('Posts') ][] = array( 'edited_user_notify_post_proposed', 1, T_('someone proposed a change on a post and I have permissions to moderate it.'), $UserSettings->get( 'notify_post_proposed', $edited_User->ID ), $disabled );
		$notify_options[ T_('Posts') ][] = array( 'edited_user_send_pst_moderation_reminder', 1, sprintf( T_('posts are awaiting moderation for more than %s.'), seconds_to_period( $Settings->get( 'post_moderation_reminder_threshold' ) ) ), $UserSettings->get( 'send_pst_moderation_reminder', $edited_User->ID ), $disabled );
		$notify_options[ T_('Posts') ][] = array( 'edited_user_send_pst_stale_alert', 1, T_('there are stale posts and I have permission to moderate them.'), $UserSettings->get( 'send_pst_stale_alert', $edited_User->ID ), $disabled );
	}
	if( $edited_User->check_role( 'member' ) )
	{ // user is member of at least one collection
		$notify_options[ T_('Posts') ][] = array( 'edited_user_notify_post_assignment', 1, T_('a post was assigned to me.'), $UserSettings->get( 'notify_post_assignment', $edited_User->ID ), $disabled );
	}
	if( $current_User->check_perm( 'users', 'edit' ) )
	{ // current User is an administrator
		$notify_options[ T_('My account') ][] = array( 'edited_user_send_activation_reminder', 1, sprintf( T_('my account was deactivated or is not activated for more than %s.').get_admin_badge( 'user' ), seconds_to_period( $Settings->get( 'activate_account_reminder_threshold' ) ) ), $UserSettings->get( 'send_activation_reminder', $edited_User->ID ), $disabled );
	}
	if( $Settings->get( 'inactive_account_reminder_threshold' ) > 0 )
	{	// If setting "Trigger after" of cron job "Send reminders about inactive accounts" is selected at least to 1 second:
		$notify_options[ T_('My account') ][] = array( 'edited_user_send_inactive_reminder', 1, sprintf( T_('my account has been inactive for more than %s.'), seconds_to_period( $Settings->get( 'inactive_account_reminder_threshold' ) ) ), $UserSettings->get( 'send_inactive_reminder', $edited_User->ID ), $disabled );
	}
	if( $edited_User->check_perm( 'users', 'edit' ) )
	{ // edited user has permission to edit all users, save notification preferences
		$notify_options[ T_('System users') ][] = array( 'edited_user_notify_new_user_registration', 1, T_( 'a new user has registered.' ), $UserSettings->get( 'notify_new_user_registration', $edited_User->ID ), $disabled );
		$notify_options[ T_('System users') ][] = array( 'edited_user_notify_activated_account', 1, T_( 'an account was activated.' ), $UserSettings->get( 'notify_activated_account', $edited_User->ID ), $disabled );
		$notify_options[ T_('System users') ][] = array( 'edited_user_notify_closed_account', 1, T_( 'an account was closed.' ), $UserSettings->get( 'notify_closed_account', $edited_User->ID ), $disabled );
		$notify_options[ T_('System users') ][] = array( 'edited_user_notify_reported_account', 1, T_( 'an account was reported.' ), $UserSettings->get( 'notify_reported_account', $edited_User->ID ), $disabled );
		$notify_options[ T_('System users') ][] = array( 'edited_user_notify_changed_account', 1, T_( 'an account was changed.' ), $UserSettings->get( 'notify_changed_account', $edited_User->ID ), $disabled );
	}
	if( $edited_User->check_perm( 'options', 'edit' ) )
	{ // edited user has permission to edit options, save notification preferences
		$notify_options[ T_('System maintenance') ][] = array( 'edited_user_notify_cronjob_error', 1, T_( 'a scheduled task ends with an error or timeout.' ), $UserSettings->get( 'notify_cronjob_error',  $edited_User->ID ), $disabled );
	}

	$notify_options[ T_('System maintenance') ][] = array( 'edited_user_notify_list_new_subscriber', 1, T_('one of my Lists gets a new subscriber.'), $UserSettings->get( 'notify_list_new_subscriber', $edited_User->ID ), $disabled );
	$notify_options[ T_('System maintenance') ][] = array( 'edited_user_notify_list_lost_subscriber', 1, T_('one of my Lists loses a subscriber.'), $UserSettings->get( 'notify_list_lost_subscriber', $edited_User->ID ), $disabled );
	if( $current_User->check_perm( 'users', 'edit' ) && $edited_User->check_perm( 'options', 'view' ) )
	{	// current User is an administrator and the edited user has a permission to automations:
		$notify_options[ T_('System maintenance') ][] = array( 'edited_user_notify_automation_owner', 1, T_('one of my automations wants to notify me.'), $UserSettings->get( 'notify_automation_owner', $edited_User->ID ), $disabled );
	}
	if( !empty( $notify_options ) )
	{
		$Form->checklist( array(), 'edited_user_notification', T_('Notify me by email when the following events occur'), false, false, $checklist_params );
		foreach( $notify_options as $notify_label => $notify_checkboxes )
		{
			$Form->checklist( $notify_checkboxes, 'edited_user_notification', $notify_label, false, false, $checklist_params );
		}
	}

	// Limit notifications:
	if( $is_admin_page )
	{ // Back office view
		$Form->text_input( 'edited_user_notification_email_limit', $UserSettings->get( 'notification_email_limit',  $edited_User->ID ), 3, T_( 'Limit notifications to' ), '', array( 'maxlength' => 3, 'required' => true, 'input_suffix' => ' <b>'.T_('emails per day').'</b>' ) );
	}
	else
	{ // Front office view
		$Form->text_input( 'edited_user_notification_email_limit', $UserSettings->get( 'notification_email_limit',  $edited_User->ID ), 3, T_( 'Limit notifications to %s emails per day' ), '', array( 'maxlength' => 3, 'required' => true, 'inline' => true ) );
	}

$Form->end_fieldset();


/***************  Buttons  **************/

if( $action != 'view' )
{	// Edit buttons
	$Form->buttons( array( array( '', 'actionArray[update]', T_('Save Changes!'), 'SaveButton' ) ) );
}

$Form->end_form();
