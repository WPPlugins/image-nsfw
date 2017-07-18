=== Image (NSFW) ===
Contributors: ModerateContent.com
Donate link: http://moderatecontent.com
Tags: image, upload, NSFW, moderate, content
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.0.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Stops the upload of NSFW images. Using the FREE api at moderatecontent.com to rate content and block it if it's inappropriate.

== Description ==

* blocks any image uploaded with inappropriate content (nudity etc)
* leverages the built in WordPress upload mechanism
* works with most other upload plugins
* leverages the FREE api at [ModerateContent.com](https://moderatecontent.com "The FREE image moderation API")
* bbPress support
* external image url review

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/moderatecontent` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. DONE!

== Frequently Asked Questions ==

= An image I'm uploading doesn't appear to be NSFW, why was it flagged?  =

Test images at [ModerateContent.com](https://moderatecontent.com "The FREE image moderation API") to understand what rating the image received. In very rare cases the computer vision model can misidentify inappropriate content.

== Screenshots ==

1. The plugin leverages the build in WordPress upload utility.
1. Selected file has acceptable content.
1. Uploads normally to the Wordpress file system.
1. Upload inappropriate file.
1. File is switched to adult content image.

== Changelog ==

= 1.0.6 =
* Tested with WordPress 4.8

= 1.0.5 =
* Updated to work with ModerateContent.com API V2
* UI Clean Up
* Test for API Key
* Enhanced compatibility with BBPress Plugins
* Updated compatibility with 4.7.2

= 1.0.4 =
* Updated compatibility with 4.7

= 1.0.3 =
* Installation Improvements

= 1.0.2 =
* Installation Improvements

= 1.0.1 =
* Added support for external url's
* Added support for bbPress

= 1.0.0 =
* Initial Release
