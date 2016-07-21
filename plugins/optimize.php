<?php
/**
 * Don't allow this file to be loaded directly
 */
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

function vevida_optimize_db_tables() {
    ?>
        <div class="wrap">
            <h2><?php _e( 'Optimize MySQL tables', 'vevida-optimizer' ); ?></h2>

            <p><?php _e( 'Keeping your WordPress database optimized is <em>a must</em> for a well performing WordPress website. Your plugin authors over at ','vevida-optimizer' ); ?>
			<a href="https://vevida.com" title="WordPress hosting made easy by Vevida"><?php _e( 'Vevida', 'vevida-optimizer' ); ?></a>
			<?php _e( 'care about your site performance.', 'vevida-optimizer' ); ?></p>
            <p><?php _e( 'Every WordPress post, draft, revision and comment adds data to your MySQL database, and removing revisions, drafts and comments creates empty space between data. This makes your database bigger and more fragmented. Defragmenting the data puts all data back in order and removes empty space. This results in a smaller database. The defragmented database performs faster because MySQL will be able to locate information much faster. ', 'vevida-optimizer' ); ?>
            <?php _e( 'Pressing the button below performs an MySQL optimization by executing an <code>OPTIMIZE TABLE</code> statement on all WordPress MySQL tables. All other, non-WordPress tables remain untouched.', 'vevida-optimizer' ); ?>
            <p>&nbsp;</p>
			
            <script type="text/javascript" >
            jQuery(document).ready(function($) {
                $(document).on('click', '#vevida_optimizer_optimize', function(e) {
                    e.preventDefault();         
                    var data = {
                            'action': 'vevida-optimizer-optimize-db',
                            '_ajax_nonce': '<?php echo wp_create_nonce( 'vevida-optimizer-nonce' ); ?>'
                    };

                    $.post( ajaxurl, data, function( response ) {
                        document.getElementById('vevida-optimizer-optimize').innerHTML = response;
                    });
                });
            });
            </script>
            <input type="button" id="vevida_optimizer_optimize" class="button button-primary" value="<?php _e( 'Optimize MySQL database tables', 'vevida-optimizer' ); ?>" />
            <div id="vevida-optimizer-optimize"></div>
        </div>
    <?php
}

add_action( 'wp_ajax_vevida-optimizer-optimize-db', 'vevida_optimizer_optimize_db' );

function vevida_optimizer_optimize_db() {
    check_ajax_referer( 'vevida-optimizer-nonce' );
    if ( !vevida_optimize_tables() ) {
        echo '<h2>';
        _e( 'Whoops, error!', 'vevida-optimizer' );
        echo '</h2><p>';
        _e( 'Turns out DB optimization went wrong... Please check your PHP error log file.', 'vevida-optimizer' );
        echo '</p>';
    } else {
        echo '<h2>';
        _e( 'MySQL database optimization successful!', 'vevida-optimizer' );
        echo '</h2><p>';
        _e( 'You MySQL database tables were successfully optimized!', 'vevida-optimizer' );
        echo '</p>';
    }
    wp_die();
}

function vevida_optimize_tables() {
	global $wpdb;
	$tables = $wpdb->get_col( "SHOW TABLES" );
	foreach ( $tables as $table ) {
		$wpdb->query( "OPTIMIZE TABLE $table" );
	}
	return true;
}
?>
