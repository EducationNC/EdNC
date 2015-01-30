# [EdNC.org](https://www.ednc.org/)

EdNC is an open source publication platform built on WordPress, Roots, and a myriad of plugins.

* Source: [https://github.com/EducationNC/EdNC](https://github.com/EducationNC/EdNC)
* Homepage: [https://www.ednc.org/](https://www.ednc.org/)

## Contributing

Everyone is welcome to help contribute and improve this project. There are several ways you can contribute:

* Reporting [issues] (https://github.com/EducationNC/EdNC/issues)
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/roots/roots/issues)

## Theme features

* [Grunt](http://roots.io/using-grunt-for-wordpress-theme-development/) for compiling SASS to CSS, checking for JS errors, live reloading, concatenating and minifying files, versioning assets, and generating lean Modernizr builds
* [Bower](http://bower.io/) for front-end package management
* [HTML5 Boilerplate](http://html5boilerplate.com/)
  * The latest [jQuery](http://jquery.com/) via Google CDN, with a local fallback
  * The latest [Modernizr](http://modernizr.com/) build for feature detection, with lean builds with Grunt
  * An optimized Google Analytics snippet
* [Bootstrap](http://getbootstrap.com/)
* Organized file and template structure
* ARIA roles and microformats
* [Theme activation](http://roots.io/roots-101/#theme-activation)
* [Theme wrapper](http://roots.io/an-introduction-to-the-roots-theme-wrapper/)
* Cleaner HTML output of navigation menus
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](http://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/roots-translations)

## Installation

This project does not include WordPress, so you will need to first download and install WordPress in your project directory.

Clone the git repo - `git clone git://github.com/EducationNC/EdNC.git` - or [download it](https://github.com/EducationNC/EdNC/zipball/master) and then place the files into your project directory.

If you don't use [Bedrock](https://github.com/roots/bedrock), you'll need to add the following to your `wp-config.php` on your development installation:

```php
define('WP_ENV', 'development');
```

## Theme activation

Reference the [theme activation](http://roots.io/roots-101/#theme-activation) documentation to understand everything that happens once you activate Roots.

## Theme development

Roots uses [Grunt](http://gruntjs.com/) for compiling SASS to CSS, checking for JS errors, live reloading, concatenating and minifying files, versioning assets, and generating lean Modernizr builds.

Pleaes refer to the Roots [README] (https://github.com/roots/roots-sass/blob/master/README.md) for more information on setting up your local dev environment to develop using Grunt.

## Documentation

* [Roots 101](http://roots.io/roots-101/) — A guide to installing Roots, the files, and theme organization
