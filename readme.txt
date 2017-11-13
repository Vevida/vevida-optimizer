=== Plugin Name ===
Contributors: vlastuin, janr
Tags: auto-update, updates, MySQL optimization, update, automatic update, vevida, hosting
Requires at least: 3.9
Tested up to: 4.9
Stable tag: 1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily configure automatic updates from the admin interface and modernize your 
MySQL database.

== License ==
Released under the terms of the GNU General Public License.

== Description ==

Installing WordPress is one thing, keeping it up to date is something else. Each week brings new bugs or potential attack scenarios that will make a WordPress website vulnerable to hacks. Enabling automatic updates for all or at least most parts of WordPress solves a large number of problems with irregularly maintained WordPress websites.
 
This plugin extends the automatic update feature already present in WordPress.  The core updates can be switched on or off, themes and translations can be automatically updated, and the plugin updates can be configured on a per-plugin basis. 

Many websites started originally with older versions of WordPress. Previously those installs used older versions of MySQL, when the default table format was MyISAM. Nowadays, modern versions of MySQL use the InnoDB format, which is currently enabled by default. Through this plugin the database tables can be optimized for those newer versions of MySQL, converting older MyISAM tables to InnoDB.  This is required only once, and only when you have been using WordPress for a long time or with a hosting provider that has not actively kept its MySQL installations up to date.

Vevida is a major webhosting provider based in The Netherlands. We have been hosting websites since 1997. We offer specialized WordPress hosting, which includes this plugin by default. This plugin is useful for all WordPress users, so we make the latest version of this plugin available in the WordPress repository. The source code is also freely available on GitHub.  

== Installation ==

1. Upload the package contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure automatic updates through 'Dashboard' -> 'Update Settings'
1. Optimize your MySQL database through 'Tools' -> 'Convert DB tables'

Or login to WordPress. Go to Plugins -> Add New and search for Vevida. The search box is located at the top right of the page. Click Install and enjoy automatic updates.

== Frequently Asked Questions ==

= How can I configure Auto-Updates? =

Go to 'Dashboard' -> 'Update Settings'. The core updates can be switched on or off, themes and translations can be automatically updated, and individual plugin updates can also be configured.

= Why would I use Auto-Updates? =

Not updating your WordPress site regularly exposes your site and your hosting provider to bugs and other attack vectors that can enable an attacker to hack into your website. Keeping your WordPress website up-to-date is one of the key ingredients to keeping your website secure.

= Why would I not use Auto-Updates? =

If you are an expert user of WordPress, and you are always available to test each and every new version of each and every plugin, theme and core update before deploying them to your server, then you don't need this plugin. However, the plugin offers a unique selection mechanism whereby only those parts you want to auto-update, will in fact auto-update.

= Why has this or that plugin not been updated yet? =

First check whether automatic updates are enabled for the plugin in 'Dashboard' -> 'Update Settings'. If automatic updates are enabled, it can take up to 12 hours for a plugin to actually update.

= How can I optimize my database tables =

Go to Tools -> Convert DB tables. This will launch the utility that converts MyISAM tables to InnoDB.

= Why would I convert my database tables? =

Many older versions of MySQL used MyISAM tables by default. Nowadays InnoDB is used by recent versions of MySQL, and this is a much faster format. If you have created your WordPress site in the past on previous versions of MySQL, chances are that you still use MyISAM.

= Can I use this plugin on hosting platforms other than at vevida.com? =

Of course you can, and we encourage you to use our plugin to keep your website up to date. That's why we made it available in the WordPress repository, and the source code is available on GitHub: https://github.com/vlastuin/vevida-optimizer

== Screenshots ==

1. The submenu under 'Dashboard' that allows the configuration of automatic updates.

== Changelog ==

= 1.2 =
Release date: November 13th 2017
* Added a spinner to indicate that the conversion process is running
* Added the option to add a different e-mail address to send update notifications to

= 1.1.5 =
Release date: June 17th 2017
* Fix: Table conversion bug solved
* Adapted readme.txt to new WordPress website
* Compatibility with WP 4.8

= 1.1.4 = 
Release date: October 19th 2016

* Fix: removed more unnecessary curly braces
* Change: removed unwanted newlines

= 1.1.3 =
Release date: October 19th 2016

* Bug fix: InnoDB conversion did not complete correctly for all tables (thanks to 
  simonmm)

= 1.1.2 =
Release date: August 17th 2016

* Bug fix: Auto-update for minor version updates could not be disabled seperately
  (thanks to noplanman)
* New: German translation (thanks to hofmannsven)

= 1.1.1 = 
Release date: June 26th 2016

* Bug fixed in the InnoDB conversion function where InnoDB tables have a 
  fulltextindex.
* Tested with WordPress 4.5.3

= 1.1 =
Release date: April 7th 2016

* Tested with WordPress 4.5
* New: major overhaul, Vevida Optimizer now supports its own plugins. Drop your 
  extension in the plugins/ directory. You do have to add your own plugin to the
  WordPress Administration Menu in vevida-optimizer.php, see 
  https://codex.wordpress.org/Administration_Menus
* New: plugins/optimize.php, to perform a manual OPTIMIZE TABLE statement on 
  WordPress database tables.
* Fix: renamed convert_2_innodb.php to convert.php and moved to plugins/.
* Fix: renamed functions to a more standard form.
* Fix: language updates.

= 1.0.15 =
Release date: January 11th 2016

* Tested with WordPress 4.4

= 1.0.14 =
Release date: August 9th 2015

* Tested  with WordPress 4.3
* fix: minor language updates
* New installation instructions

= 1.0.13 =
Release date: April 21th 2015

* fix: activation bug fixed when used on PHP 5.3

= 1.0.12 =
Release date: April 21th 2015

* new: Addition of uninstall.php, to tidy up wp_options table after uninstall
* fix: Translation updates activated, due to a bug language updates were disabled

= 1.0.11 =
Release date: April 20th 2015

* Updates in plugin details
* fix: Error when activating plugin if get_plugins returns uncommon plugin names

= 1.0.10 =
Release date: April 16th 2015

* Language update
* fix: Proper register setting for email notifications 

= 1.0.9 =
Release date: April 1st 2015

* Fixed comments and code standard
* More language strings added to conversion function
* Full nl_NL support

= 1.0.8 =
Release date: March 30th 2015

* Added email function to send email with the update results after update
* Email function configurable in settings
* New translation strings added

= 1.0.7 =
Release date: March 26th 2015

* Fixed version numbering

= 1.0.6 =
Release date: March 26th 2015

* Include Ajax-nonce

= 1.0.5 =
Release date: March 26th 2015

* Update FAQ
* Rewrite InnoDB conversion using AJAX request
* Skip InnoDB conversion on fulltext indexed tables
* Removed unused function

= 1.0.4 =
Release date: March 21th 2015

* Rewrite of readme.txt
* Minor NL language updates 

= 1.0.3 =
Release date: March 20th 2015

* Minor language improvements 
* version number fix.

= 1.0.2 =
Release date: March 11th 2015 

* Now includes an admin page based on the Settings API.
