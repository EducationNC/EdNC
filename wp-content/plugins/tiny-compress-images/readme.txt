=== Compress JPEG & PNG images ===
Contributors: TinyPNG
Donate link: https://tinypng.com/
Tags: compress, optimize, shrink, resize, fit, scale, improve, images, tinypng, tinyjpg, jpeg, jpg, png, lossy, jpegmini, crunch, minify, smush, save, bandwidth, website, speed, faster, performance, panda, wordpress app
Requires at least: 3.0.6
Tested up to: 4.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Speed up your website. Optimize your JPEG and PNG images automatically with TinyPNG.

== Description ==

Make your website faster by compressing your JPEG and PNG images. This plugin automatically optimizes your images by integrating with the popular image compression services TinyJPG and TinyPNG.

= Features =

* Automatically compress new images on upload.
* Resize large original images by setting a maximum width and/or height.
* Compress individual images already in your media library.
* Easy bulk compression of your existing media library.
* Select which thumbnail sizes of an image may be compressed.
* Multisite support with a single API key.
* Color profiles are translated to the standard RGB color space.
* See your usage directly from the media settings and during bulk compression.
* Convert CMYK to RGB to save more space and maximize compatibility.
* Compress and resize uploads with the WordPress mobile app.
* No file size limits.

= How does it work? =

After you upload an image to your WordPress site, each resized image is uploaded to the TinyJPG or TinyPNG service. Your image is analyzed to apply the best possible compression. Based on the content of your image an optimal strategy is chosen. The result is sent back to your WordPress site and will replace the original image with one smaller in size. On average JPEG images are compressed by 40-60% and PNG images by 50-80% without visible loss in quality. Your website will load faster for your visitors, and you’ll save storage space and bandwidth!

= Getting started =

Install this plugin and obtain your free API key from https://tinypng.com/developers. With a free account you can compress roughly 100 images each month (based on a regular WordPress installation). The exact number depends on the number of thumbnail sizes you use. You can change which of the generated thumbnail sizes should be compressed, because each one of them counts as a compression. And if you’re a heavy user, you can compress more images for a small additional fee per image.

= Multisite support =

The API key can optionally be configured in wp-config.php. This removes the need to set a key on each site individually in your multisite network.

= Contact us =

Got questions or feedback? Let us know! Contact us at support@tinypng.com or find us on [Twitter @tinypng](https://twitter.com/tinypng).

= Contributors =

Want to contribute? Checkout our [GitHub page](https://github.com/tinify/wordpress-plugin).

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'.
2. Search for 'tinypng' and press the Install Now button for the plugin named 'Compress JPEG & PNG images' by 'TinyPNG'.
3. Activate the plugin from your Plugins page.
4. Register for an API key on https://tinypng.com/developers.
5. Configure the API key in the Settings -> Media page.
6. Upload an image and see it be compressed!

= From WordPress.org =

1. Download the plugin name 'Compress JPEG & PNG images' by 'TinyPNG'.
2. Upload the 'tiny-compress-images' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate the plugin from your Plugins page.
4. Register for an API key on https://tinypng.com/developers.
5. Configure the API key in the Settings -> Media page.
6. Upload an image and see it be compressed!

= Optional configuration =

The API key can also be configured in wp-config.php. You can add a TINY_API_KEY constant with your API key. Once set up you will see a message on the media settings page. This will work for normal and multisite WordPress installations.

== Screenshots ==

1. Enter your TinyPNG or TinyJPG API key and configure the image sizes you would like to have compressed.
2. See how much space TinyPNG has saved you from the media browser and compress additional sizes per image.
3. Bulk compress existing images after installing the plugin or when additional sizes have to be compressed.
4. Show progress while bulk compressing (selection from) media library.
5. Bulk compress complete media library.

== Frequently Asked Questions ==

= Q: I don't recall uploading 500 photos this month but my limit is already reached. How is this number calculated? =
A: When you upload an image to your website, Wordpress will create different sized versions of it (see Settings > Media). The plugin will compress each of these sizes, so when you have 100 images and 5 different sizes you will do 500 compressions.

= Q: What happens to the compressed images when I uninstall the plugin? =
A: When you remove the TinyPNG plugin all your compressed images will remain compressed.

= Q: Is there a file size limit? =
A: No. There are no limitations on the size of the images you want to compress.

= Q: What happens when I reach my monthly compression limit? =
A: Everything will keep on working, but newly uploaded images will not be compressed. Of course we encourage everyone to sign up for a full subscription.

= Q: Can I compress all existing images in my media library? =
A: Yes! After installing the plugin, go to Tools > Compress JPEG & PNG images, and click on "Compress all images" to compress all uncompressed images in your media library.

== Changelog ==

= 1.5.0 =
* Resize original images when compressing. Set a maximum width and/or height and your original images will be scaled down in case they are bigger.
* Added support for the mobile WordPress app (thanks to David Goodwin).

= 1.4.0 =
* Indication of the number of images you can compress for free each month.
* Link to the settings page from the plugin listing.
* Clarification that original images will be overwritten when compressed.

= 1.3.2 =
* Detect different thumbnail sizes with the same dimensions so they will not be compressed again.

= 1.3.1 =
* Media library shows files that are in the process of compression.

= 1.3.0 =
* Improved bulk compression from media library. Bulk compress your whole media library in one step.
* Better indication of image sizes that have been compressed.
* Detection of image sizes modified after compression by other plugins.

= 1.2.1 =
* Prevent compressing the original image if it is the only selected image size.

= 1.2.0 =
* Display connection status and number of compressions this month on the settings page. This also allows you to check if you entered a valid API key.
* Show a notice to administrators when the limit of the fixed and free plans is reached.
* The plugin now works when php's parse_ini_file is disabled on your host.
* Avoids warnings when no image sizes have been selected.

= 1.1.0 =
* The API key can now be set with the TINY_API_KEY constant in wp-config.php. This will work for normal and multisite WordPress installations.
* You can now enable or disable compression of the original uploaded image. If you upgrade the plugin from version 1.0 you may need to go to media settings to include it for compression.
* Improved display of original sizes and compressed sizes showing the total size of all compressed images in media library list view.

= 1.0.0 =
* Initial version.
