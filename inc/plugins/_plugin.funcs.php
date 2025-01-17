<?php
/**
 * Functions for Plugin handling.
 *
 * This file is part of the evoCore framework - {@link http://evocore.net/}
 * See also {@link https://github.com/b2evolution/b2evolution}.
 *
 * @license GNU GPL v2 - {@link http://b2evolution.net/about/gnu-gpl-license}
 *
 * @copyright (c)2003-2018 by Francois Planque - {@link http://fplanque.com/}
 * Parts of this file are copyright (c)2004-2006 by Daniel HAHLER - {@link http://thequod.de/contact}.
 *
 * @package evocore
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );


/**
 * Recursive helper function to display a field of the plugin's settings (by manipulating a Form).
 *
 * Used for PluginSettings ("Edit plugin") and PluginUserSettings ("Edit user settings") as well as widgets.
 *
 * @todo dh> Allow to move setting sets up and down (order). Control goes into /inc/CONTROL/settings/plugins.php.
 * @todo NOTE: fp> I'm using this outside of Plugins; I'm not sure about proper factorization yet.
 *       This should probably be an extension of the Form class. Sth like "AutoForm" ;)
 *
 * @param string Settings path, e.g. 'locales[0]' or 'setting'
 * @param array Meta data for this setting.
 * @param Form (by reference)
 * @param string Settings type ('Settings' or 'UserSettings' or 'Widget' or 'Skin')
 * @param Plugin|Widget
 * @param mixed Target (User object for 'UserSettings')
 * @param mixed Value to really use (used for recursion into array type settings)
 */
function autoform_display_field( $parname, $parmeta, & $Form, $set_type, $Obj, $set_target = NULL, $use_value = NULL )
{
	global $debug;
	static $has_array_type;

	if( ! is_array( $parmeta ) )
	{	// Must be array:
		return;
	}

	if( ! empty($parmeta['no_edit']) )
	{ // this setting is not editable
		return;
	}

	$params = array();

	if( $use_value === NULL )
	{ // outermost level
		$has_array_type = false; // for adding a note about JS
		$outer_most = true;
	}
	else
	{
		$outer_most = false;
	}

	// Set input group
	if( isset( $parmeta['group'] ) )
	{
		$group = $parmeta['group'];
		if( substr( $group, -1 ) === ']' )
		{	// If group name is in array format like "edit_plugin_1_set_sample_sets[0][group_name]",
			// then param name must be like "edit_plugin_1_set_sample_sets[0][group_name_param_name]":
			$parname = substr( $group, 0, strlen( $group ) - 1 ).$parname.']';
		}
		else
		{	// If group name is simple like "group_name",
			// then param name must be like "group_name_param_name"
			$parname = $group.$parname;
		}
	}
	else
	{
		$group = NULL;
	}

	// Passthrough some attributes to elements:
	foreach( $parmeta as $k => $v )
	{
		if( in_array( $k, array( 'id', 'class', 'onchange', 'onclick', 'onfocus', 'onkeyup', 'onkeydown', 'onreset', 'onselect', 'cols', 'rows', 'maxlength', 'placeholder' ) ) )
		{
			$params[$k] = $v;
		}
	}
	if( ! empty($parmeta['multiple']) )
	{ // "multiple" attribute for "select" inputs:
		$params['multiple'] = 'multiple';
	}

	if( isset($parmeta['note']) )
	{
		$params['note'] = $parmeta['note'];
	}

	if( ! isset($parmeta['type']) || $parmeta['type'] == 'html_input' )
	{
		$parmeta['type'] = 'text';
	}
	elseif( $parmeta['type'] == 'html_textarea' )
	{
		$parmeta['type'] = 'textarea';
	}

	if( strpos($parmeta['type'], 'select_') === 0 )
	{ // 'allow_none' setting for select_* types
		if( isset($parmeta['allow_none']) )
		{
			$params['allow_none'] = $parmeta['allow_none'];
		}
	}

	$help_icon = NULL;
	if( isset($parmeta['help']) )
	{
		if( $parmeta['help'] === true )
		{ // link to $parname-target:
			$help_target = '#'.preg_replace( array('~\]?\[\d+\]\[~', '~\]$~'), array('_',''), $parname );
		}
		else
		{
			$help_target = $parmeta['help'];
		}
		$help_icon = $Obj->get_help_link( $help_target );
	}

	$set_label = isset($parmeta['label']) ? $parmeta['label'] : '';

	if( ! empty($parmeta['disabled']) )
	{
		$params['disabled'] = 'disabled';
	}


	// "Layout" settings:
	if( isset($parmeta['layout']) )
	{
		switch( $parmeta['layout'] )
		{
			case 'begin_fieldset':
				$fieldset_title = $set_label;
				$fieldset_params = array();
				if( isset( $parmeta['fold'] ) && $parmeta['fold'] === true )
				{	// Enable folding for the fieldset:
					$fieldset_params['fold'] = $parmeta['fold'];
					if( isset( $parmeta['deny_fold'] ) )
					{	// TRUE to don't allow fold the block and keep it opened always on page loading:
						$fieldset_params['deny_fold'] = $parmeta['deny_fold'];
					}
					// Unique ID of fieldset to store in user  settings or in user per collection settings:
					$fieldset_params['id'] = isset( $parmeta['id'] ) ? $parmeta['id'] : $parname;
				}
				$Form->begin_fieldset( $fieldset_title.$help_icon, $fieldset_params );
				break;

			case 'end_fieldset':
				$Form->end_fieldset();
				break;

			case 'separator':
				echo '<hr />';
				break;

			case 'html': // Output HTML code here
				if( ! isset($parmeta['value']) )
				{
					$parmeta['value'] = '<div class="error">HTML layout usage:<pre>'.
							htmlentities("'layout' => 'html',\n'value' => '<em>My HTML code</em>',").'</pre></div>';
				}
				echo $parmeta['value'];
				break;
		}
		return;
	}

	if( ! empty($help_icon) )
	{ // Append help icon to note:
		if( empty($params['note']) )
		{
			$params['note'] = $help_icon;
		}
		else
		{
			$params['note'] .= ' '.$help_icon;
		}
	}

	if( isset($use_value) )
	{
		$set_value = $use_value;
	}
	else
	{
		switch( $set_type )
		{
			case 'CollSettings':
				$set_value = $Obj->get_coll_setting( $parname, $set_target, false, $group );
				$error_value = NULL;
				break;

			case 'MsgSettings':
				$set_value = $Obj->get_msg_setting( $parname, $group );
				$error_value = NULL;
				break;

			case 'EmailSettings':
				$set_value = $Obj->get_email_setting( $parname, $group );
				$error_value = NULL;
				break;

			case 'Skin':
				$set_value = $Obj->get_setting( $parname, $group );
				$error_value = NULL;
				break;

			case 'Widget':
				$set_value = $Obj->get_param( $parname, NULL, $group );
				$error_value = NULL;
				break;

			case 'UserSettings':
				// NOTE: this assumes we come here only on recursion or with $use_value set..!
				$set_value = $Obj->UserSettings->get( $parname, $set_target->ID );
				$tmp_params = array(
					'name' => $parname,
					'value' => & $set_value,
					'meta' => $parmeta,
					'User' => $set_target,
					'action' => 'display' );
				$error_value = $Obj->PluginUserSettingsValidateSet( $tmp_params );
				break;

			case 'Settings':
				// NOTE: this assumes we come here only on recursion or with $use_value set..!
				$set_value = $Obj->Settings->get( $parname );
				$tmp_params = array(
					'name'   => $parname,
					'value'  => & $set_value,
					'meta'   => $parmeta,
					'action' => 'display' );
				$error_value = $Obj->PluginSettingsValidateSet( $tmp_params );
				break;

			default:
				debug_die( "unhandled set_type $set_type" );
				break;
		}

		if( $error_value )
		{ // add error
			param_error( $Obj->get_param_prefix().$parname, NULL, $error_value ); // only add the error to the field
		}
	}

	// Display input element:
	$input_name = $Obj->get_param_prefix().$parname;
	if( substr($parmeta['type'], 0, 6) == 'select' && ! empty($parmeta['multiple']) )
	{ // a "multiple" select:
		$input_name .= '[]';
	}

	// Get a value from _POST request to display it e.g. when some error was created during update
	$value_from_request = get_param( $input_name );
	if( $value_from_request !== NULL )
	{
		$set_value = $value_from_request;
	}

	if( ! empty( $parmeta['hide'] ) )
	{	// Hide this field on the editing form:
		$original_form_fieldstart = $Form->fieldstart;
		$Form->fieldstart = preg_replace( '/>$/', 'style="display:none">', $Form->fieldstart );
	}

	switch( $parmeta['type'] )
	{
		case 'begin_line':
			$Form->begin_line( $set_label );
			break;

		case 'end_line':
			$Form->end_line( $set_label );
			break;

		case 'string':
			echo $set_label;
			break;

		case 'checkbox':
			$Form->checkbox_input( $input_name, $set_value, $set_label, $params );
			break;

		case 'checklist':
			$options = array();
			foreach( $parmeta['options'] as $meta_option )
			{
				$meta_option_checked = $set_value === NULL ?
					/* default value */ $meta_option[2] :
					/* saved value */   ! empty( $set_value[ $meta_option[0] ] );

				$meta_option_disabled = isset( $meta_option[4] ) ? $meta_option[4] : NULL;
				$meta_option_note = isset( $meta_option[5] ) ? $meta_option[5] : NULL;
				$meta_option_class = isset( $meta_option[6] ) ? $meta_option[6] : NULL;
				$meta_option_hidden = isset( $meta_option[7] ) ? $meta_option[7] : NULL;
				$meta_option_label_attribs = isset( $meta_option[8] ) ? $meta_option[8] : NULL;
				$options[] = array( $input_name.'['.$meta_option[0].']', 1, $meta_option[1], $meta_option_checked, $meta_option_disabled, $meta_option_note, $meta_option_class, $meta_option_hidden, $meta_option_label_attribs );
			}
			$Form->checklist( $options, $input_name, $set_label, false, false, $params );
			break;

		case 'textarea':
			$textarea_rows = isset($parmeta['rows']) ? $parmeta['rows'] : 3;
			$Form->textarea_input( $input_name, $set_value, $textarea_rows, $set_label, $params );
			break;

		case 'select':
			$params['force_keys_as_values'] = true; // so that numeric keys get used as values! autoform_validate_param_value() checks for the keys only.
			$Form->select_input_array( $input_name, $set_value, $parmeta['options'], $set_label, isset($parmeta['note']) ? $parmeta['note'] : NULL, $params );
			break;

		case 'select_blog':
			$BlogCache = & get_BlogCache();
			$Form->select_input_object( $input_name, $set_value, $BlogCache, $set_label, $params );
			break;

		case 'select_group':
			$GroupCache = & get_GroupCache();
			$Form->select_input_object( $input_name, $set_value, $GroupCache, $set_label, $params );
			break;

		case 'select_user':
			$UserCache = & get_UserCache();
			$UserCache->clear();
			$users_SQL = $UserCache->get_SQL_object();
			$users_SQL->LIMIT( isset( $parmeta['users_limit'] ) ? intval( $parmeta['users_limit'] ) : 20 );
			$UserCache->load_by_sql( $users_SQL );
			if( ! isset( $params['loop_object_method'] ) )
			{
				$params['loop_object_method'] = 'get_preferred_name';
			}
			$Form->select_input_object( $input_name, $set_value, $UserCache, $set_label, $params );
			break;

		case 'radio':
			if( isset( $parmeta['field_lines'] ) )
			{
				$params['lines'] = $parmeta['field_lines'];
			}
			$options = array();
			foreach( $parmeta['options'] as $l_key => $l_options )
			{
				$options[$l_key] = array(
					'value' => $l_options[0],
					'label' => $l_options[1]
				);

				if( isset( $l_options[2] ) )
				{
					$options[$l_key]['note'] = $l_options[2];
				}
				if( isset( $l_options[4] ) )
				{	// Convert "inline attribs" to "params" array:
					preg_match_all( '#(\w+)=[\'"](.*)[\'"]#', $l_options[4], $matches, PREG_SET_ORDER );

					foreach( $matches as $l_set_nr => $l_match )
					{
						$options[$l_key][$l_match[1]] = $l_match[2];
					}
				}

				if( isset( $l_options[3] ) )
				{
					$options[$l_key]['suffix'] = $l_options[3];
				}
			}
			$Form->radio_input( $input_name, $set_value, $options, $set_label, $params );
			break;

		case 'array':
		case 'array:integer':
		case 'array:array:integer':
		case 'array:string':
		case 'array:array:string':
		case 'array:regexp':
			$has_array_type = true;
			$has_color_field = false;

			// Always use 'fieldset' layout to display it the same way from normal and ajax calls
			$Form->switch_layout( 'fieldset' );
			if( substr_count( $parname, '[' ) % 2 )
			{ // this refers to a specific array type set (with index pos at the end), e.g. when adding a field through AJAX:
				$pos_last_bracket = strrpos($parname, '[');
				$k_nb = substr( $parname, $pos_last_bracket+1, -1 );
				$disp_arrays = array( '' => $set_value ); // empty key..
				$parname = substr($parname, 0, $pos_last_bracket);
			}
			else
			{ // display all values hold in this set:
				$disp_whole_set = true;
				$disp_arrays = $set_value;
				$fieldset_title = $set_label;
				if( $debug )
				{
					$fieldset_title .= ' [debug: '.$parname.']';
				}
				$fieldset_params = array();
				if( isset( $parmeta['fold'] ) && $parmeta['fold'] === true )
				{	// Enable folding for the fieldset:
					$fieldset_params['fold'] = $parmeta['fold'];
					if( isset( $parmeta['deny_fold'] ) )
					{	// TRUE to don't allow fold the block and keep it opened always on page loading:
						$fieldset_params['deny_fold'] = $parmeta['deny_fold'];
					}
					// Unique ID of fieldset to store in user  settings or in user per collection settings:
					$fieldset_params['id'] = isset( $parmeta['id'] ) ? $parmeta['id'] : $parname;
				}
				$Form->begin_fieldset( $fieldset_title, $fieldset_params );

				if( ! empty($params['note']) )
				{
					echo '<p class="notes">'.$params['note'].'</p>';
				}
				$k_nb = 0;
			}

			// check if a color field is among the entries
			foreach( $parmeta['entries'] as $entry )
			{
				if( isset( $entry['type'] ) && $entry['type'] == 'color' )
				{
					$has_color_field = true;
					break;
				}
			}

			$user_ID = $set_type == 'UserSettings' ? $set_target->ID : '';
			if( is_array( $set_value ) && ! empty($set_value) )
			{ // Display value of the setting. It may be empty, if there's no set yet.
				foreach( $disp_arrays as $k => $v )
				{
					$fieldset_params = array(
							'class' => 'bordered',
							// Unique ID of fieldset(Also used to store a folding state in user settings or in user per collection settings):
							'id'    => isset( $parmeta['id'] ) ? $parmeta['id'] : $parname.'_'.$k_nb,
						);
					$remove_action = '';
					if( ! isset($parmeta['min_count']) || count($set_value) > $parmeta['min_count'] )
					{ // provide icon to remove this set
						$remove_action = '<span class="pull-right">'.action_icon(
								T_('Remove'),
								'minus',
								regenerate_url( 'action', array('action=del_settings_set&amp;set_path='.$parname.'['.$k.']'.( $set_type == 'UserSettings' ? '&amp;user_ID='.$user_ID : '' ), 'plugin_ID='.$Obj->ID) ),
								T_('Remove'),
								5, 3, /* icon/text prio */
								// attach onclick event to remove the whole fieldset:
								array(
									'onclick' => '
										jQuery( \'#fieldset_wrapper_'.$parname.'_'.$k_nb.'\' ).remove();
										'.( isset( $parmeta['max_number'] ) ? '
										if( jQuery( \'[id^=fieldset_wrapper_'.$parname.'_\' ).length < '.intval( $parmeta['max_number'] ).' )
										{
											jQuery( \'#button_add_new_setting_'.$parname.'\' ).show();
										}' : '' ).'
										return false;',
									)
								).'</span>';
					}
					if( isset( $parmeta['fold'] ) && $parmeta['fold'] === true )
					{	// Enable folding for the fieldset:
						$fieldset_params['fold'] = $parmeta['fold'];
						if( isset( $parmeta['deny_fold'] ) )
						{	// TRUE to don't allow fold the block and keep it opened always on page loading:
							$fieldset_params['deny_fold'] = $parmeta['deny_fold'];
						}

						$fieldset_params['id'] = $parname;
					}
					$Form->begin_fieldset( '#'.$k_nb.$remove_action, $fieldset_params );

					if( isset($parmeta['key']) )
					{ // KEY FOR THIS ENTRY:
						if( ! strlen($k) && isset($parmeta['key']['defaultvalue']) )
						{ // key is not given/set and we have a default:
							$l_value = $parmeta['key']['defaultvalue'];
						}
						else
						{
							$l_value = $k;
						}
						// RECURSE:
						autoform_display_field( $parname.'['.$k_nb.'][__key__]', $parmeta['key'], $Form, $set_type, $Obj, $set_target, $l_value );
					}

					foreach( $parmeta['entries'] as $l_set_name => $l_set_entry )
					{
						if( isset( $set_value[$k][$l_set_name] ) )
						{	// Use a saved value:
							$l_value = $set_value[$k][$l_set_name];
						}
						else
						{	// Use default value if it is defined:
							$l_value = isset( $l_set_entry['defaultvalue'] ) ? $l_set_entry['defaultvalue'] : NULL;
						}
						// RECURSE:
						autoform_display_field( $parname.'['.$k_nb.']['.$l_set_name.']', $l_set_entry, $Form, $set_type, $Obj, $set_target, $l_value );
					}
					$Form->end_fieldset();
					$k_nb++;
				}
			}

			// This div is used to insert new set of setting:
			echo '<div id="block_add_new_setting_'.$parname.'" data-param-num="'.$k_nb.'"></div>';

			// TODO: fix this for AJAX callbacks, when removing and re-adding items (dh):
			if( ! is_ajax_request() )
			{	// Don't display "Add" button twice after AJAX request:
				global $Blog;
				$set_path = $parname.'['.$k_nb.']';

				echo action_icon(
					sprintf( T_('Add a new set of &laquo;%s&raquo;'), $set_label ),
					'add',
					regenerate_url( 'action', array('action=add_settings_set', 'set_path='.$set_path.( $set_type == 'UserSettings' ? '&amp;user_ID='.get_param('user_ID') : '' ), 'plugin_ID='.$Obj->ID) ),
					T_('Add'),
					5, 3, /* icon/text prio */
					array(
						// Set button id to hide/show it depending on current number of settings:
						'id' => 'button_add_new_setting_'.$parname,
						// Hide button to add new set of setting when max number is reached:
						'style' => ( isset( $parmeta['max_number'] ) && $k_nb >= $parmeta['max_number'] ? 'display:none' : '' ),
						// Replace the 'add new' div with a new set of setting:
						'onclick' => 'jQuery.get( \''.get_htsrv_url().'async.php\',
							{
								action: \'add_plugin_sett_set\',
								plugin_ID: '.$Obj->ID.',
								set_type: \''.$set_type.'\',
								param_name: \''.$parname.'\',
								//param_num: jQuery( \'[id^=fieldset_wrapper_'.$parname.'_\' ).length
								param_num: jQuery( \'#block_add_new_setting_'.$parname.'\' ).data( \'param-num\' )
								'.( isset( $Blog ) ? ',blog: '.$Blog->ID : '' ).'
								'.( $set_type == 'UserSettings' ? ',user_ID: '.get_param( 'user_ID' ) : '' ).'
							},
							function( r, status )
							{
								jQuery( \'#block_add_new_setting_'.$parname.'\' ).replaceWith( ajax_debug_clear( r ) );
								'.( $has_color_field ? 'evo_initialize_colorpicker_inputs();' : '' ).'
								'.( isset( $parmeta['max_number'] ) ? '
								if( jQuery( \'[id^=fieldset_wrapper_'.$parname.'_\' ).length >= '.intval( $parmeta['max_number'] ).' )
								{
									jQuery( \'#button_add_new_setting_'.$parname.'\' ).hide();
								}' : '' ).'
							}
						);
						return false;'
					)
				);
			}

			if( ! empty($disp_whole_set) )
			{ // close the surrounding fieldset:
				$Form->end_fieldset();
			}
			$Form->switch_layout( NULL );

			break;

		case 'password':
			$params['type'] = 'password'; // same as text input, but type=password

		case 'float':
		case 'integer':
		case 'text':
			// Default: "text input"
			if( isset($parmeta['size']) )
			{
				$size = (int)$parmeta['size'];
			}
			else
			{ // Default size:
				$size = 15;
			}
			if( isset($parmeta['maxlength']) )
			{
				$params['maxlength'] = (int)$parmeta['maxlength'];
			}
			else
			{ // do not use size as maxlength, if not given!
				$params['maxlength'] = '';
			}

			if( isset( $parmeta['hide_label'] ) )
			{ // This param is used to hide a label
				$params['hide_label'] = $parmeta['hide_label'];
			}

			if( $parmeta['type'] == 'integer' )
			{	// Set special type 'number' for integer param to initialize control arrows to allow increase/decrease a value:
				$params['type'] = 'number';
				if( isset( $parmeta['valid_range']['min'] ) )
				{	// Restrict with min value:
					$params['min'] = $parmeta['valid_range']['min'];
				}
				if( isset( $parmeta['valid_range']['max'] ) )
				{	// Restrict with max value:
					$params['max'] = $parmeta['valid_range']['max'];
				}
				// Input number element doesn't support attribute "size", so we have only one way to set width with style:
				$params['style'] = 'width:'.( 40 + $size * 8 ).'px';
			}

			$Form->text_input( $input_name, $set_value, $size, $set_label, '', $params ); // TEMP: Note already in params
			break;

		case 'usertag':
			$size = isset( $parmeta['size'] ) ? intval( $parmeta['size'] ) : 30;
			$Form->usertag_input( $input_name, $set_value, $size, $set_label, '', $params );
			break;

		case 'itemtag':
			$size = isset( $parmeta['size'] ) ? intval( $parmeta['size'] ) : 30;
			$Form->tag_input( $input_name, $set_value, $size, $set_label, '', $params );
			break;

		case 'info':
			$Form->info( $parmeta['label'], $parmeta['info'] );
			break;

		case 'color':
			if( isset( $parmeta['transparency'] ) )
			{
				$params['transparency'] = $parmeta['transparency'];
			}
			$Form->color_input( $input_name, $set_value, $set_label, '', $params );
			break;

		case 'fileselect':
			if( isset( $parmeta['size'] ) )
			{
				$params['max_file_num'] = $parmeta['size'];
			}

			$params['root'] = isset( $parmeta['root'] ) ? $parmeta['root'] : '';
			$params['path'] = isset( $parmeta['path'] ) ? $parmeta['path'] : '';
			$params['size_name'] = isset( $parmeta['thumbnail_size'] ) ? $parmeta['thumbnail_size'] : 'crop-64x64';
			$params['max_file_num'] = isset( $parmeta['max_file_num'] ) ? $parmeta['max_file_num'] : 1;
			$params['initialize_with'] = isset( $parmeta['initialize_with'] ) ? $parmeta['initialize_with'] : '';
			$params['note'] = isset( $parmeta['note'] ) ? $parmeta['note'] : '';

			$Form->fileselect( $input_name, $set_value, $set_label, $params['note'], $params );
			break;

		case 'input_group':
			if( ! empty( $parmeta['inputs'] ) && is_array( $parmeta['inputs'] ) )
			{
				$Form->begin_line( $parmeta['label'], $input_name );
				foreach( $parmeta['inputs'] as $l_parname => $l_parmeta )
				{
					$l_parmeta['group'] = $parname; // inject group
					autoform_display_field( $l_parname, $l_parmeta, $Form, $set_type, $Obj, $set_target, $use_value );
				}
				$Form->end_line();
			}
			break;


		default:
			debug_die( 'Unsupported type ['.$parmeta['type'].'] from GetDefaultSettings()!' );
	}

	if( isset( $original_form_fieldstart ) )
	{	// Revert original field start html code:
		$Form->fieldstart = $original_form_fieldstart;
	}

	if( $outer_most && $has_array_type )
	{ // Note for Non-Javascript users:
		echo '<script></script><noscript>';
		echo '<p class="note">'.T_('Note: before adding a new set you have to save any changes.').'</p>';
		echo '</noscript>';
	}
}


/**
 * Helper method for "add_settings_set" and "delete_settings_set" action.
 *
 * Walks the given settings path and either inits the target entry or unsets it ($init_value=NULL).
 *
 * @param object Plugin or Skin
 * @param string Settings type ("Settings" or "UserSettings")
 * @param string The settings path, e.g. 'setting[0]foo[1]'. (Is used as array internally for recursion.)
 * @param mixed The initial value of the setting, typically array() - NULL to unset it (action "delete_settings_set" uses it)
 * @return array|false
 */
function _set_setting_by_path( & $Plugin, $set_type, $path, $init_value = array() )
{
	$r = get_plugin_settings_node_by_path( $Plugin, $set_type, $path, true );
	if( $r === false )
	{
		return false;
	}

	// Make return value handier. Note: list() would copy and destroy the references (setting and set_node)!
	$set_name = & $r['set_name'];
	$set_node = & $r['set_node'];
	$set_meta = & $r['set_meta'];
	$set_parent = & $r['set_parent'];
	$set_key  = & $r['set_key'];
	$setting  = & $r['setting'];
	#pre_dump( $r );

	#if( isset($set_node) && $init_value !== NULL )
	#{ // Setting already exists (and we do not want to delete), e.g. page reload!
	#	return false;
	#	/*
	#	while( isset($l_setting[ $path[0] ]) )
	#	{ // bump the index until not set
	#		$path[0]++;
	#	}
	#	*/
	#}
	#else
	if( is_null($init_value) )
	{ // NULL is meant to unset it
		unset($set_parent[$set_key]);
	}
	else
	{ // Init entries:
		// destroys reference: $set_node = $init_value;

		// Copy meta entries:
		foreach( $set_meta['entries'] as $k => $v )
		{
			if( isset( $v['defaultvalue'] ) )
			{ // set to defaultvalue
				$set_node[$k] = $v['defaultvalue'];
			}
			else
			{
				if( isset($v['type']) && strpos( $v['type'], 'array' ) === 0 )
				{
					$set_node[$k] = array();
				}
				else
				{
					$set_node[$k] = '';
				}
			}
		}
	}

	switch( $set_type )
	{
		case 'Settings':
		case 'CollSettings':
		case 'Widget':
			$Plugin->Settings->set( $set_name, $setting );
			break;

		case 'UserSettings':
			$Plugin->UserSettings->set( $set_name, $setting );
			break;

		case 'MsgSettings':
			$set_name = ( $set_name == 'msg_apply_rendering' ? '' : 'msg_' ).$set_name;
			$Plugin->Settings->set( $set_name, $setting );
			break;

		case 'EmailSettings':
			$set_name = ( $set_name == 'email_apply_rendering' ? '' : 'email_' ).$set_name;
			$Plugin->Settings->set( $set_name, $setting );
			break;

		case 'Skin':
			$Skin = & $Plugin;
			$Skin->set_setting( $set_name, $setting );
			break;

		default:
			debug_die( 'Invalid plugin type param!' );
	}


	return $setting;
}


/**
 * Get a node from settings by path (e.g. "locales[0][questions]")
 *
 * @param object Plugin or Skin
 * @param string Settings type ("Settings" or "UserSettings")
 * @param string The settings path, e.g. 'setting[0]foo[1]' or even 'setting[]'. (Is used as array internally for recursion.)
 * @return array Array(
 *          - 'set_name': setting name (string); key of the first level
 *          - 'set_node': selected setting node, may be NULL (by reference)
 *          - 'set_meta': meta info (from GetDefault[User]Settings()) for selected node (array)
 *          - 'set_parent': parent node (by reference)
 *          - 'set_key': key in parent node (by reference)
 *          - 'setting': whole settings (array)
 */
function get_plugin_settings_node_by_path( & $Plugin, $set_type, $path, $create = false )
{
	// Init:
	if( ! preg_match( '~^\w+(\[\w+\])+$~', $path ) )
	{
		debug_die( 'Invalid path param!' );
	}

	$path = preg_split( '~(\[|\]\[?)~', $path, -1 ); // split by "[" and "][", so we get an array with setting name and index alternating
	$foo = array_pop($path); // remove last one
	if( ! empty($foo) )
		debug_die('Assertion failed!');

	$set_name = $path[0];

	// meta info for this setting:
	$tmp_params = array( 'for_editing' => true );

	switch( $set_type )
	{
		case 'Settings':
			$setting = $Plugin->Settings->get( $set_name );
			$defaults = $Plugin->GetDefaultSettings( $tmp_params );
			break;

		case 'UserSettings':
			$setting = $Plugin->UserSettings->get( $set_name );
			$defaults = $Plugin->GetDefaultUserSettings( $tmp_params );
			break;

		case 'CollSettings':
			$setting = $Plugin->Settings->get( $set_name );
			$defaults = $Plugin->get_coll_setting_definitions( $tmp_params );
			break;

		case 'MsgSettings':
			$param_name = ( $set_name == 'msg_apply_rendering' ? '' : 'msg_' ).$set_name;
			$setting = $Plugin->Settings->get( $param_name );
			$defaults = $Plugin->get_msg_setting_definitions( $tmp_params );
			break;

		case 'EmailSettings':
			$param_name = ( $set_name == 'email_apply_rendering' ? '' : 'email_' ).$set_name;
			$setting = $Plugin->Settings->get( $param_name );
			$defaults = $Plugin->get_email_setting_definitions( $tmp_params );
			break;

		case 'Widget':
			$setting = $Plugin->Settings->get( $set_name );
			$defaults = $Plugin->get_widget_param_definitions( $tmp_params );
			break;

		case 'Skin':
			$Skin = & $Plugin;
			$setting = $Skin->get_setting( $set_name );
			$defaults = $Skin->get_param_definitions( $tmp_params );
			break;

		default:
			debug_die( 'Invalid plugin type param!' );
	}

	if( ! is_array( $setting ) )
	{ // this may happen, if there was a non-array setting stored previously:
		// discard those!
		$setting = array();
	}

	if( ! isset( $defaults[ $set_name ] ) )
	{
		//debug_die( 'Invalid setting ('.$set_name.') - no meta data!' );
		return false;
	}

	$found_node = & $setting;
	$defaults_node = & $defaults;
	$set_meta = $defaults[$set_name];
	$set_parent = NULL;
	$set_key = NULL;

	$count = 0;
	while( count($path) )
	{
		$count++;

		$loop_name = array_shift($path);

		if( $count > 1 )
		{
			$set_parent = & $found_node[$loop_name];
			$set_key = NULL;
			$defaults_node = & $defaults_node['entries'][$loop_name];
			$found_node = & $found_node[$loop_name];
		}
		else
		{
			$defaults_node = & $defaults_node[$loop_name];
			$set_parent = & $setting;
		}

		if( count($path) )
		{ // has an index => array
			$loop_index = array_shift($path);

			#$set_parent = & $set_parent[$loop_name];
			$set_key = $loop_index;

			if( $set_key === '' )
			{ // []-syntax: append entry
				if( $create && ! count($path) )
				{ // only create, if at the end
					$found_node[] = array();
				}
				$found_node = & $found_node[ array_pop(array_keys($found_node)) ];
			}
			else
			{ // specific key:
				if( ! isset($found_node[$loop_index]) )
				{
					$found_node[$loop_index] = array();
				}
				$found_node = & $found_node[$loop_index];
			}
		}
	}

	#echo '<h1>RETURN</h1>'; pre_dump( $set_parent, $set_key );
	return array(
		'set_name' => $set_name,
		'set_node' => & $found_node,
		'set_meta' => $defaults_node,
		'set_parent' => & $set_parent,
		'set_key' => & $set_key,
		'setting' => & $setting );
}


/**
 * Set Plugin settings from params.
 *
 * fp> WARNING: also used outside of plugins. Work in progress.
 *
 * This handled plugin specific params when saving a user profile (PluginUserSettings) or plugin settings (PluginSettings).
 *
 * @param string Settings path, e.g. 'locales[0]' or 'setting'
 * @param array Meta data for this setting.
 * @param Plugin|Widget
 * @param string Type of Settings (either 'Settings' or 'UserSettings').
 * @param mixed Target (User object for 'UserSettings')
 * @param mixed NULL to use value from request, OR set value what you want to force
 */
function autoform_set_param_from_request( $parname, $parmeta, & $Obj, $set_type, $set_target = NULL, $set_value = NULL )
{
	if( ! is_array( $parmeta ) )
	{	// Must be array:
		return;
	}

	if( isset($parmeta['layout']) )
	{ // a layout "setting"
		return;
	}

	if( ( ! empty( $parmeta['disabled'] ) || ! empty( $parmeta['no_edit'] ) )
	    && $set_value === NULL )
	{ // the setting is disabled, but allow to update the value when it is forced by $set_value
		return;
	}

	if( ! empty( $parmeta['inputs'] ) )
	{
		foreach( $parmeta['inputs'] as $l_parname => $l_parmeta )
		{
			$l_parmeta['group'] = $parname; // inject group into meta
			autoform_set_param_from_request( $l_parname, $l_parmeta, $Obj, $set_type, $set_target, $set_value );
		}
		return;
	}

	// set input group
	if( isset( $parmeta['group'] ) )
	{
		$parname = $parmeta['group'].$parname;
	}

	$l_param_type = 'string';
	$l_param_default = '';
	if( isset($parmeta['type']) )
	{
		if( substr($parmeta['type'], 0, 6) == 'select' && ! empty($parmeta['multiple']) )
		{ // a "multiple" select:
			$l_param_type = 'array';
		}
		switch( $parmeta['type'] )
		{
			case 'array':
			case 'array:integer':
			case 'array:array:integer':
			case 'array:string':
			case 'array:array:string':
			case 'array:regexp':
				// this settings has a type
				$l_param_type = $parmeta['type'];
				break;

			case 'checkbox':
				$l_param_type = 'integer';
				$l_param_default = 0;
				break;

			case 'checklist':
				$l_param_type = 'array';
				$l_param_default = array();
				break;

			case 'textarea':
				$l_param_type = 'text';
				break;

			case 'html_input':
			case 'html_textarea':
				$l_param_type = 'html';
				break;

			default:
		}
	}

	if( $set_value === NULL )
	{ // Get the value from request:
		$l_value = param( $Obj->get_param_prefix().$parname, $l_param_type, $l_param_default );
		// Store values of unchecked checkboxes manually because their empty values are not passed to a submitted form:
		switch( $l_param_type )
		{
			case 'array':
			case 'array:integer':
			case 'array:array:integer':
			case 'array:string':
			case 'array:array:string':
			case 'array:regexp':
				if( ! empty( $parmeta['entries'] ) )
				{
					foreach( $l_value as $l_index => $l_index_values )
					{
						if( count( $parmeta['entries'] ) != count( $l_index_values ) )
						{	// If some entry(like checkbox) is missed:
							foreach( $parmeta['entries'] as $parmeta_entry_key => $parmeta_entry_data )
							{
								if( isset( $parmeta_entry_data['type'] ) &&
								    $parmeta_entry_data['type'] == 'checkbox' &&
								    ! isset( $l_index_values[ $parmeta_entry_key ] ) )
								{	// If field is checkbox but value is not passed to a submitted form becauase it was unchecked:
									$l_value[ $l_index ][ $parmeta_entry_key ] = 0;
								}
							}
						}
					}
				}
				break;
		}
	}
	else
	{ // Force value
		$l_value = $set_value;
	}

	if( isset( $parmeta['type'] ) )
	{	// Prepare values before store them in DB:
		if( strpos( $parmeta['type'], 'array' ) === 0 )
		{	// Make keys (__key__) in arrays unique and remove them
			handle_array_keys_in_plugin_settings( $l_value );
		}
		elseif( $parmeta['type'] == 'integer' )
		{	// Convert to correct integer value:
			if( $l_value !== '' )
			{	// Don't convert empty string '' to integer 0:
				$l_value = intval( $l_value );
			}
			if( empty( $l_value ) && ! empty( $parmeta['allow_empty'] ) &&
					isset( $parmeta['valid_range'], $parmeta['valid_range']['min'] ) && $parmeta['valid_range']['min'] > 0 )
			{	// Convert 0 to empty value for integer field if it allows empty values:
				$l_value = NULL;
			}
		}
	}

	if( ! autoform_validate_param_value( $Obj->get_param_prefix().$parname, $l_value, $parmeta ) )
	{
		return;
	}

	// Validate form values:
	switch( $set_type )
	{
		case 'CollSettings':
			$error_value = NULL;
			$Obj->set_coll_setting( $parname, $l_value );
			break;

		case 'Skin':
			$error_value = NULL;
			$Obj->set_setting( $parname, $l_value );
			break;

		case 'Widget':
			$error_value = NULL;
			if( isset( $parmeta['type'] ) && $parmeta['type'] == 'checklist' && $parname == 'renderers' )
			{	// Save "stealth" and "always" plugin render options:
				// (they are hidden or disabled checkboxes of the form and cannot be submitted automatically)
				global $Plugins;
				$widget_Blog = & $Obj->get_Blog();
				$l_value = $Plugins->validate_renderer_list( array_keys( $l_value ), array(
						'Blog'         => & $widget_Blog,
						'setting_name' => 'coll_apply_rendering',
					) );
				$l_value = array_fill_keys( $l_value, 1 );
			}
			$Obj->set( $parname, $l_value, false, ( isset( $parmeta['group'] ) ? $parmeta['group'] : NULL ) );
			break;

		case 'UserSettings':
			// Plugin User settings:
			$dummy = array(
				'name' => $parname,
				'value' => & $l_value,
				'meta' => $parmeta,
				'User' => $set_target,
				'action' => 'set' );
			$error_value = $Obj->PluginUserSettingsValidateSet( $dummy );
			// Update the param value, because a plugin might have changed it (through reference):
			$GLOBALS[ $Obj->get_param_prefix().$parname ] = $l_value;

			if( empty( $error_value ) )
			{
				$Obj->UserSettings->set( $parname, $l_value, $set_target->ID );
			}
			break;

		case 'Settings':
			// Plugin global settings:
		case 'MsgSettings':
			// Plugin messages settings:
		case 'EmailSettings':
			// Plugin emails settings:
			$dummy = array(
				'name'   => $parname,
				'value'  => & $l_value,
				'meta'   => $parmeta,
				'action' => 'set' );
			$error_value = $Obj->PluginSettingsValidateSet( $dummy );
			// Update the param value, because a plugin might have changed it (through reference):
			$GLOBALS[ $Obj->get_param_prefix().$parname ] = $l_value;

			if( empty( $error_value ) )
			{	// Set new value:
				if( $set_type == 'MsgSettings' && $parname != 'msg_apply_rendering' )
				{	// Use prefix 'msg_' for all message settings except of "msg_apply_rendering":
					$Obj->Settings->set( 'msg_'.$parname, $l_value );
				}
				elseif( $set_type == 'EmailSettings' && $parname != 'email_apply_rendering' )
				{	// Use prefix 'email_' for all message settings except of "email_apply_rendering":
					$Obj->Settings->set( 'email_'.$parname, $l_value );
				}
				else
				{	// Global settings:
					$Obj->Settings->set( $parname, $l_value );
				}
			}
			break;

		default:
			debug_die( "unhandled set_type $set_type" );
			break;
	}

	if( $error_value )
	{ // A validation error has occured, record error message:
		param_error( $Obj->get_param_prefix().$parname, $error_value );
	}
}


/**
 * Validates settings according to their meta info recursively.
 *
 * @todo Init "checkbox" values in "array" type settings (they do not get send) (dh)
 * @param string Param name
 * @param array Meta info
 * @return boolean
 */
function autoform_validate_param_value( $param_name, $value, $meta )
{
	global $Messages;

	if( ! is_array( $meta ) )
	{	// Must be array:
		return;
	}

	if( is_array( $value ) && isset( $meta['entries'] ) )
	{
		$r = true;
		if( isset( $meta['key'] ) )
		{ // validate keys:
			foreach( array_keys( $value ) as $k )
			{
				if( ! autoform_validate_param_value( $param_name.'['.$k.'][__key__]', $k, $meta['key'] ) )
				{
					$r = false;
				}
			}
		}

		// Check max_count/min_count
		// dh> TODO: find a way to link it to the form's fieldset (and add an "error" class to it)
		if( isset( $meta['max_count'] ) && count( $value ) > $meta['max_count'] )
		{
			$r = false;
			$label = isset( $meta['label'] ) ? $meta['label'] : $param_name;
			$Messages->add( sprintf( T_('Too many entries in the "%s" set. It must have %d at most.'), $label, $meta['max_count'] ), 'error' );
		}
		elseif( isset( $meta['min_count'] ) && count( $value ) < $meta['min_count'] )
		{
			$r = false;
			$label = isset( $meta['label'] ) ? $meta['label'] : $param_name;
			$Messages->add( sprintf( T_('Too few entries in the "%s" set. It must have %d at least.'), $label, $meta['min_count'] ), 'error' );
		}

		foreach( $meta['entries'] as $mk => $mv )
		{
			foreach( $value as $vk => $vv )
			{
				if( isset( $mv['type'] ) && $mv['type'] == 'input_group' &&
				    ! empty( $mv['inputs'] ) && is_array( $mv['inputs'] ) )
				{	// Validate each input field of the setting with type "input_group":
					foreach( $mv['inputs'] as $k => $v )
					{
						if( isset( $vv[ $mk.$k ] ) && ! autoform_validate_param_value( $param_name.'['.$vk.']['.$mk.$k.']', $vv[ $mk.$k ], $v ) )
						{	// If at least one setting of the grouped inputs has an error:
							$r = false;
						}
					}
				}
				elseif( isset( $vv[ $mk ] ) && ! autoform_validate_param_value( $param_name.'['.$vk.']['.$mk.']', $vv[ $mk ], $mv ) )
				{	// If single setting has an error:
					$r = false;
				}
			}
		}
		return $r;
	}


	if( isset( $meta['type'] ) )
	{
		if( isset( $meta['allow_empty'] ) && $meta['allow_empty'] && empty( $value ) )
		{ // Allow an empty value
			return true;
		}

		switch( $meta['type'] )
		{
			case 'text':
				if( isset( $meta['allow_empty'] ) && ! $meta['allow_empty'] && $value === '' )
				{	// Display error if the text field is required to be not empty:
					param_error( $param_name, sprintf( T_('The field &laquo;%s&raquo; cannot be empty.'), $meta['label'] ), T_('This field cannot be empty.') );
					return false;
				}
				break;

			case 'integer':
				if( ! preg_match( '~^[-+]?\d+$~', $value ) )
				{
					param_error( $param_name, sprintf( T_('The value for &laquo;%s&raquo; must be numeric.'), $meta['label'] ), T_('The value must be numeric.') );
					return false;
				}
				break;

			case 'float':
				if( ! preg_match( '~^[-+]?\d+(\.\d+)?$~', $value ) )
				{
					param_error( $param_name, sprintf( T_('The value for &laquo;%s&raquo; must be numeric.'), $meta['label'] ), T_('The value must be numeric.') );
					return false;
				}
				break;

			case 'radio':
				$check_value = false;
				foreach($meta['options'] as $arr)
				{
					if( ! is_array($arr) )
					{
						param_error( $param_name, sprintf( T_('Invalid option &laquo;%s&raquo;.'), $arr ) );
						return false;
					}
					if( $value == $arr[0] )
					{
						$check_value = true;
						break;
					}
				}
				if ( ! $check_value )
				{
					param_error( $param_name, sprintf( T_('Invalid option &laquo;%s&raquo;.'), $value ) );
					return false;
				}
				break;

			case 'select':
				$check_options = $value;
				if( ! is_array($check_options) )
				{ // no "multiple" select:
					$check_options = array($check_options);
				}

				// Get all possible values for the select element:
				$meta_options = array();
				foreach( $meta['options'] as $meta_option_key => $meta_option_value )
				{
					if( is_array( $meta_option_value ) )
					{	// It is a grouped options:
						foreach( $meta_option_value as $meta_group_option_key => $meta_group_option_value )
						{
							$meta_options[] = $meta_group_option_key;
						}
					}
					else
					{	// Single option:
						$meta_options[] = $meta_option_key;
					}
				}

				// Check if the selected values can be used for the select element:
				foreach( $check_options as $v )
				{
					if( empty( $v ) && ! empty( $meta['allow_none'] ) )
					{ // empty is ok:
						continue;
					}
					if( ! in_array( $v, $meta_options ) )
					{
						param_error( $param_name, sprintf( T_('Invalid option &laquo;%s&raquo;.'), $v ) );
						return false;
					}
				}
				break;

			case 'select_blog':
			case 'select_group':
			case 'select_user':
				if( is_array($value) && empty($value) // empty "multiple" select
					|| ( ! is_array($value) && ! strlen($value) ) )
				{
					if( empty($meta['allow_none']) )
					{ // empty is not ok
						param_error( $param_name, sprintf( T_('Invalid option &laquo;%s&raquo;.'), $value ) );
						return false;
					}
				}
				else
				{ // Try retrieving the value from the corresponding Cache:
					switch( $meta['type'] )
					{
						case 'select_blog':
							$Cache = & get_BlogCache();
							break;

						case 'select_group':
							$Cache = & get_GroupCache();
							break;

						case 'select_user':
							$Cache = & get_UserCache();
							break;
					}

					$check_options = $value;
					if( ! is_array($check_options) )
					{ // no "multiple" select:
						$check_options = array($check_options);
					}

					foreach($check_options as $v)
					{
						if( empty($v) && ! empty($meta['allow_none']) )
						{ // empty is ok:
							continue;
						}
						if( ! $Cache->get_by_ID($v, false, false) )
						{
							param_error( $param_name, sprintf( T_('Invalid option &laquo;%s&raquo;.'), $v ) );
							return false;
						}
					}
				}
				break;

			case 'color':
				if( ! is_color( $value ) )
				{
					param_error( $param_name, sprintf(  T_('Invalid color code for &laquo;%s&raquo;.'), $meta['label'] ) );
					return false;
				}
				break;
		}
	}

	// Check maxlength:
	if( isset($meta['maxlength']) )
	{
		if( utf8_strlen($value) > $meta['maxlength'] )
		{
			param_error( $param_name, sprintf( T_('The value is too long.'), $value ) );
		}
	}

	// Check valid pattern:
	if( isset( $meta['valid_pattern'] ) )
	{
		$param_pattern = is_array( $meta['valid_pattern'] ) ? $meta['valid_pattern']['pattern'] : $meta['valid_pattern'];
		if( ! preg_match( $param_pattern, $value ) )
		{
			$param_error = is_array( $meta['valid_pattern'] ) ? $meta['valid_pattern']['error'] : sprintf( T_('The value is invalid. It must match the regular expression &laquo;%s&raquo;.'), $param_pattern );
			param_error( $param_name, $param_error );
			return false;
		}
	}

	// Check valid range:
	if( isset($meta['valid_range']) )
	{
		// Transform numeric indexes into associative keys:
		if( ! isset($meta['valid_range']['min'], $meta['valid_range']['max'])
			&& isset($meta['valid_range'][0], $meta['valid_range'][1]) )
		{
			$meta['valid_range']['min'] = $meta['valid_range'][0];
			$meta['valid_range']['max'] = $meta['valid_range'][1];
		}
		if( isset($meta['valid_range'][2]) && ! isset($meta['valid_range']['error']) )
		{
			$meta['valid_range']['error'] = $meta['valid_range'][2];
		}

		if( ( isset( $meta['valid_range']['min'] ) && $value < $meta['valid_range']['min'] )
				|| ( isset( $meta['valid_range']['max'] ) && $value > $meta['valid_range']['max'] ) )
		{
			if( isset( $meta['valid_range']['error'] ) )
			{
				$param_error = $meta['valid_range']['error'];
			}
			else
			{
				if( isset( $meta['valid_range']['min'] ) && isset( $meta['valid_range']['max'] ) )
				{
					$param_error = sprintf( T_('The value is invalid. It must be in the range from %s to %s.'), $meta['valid_range']['min'], $meta['valid_range']['max'] );
				}
				elseif( isset( $meta['valid_range']['max'] ) )
				{
					$param_error = sprintf( T_('The value is invalid. It must be smaller than or equal to %s.'), $meta['valid_range']['max'] );
				}
				else
				{
					$param_error = sprintf( T_('The value is invalid. It must be greater than or equal to %s.'), $meta['valid_range']['min'] );
				}
			}

			param_error( $param_name, $param_error );
			return false;
		}
	}

	return true;
}


/**
 * This handles the special "__key__" index in all array type values
 * in the given array. It makes sure, that "__key__" is unique and
 * replaces the original key of the value with it.
 * @param array (by reference)
 */
function handle_array_keys_in_plugin_settings( & $a )
{
	if( ! is_array( $a ) )
	{
		return;
	}

	$new_arr = array(); // use a new array to maintain order, also for "numeric" keys

	foreach( array_keys( $a ) as $k )
	{
		$v = & $a[$k];

		if( is_array( $v ) && isset( $v['__key__'] ) )
		{
			if( $k != $v['__key__'] )
			{
				$k = $v['__key__'];
				if( ! strlen( $k ) || isset($a[ $k ]) )
				{ // key already exists (or is empty):
					$c = 1;

					while( isset( $a[ $k.'_'.$c ] ) )
					{
						$c++;
					}
					$k = $k.'_'.$c;
				}
			}
			unset( $v['__key__'] );

			$new_arr[$k] = $v;
		}
		else
		{
			$new_arr[$k] = $v;
		}

		// Recurse:
		foreach( array_keys( $v ) as $rk )
		{
			if( is_array( $v[$rk] ) )
			{
				handle_array_keys_in_plugin_settings($v[$rk]);
			}
		}
	}
	$a = $new_arr;
}


/**
 * Helper function to do the action part of DB schema upgrades for "enable" and "install"
 * actions.
 *
 * @param object Plugin
 * @param boolean Force install DB for the plugin (used in installation of b2evo)
 * @return boolean True, if no changes needed or done; false if we should break out to display "install_db_schema" action payload.
 */
function install_plugin_db_schema_action( & $Plugin, $force_install_db_deltas = false )
{
	global $inc_path, $install_db_deltas, $DB, $Messages;

	// Prepare vars for DB layout changes
	$install_db_deltas_confirm_md5 = param( 'install_db_deltas_confirm_md5' );

	$db_layout = $Plugin->GetDbLayout();
	$install_db_deltas = array(); // This holds changes to make, if any (just all queries)
	//pre_dump( $db_layout );

	if( ! empty( $db_layout ) )
	{ // The plugin has a DB layout attached
		load_funcs('_core/model/db/_upgrade.funcs.php');

		// Get the queries to make:
		foreach( db_delta( $db_layout ) as $table => $queries )
		{
			foreach( $queries as $query_info )
			{
				foreach( $query_info['queries'] as $query )
				{ // subqueries for this query (usually one, but may include required other queries)
					$install_db_deltas[] = $query;
				}
			}
		}

		if( ! empty( $install_db_deltas ) )
		{ // delta queries to make
			if( empty( $install_db_deltas_confirm_md5 ) && !$force_install_db_deltas )
			{ // delta queries have to be confirmed in payload
				return false;
			}
			elseif( $install_db_deltas_confirm_md5 == md5( implode( '', $install_db_deltas ) ) || $force_install_db_deltas )
			{ // Confirmed in first step:
				foreach( $install_db_deltas as $query )
				{
					$DB->query( $query );
				}

				$Messages->add( T_('The database has been updated.'), 'success' );
			}
			else
			{ // should not happen
				$Messages->add( T_('The DB schema has been changed since confirmation.'), 'error' );

				// delta queries have to be confirmed (again) in payload
				return false;
			}
		}
	}

	return true;
}

?>