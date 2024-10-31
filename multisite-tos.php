<?php
/*
Plugin Name: Multisite TOS
Plugin URI: http://lettoblog.com
Description: This plugin adds a TOS (Terms of Service) field on the multisite signup form. (Such as wordpress.com TOS field)
Author: Mustafa UYSAL
Version: 1.1
Text Domain: ms-tos
Author URI: http://blog.uysalmustafa.com
License: GPLv2 (or later)
Network: true
*/


//get locale
function plugin_localization() {  
	load_plugin_textdomain( 'ms-tos', false, '/multisite-tos/languages/' );
}

//setting menu item
function tos_setting_page() {	
	if ( is_multisite() ) {
	add_submenu_page('settings.php', __('TOS', 'ms-tos'), __('TOS', 'ms-tos'), 'manage_options', 'multisite-tos', 'ms_tos_page');
	} else {
    add_options_page(__('TOS', 'ms-tos'), __('TOS', 'ms-tos'), 'manage_options', 'multisite-tos', 'ms_tos_page');
  }
}
	

//add to signup area
function multisite_tos_area($errors) {
	if (!empty($errors)){
		$error = $errors->get_error_message('tos');
	}

  $signup_tos_url = get_site_option('ms_tos_url');

	if ( !empty( $signup_tos_url ) ) {
	?>
	
    <p class="ms-tos" style="clear:both;margin-top:10px; font-family:Arial,'Helvetica Neue',Helvetica,sans-serif; font-weight: bold;  padding-left:5px;  line-height:24px; font-size:13px; height:24px; color:#000000; background: #FFFFAA; border: 1px solid #FFAD33; width:392px;" >
	<?php _e('You agree to the ', 'ms-tos'); ?><a href="<?php echo $signup_tos_url ?>"> <?php _e('terms of service', 'ms-tos'); ?></a> <?php _e('by submitting this form.', 'ms-tos'); ?></p>


		<?php
        if(!empty($error)) {
			echo '<p class="error">' . $error . '</p>';
        }
		?>
		
	<?php
	}
}

//setting page
function ms_tos_page() {
	global $wpdb, $wp_roles, $current_user;
	//check user role
	if( !current_user_can('edit_users') ) {
		echo '<p>You do not have sufficient permissions to access this page.</p>'; 
		return;
	}
	
	echo '<div class="wrap">';
	if (isset($_POST['ms_tos_url'])) {
    update_site_option( "ms_tos_url", stripslashes($_POST['ms_tos_url']) );
		?><div class="oki-doki" style="clear:both;line-height:22px; width:200px; text-align:center; border-radius:4px 4px 4px 4px; font-size:16px; background-color:#FFFBCC;border-color:#E6DB55;color:#555;"><p><?php _e('Settings Saved.', 'ms-tos'); ?></p></div><?php
	}

	$tos_url = get_site_option('ms_tos_url');

	?>
  <h2><?php _e('Terms of Service', 'ms-tos') ?></h2>
  <form method="post" action="">
  <div id="tos-url"><?php _e('Terms of Service URL', 'ms-tos') ?>:</th>  
  <input name="ms_tos_url" type="text"   style="width: 200px;" value="<?php echo esc_attr($tos_url); ?>"/> <em><?php _e('(Ex: http://example.com/tos.php)', 'ms-tos') ?></em>
  </div>

  <p class="submit">
  <input type="submit" name="Submit" value="<?php _e('Save Changes', 'ms-tos') ?>" />
  </p>
  </form>
  <?php
	echo '</div>';
}

//Hooks
add_action('plugins_loaded', 'plugin_localization');
add_action('signup_extra_fields', 'multisite_tos_area');
add_action('bp_before_registration_submit_buttons', 'multisite_tos_area');
add_action('network_admin_menu', 'tos_setting_page');
add_action('admin_menu', 'tos_setting_page');
?>