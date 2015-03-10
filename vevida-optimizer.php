<?php
/**
 * Plugin Name: Vevida Optimizer
 * Description: The Vevida Optimizer plugin is enabled by default on the WordPress hosting platform at Vevida. The plugin introduces automatic updates for all WordPress components, such as the WordPress core, themes and plugins. Also, the plugin introduces database improvements for upgraded WordPress sites. Updated WordPress sites are less vulnerable to attacks, offer better performance and allow us to offer a safer webhosting platform.
 * Version: 0.2
 * Author: Jan Vlastuin, Jan Reilink
 * Author URI: vevida.hosting
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
add_filter( 'allow_major_auto_core_updates', 'vevida_optimizer_allow_minor_core' );

function vevida_optimizer_allow_plugin( $update, $item ) {
    return get_option( 'vevida_optimizer_plugin_'.$item->slug );
}
add_filter( 'auto_update_plugin', 'vevida_optimizer_allow_plugin', 10, 2 );

function vevida_optimizer_allow_theme( $update ) {
    return get_option( 'vevida_optimizer_theme_updates' );
}
add_filter( 'auto_update_theme', 'vevida_optimizer_allow_theme' );


/** Plugin defaults **/
function vevida_optimizer_init_plugin() {
    add_option( 'vevida_optimizer_core_major_updates', true );
    add_option( 'vevida_optimizer_core_minor_updates', true );
    add_option( 'vevida_optimizer_theme_updates', true );
    $loaded_plugins = get_plugins();
    foreach ($loaded_plugins as $key => $val) {
        $plugin_slug = explode( '/', $key )[0];
        add_option( 'vevida_optimizer_plugin_'.$plugin_slug, true );
    }
}
add_action( 'admin_init', 'vevida_optimizer_init_plugin' );


/** Build admin pages, using Settings API **/

/** Add Settings Page **/
function vevida_optimizer_add_admin_pages() {
	add_dashboard_page( 
		'Update Settings', 
		__( 'Update Settings', 'vevida-optimizer' ), 
		'manage_options', 
		'vevida-optimizer', 
		'vevida_optimizer_settings_page'
	);
}
add_action( 'admin_menu', 'vevida_optimizer_add_admin_pages' );
 
/** Settings Page Content **/
function vevida_optimizer_settings_page() {
    ?>
    <div class="wrap">
        <?php settings_errors(); ?>
 
        <h2><?php _e( 'Automatic update settings', 'vevida-optimizer' ); ?></h2>
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
			__( 'e.g. WordPress 4.1 to 4.2', 'vevida-optimizer' ) )
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
			__( 'e.g. WordPress 4.1 to 4.1.1', 'vevida-optimizer' )  )
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
 
	/** Setting section 2, exclude specific plugins. **/
	add_settings_section(
		'vevida_optimizer_settings_section_2',
		__( 'Enable or disable plugin updates', 'vevida-optimizer' ),
		'vevida_optimizer_settings_section_2_callback',
		'vevida_optimizer_settings'
	);
        
        $loaded_plugins = get_plugins();
        foreach ($loaded_plugins as $key => $val) {
//            add_option( 'vevida_optimizer_plugin_'.$key, true );
            $plugin_slug = explode( '/', $key )[0];
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
add_action( 'admin_init', 'vevida_optimizer_settings_init' );
 
/** Format Callbacks **/
function vevida_optimizer_settings_section_1_callback() {
    echo( __( 'All updates are enabled by default. Only change this if your website experiences issues after an automatic update. In that case, resolve the issue that blocks the automatic update process, and reenable automatic updates.', 'vevida-optimizer' ) );
}
function vevida_optimizer_settings_section_2_callback() {
    echo( __( 'Some plugins require a different update method. Or the plugin simpy breaks as a result of the update. In that case automatic updates for the plugin can be (temporarily) disabled.', 'vevida-optimizer' ) );
}
function vevida_optimizer_checkbox_callback( $args ) {
    $option = get_option( $args[0] );
    $html = '<input type="checkbox" id="'.$args[0].'" name="'.$args[0].'" value="1"' . checked( 1, $option, false ) . '/>';
    $html .= '<label for="'.$args[0].'">'.$args[1].'</label>';

    echo $html;
}

//Adds settings link on Installed Plugins page
function vevida_optimizer_plugin_link_settings($links) { 
  $settings_link = '<a href="index.php?page=vevida-optimizer">'.__( 'Settings', 'vevida-optimizer' ).'</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter( "plugin_action_links_$plugin", 'vevida_optimizer_plugin_link_settings' );
