<?php
/**
 * Don't allow this file to be loaded directly
 */
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

function convert_db_tables() {
    ?>
        <div class="wrap">
            <h2>Convert MySQL MyISAM tables to InnoDB</h2>
            <p>This plugin will convert your old MyISAM MySQL database tables to the InnoDB storage engine.</p>
            <p>In the earlier days of MySQL, the default storage engine for your database was MyISAM. This is why you still encounter a lot of examples with <code>engine=MyISAM</code> online. Nowadays, the InnoDB storage engine is MySQL's default. MyISAM is no longer actively developed, InnoDB is. Therefor, most <a href="http://www.saotn.org/mysql-55-innodb-performance-improvement/" title="MySQL 5.5 InnoDB performance improvement" target="_blank">MySQL performance optimizations</a> are for the InnoDB engine and it's wise to choose this as your table storage engine.</p>
            <p>Please note, the performance gain depends on your web hosting company's MySQL server configuration. Contact your hosting provider for more information about the specific MySQL (InnoDB storage engine) set up. If you want to know more about this converting process, see my blog post on how to <a href="http://www.saotn.org/convert-mysql-myisam-tables-innodb/" title="convert MySQL MyISAM tables to InnoDB" target="_blank">convert MySQL MyISAM tables to InnoDB</a>, and how to <a href="http://www.saotn.org/optimize-all-mysql-tables-with-mysqli-multi_query/" title="Optimize all MySQL tables with MySQLi multi_query" target="_blank">optimize all MySQL tables with MySQLi multi_query</a>.</p>
            <p>The plugin tries to be as gentle as possible, however, you use this plugin at your own risk!</p>
            <p>As a bonus, the plugin optimizes the WordPress <code>wp_options</code> table with an index on the autoload column too. More on that <a href="http://www.saotn.org/wordpress-wp-options-table-autoload-micro-optimization/" title="WordPress wp_options table autoload micro-optimization" target="_blank">here</a>.</p>
            <p>&nbsp;</p>
            <script type="text/javascript" >
            jQuery(document).ready(function($) {
                $(document).on('click', '#vevida_optimizer_convert', function(e) {
                    e.preventDefault();         
                    var data = {
                            'action': 'vevida-optimizer-convertMyisamToInnodb',
                            '_ajax_nonce': '<?php echo wp_create_nonce( 'vevida-optimizer-nonce' ); ?>'
                    };

                    $.post( ajaxurl, data, function( response ) {
                        document.getElementById('vevida-optimizer-message').innerHTML = response;
                    });
                });
            });
            </script>
            <input type="button" id="vevida_optimizer_convert" class="button button-primary" value="Convert my MySQL tables" />
            <div id="vevida-optimizer-message"></div>
        </div>
    <?php
}

add_action( 'wp_ajax_vevida-optimizer-convertMyisamToInnodb', 'vevida_optimizer_convertMyisamToInnodb' );
function vevida_optimizer_convertMyisamToInnodb() {
    check_ajax_referer( 'vevida-optimizer-nonce' );
    if ( !convertTables() ) {
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

function convertTables() {
    global $wpdb;
    foreach ( $wpdb->get_results("SELECT table_name FROM information_schema.tables WHERE ENGINE = 'MyISAM' AND  table_name LIKE '{$wpdb->prefix}%'")  as $key => $row) {
        $fulltextIndex = $wpdb->get_results("SELECT
			table_schema,
			table_name
			FROM information_schema.statistics
			WHERE index_type = 'FULLTEXT'
			AND table_name = ".'{$row}');
	if ( $fulltextIndex ) {
            continue;
	}
        $wpdb->query("ALTER TABLE `{$row->table_name}` ENGINE=InnoDB");
    }

    $indexResults = $wpdb->get_results("SHOW INDEXES FROM `{$wpdb->options}` WHERE Column_name='autoload'");
    if (!$indexResults) {
        $addedIndex = $wpdb->query("ALTER TABLE `{$wpdb->options}` ADD INDEX autoload(`autoload`)");
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
