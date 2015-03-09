<?php
/**
 * Plugin Name: Vevida Optimizer
 * Description: De Vevida WordPress plugin wordt standaard geïnstalleerd in de hostingomgeving van Vevida. Deactiveren van deze plugin is mogelijk, maar wordt afgeraden. Voor een optimale veiligheid van WordPress en onze hostingomgeving verzorgt deze plugin het automatisch bijwerken van WordPress, andere plugins en thema's. Veilige WordPress hosting, gewoon bij Vevida.
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
 
        <h2><?php __( 'Automatic update settings', 'vevida-optimizer' ); ?></h2>
        <p><?php _e( "Het is mogelijk om de verschillende soorten automatische updates uit te schakelen. Ook is het mogelijk het bijwerken van specifieke plugins uit te schakelen. Doe dit alleen als het automatisch uitvoeren van updates problematisch is.", 'vevida-optimizer' ); ?> </p>

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
		'Werk minor versies bij',
		'vevida_optimizer_checkbox_callback',
		'vevida_optimizer_settings',
		'vevida_optimizer_settings_section_1',
		array (	
			'vevida_optimizer_core_minor_updates', 
			'Bijvoorbeeld: van WordPress versie 4.0 naar 4.0.1' )
	);
	register_setting( 'vevida_optimizer_settings_group', 'vevida_optimizer_core_minor_updates' );
	add_settings_field(
		'vevida_optimizer_theme_updates',
		'Werk thema\'s bij',
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
		'Bijwerken van plugins in- of uitschakelen',
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
    echo( 'Standaard zijn alle soorten updates geactiveerd. Wijzig dit alleen als u problemen ondervindt bij het automatisch bijwerken. Los in dat geval het probleem op, en heractiveer het automatisch bijwerken.' );
}
function vevida_optimizer_settings_section_2_callback() {
    echo( 'Sommige plugins vereisen een afwijkende methode voor het bijwerken. Of de plugin gaat simpelweg stuk als gevolg van de update. In dat geval kan het automatisch bijwerken van die plugin (tijdelijk) worden uitgeschakeld.' );
}
function vevida_optimizer_checkbox_callback( $args ) {
    $option = get_option( $args[0] );
    $html = '<input type="checkbox" id="'.$args[0].'" name="'.$args[0].'" value="1"' . checked( 1, $option, false ) . '/>';
    $html .= '<label for="'.$args[0].'">'.$args[1].'</label>';

    echo $html;
}

//Adds settings link on Installed Plugins page
function vevida_optimizer_plugin_link_settings($links) { 
  $settings_link = '<a href="index.php?page=vevida-optimizer">Instellingen</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter( "plugin_action_links_$plugin", 'vevida_optimizer_plugin_link_settings' );
