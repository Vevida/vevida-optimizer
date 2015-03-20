=== Plugin Name ===
Contributors: vlastuin, janr
Tags: auto-update
Requires at least: 3.9
Tested up to: 4.1.1
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Configurable Auto-Updates and Database Optimization

== License ==
Released under the terms of the GNU General Public License.

== Description ==

This plugin extends the automatic update feature already present in WordPress. The core updates can be switched on or off, themes and translations can be automatically updated, and the plugin updates can be configured on a per-plugin basis.
Also, through this plugin the database tables can be optimized for newer versions of mySQL, converting older myISAM tables to InnoDB.

== Installation ==

1. Upload the package contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How can I configure Auto-Updates? =

Go to 'Dashboard' -> 'Update Settings'. The core updates can be switched on or off, themes and translations can be automatically updated, and individual plugin updates can also be configured.

= Why would I use Auto-Updates? =

Not updating your WordPress site regularly exposes your site and your hosting provider to bugs and other attack vectors that can enable an attacker to hack into your website. Keeping your WordPress website up-to-date is one of the key components to keeping your website secure.

= How can I optimize my database tables =

Go to Tools -> Convert DB tables. This will launch the utility that converts myISAM tables to InnoDB.

= Why would I convert my database tables? =

Many older versions of mySQL used myISAM tables by default. Nowadays InnoDB is used by recent versions of mySQL, and this is a much faster format. If you have created your WordPress site in the past on previous versions of mySQL, chances are that you still use myISAM.

== Screenshots ==

1. The submenu under 'Dashboard' that allows the configuration of automatic updates.

== Changelog ==

= 1.0.2 =
* Now includes an admin page based on the Settings API.
