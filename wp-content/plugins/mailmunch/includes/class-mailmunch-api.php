<?php
  class Mailmunch_Api {
    protected $base_url = MAILMUNCH_URL;
    protected $headers = array('Accept' => 'application/json');
    protected $requestType = 'get';
    protected $mailmunch_prefix;
    protected $referral = 'mailmunch-wordpress-plugin';

    function __construct() {
      $this->mailmunch_prefix = MAILMUNCH_PREFIX.'_';
      $this->ensureUser();
      $this->findOrCreateSite();
    }

    function ensureUser() {
      $userToken = $this->getUserToken();
      if (empty($userToken)) {
        $userToken = $this->generateUserToken();
        if( is_wp_error( $userToken ) ) {
          return new WP_Error( 'broke', "Unable to connect to MailMunch. Please try again later." );
        }
        
        $this->setUserToken($userToken);
      }
    }

    function getSetting($settingName) {
      return get_option($this->getPrefix(). $settingName);
    }

    function setSetting($settingName, $value=null) {
      return update_option($this->getPrefix(). $settingName, $value);
    }

    function getUserToken() {
      return get_option($this->getPrefix(). 'user_token');
    }

    function setUserToken($userToken) {
      return update_option($this->getPrefix(). 'user_token', $userToken );
    }

    function widgets($widgetTypeName, $siteId=null) {
      if (empty($siteId)) { $siteId = $this->getSiteId(); }
      $this->requestType = 'get';
      if (!empty($widgetTypeName)) {
        return $this->ping('/sites/'.$siteId.'/widgets?widget_type_name='.$widgetTypeName);
      } else {
        return $this->ping('/sites/'.$siteId.'/widgets');
      }
    }

    function getPrefix() {
      return $this->mailmunch_prefix;
    }

    function getSites() {
      $this->requestType = 'get';
      $request = $this->ping('/sites');

      if( is_wp_error( $request ) ) {
        return new WP_Error( 'broke', "Unable to get sites. Please try again later." );
      }

      $sites = $request['body'];
      $result = json_decode($sites);
      return $result;
    }

    function getSite($siteId=null) {
      if (empty($siteId)) $siteId = $this->getSiteId();
      if (empty($siteId)) return false;

      $this->requestType = 'get';
      $request = $this->ping('/sites/'. $siteId);

      if( is_wp_error( $request ) ) {
        return false;
      }

      $site = $request['body'];
      $result = json_decode($site);
      return $result;
    }

    function getLists($siteId=null) {
      if (empty($siteId)) { $siteId = $this->getSiteId(); }
      $this->requestType = 'get';
      $request = $this->ping('/sites/'. $siteId. '/lists');
      if( is_wp_error( $request ) ) {
        return new WP_Error( 'broke', "Unable to get lists. Please try again later." );
      }

      return json_decode($request['body']);
    }

    function findOrCreateSite() {
      $site = $this->getSite();
      if (empty($site)) {
        $siteName = get_bloginfo();
        $decodedSiteName = html_entity_decode($siteName, ENT_QUOTES);
        $decodedSiteName = stripslashes($decodedSiteName);
        $site = $this->createSite($decodedSiteName, home_url());
        if (!empty($site)) $this->setSiteId($site->id);
      }
      return $site;
    }

    function createList($listName, $siteId=null) {
      if (empty($listName)) $listName = 'General';
      if (empty($siteId)) { $siteId = $this->getSiteId(); }
      $this->requestType = 'post';
      $response = $this->ping('/sites/'. $siteId. '/lists', array(
        'list' => array(
          'name' => $listName,
          )
      ));
      $list = json_decode($response['body']);
      return $list;
    }

    function createSite($siteName, $domain) {
      if (empty($siteName)) $siteName = 'WordPress';
      $this->requestType = 'post';
      $request = $this->ping('/sites', array(
        'site' => array(
          'name' => $siteName,
          'domain' => $domain,
          'wordpress' => true
          )
      ));
      if( is_wp_error( $request ) ) {
        return new WP_Error( 'broke', "Unable to create site. Please try again later." );
      }

      $site = json_decode($request['body']);
      return $site;
    }

    function updateSite($sitename, $domain) {
      $this->requestType = 'post';
      return $this->ping('/wordpress/update_site', array(
        'id' => $this->getSiteId(),
        'site' => array(
          'name' => $sitename,
          'domain' => $domain
          )
      ));
    }

    function isLegacyUser() {
      $email = get_option($this->getPrefix(). "user_email");
      $password = get_option($this->getPrefix(). "user_password");
      
      if (!empty($email) && !empty($password)) {
        return true;
      }

      return false;
    }

    function migrateUser() {
      $currentHeaders = $this->headers;
      $email = get_option($this->getPrefix(). "user_email");
      $password = get_option($this->getPrefix(). "user_password");

      if (empty($email) || empty($password)) {
        return false;
      }

      $this->headers = array_merge($this->headers, array(
        'Authorization' => 'Basic ' . base64_encode( $email . ':' . $password )
        )
      );

      $this->requestType = 'post';
      $request = $this->ping('/wordpress/migrate_user.json', array(), false);
      $this->headers = $currentHeaders;

      if( is_wp_error( $request ) ) {
        return false;
      }

      $request = json_decode($request['body']);
      if ($request->success == true && !empty($request->token)) {
        $this->setUserToken($request->token);

        // Migrate Site ID
        $old_data = $this->deep_unserialize(get_option($this->getPrefix(). "data"));
        if (isset($old_data["site_id"])) {
          $this->setSiteId($old_data["site_id"]);
          delete_option($this->getPrefix(). 'data');
        }

        // Delete options for old site
        delete_option($this->getPrefix(). 'user_email');
        delete_option($this->getPrefix(). 'user_password');
        delete_option($this->getPrefix(). 'guest_user');
        delete_option($this->getPrefix(). 'wordpress_instance_id');

        return true;
      }

      return false;
    }

    function deep_unserialize($value, $retries=0) {
      $retries++;
      if ($retries > 3) return $value;

      if (is_string($value)) {
        $value = unserialize($value);
        if (is_string($value)) $value = $this->deep_unserialize($value, $retries);
      }
      return $value;
    }

    function generateUserToken() {
      if ($this->isLegacyUser()) {
        if ($this->migrateUser()) {
          return $this->getUserToken();
        }
      }

      $guestUser = $this->createGuestUser();
      if( is_wp_error( $guestUser ) ) {
        return new WP_Error( 'broke', "Unable to create user. Please try again later." );
      }
      return json_decode($guestUser['body'])->user_token;
    }

    function createGuestUser() {
      $this->requestType = 'post';
      return $this->ping('/users', array(
        'user' => array(
          'email' => 'guest_' . uniqid() . '@mailmunch.co',
          'password' => uniqid(),
          'guest_user' => true,
          'referral' => $this->referral,
          )
      ));
    }

    function getWidgetsHtml($siteId=null) {
      if (empty($siteId)) { $siteId = $this->getSiteId(); }

      $this->requestType = 'get';
      $request = $this->ping('/sites/'.$siteId.'/widgets/wordpress?plugin=mailmunch');
      if( is_wp_error( $request ) ) {
        return $request->get_error_message();
      }

      $body = str_replace('{{TOKEN}}', $this->getUserToken(), $request['body']);
      return $body;
    }

    function getSiteId() {
      return get_option($this->getPrefix(). 'site_id');
    }

    function setSiteId($siteId) {
      return update_option($this->getPrefix(). 'site_id', $siteId);
    }

    function getListId() {
      return get_option($this->getPrefix(). 'list_id');
    }

    function setListId($listId) {
      return update_option($this->getPrefix(). 'list_id', $listId);
    }

    function deleteWidget($widgetId) {
      $this->requestType = 'post';
      $request = $this->ping('/sites/'.$this->getSiteId().'/widgets/'.$widgetId.'/delete');
      if ( is_wp_error( $request ) ) {
        return array('success' => false);
      }
      return array('success' => true);
    }

    function signInUser($email, $password) {
      $this->requestType = 'post';
      $request = $this->ping('/wordpress/sign_in.json', array(
          'user' => array(
            'email' => $email,
            'password' => $password
          ),
          'site_id' => $this->getSiteId()
        )
      );

      if( is_wp_error( $request ) ) {
        return false;
      }

      $newUser = json_decode($request['body']);
      if (intval($newUser->site_id) && $newUser->site_id != $this->getSiteId()) {
        $this->setSiteId($newUser->site_id);
      }
      return $newUser;
    }

    function signUpUser($email, $password, $siteName, $siteDomain) {
      $this->requestType = 'post';
      $request = $this->ping('/wordpress/sign_up.json', array(
          'user' => array(
            'email' => $email,
            'password' => $password,
            'guest_user' => false
          ),
          'site' => array(
            'id' => $this->getSiteId(),
            'name' => $siteName,
            'domain' => $siteDomain
          )
        )
      );

      if( is_wp_error( $request ) ) {
        return false;
      }

      $newUser = json_decode($request['body']);
      if (intval($newUser->site_id) && $newUser->site_id != $this->getSiteId()) {
        $this->setSiteId($newUser->site_id);
      }
      return $newUser;
    }

    function signOutUser() {
      delete_option($this->getPrefix(). 'user_token');
      delete_option($this->getPrefix(). 'site_id');
      delete_option($this->getPrefix(). 'class-mailmunchaccess_token');
      delete_option($this->getPrefix(). 'class-mailmunchlist_id');
    }
    
    function ping($path, $options=array(), $useTokenAuth=true) {
      $type = $this->requestType;
      $url = $this->base_url. $path;

      $parsedUrl = parse_url($url);
      $parseUrlQuery = isset($parsedUrl['query']) ? $parsedUrl['query'] : null;
      if (!empty($parseUrlQuery)) {
        $url .= '&version='. MAILMUNCH_VERSION;
      }
      else {
        $url .= '?version='. MAILMUNCH_VERSION;
      }

      if ($useTokenAuth) { $url .= '&token='. $this->getUserToken(); }

      $args = array(
        'headers' => $this->headers,
        'timeout' => 120,
      );

      if ($type != 'post') {
        $request = wp_remote_get($url, $args);
      }
      else {
        $args = array_merge($args, array('method' => 'POST', 'body' => $options));
        $request = wp_remote_post($url, $args);
      }

      if ( !is_wp_error( $request ) && ( $request['response']['code'] == 500 || $request['response']['code'] == 503 ) ) {
        return new WP_Error( 'broke', "Internal Server Error" );
      }

      if ($useTokenAuth) {
        if (!is_wp_error( $request ) && isset($request['response']['code']) && $request['response']['code'] == 401) {
          $this->signOutUser();
          return new WP_Error( 'broke', 'Unauthorized. Please try again.');
        }
      }

      return $request;
    }
  }
?>
