<?php
/*
Plugin Name: Email Address Encoder
Plugin URI: http://wordpress.org/plugins/email-address-encoder/
Description: A lightweight plugin to protect email addresses from email-harvesting robots by encoding them into decimal and hexadecimal entities.
Version: 1.0.4
Author: Till Krüss
Author URI: http://till.kruss.me/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Copyright 2014 Till Krüss  (http://till.kruss.me/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Email Address Encoder
 * @copyright 2014 Till Krüss
 */

/**
 * Define plugin constants that can be overridden, generally in wp-config.php.
 */
if (!defined('EAE_FILTER_PRIORITY'))
	define('EAE_FILTER_PRIORITY', 1000);

/**
 * Register filters to encode exposed email addresses in
 * posts, pages, excerpts, comments and widgets.
 */
foreach (array('the_content', 'the_excerpt', 'widget_text', 'comment_text', 'comment_excerpt') as $filter) {
	add_filter($filter, 'eae_encode_emails', EAE_FILTER_PRIORITY);
}

/**
 * Searches for plain email addresses in given $string and
 * encodes them (by default) with the help of eae_encode_str().
 *
 * Regular expression is based on based on John Gruber's Markdown.
 * http://daringfireball.net/projects/markdown/
 *
 * @param string $string Text with email addresses to encode
 * @return string $string Given text with encoded email addresses
 */
function eae_encode_emails($string) {

	// abort if $string doesn't contain a @-sign
	if (apply_filters('eae_at_sign_check', true)) {
		if (strpos($string, '@') === false) return $string;
	}

	// override encoding function with the 'eae_method' filter
	$method = apply_filters('eae_method', 'eae_encode_str');

	// override regex pattern with the 'eae_regexp' filter
	$regexp = apply_filters(
		'eae_regexp',
		'{
			(?:mailto:)?
			(?:
				[-!#$%&*+/=?^_`.{|}~\w\x80-\xFF]+
			|
				".*?"
			)
			\@
			(?:
				[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
			|
				\[[\d.a-fA-F:]+\]
			)
		}xi'
	);

	return preg_replace_callback(
		$regexp,
		create_function(
            '$matches',
            'return '.$method.'($matches[0]);'
        ),
		$string
	);

}

/**
 * Encodes each character of the given string as either a decimal
 * or hexadecimal entity, in the hopes of foiling most email address
 * harvesting bots.
 *
 * Based on Michel Fortin's PHP Markdown:
 *   http://michelf.com/projects/php-markdown/
 * Which is based on John Gruber's original Markdown:
 *   http://daringfireball.net/projects/markdown/
 * Whose code is based on a filter by Matthew Wickline, posted to
 * the BBEdit-Talk with some optimizations by Milian Wolff.
 *
 * @param string $string Text with email addresses to encode
 * @return string $string Given text with encoded email addresses
 */
function eae_encode_str($string) {

	$chars = str_split($string);
	$seed = mt_rand(0, (int) abs(crc32($string) / strlen($string)));

	foreach ($chars as $key => $char) {

		$ord = ord($char);

		if ($ord < 128) { // ignore non-ascii chars

			$r = ($seed * (1 + $key)) % 100; // pseudo "random function"

			if ($r > 60 && $char != '@') ; // plain character (not encoded), if not @-sign
			else if ($r < 45) $chars[$key] = '&#x'.dechex($ord).';'; // hexadecimal
			else $chars[$key] = '&#'.$ord.';'; // decimal (ascii)

		}

	}

	return implode('', $chars);

}
