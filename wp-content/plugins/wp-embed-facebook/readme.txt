=== WP Embed Facebook ===
Contributors: poxtron
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26
Tags: Facebook, facebook, Social Plugins, embed facebook, facebook video, facebook posts, facebook publication, facebook publications, facebook event, facebook events, facebook pages, facebook page, facebook profiles, facebook album, facebook albums, facebook photos, facebook photo, social,
Requires at least: 3.8.1
Tested up to: 4.5.2
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Facebook video, page, event, album, photo, profile or post.

== Description ==

Embed any **public** facebook video, page, post, profile, photo or event directly into a WordPress post, without having to write a single line of code.

= Supported Embeds =
* Facebook Videos
* Facebook Albums
* Facebook Events
* Facebook Photos
* Facebook Fan pages
* Facebook Community pages
* Facebook Profiles
* Facebook Posts

**[Live Demo](http://www.wpembedfb.com/demo/)**

= Requirements =
* Nothing for posts, pages and videos
* For custom embeds a Facebook App Id and Secret are required.
    * To get them first login to facebook then go [here](https://developers.facebook.com/apps/) register as a developer and/or create a new App.

= How to use it =
Copy the facebook url on a single line or use the WordPress native [embed] shortcode [example](https://codex.wordpress.org/Embeds).

You should see the embed right on the editor, try switching from text to visual if it does not.

Alternatively you can use the [facebook] shortcode.

Read more about the shortcodes on [this](http://www.wpembedfb.com/shortcode-attributes-and-examples/) page.

= Premium extension =
* Embed full event shortcode
* Embed full fan page shortcode
* Embed events with address and admins
* Embed albums with more that 100 photos
* Embed all upcoming events of a fan page
* Features cooking
    * Embed personal data
    * Shortcode creator
    * Special templates for albums and pages


== Installation ==

1. Download wp embed facebook plugin from [Wordpress](http://wordpress.org/plugins/wp-embed-facebook)
1. Extract to /wp-content/plugins/ folder, and activate the plugin in /wp-admin/.
1. Create a [facebook app](https://developers.facebook.com/apps).
1. Copy the app id and app secret to the “Embed Facebook” page under the Settings section.
1. Enjoy and tell someone !

== Frequently Asked Questions ==

= How can I change the way an embed looks? =

You can override the embed template with a custom one on your theme read more about it here

= Is there a way to embed an album with more than 100 photos ? =

This can only be achieved using the premium version


== Screenshots ==

1. Fan Page Social Plugin
2. Fan Page Custom Embed
3. Video Social Plugin
4. Video Custom Embed
5. Album
6. Album with more than 100 photos  (Premium only)
7. Event
8. Full Event Shortcode (Premium only)
9. Full Page Shortcode (Premium only)
10. Fan page upcoming events (Premium only)
11. Post Social Plugin
12. Post Custom Embed
13. Photo Custom embed
14. Settings
15. Profile


== Changelog ==

= 2.1 =
* Removed all options and moved them to a single one 'wpemfb_options'
* Removed resize cover javascript it is now done with css
* Fixed timezone bug custom post and events
* Added option to only load scripts when an embed is present
* Added option to reset all options
* Added Jetpack Photon compatibility
* Added X Theme compatibility
* Added lightbox.sass for theme developers
* Updated Lightbox script and style
* Added Lightbox Option Album Label
* Added Lightbox Option Always Show Nav On Touch Devices
* Added Lightbox Option Show Image Number Label
* Added Lightbox Option Wrap Around
* Added Lightbox Option Disable Scrolling
* Added Lightbox Option Fit Images In Viewport
* Added Lightbox Option Max Width
* Added Lightbox Option Max Height
* Added Lightbox Option Position From Top
* Added Lightbox Option Resize Duration
* Added Lightbox Option Fade Duration
* Changed css on classic theme

= 2.0.9.1 =
* Fixed Admin notice bug
* Lightbox css improved
* Fixed cover css

= 2.0.9 =
* Fixed css on footer when using different themes
* Updated all.js to sdk.js (bryant1410)
* Optimization for sites with no Facebook App
* Added error messages for special cases
* Added advanced option for selecting Facebook SDK version
* Fixed locale error inside editor
* Fixed link underline in some themes
* Fixed several css and html structure nothing critical


= 2.0.8 =
* Fix Event title css

= 2.0.7 =
* Settings translation link
* Improved object id identification for fan pages and posts
* Video download option

= 2.0.6 =
* Added new filter "wpemfb_embed_fb_id"
* Added Download Video option
* Added Settings link on plugin description
* Improved type and fb_id recognition

= 2.0.5 =
* Improved [embed] shortcode compatibility !
* Added new 'photos' attribute for shortcode used only on albums
* Added 'type' parameter to wpemfb_template filter
* Fixed https on all templates
* Fixed like and comment links on single post raw
* Fixed forced app token only if it has app
* Fixed admin shortcode references
* Fixed removed unused options on uninstall
* Fixed translations strings
* Fixed notice on installations with no FB App

= 2.0.4 =
* changed shortcode tag from [facebook=url] to [facebook url]
* force app access token

= 2.0.3 =
* Fixed notice on pages and events with no cover
* Moved admin scripts to footer

= 2.0.2 =
* Added options for page social plugins
* Changed admin layout
* Does not need facebook app for simple embeds
* More human friendly

= 2.0.1 =
* Fixed message on photo single post

= 2.0 =
* Fixed language issue when embedding social plugins in admin
* Fixed time on events
* Update Facebook API to 2.4
* Added a new parameters for shortcode 'social_plugin' and 'theme'
* Fixed shortcode use [facebook FB_Object_ID ]
* Improved CSS and themes
* New Embed Post Raw

= 1.9.6.7 =
* Fixed delete of options on uninstall

= 1.9.6.6 =
* Fixed Embed Video Error
* Fixed like and follow button html

= 1.9.6.5 =
* Fixed more things on multisite
* Fixed Page Template HTML

= 1.9.6.4 =
* Fixed translation files
* Fixed bug on event template

= 1.9.6.3 =
* Fixed MultiSite error
* New Shortcode use [facebook FB_Object_ID ] solution for fb permalinks
* Fixed raw attribute on shortcode when url is video

= 1.9.6.2 =
* Local Release

= 1.9.6.1 =
* Fixed headers already sent notice.
* Added Links to Facebook Apps and plugin settings
* Removed deprecated is_date_only field on event template

= 1.9.6 =
* Fix Fatal Error on non object

= 1.9.5 =
* Fixed event templates
* Fixed album thumbnails
* Fixed jQuery UI error when admin is in https

= 1.9.4 =
* Added option to embed raw videos with facebook code
* Added poster on raw embed videos
* Update to FB API v2.3
* Update raw photo template

= 1.9.3 =
* Fixed error on older versions of PHP

= 1.9.2 =
* Line breaks fix

= 1.9.1 =
* Line breaks fix

= 1.9 =
* Facebook video embed code in case video type is not supported
* Fix: Compatibility with other facebook plugins thanks to ozzWANTED
* New filter: 'wpemfb_api_string' and 'wpemfb_2nd_api_string'
* Show embedded posts on admin
* Fix undefined variable on js
* Fix languages on event time

= 1.8.3 =
* Better Video Embeds

= 1.8.2 =
* Fix: Error on some systems nothing critic.

= 1.8.1 =
* Fix: Warning on Dashboard
* Update: Readme.txt

= 1.8 =
* Compatibility with twenty 15 theme
* New css for embeds
* Compatibility with premium plugin

= 1.7.1 =
* Documentation Update
* New advanced option

= 1.7 =
* Better detection of video urls
* FB js now loaded via jquery
* More comprehensive admin section
* Fix -- pictures not showing on chrome

= 1.6.2 =
* minor bugs

= 1.6.1 =
* fix website url
* fix embed post width

= 1.6 =
* Responsive Template
* Posts on Page Embeds
* Album Photo Count
* Fixes on Admin Page
* Remove of unnecessary code

= 1.5.3 =
* fixed Warning in admin

= 1.5 =
* Support for raw videos and photos
* Support for albums
* Spanish translations

= 1.4 =
* Support for Video url's
* Support for filter 'wpemfb_category_template'
* Follow buttons
* Better photo embeds
* New webstie www.wpembedfb.com !

= 1.3.1 =
* Documentation and screenshots.

= 1.3 =
* Shortcode [facebook url width=600] width is optional
* Themes
* Multilingual Like Buttons

= 1.2.3 =
* Bugs and documentation

= 1.2.1 =
* Updated Instructions
* Change theme template directory

= 1.2 =
* Embed posts
* Embed photos
* Like buttons

= 1.1.1 =
* Corrected links on events.

= 1.1 =
* Making the plugin public.

= 1.0 =
* Making the plugin.

== Upgrade Notice ==

= 2.1 =
2.1 version is a lot faster and provides full compatibility with Jetpack Photon and some premium "drag and drop" themes all options have been moved to a single one 'wpemfb_options' and new features where added: Enqueue styles and scripts only when an embed is present, 11 Lightbox options like 'Disable Scrolling' 'Loop though Album' etc. Cover resize now done with css