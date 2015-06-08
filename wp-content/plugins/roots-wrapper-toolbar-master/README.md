# [Roots Wrapper Toolbar](http://roots.io/plugins/roots-wrapper-toolbar/)

The Roots Wrapper Toolbar is a WordPress plugin (or mu-plugin) that displays the base and main templates selected by the [Roots wrapper](http://roots.io/an-introduction-to-the-roots-theme-wrapper/) in the WordPress toolbar. 

It requires the [Roots theme](http://roots.io/) and wrapper to function.

## Installation

Clone or download the repo and place into the `plugins` directory of your Roots based theme and activate.

Alternatively, copy the `roots-wrapper-toolbar.php` into your `mu-plugins` folder to prevent unwanted or accidental de-activation.

### Privileges
The default user level to view the toolbar is set using `is_super_admin()`. You can override this by filtering `rwt_user_level`. Set the filter function to return true to display the toolbar, or false to hide it.

## Contributing

Everyone is welcome to help and improve this project. There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines/))
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/roots/roots-wrapper-toolbar/issues/)
* Replying to questions on the [forum](http://discourse.roots.io/)

## Support

Please visit [Roots Discourse](http://discourse.roots.io/) to ask questions and get support from the Roots community. 

If you would like to support the author, why not try the [Roots Wrapper Override](http://roots.io/plugins/roots-wrapper-override/) plugin?
