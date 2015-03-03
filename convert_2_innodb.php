	add_action('admin_menu', 'convert_myisam_to_innodbgenerate_page');
	function convert_myisam_to_innodbgenerate_page() {
		if(function_exists('add_submenu_page')) add_submenu_page('tools.php', __('Convert DB tables'),
			__('Convert DB tables'), 'manage_options', 'ConvertMyisamToInnodb_settings_page', 'convert_db_tables');
	}

	function convert_db_tables() {
		global $status;
		if ( $_POST['submit'] ) {
			$st = convertTables();
			if (!$st) {
				$html = '<div class="wrap">
					<h2>Whoops, error!</h2>
					<p>Turns out something went wrong... Please check your PHP error log file.</p>
					</div>';
				echo $html;
				exit();
			}
			?>
			<div class="wrap">
				<h2>Database convert complete!</h2>
				<p>Either your database tables were already created with the InnoDB storage engine, or the convert process is completed successfully.</p>
				<p>You can now <a href="plugins.php">deactivate</a> this plugin.</p>
			</div>
			<?php
		}
		else { ?>
			<div class="wrap">
				<div style="width:800px; padding:10px 20px; background-color:#eee; font-size: 14px; margin:20px">
					<h2>Convert MySQL MyISAM tables to InnoDB</h2>
					<p class="lead">This plugin will convert your old MyISAM MySQL database tables to the InnoDB storage engine.</p><p>In the earlier days of MySQL, the default storage engine for your database was MyISAM. This is why you still encounter a lot of examples with <code>engine=MyISAM</code> online. Nowadays, the InnoDB storage engine is MySQL's default. MyISAM is no longer actively developed, InnoDB is. Therefor, most <a href="http://www.saotn.org/mysql-55-innodb-performance-improvement/" title="MySQL 5.5 InnoDB performance improvement" target="_blank">MySQL performance optimizations</a> are for the InnoDB engine and it's wise to choose this as your table storage engine.</p>
					<p>Please note, the performance gain depends on your web hosting company's MySQL server configuration. Contact your hosting provider for more information about the specific MySQL (InnoDB storage engine) set up. If you want to know more about this converting process, see my blog post on how to <a href="http://www.saotn.org/convert-mysql-myisam-tables-innodb/" title="convert MySQL MyISAM tables to InnoDB" target="_blank">convert MySQL MyISAM tables to InnoDB</a>, and how to <a href="http://www.saotn.org/optimize-all-mysql-tables-with-mysqli-multi_query/" title="Optimize all MySQL tables with MySQLi multi_query" target="_blank">optimize all MySQL tables with MySQLi multi_query</a>.</p><p>The plugin tries to be as gentle as possible, however: you use this plugin at your own risk!</p>

					<p>As a bonus, the plugin optimizes the WordPress <code>wp_options</code> table with an index on the autload column too. More on that <a href="http://www.saotn.org/wordpress-wp-options-table-autoload-micro-optimization/" title="WordPress wp_options table autoload micro-optimization" target="_blank">here</a>.</p>
					<p>&nbsp;</p>
	
					<form id="options_form" method="post" action="">
					    <div class="submit">
							<input type="submit" name="submit" id="sb_submit" value="Convert my MySQL tables" />
						</div>
					</form></p>
				</div>
			</div>
		<?php
		}
	}

    function convertTables() {
		global $wpdb;
		foreach ( $wpdb->get_results("SELECT table_name FROM information_schema.tables WHERE ENGINE = 'MyISAM' AND  table_name LIKE '{$wpdb->prefix}%'")  as $key => $row) {
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

	function optimizeTables () {
		//
	}
?>
