<?php

use Roots\Sage\Assets;
use Roots\Sage\Extras;

?>
<!-- Social Media Metadata -->
<meta property="og:type" content="article" />
<?php
$static_map = get_field('static_map');
$upload_dir = wp_upload_dir();
$post_name = str_replace('-', '_', $post->post_name);
$filename = '/data-viz/' . $post_name . '.png';
if (file_exists($upload_dir['basedir'] . $filename)) {
	?>
	<meta property="og:image" content="<?php echo str_replace('http://www.ednc.org', 'https://www.ednc.org', $upload_dir['baseurl']) . $filename; ?>" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="630" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:image" content="<?php echo str_replace('http://www.ednc.org', 'https://www.ednc.org', $upload_dir['baseurl']) . $filename; ?>" />
	<?php
} elseif (!empty($static_map['url'])) {
  ?>
  <meta property="og:image" content="<?php echo $static_map['url']; ?>" />
	<meta property="og:image:width" content="<?php echo $static_map['width']; ?>" />
	<meta property="og:image:height" content="<?php echo $static_map['height']; ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:image" content="<?php echo $static_map['url']; ?>" />
  <?php
} else {
	if ( !empty($text = get_field('text-based_data')) && preg_match('/https?\:\/\/[^\"\' \n]+/i', $text, $match) && !empty($postid = Extras\full_url_to_postid($match[0])) ) {
    ?>
		<meta property="og:image" content="<?php echo get_the_post_thumbnail($postid, 'full'); ?>" />
    <?
  } else {
		?>
		<meta property="og:image" content="<?php echo Assets\asset_path('images/dashboard-icon.png'); ?>" />
		<?php
	}
	?>
	<meta property="og:image:width" content="400" />
	<meta property="og:image:height" content="400" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:image" content="<?php echo Assets\asset_path('images/dashboard-icon.png'); ?>" />
	<?php
}
?>
<meta property="fb:app_id" content="880236482017135" />
<meta property="og:site_name" content="EducationNC" />
<meta property="og:description" content="Discover and explore North Carolina's education data." />
<?php if (is_post_type_archive('data')) { ?>
	<meta property="og:url" content="<?php echo get_post_type_archive_link('data'); ?>" />
		<meta property="og:title" content="EdNC Data Dashboard" />
	<meta name="twitter:title" content="EdNC Data Dashboard" />
<?php } else { ?>
	<meta property="og:url" content="<?php the_permalink(); ?>" />
		<meta property="og:title" content="<?php the_title(); ?>" />
	<meta name="twitter:title" content="<?php the_title(); ?>" />
<?php } ?>
<meta name="twitter:site" content="@EducationNC" />
<meta name="twitter:description" content="Discover and explore North Carolina's education data." />
