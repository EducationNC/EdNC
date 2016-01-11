=== WP Embed Facebook ===
Contributors: poxtron
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26
Tags: Facebook, facebook, Social Plugins, embed facebook, facebook video, facebook posts, facebook publication, facebook publications, facebook event, facebook events, facebook pages, facebook page, facebook profiles, facebook album, facebook albums, facebook photos, facebook photo, social,
Requires at least: 3.8.1
Tested up to: 4.4
Stable tag: 2.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Facebook video, page, event, album, photo, profile or post to any WordPress post or page.

== Description ==

Embed any public facebook video, page, post, profile, photo or event directly into a WordPress post, without having to write a single line of code. Simply put the facebook url on a separate line on the content of any post, and this plugin will fetch data associated with that url and display it. If the data is not public, like “invite only” events or private profiles it will return a link.

= Supported Embeds =
* Facebook Videos
* Facebook Albums
* Facebook Events
* Facebook Photos
* Facebook Fan pages
* Facebook Community pages
* Facebook Profiles
* Facebook Publications

**[Live Demo](http://www.wpembedfb.com/demo/)**

= Requirements =
* Facebook App Id and Secret you can get them [here](https://developers.facebook.com/apps/)

= How to use it =
Copy the facebook url on a single line.

You can *override* the general setting for each embed using a shortcode.

= Shortcode Examples =

Assuming all settings are in their default state.

**Videos**

* From source using html5 video tag

`[facebook https://www.facebook.com/ArchanNair/videos/10152579879850694 ]`

* Use facebook [embedded video](https://developers.facebook.com/docs/plugins/embedded-video-player) plugin

`[facebook https://www.facebook.com/ArchanNair/videos/10152579879850694 social_plugin=true ]`

**Pages**

* Using default settings

`[facebook https://www.facebook.com/sydneyoperahouse ]`

* Changing the number of posts shown only for custom embeds

`[facebook https://www.facebook.com/sydneyoperahouse posts=2 ]`

* Use facebook [page plugin](https://developers.facebook.com/docs/plugins/page-plugin)

`[facebook https://www.facebook.com/sydneyoperahouse social_plugin=true  ]`

All attributes are optional and if 'attributes' is not present it will use the default values

**Albums**

`[facebook https://www.facebook.com/media/set/?set=a.10151932001713698.1073741831.10883823697 ]`

**Events**

`[facebook https://www.facebook.com/events/507909879383103/ ]`

**Photos**

* Embed the photo as a plain image on your site

`[facebook https://www.facebook.com/ArchanNair/photos/a.10151932001713698.1073741831.10883823697/10153545048758698/]`

* Use facebook [embedded post](https://developers.facebook.com/docs/plugins/embedded-posts) plugin

`[facebook https://www.facebook.com/ArchanNair/photos/a.10151932001713698.1073741831.10883823697/10153545048758698/ social_plugin=true ]`

**Posts**

`[facebook https://www.facebook.com/radiohead/posts/10152688346467245 ]`

* Use facebook [embedded post](https://developers.facebook.com/docs/plugins/embedded-posts) plugin

`[facebook https://www.facebook.com/radiohead/posts/10152688346467245 social_plugin=true ]`


**Profiles**

`[facebook https://www.facebook.com/4 ]`

You will need the facebook id of the user to embed the profile.

**Changing the width**

`[facebook https://www.facebook.com/sydneyoperahouse width=970 ]`

All shortcodes can be affected by the width attribute and it represents the *maximum width* in pixels the embed can be rendered, never the less all embeds are responsive.

**Changing the theme**

`[facebook https://www.facebook.com/sydneyoperahouse theme=classic ]`

**Embedding a Facebook object_id**

`[facebook 507909879383103 ]`

== Installation ==

1. Download wp embed facebook plugin from [Wordpress](http://wordpress.org/plugins/wp-embed-facebook)
1. Extract to /wp-content/plugins/ folder, and activate the plugin in /wp-admin/.
1. Create a [facebook app](https://developers.facebook.com/apps).
1. Copy the app id and app secret to the “Embed Facebook” page under the Settings section.
1. Enjoy and tell someone !

== Frequently Asked Questions ==

= Why is a facebook app needed?

All facebook queries to the api need to come from someone so an app is required to tell facebook who did the query

= Is there a way to embed an album with more than 100 photos ? =

This is a facebook limitation, will try to work around it.

== Screenshots ==

1. Fan Page Embed
2. Album
3. Profile
4. Event

== Changelog ==

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

= 2.0.2 =
* More human friendly.