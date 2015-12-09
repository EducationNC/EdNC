=== Widgetize Navigation Menu ===
Contributors: chows305
Tags: navigation, dropdown, navigation menu, recent posts, widget, navigation widget, menu widget
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.03
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

= Widgetize Navigation Menu allows you to add any widget to your navigation’s drop-down menus with an easy to use interface. Comes with four custom widgets to help you beautify your menu. =


= Features =

1. Simple Interface: - Any main menu item you create will show up in the options page. Check off which items to widgetize and how many columns of widgets you would like to have for each.

2. Add any widget - Depending on the menu items and column choices you made, correlating widget areas will be created.

3. Search Bar - Display built in search bar that helps save space in your header.

4. Social Meda - Display Contact, Facebook and Twitter icons in your menu.

5. Built in Widgets - Widgets: Advanced Pages, Advanced Categories, and Advanced Recent Posts come with this plugin that are optimized for display in your drop-down menus.

6. Columns: Allow you to easily organize your content for your users. 

7. Advanced Button Widget - The fourth widget that accompanies this plugin allows you to display a custom button and optional image in your dropdown menu. 

8. Responsive - Makes your navigation responsive to suit both mobile and tablet devices.

9. Custom Styling - Style your menu with custom colors as well as applying custom css.

= Demo =

http://www.lendingapaw.org/widgetized-navigation-menu-in-wordpress/


= Built in Widgets =

Four widgets come with this plugin:

1. Advanced Recent Posts: List your most recent posts by multiple categories. Display resizable featured images. Split into two columns for enhanced look in menu.  
2. Advanced Pages: Check off the pages you wish to list. Display resizable featured images. Split into two columns for enhanced look in menu.
3. Advanced Categories: Check off the categories you wish to list. Split into two columns for enhanced look in menu.
4. Advanced Button Widget: Display a button with custom text and color. Optionally accompany with an image.

== Screenshots ==

1. Simple Interface
2. Add any widget
3. Search Bar
4. Social Media Icons
5. Built in Widgets
6. Columns
7. Advanced Button Widget
8. Responsive
9. Custom Styling


== Installation ==

1. Upload expanded widgetize-navigation-menu folder to the /wp-content/plugins/ directory
2. Activate the plugin through the "Plugins" menu in WordPress
3. Create your standard Wordpress Menu under Appearance -> Menus
4. Go to Appearance -> Dropdown Menus 
5. Select the navigation menu you would like to widgetize 
6. Under each menu item, select how many columns to add to each menu item.  (you can have multiple widgets in a column and you can select up to four columns.)
7. Style your menu by setting its colors, width and optional search bar. 
8. Make sure to select "Enable Menu" when you are ready to show the new widgetized menu.

= Use of the Widgets = 

Go to Appearance -> Widgets and you will see all the widget areas created - named by the menu item's title and column number it correlates to.

The four widgets that come with this plugin will also be in this section on the left hand side for you to drag over into any widget area.

= Manual Installation =
Alternatively you can manually insert the code to replace your navigation menu with the widgetized one.  
Go into theme's file where that navigation is called (typically header.php):
	Replace this function:
	wp_nav_menu(array(... ‘theme_location’ => ‘MENU NAME’ ) );
	
	With:
	if (function_exists(‘widgetize_my_dropdown_menus’)) {
	widgetize_my_dropdown_menus(‘MENU NAME’);
	}
(Make sure to use your menu name in place of “MENU NAME”)

== Frequently Asked Questions ==
= I don't know what my menu name is... =
Go to Appearance -> Dropdown Menus.  In the dropdown option you can view all the registered menus as well as their "MENU NAME". 

= How many widgets can I fit in a column? =
As many as you like although I only recommend a few as you also want your menu to look good on smaller screens like tablets and mobile devices, which is the same reason I chose to limit each wigetized menu to 4 columns total.

= I don't see all the sidebars showing up =
This can happen when there are unique/special characters in the menu item title.  Try to avoid this.  

== Upgrade Notice == 
Please make sure you upgrade your Wordpress to the latest version.  You will not be able to customize your colors on the settings page if you are not up to at least version 3.5

== Changelog ==

= 1.0 =
First version
= 1.01 =
Fixed submenu height when switching screen width sizes
= 1.02 =
- Widget areas now accept accented characters and multi-language characters
- Fixed styling of other widgets in menu that do not come with plugin
= 1.03 =
- BIG UPDATE - New "Enable Menu" button allows you to widgetize your navigation menu without needing to edit the code.
- Changed use of jQuery's window width to window.innerwidth