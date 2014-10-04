<?php		

add_action( 'admin_menu', 'register_as_contact_settings_page' );
add_action('admin_head', 'admin_register_head');

function register_as_contact_settings_page()
{
	add_menu_page("Asset Store Contact Form", "AS Contact", "manage_options", "as_contact_settings", show_as_contact_config);
}

function admin_register_head() 
{  
	$url = plugins_url('stylesheet.css', __FILE__); 
	echo "<link rel='stylesheet' href='$url' >";
}

function show_as_contact_config()
{
	if ( current_user_can( manage_options ) ) {
		show_as_contact_settings();
	} else
	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
}

function show_as_contact_settings()
{
	if (isset($_POST['submitted_update']) && $_POST['submitted_update'] == 'y')
	{
		$key = trim($_POST['key']);
		$width = trim($_POST['form_width']);
		$form_email = trim($_POST['form_email']);
		update_option('as_pub_key', $key);
		update_option('as_form_width',$width);
		update_option('as_form_email',$form_email);
	}
	?>
	<div class="wrap" class="widefat"><div id="icon-users" class="icon32"></div>
	<h2>Asset Store Contact Form</h2>
	<form name="as_contact_form" method="POST">
	<table width=100%>
	<tr>
	<td width=200px>Contact form email</td>
	<td width=30px>&nbsp;</td>
	<td><input type="text" name="form_email" value="<?php echo get_option('as_form_email');?>"></td>
	</tr>
	<tr>
	<td width=200px>Publisher's API key</td>
	<td width=30px>&nbsp;</td>
	<td><input type="text" name="key" value="<?php echo get_option('as_pub_key');?>"></td>
	</tr>
	<tr>
	<td width=200px>Form width</td>
	<td width=30px>&nbsp;</td>
	<td><input type="text" name="form_width" value="<?php echo get_option('as_form_width');?>"></td>
	</tr>
	</table>
	<input type="hidden" name="submitted_update" value="y">
	<input type="submit" class="button-primary" value="Update settings">
	</form>
	</div>
	<?php
}

?>