<?php
/**
 * Don't allow this file to be loaded directly
 */
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

function vevida_convert_db_tables() {
    ?>
        <div class="wrap">
            <h2><?php _e( 'Convert MySQL MyISAM tables to InnoDB', 'vevida-optimizer' ); ?></h2>
            <p><?php _e( 'This plugin will convert your old MyISAM MySQL database tables to the InnoDB storage engine.','vevida-optimizer' )?></p>
            <p><?php _e( 'In the earlier days of MySQL, the default storage engine for your database was MyISAM. This is why you still encounter a lot of examples with <code>engine=MyISAM</code> online. Nowadays, the InnoDB storage engine is MySQL\'s default. MyISAM is no longer actively developed, InnoDB is. Therefore, most ', 'vevida-optimizer' ); ?>
            <a href="http://www.saotn.org/mysql-55-innodb-performance-improvement/" title="MySQL 5.5 InnoDB performance improvement" target="_blank"><?php _e( 'MySQL performance optimizations', 'vevida-optimizer' ); ?></a> 
            <?php _e( 'are for the InnoDB engine and it\'s wise to choose this as your table storage engine.', 'vevida-optimizer' ); ?></p>
            <p><?php _e( 'Please note, the performance gain depends on your web hosting company\'s MySQL server configuration. Contact your hosting provider for more information about the specific MySQL (InnoDB storage engine) set up. If you want to know more about this conversion process, see my blog post on how to', 'vevida-optimizer' ); ?> 
            <a href="http://www.saotn.org/convert-mysql-myisam-tables-innodb/" title="convert MySQL MyISAM tables to InnoDB" target="_blank"><?php _e( 'convert MySQL MyISAM tables to InnoDB', 'vevida-optimizer' ); ?></a>, <?php _e( 'and how to', 'vevida-optimizer' ); ?>
            <a href="http://www.saotn.org/optimize-all-mysql-tables-with-mysqli-multi_query/" title="Optimize all MySQL tables with MySQLi multi_query" target="_blank"><?php _e( 'optimize all MySQL tables with MySQLi multi_query', 'vevida-optimizer' ); ?></a>.</p>
            <p><?php _e( 'The plugin tries to be as gentle as possible, however, you use this plugin at your own risk!', 'vevida-optimizer' ); ?></p>
            <p><?php _e( 'As a bonus, the plugin optimizes the WordPress <code>wp_options</code> table with an index on the autoload column too. More on that', 'vevida-optimizer' ); ?>
            <a href="http://www.saotn.org/wordpress-wp-options-table-autoload-micro-optimization/" title="WordPress wp_options table autoload micro-optimization" target="_blank"><?php _e( 'here', 'vevida-optimizer' ); ?></a>.</p>
            <p>&nbsp;</p>
            <script type="text/javascript" >
            jQuery(document).ready(function($) {
                $(document).on('click', '#vevida_optimizer_convert', function(e) {
                    e.preventDefault();         
                    var data = {
                            'action': 'vevida-optimizer-convert-myisam-innodb',
                            '_ajax_nonce': '<?php echo wp_create_nonce( 'vevida-optimizer-nonce' ); ?>'
                    };

                    var submitButton = document.getElementById('vevida_optimizer_convert');
                    var feedbackElement = document.getElementById('vevida-optimizer-convert');
                    submitButton.setAttribute('disabled', 'disabled');
                    feedbackElement.innerHTML = '<p><img src="<?php echo VEVIDAOPTIMIZERURL; ?>public/images/lader-logo.gif" alt="Please wait..."/></p>';
                    $.post( ajaxurl, data, function( response ) {
                        submitButton.removeAttribute('disabled');
                        feedbackElement.innerHTML = response;
                    });
                });
            });
            </script>
            <input type="button" id="vevida_optimizer_convert" class="button button-primary" value="<?php _e( 'Convert my MySQL tables', 'vevida-optimizer' ); ?>" />
            <div id="vevida-optimizer-convert"></div>
        </div>
    <?php
}

add_action( 'wp_ajax_vevida-optimizer-convert-myisam-innodb', 'vevida_optimizer_convert_myisam_innodb' );

function vevida_optimizer_convert_myisam_innodb() {
    check_ajax_referer( 'vevida-optimizer-nonce' );
    if ( !vevida_convert_tables() ) {
        echo '<h2>';
        _e( 'Whoops, error!', 'vevida-optimizer' );
        echo '</h2><p>';
        _e( 'Turns out something went wrong... Please check your PHP error log file.', 'vevida-optimizer' );
        echo '</p>';
    } else {
        echo '<h2>';
        _e( 'Database convert complete!', 'vevida-optimizer' );
        echo '</h2><p>';
        _e( 'Either your database tables were already created with the InnoDB storage engine, or the convert process is completed successfully.', 'vevida-optimizer' );
        echo '</p>';
    }
    wp_die();
}

function vevida_convert_tables() {
    global $wpdb;
    foreach ( $wpdb->get_results("SELECT table_name FROM information_schema.tables WHERE ENGINE = 'MyISAM' AND table_name LIKE '$wpdb->prefix%'")  as $key => $row) {
        $fulltextIndex = $wpdb->get_results("SELECT
			table_schema,
			table_name
			FROM information_schema.statistics
			WHERE index_type = 'FULLTEXT'
			AND table_name = '$row->table_name'");
	if ( $fulltextIndex ) {
            continue;
	}
        $wpdb->query("ALTER TABLE {$row->table_name} ENGINE=InnoDB");
    }

    $indexResults = $wpdb->get_results("SHOW INDEXES FROM {$wpdb->options} WHERE Column_name='autoload'");
    if (!$indexResults) {
        $addedIndex = $wpdb->query("ALTER TABLE {$wpdb->options} ADD INDEX autoload(`autoload`)");
        if (!$addedIndex) {
            // ALTER TABLE returned an error
            $wpdb->show_errors();
            $wpdb->print_error();				
            return false;
        }
        else {
            // index added
            return true;
        }
    }
    else {
        // wp_options autoload already indexed
        return true;
    }
}
