=== Plugin Name ===
Contributors: vlastuin, janr
Tags: auto-update, updates, mysql optimization, update, automatic update, vevida, hosting
Requires at least: 3.9
Tested up to: 4.1.1
Stable tag: 1.0.9
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily configure automatic updates from the admin interface and modernize your 
mySQL database.

== License ==
Released under the terms of the GNU General Public License.

== Description ==

Installing WordPress is one thing, keeping it up to date is something else. Each 
week brings new bugs or potential attack scenarios that will make a WordPress 
website vulnerable to hacks. Enabling automatic updates for all or at least most 
parts of WordPress solves a large number of problems with irregularly maintained 
WordPress websites.
 
This plugin extends the automatic update feature already present in WordPress. 
The core updates can be switched on or off, themes and translations can be 
automatically updated, and the plugin updates can be configured on a per-plugin 
basis. 

Many websites started originally with older versions of 
WordPress. Previously those installs used older versions of mySQL, when the 
default table format was myISAM. Nowadays, modern versions of mySQL use the 
InnoDB format, which 
is currently enabled by default. Through this plugin the database tables can be 
optimized for those newer versions of mySQL, converting older myISAM tables to 
InnoDB. 
This is required only once, and only when you have been using WordPress for a 
long time or with a hosting provider that has not actively kept its mySQL 
installations up to date.

Vevida is a major webhosting provider based in The Netherlands. We have been 
hosting websites since 1997. We offer specialized WordPress hosting, which 
includes this plugin by default. This plugin is useful for all WordPress users, 
so we make the latest version of this plugin available in the WordPress 
repository. The source code is also freely available on GitHub.

== Installation ==

1. Upload the package contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure automatic updates through 'Dashboard' -> 'Update Settings'
1. Optimize your mySQL database through 'Tools' -> 'Convert DB tables'

== Frequently Asked Questions ==

= How can I configure Auto-Updates? =

Go to 'Dashboard' -> 'Update Settings'. The core updates can be switched on or 
off, themes and translations can be automatically updated, and individual plugin 
updates can also be configured.

= Why would I use Auto-Updates? =

Not updating your WordPress site regularly exposes your site and your hosting 
provider to bugs and other attack vectors that can enable an attacker to hack 
into your website. Keeping your WordPress website up-to-date is one of the key 
ingredients to keeping your website secure.

= Why has this or that plugin not been updated yet? =

First check whether automatic updates are enabled for the plugin in 'Dashboard' 
-> 'Update Settings'. If automatic updates are enabled, it can take up to 12 
hours for a plugin to actually update.

= How can I optimize my database tables =

Go to Tools -> Convert DB tables. This will launch the utility that converts 
myISAM tables to InnoDB.

= Why would I convert my database tables? =

Many older versions of mySQL used myISAM tables by default. Nowadays InnoDB is 
used by recent versions of mySQL, and this is a much faster format. If you have 
created your WordPress site in the past on previous versions of mySQL, chances 
are that you still use myISAM.

= Can I use this plugin on hosting platforms other than at vevida.com? =

Of course you can, and we encourage you to use our plugin to keep your website
up to date. That's why we made it available in the WordPress repository, and the 
source code is available on GitHub: https://github.com/vlastuin/vevida-optimizer

== Screenshots ==

1. The submenu under 'Dashboard' that allows the configuration of automatic updates.

== Changelog ==

= 1.0.9 =
Release date:

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