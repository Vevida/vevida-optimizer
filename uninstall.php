<?php
if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'vevida_optimizer%'");
}
