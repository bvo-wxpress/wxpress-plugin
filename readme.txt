=== Plugin Name ===
Contributors: Atlas_Gondal
Tags: extract urls, export urls, links, get links, get urls, custom post type urls, see links, extract title, export title, export title and url, export category
Requires at least: 3.0.1
Tested up to: 4.9.2
Stable tag: 4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to extract posts/pages Title, URL and Categories. You can write output in CSV or in dashboard.

== Description ==


This plugin will add a page called "Export All URLs" under Settings. You can navigate there and can extract data from your site. You can export Posts:

* Titles
* URLs
* And Categories

You can export data and categorize it by their post types.


Why we need this plugin?

* When we need to check all URLs of your website
* You need All URLs of your site to share with SEO guy
* When you are transferring your website
* 301 Redirects handling using htaccess

== New Features ==

* Now filter URLs by Author
* Specify range before extracting (especially beneficial in case of timeout or memory out error)
* Generates CSV file name randomly (sensitive data protection for security reasons)

If you found any bug then report me, I'll try to fix it as soon as possible! 

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'Export All URLs'
3. Activate Export All URLs from your Plugins page. 

= From WordPress.org =

1. Download Export All URLs.
2. Unzip plugin.
2. Upload the 'Export All URLs' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate Export All URLs from your Plugins page. 

= Usage =

1. Go to Settings > Export All URLs to export URLs of your website.
2. Select Post Type
3. Choose Data (e.g Title, URLs, Categories)
4. Apply Filters (e.g Post Status, Author, Post Range) 
5. Finally Select Export type and click on Export Now.

= Uninstalling: =

1. In the Admin Panel, go to "Plugins" and deactivate the plugin.
2. Go to the "plugins" folder of your WordPress directory and delete the files/folder for this plugin.


== Frequently Asked Questions ==

= About Plugin Support? =

Post your question on support forum for this plugin and I will try to answer quickly. 

== Screenshots ==

1. Admin screenshot of Export All URLs. screenshot-1.png


== Changelog ==

= 1.0 =

* Initial release

= 2.0 = 

* Support for exporting title and categories.

= 2.1 = 

* Fixed special character exporting for Polish Language

= 2.2 = 

* Added support for wordpress 4.6.1

= 2.3 =

* Fixed categories export, (only first category was exporting)
* Tested with wordpress 4.7

= 2.4 =

* Fatal error bug fixed
* tested with 4.7.2

= 2.5 =

* Add support for selecting post status
* tested with 4.7.5

= 2.6 =

* Fixed variable initialization errors
* tested with 4.9

= 3.0 =

* Filter data by author
* Specify post range for extraction
* Generates random file name
* tested with 4.9.2