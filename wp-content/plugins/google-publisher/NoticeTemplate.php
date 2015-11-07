<?php
/*
Copyright 2014 Google Inc. All Rights Reserved.

This file is part of the AdSense Plugin.

The AdSense Plugin is free software:
you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation,
either version 2 of the License, or (at your option) any later version.

The AdSense Plugin is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License
along with the AdSense Plugin.
If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH')) {
  exit;
}
?>
<div id="google-publisher-plugin-notification" class="<?php echo $class ?>">
  <p>
    <?php echo $message ?>
    <a href="<?php echo admin_url('options-general.php?page=GooglePublisherPlugin'); ?>">
      <?php echo $link ?>
    </a>
  </p>
</div>
