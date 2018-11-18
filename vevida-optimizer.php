<?php
/**
 * Plugin Name: Vevida Optimizer
 * Plugin URI: https://wordpress.org/plugins/vevida-optimizer/
 * Description: Configure automatic updates for each WordPress component, and optimize the mySQL database tables.
 * Version: 1.2
 * Author: Jan Vlastuin, Jan Reilink, Brian Stal
 * Author URI: https://vevida.com
 * License: GPLv2
 * Text Domain: vevida-optimizer
 * Domain Path: /languages
 */

/**
 * Don't allow the plugin to be loaded directly
 */
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

function vevida_optimizer_scripts() {
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'style',  $plugin_url . "/css/style.css");
}
add_action( 'admin_enqueue_scripts', 'vevida_optimizer_scripts' );

if( !defined( 'VEVIDAOPTIMIZERHOME' ) )
	define('VEVIDAOPTIMIZERHOME', dirname(__FILE__).'/');

if( !defined( 'VEVIDAOPTIMIZERURL' ) )
	define('VEVIDAOPTIMIZERURL', plugin_dir_url(__FILE__));

if( !isset( $vevida_optimizer_plugins_dir ) )
	$vevida_optimizer_plugins_dir = VEVIDAOPTIMIZERHOME . 'plugins';

$plugins = glob( $vevida_optimizer_plugins_dir . '/*.php' );
if( is_array( $plugins ) ) {
	foreach ( $plugins as $plugin ) {
	if( is_file( $plugin ) )
		require_once( $plugin );
	}
}

/**
 * Load textdomain for vevida optimizer plugin
 */
function vevida_optimizer_load_textdomain() {
	load_plugin_textdomain( 'vevida-optimizer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'vevida_optimizer_load_textdomain' );

/**
 * Plugin core. Automatic Update settings are modified here.
 */
function vevida_optimizer_allow_major_core( $update ) {
	return get_option( 'vevida_optimizer_core_major_updates' );
}
add_filter( 'allow_major_auto_core_updates', 'vevida_optimizer_allow_major_core' );

function vevida_optimizer_allow_minor_core( $update ) {
	return get_option( 'vevida_optimizer_core_minor_updates' );
}
add_filter( 'allow_minor_auto_core_updates', 'vevida_optimizer_allow_minor_core' );

function vevida_optimizer_allow_plugin( $update, $item ) {
	return get_option( 'vevida_optimizer_plugin_'.$item->slug );
}
add_filter( 'auto_update_plugin', 'vevida_optimizer_allow_plugin', 10, 2 );

function vevida_optimizer_allow_theme( $update ) {
	return get_option( 'vevida_optimizer_theme_updates' );
}
add_filter( 'auto_update_theme', 'vevida_optimizer_allow_theme' );

function vevida_optimizer_allow_translation( $update ){
	return get_option( 'vevida_optimizer_translations_updates' );
}
add_filter( 'auto_update_translation', 'vevida_optimizer_allow_translation' );

/** Plugin defaults **/
function vevida_optimizer_init_plugin() {
	add_option( 'vevida_optimizer_core_major_updates', true );
	add_option( 'vevida_optimizer_core_minor_updates', true );
	add_option( 'vevida_optimizer_theme_updates', true );
	add_option( 'vevida_optimizer_translations_updates', true );
	add_option( 'vevida_optimizer_send_email', true );
	add_option( 'vevida_optimizer_admin_email', '' );
	$loaded_plugins = get_plugins();
	foreach ($loaded_plugins as $key => $val) {
		$plugin_array = explode( '/', $key );
		if ( is_array( $plugin_array ) ) {
			$plugin_slug = $plugin_array[0];
			add_option( 'vevida_optimizer_plugin_'.$plugin_slug, true );
		}
	}
}
add_action( 'admin_init', 'vevida_optimizer_init_plugin' );

/** Replace default email adress (admin_email) for update emails when configured */
function vevida_optimizer_update_email ( $email ) {
	$admin_email = get_option( 'vevida_optimizer_admin_email' );
	if ( $admin_email !== '' ) {
		$email['to'] = $admin_email;
	}
	return $email;
}

if ( get_option( 'vevida_optimizer_send_email') ) {
	add_filter( 'automatic_updates_send_debug_email', '__return_true' );
	add_filter( 'auto_core_update_email', 'vevida_optimizer_update_email' );
	add_filter( 'automatic_updates_debug_email', 'vevida_optimizer_update_email' );
}

/** Build admin pages, using Settings API **/

function vevida_optimizer_add_admin_pages() {
	/** Add Settings Page **/
	add_dashboard_page(
			'Update Settings',
			__( 'Update Settings', 'vevida-optimizer' ),
			'manage_options',
			'vevida-optimizer',
			'vevida_optimizer_settings_page'
	);
	/** Add Database Optimisation Page **/
	add_management_page(
			'Convert MySQL MyISAM tables to InnoDB',
			__( 'Convert MyISAM to InnoDB', 'vevida-optimizer' ),
			'manage_options',
			'vevida-optimizer-convert-myisam-innodb',
			'vevida_convert_db_tables' );
	add_management_page(
			'Optimize MySQL database tables',
			__( 'Optimize MySQL database tables', 'vevida-optimizer' ),
			'manage_options',
			'vevida-optimizer-optimize-db',
			'vevida_optimize_db_tables' );
}
add_action( 'admin_menu', 'vevida_optimizer_add_admin_pages' );

/** Settings Page Content **/
function vevida_optimizer_settings_page() {
	?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<svg class="gears" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path d="M960 896q0-106-75-181t-181-75-181 75-75 181 75 181 181 75 181-75 75-181zm768 512q0-52-38-90t-90-38-90 38-38 90q0 53 37.5 90.5t90.5 37.5 90.5-37.5 37.5-90.5zm0-1024q0-52-38-90t-90-38-90 38-38 90q0 53 37.5 90.5t90.5 37.5 90.5-37.5 37.5-90.5zm-384 421v185q0 10-7 19.5t-16 10.5l-155 24q-11 35-32 76 34 48 90 115 7 11 7 20 0 12-7 19-23 30-82.5 89.5t-78.5 59.5q-11 0-21-7l-115-90q-37 19-77 31-11 108-23 155-7 24-30 24h-186q-11 0-20-7.5t-10-17.5l-23-153q-34-10-75-31l-118 89q-7 7-20 7-11 0-21-8-144-133-144-160 0-9 7-19 10-14 41-53t47-61q-23-44-35-82l-152-24q-10-1-17-9.5t-7-19.5v-185q0-10 7-19.5t16-10.5l155-24q11-35 32-76-34-48-90-115-7-11-7-20 0-12 7-20 22-30 82-89t79-59q11 0 21 7l115 90q34-18 77-32 11-108 23-154 7-24 30-24h186q11 0 20 7.5t10 17.5l23 153q34 10 75 31l118-89q8-7 20-7 11 0 21 8 144 133 144 160 0 8-7 19-12 16-42 54t-45 60q23 48 34 82l152 23q10 2 17 10.5t7 19.5zm640 533v140q0 16-149 31-12 27-30 52 51 113 51 138 0 4-4 7-122 71-124 71-8 0-46-47t-52-68q-20 2-30 2t-30-2q-14 21-52 68t-46 47q-2 0-124-71-4-3-4-7 0-25 51-138-18-25-30-52-149-15-149-31v-140q0-16 149-31 13-29 30-52-51-113-51-138 0-4 4-7 4-2 35-20t59-34 30-16q8 0 46 46.5t52 67.5q20-2 30-2t30 2q51-71 92-112l6-2q4 0 124 70 4 3 4 7 0 25-51 138 17 23 30 52 149 15 149 31zm0-1024v140q0 16-149 31-12 27-30 52 51 113 51 138 0 4-4 7-122 71-124 71-8 0-46-47t-52-68q-20 2-30 2t-30-2q-14 21-52 68t-46 47q-2 0-124-71-4-3-4-7 0-25 51-138-18-25-30-52-149-15-149-31v-140q0-16 149-31 13-29 30-52-51-113-51-138 0-4 4-7 4-2 35-20t59-34 30-16q8 0 46 46.5t52 67.5q20-2 30-2t30 2q51-71 92-112l6-2q4 0 124 70 4 3 4 7 0 25-51 138 17 23 30 52 149 15 149 31z"/></svg>
		<h1><?php _e( 'Automatic update settings', 'vevida-optimizer' ); ?></h1>
		<p><?php _e( "It is possible to disable the different kinds of automatic updates. Also, updates for specific plugins can be disabled. Only use this option when automatically updating a plugin is not possible or problematic.", 'vevida-optimizer' ); ?> </p>
		<form method="post" action="options.php">
			<?php
				do_settings_sections( 'vevida_optimizer_settings' );
				settings_fields( 'vevida_optimizer_settings_group' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/** Settings Form Initialization **/
function vevida_optimizer_settings_init() {

	/** Setting section 1, automatic updates. **/
	add_settings_section(
		'vevida_optimizer_settings_section_1',
		__( 'Enable or disable automatic updates', 'vevida-optimizer' ),
		'vevida_optimizer_settings_section_1_callback',
		'vevida_optimizer_settings'
	);
	add_settings_field(
		'vevida_optimizer_core_major_updates',
		__( 'Update to new major version', 'vevida-optimizer' ),
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_1',
		array (
			'vevida_optimizer_core_major_updates',
			__( 'e.g. WordPress 4.4 to 4.5', 'vevida-optimizer' ) )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_core_major_updates' );
	add_settings_field(
		'vevida_optimizer_core_minor_updates',
		__( 'Update to new minor version', 'vevida-optimizer' ),
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_1',
		array (
			'vevida_optimizer_core_minor_updates',
			__( 'e.g. WordPress 4.4.1 to 4.4.2', 'vevida-optimizer' )  )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_core_minor_updates' );
	add_settings_field(
		'vevida_optimizer_theme_updates',
		__( 'Update themes', 'vevida-optimizer' ),
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_1',
		array (
			'vevida_optimizer_theme_updates',
			'' )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_theme_updates' );
	add_settings_field(
		'vevida_optimizer_translations_updates',
		__( 'Update Translations', 'vevida-optimizer' ),
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_1',
		array (
			'vevida_optimizer_translations_updates',
			'' )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_translations_updates' );

	/** Setting section 2, exclude specific plugins. **/
	add_settings_section(
		'vevida_optimizer_settings_section_2',
		__( 'Enable or disable plugin updates', 'vevida-optimizer' ),
		'vevida_optimizer_settings_section_2_callback',
		'vevida_optimizer_settings'
	);

	$loaded_plugins = get_plugins();
	foreach ($loaded_plugins as $key => $val) {
		$plugin_array = explode( '/', $key );
		if ( is_array( $plugin_array ) ) {
			$plugin_slug = $plugin_array[0];
			add_settings_field(
				'vevida_optimizer_plugin_'.$plugin_slug,
				$val['Name'],
				'vevida_optimizer_checkbox_callback',
				'vevida_optimizer_settings',
				'vevida_optimizer_settings_section_2',
				array (
						'vevida_optimizer_plugin_'.$plugin_slug,
						'' )
			);
			register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_plugin_'.$plugin_slug );
		}
	}

	/** Setting section 3, enable emails after update. **/
	add_settings_section(
		'vevida_optimizer_settings_section_3',
		__( 'Send email notifications', 'vevida-optimizer' ),
		'vevida_optimizer_settings_section_3_callback',
		'vevida_optimizer_settings'
	);
	add_settings_field(
		'vevida_optimizer_send_email',
		__( 'Enable notifications', 'vevida-optimizer' ),
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_3',
		array (
			'vevida_optimizer_send_email',
			'' )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_send_email' );
	add_settings_field(
		'vevida_optimizer_admin_email',
		__( 'Email address', 'vevida-optimizer' ),
		'vevida_optimizer_input_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_3',
		array (
			'email',
			'vevida_optimizer_admin_email',
			__( 'Leave empty to use the default admin email address', 'vevida-optimizer' )
					. ' (' . get_option( 'admin_email' ) . ')'
		)
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_admin_email', 'vevida_optimizer_validate_email' );
}
add_action( 'admin_init', 'vevida_optimizer_settings_init' );

/** Format Callbacks **/
function vevida_optimizer_settings_section_1_callback() {
	echo '<div class="description">';
	_e( 'All updates are enabled by default. Only change this if your website experiences issues after an automatic update. In that case, resolve the issue that blocks the automatic update process, and reenable automatic updates.', 'vevida-optimizer' );
	echo '</div>';
}
function vevida_optimizer_settings_section_2_callback() {
	echo '<div class="description">';
	_e( 'Some plugins require a different update method. Or the plugin simpy breaks as a result of the update. In that case automatic updates for the plugin can be (temporarily) disabled.', 'vevida-optimizer' );
	echo '</div>';
}
function vevida_optimizer_settings_section_3_callback() {
	echo '<div class="description">';
	_e( 'An email can be sent after each automatic update to notify the site admin of the update. This can be useful in troubleshooting the site after an automatic update.', 'vevida-optimizer' );
	echo '</div>';
}

function vevida_optimizer_checkbox_callback( $args ) {
	$option = get_option( $args[0] );
	$html = '<input type="checkbox" id="'.$args[0].'" name="'.$args[0].'" value="1"' . checked( 1, $option, false ) . '/>';
	$html .= '<label for="'.$args[0].'">'.$args[1].'</label>';
	echo $html;
}

function vevida_optimizer_input_callback( $args ) {
	$option = get_option( $args[1] );
	$html = '<input type="' . $args[0] . '" id="' . $args[1] . '"' .
			' name="' . $args[1] . '" value="' . $option . '" />';
	if (count($args) === 3) {
		$html .= '<br/>' . $args[2];
	}
	echo $html;
}

function vevida_optimizer_validate_email( $email ) {
	if ( $email === '' ) { // allow empty
		return '';
	}
	$validated_email = filter_var( $email, FILTER_VALIDATE_EMAIL );
	if ( $validated_email === false ) {
		add_settings_error( 'vevida_optimizer_settings', 'invalid-email',
				__( 'You have entered an invalid email address' ) );
		return '';
	}
	return $validated_email;
}

//Adds settings link on Installed Plugins page
function vevida_optimizer_plugin_link_settings($links) {
	$settings_link = '<a href="index.php?page=vevida-optimizer">'.__( 'Settings', 'vevida-optimizer' ).'</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'vevida_optimizer_plugin_link_settings' );
