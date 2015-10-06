=== Delete Me ===
Contributors: cmc3215
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L5VY6QDSAAZUL
Tags: delete, delete profile, delete account, delete own account, unregister, unsubscribe, user management, multisite
Requires at least: 3.4
Tested up to: 4.3
Stable tag: 1.8
License: GPL2 http://www.gnu.org/licenses/gpl-2.0.html

Allow users with specific WordPress roles to delete themselves from the Your Profile page or anywhere Shortcodes can be used.

== Description ==

Allow users with specific WordPress roles to delete themselves from the **Your Profile** page or anywhere Shortcodes can be used using the Shortcode `[plugin_delete_me /]`.
Settings for this plugin are found on the **Settings -> Delete Me** subpanel. Multisite and Network Activation supported.

**How it works:**

* A user clicks the delete link, which defaults to "Delete Account", but can be changed.

* User is asked to confirm they want to delete themselves.

* If confirmed, user and all their Posts, Links, and (optionally) Comments are deleted.

* Deleted user (optionally) redirected to landing page URL, default is homepage, can be changed or left blank.

**Settings available:**

* Select specific WordPress roles (e.g. Subscriber) you want to allow to delete themselves using Delete Me.

* `class` and `style` attributes of the delete link.

* `<a>` tag clickable content of the delete link.

* Landing page URL.

* **Your Profile** confirmation page Heading, Warning, Button.

* Enable or disable delete link on the **Your Profile** page.

* Javascript confirm text for Shortcode.

* Enable or disable Javascript confirm for Shortcode.

* Multisite: Delete user from Network if they no longer belong to any other Network Sites after deletion from current Site.

* Delete comments.

* E-mail notification when a user deletes themselves.

== Installation ==

1. Install automatically in WordPress on the **Plugins -> Add New** subpanel or upload the **delete-me** folder to the **/wp-content/plugins/** directory.

2. Activate the plugin on the **Plugins** panel in WordPress.

3. Go to the **Settings -> Delete Me** subpanel. Select the WordPress roles you want to allow to delete themselves using Delete Me and save changes.

4. The delete link will be placed automatically on the **Your Profile** page for roles you allow, but if you have a Post or Page you'd like the delete link to appear on just copy and paste the Shortcode `[plugin_delete_me /]` there. A custom PHP template can use the Shortcode like so `<?php echo do_shortcode( '[plugin_delete_me /]' ); ?>`

== Frequently Asked Questions ==

= What happens to Posts, Links, and (optionally) Comments belonging to a deleted user? =

Most Post types and Comments are moved to Trash. Links are always deleted permanently.

= Does this plugin support WordPress Multisite? =

Yes, Network Activation and single Site activation are both supported. Users and their content will only be deleted from the Site they delete themselves from, other Network Sites will be unaffected.

= When using Multisite is a user deleted from the Network or only the Site deletion originated from? =

If the user is registered to more than one Site on the Network they're only deleted from the single Site, their user remains on the Network registered to any of their remaining Sites. However, if the option "Delete From Network" is checked and they belong to only one Site then their user will be deleted from the Network because they no longer belong to any Network Sites.

= Is it possible for a user to delete anyone but themselves? =

No, the user deleted is the currently logged in user, period.

= What does the Shortcode display when the user is not logged in or their role is not allowed to delete themselves? =

Nothing when using the self-closing Shortcode tag (i.e. `[plugin_delete_me /]`). However, when the opening and closing Shortcode tags are used (i.e. `[plugin_delete_me]`Content`[/plugin_delete_me]`) the content inside the tags will appear instead of the delete link.

= Where is a user sent after deleting themselves? =

The **Settings -> Delete Me** subpanel lets you enter any URL you'd like to redirect deleted users to, set to homepage by default. You can leave "Landing URL" blank to remain at the same URL after deletion.

= Is there a confirmation before the user deletes themselves? =

Yes, the delete link on the **Your Profile** page leads to a confirmation page. The Shortcode delete link provides a Javascript confirm dialog [OK] [Cancel] by default, but may be disabled to make using a custom confirmation page easier.

= May I be notified of users who delete themselves and what was deleted? =

Yes. The **Settings -> Delete Me** subpanel has a setting called "E-mail Notification", just check the box and save changes.

== Screenshots ==

1. **Your Profile** page.
2. **Your Profile** confirmation page.
3. Post or Page using the Shortcode. You can disable the Javascript confirm and use the Shortcode on a custom confirmation page.
4. **Settings -> Delete Me** subpanel.

== Changelog ==

= 1.8 =

* Release date: 07/15/2015
* The following new Shortcode attributes may be used to override settings, but are not required: class, style, html, js_confirm_warning, landing_url.
* **v1.7 change reverted** - %shortcode% term no longer used, attributes were added instead for a more complete and consistent way of customizing the shortcode.

= 1.7 =

* Release date: 07/14/2015
* Shortcode **Link** text can now contain **%shortcode%** which is replaced with the text inside the open and close shortcode tags. This was added to allow a dynamic way of changing the delete link text.

= 1.6 =

* Release date: 03/09/2015
* **Your Profile** delete link now leads to a customizable confirmation page instead of the Javascript confirm dialog.
* Added settings for **Your Profile** confirmation page Heading, Warning, and Button.
* Added setting to enable or disable Javascript confirm dialog for Shortcode delete link. This was added to make it easier to use a custom confirmation page with the Shortcode.

= 1.5 =

* Release date: 10/18/2014
* **Your Profile** and Shortcode "Landing URL" may now be left blank to remain at the same URL after deletion.
* Removed setting and code for "Uninstall on Deactivate". You can still wipe all traces of the plugin from the Plugins panel by deactivating and clicking Delete.
* Added button on settings page, "Restore Default Settings".
* Shortcode deletion link no longer relies on the get_permalink() function. This makes the shortcode's placement more flexible and the link location more accurate.
* wp_logout() function is now run after user deletion to cleanup session and auth cookies.
* Delete link default updated, old = "Delete Profile", new = "Delete Account".
* Javascript confirm text default updated, the line about Post and Links was removed.

= 1.4 =

* Release date: 04/24/2013
* Added setting to enable or disable the delete link on the **Your Profile** page.
* Added an uninstall.php file. This enables removal of the plugin capabilities and settings when you "Delete" the plugin from the `Plugins` panel in WordPress.
* Fixed possible PHP Warning: missing argument 2 `$wpdb->prepare()` on Multisite installations using WordPress 3.5+
* Fixed possible PHP Fatal error: undefined function `is_plugin_active_for_network()` on Multisite installations when adding a new Site from outside the WordPress Admin pages.
* Consolidated scripts to reduce the number of files used and the total plugin filesize.

= 1.3 =

* Release date: 04/23/2013
* Added setting to customize Javascript confirm text.

= 1.2 =

* Release date: 02/07/2013
* WordPress 3.4 now required.
* Added Multisite and Network Activation support.
* Added setting for Multisite to delete user from Network if user no longer belongs to any Network Sites.
* Added setting to delete comments.
* Edited e-mail notification to list the number of comments deleted.

= 1.1 =

* Release date: 04/11/2011
* Added setting for detailed e-mail notification when a user deletes themselves.
* Fixed undefined function errors for wp_delete_post and wp_delete_link when user has Posts or Links.

= 1.0 =

* Release date: 04/09/2011
* Initial release.

== Upgrade Notice ==

= 1.8 =

Recommended - Improvements added. See Changelog - https://wordpress.org/plugins/delete-me/changelog/
