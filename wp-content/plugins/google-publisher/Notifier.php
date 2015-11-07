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

require_once 'ClassAutoloader.php';

/**
 * A singleton class that shows a notification.
 */
class GooglePublisherPluginNotifier {

  /**
   * A list of names of admin pages in which notifications will be shown.
   */
  protected static $ADMIN_PAGE_NAME_WHITELIST = array(
    'index.php' => '',
    'plugins.php' => '',
  );

  const NO_NOTIFICATION = 'none';
  const PENDING_NOTIFICATION = 'pending';
  const NEW_INSTALL_NOTIFICATION = 'new_install';

  private $configuration;

  public function __construct($configuration) {
    $this->configuration = $configuration;
  }

  public function notify() {
    global $pagenow;
    if (array_key_exists($pagenow, self::$ADMIN_PAGE_NAME_WHITELIST)) {
      $notification = $this->configuration->getNotification();
      switch($notification) {
        case self::NEW_INSTALL_NOTIFICATION:
          $class = 'updated notice is-dismissible';
          $message = 'Congratulations on installing the AdSense Plugin.';
          $link = 'Get started';
          $this->displayNotice($class, $message, $link);
          $this->configuration->writeNotification(self::NO_NOTIFICATION);
          break;
        case self::PENDING_NOTIFICATION:
          $class = 'update-nag notice';
          $message = 'There are issues with your AdSense Plugin settings. ' .
              'This may affect your ad placements.';
          $link = 'View settings';
          $this->displayNotice($class, $message, $link);
          break;
        case self::NO_NOTIFICATION:
        default:
      }
    }
  }

  private function displayNotice($class, $message, $link) {
    $class = $class;
    $message = __($message, 'google-publisher-plugin');
    $link = __($link, 'google-publisher-plugin');
    include 'NoticeTemplate.php';
  }
}
