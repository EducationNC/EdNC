<?php
namespace Sixtyonedegrees\plugins\oembedCache;

class AdminPage{

    private $page_slug = 'oembed-cache';
	
    public function __construct() {
        add_action('admin_menu', array(&$this, 'add'));
        add_action('admin_init', array(&$this, 'loadSettings'));
        add_action('admin_enqueue_scripts', array(&$this, 'addScripts'));
        add_action('template_redirect', array(&$this, 'clearCache'));
    }
    
    public function clearCache() {
        if(isset($_GET['embedCacheClear']) && $_GET['embedCacheClear'] == "true" && is_user_logged_in() && current_user_can('manage_options')){
            header("Content-Type: text/plain");
            global $wpdb;
            $results = array("status" => "success");
            if(isset($_GET['postId']) && is_numeric($_GET['postId'])){
                $delres = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%_oembed_%' AND post_id = ".$_GET['postId']);
            }
            else{
                $delres = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%_oembed_%'");
            }
            if($delres === false){
                $results["status"] = "error";
            }
            echo json_encode($results);
            die();
        }
    }    
	
    public function addScripts(){ 
        if(isset($_GET['page']) && $_GET['page'] == $this->page_slug){
            wp_enqueue_script('jquery');
            wp_enqueue_script('oembed-cache', plugins_url('/js/oembed-cache.js', __FILE__ ), array('jquery'));
            wp_enqueue_script('oembed-init', plugins_url('/js/init.js', __FILE__ ), array('oembed-cache'));
            wp_localize_script('oembed-init', 'EMBEDOPT', array(
                'siteurl' => get_option('siteurl'),
                'mess_cache_dest' => __('Cache destroyed!', 'emdcache'),
                'mess_error' => __('Error:', 'emdcache'),
                'mess_error_db' => __('Error: Unexpected database error.', 'emdcache'),
                'mess_error_timeout' => __('Error: The request timed out.', 'emdcache'),
                'mess_error_network' => __('Error: No network connection.', 'emdcache'),
                'mess_error_parse' => __('Error: Parsererror.', 'emdcache'),
                'mess_error_unknown' => __('Error: Unknown error.', 'emdcache'),
                'mess_dismiss_notice' => __('Dismiss this notice.', 'emdcache')
            ));
        }
    }
    
    public function loadSettings(){
        register_setting('oembed_cache_options', 'oembed_cache_option');
    }    
	
    public function add(){
        add_submenu_page('options-general.php',__('Oembed Cache', 'emdcache'), __('Oembed Cache', 'emdcache'), 'edit_theme_options', $this->page_slug, array(&$this, 'show'));
    }
    
    public function show(){
    ?>
    <?php $options = get_option('oembed_cache_option'); ?>
    <div class="wrap">
        <h1><a style="padding:13px 10px 10px 10px; background: #282828; float:left;" href="http://www.61degreesnorth.se/" title="61 Degrees North"><img style="width:80px;" src="<?php echo plugins_url('/graphics/logo.png', __FILE__ ); ?>" alt="" /></a><span style="padding-left: 15px; line-height: 48px;"><?php _e('Oembed Cache', 'emdcache'); ?></span></h1>
        <form method="post" action="options.php">
            <?php settings_fields('oembed_cache_options'); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e('Cache time to live', 'emdcache'); ?></th>
                        <td>
                            <input id="oembed_cache_option[ttl]" name="oembed_cache_option[ttl]" class="medium-text" value="<?php echo apply_filters('oembed_ttl', DAY_IN_SECONDS); ?>" />
                            <p class="description"><?php _e('Enter a time limit in seconds until the embed cache is renewed.', 'emdcache'); ?></p>                        
                            <br/>
                            <p><?php _e('You can change the value above to make an embed cache last longer or shorter (You need to resave a post for Wordpress to fetch a new embed cache, even if its time to live has expired).', 'emdcache'); ?></p>
                        </td>
                    </tr> 
                    <tr>
                        <th scope="row"><?php _e('Fetch timeout', 'emdcache'); ?></th>
                        <td>
                            <?php
                                $args = apply_filters('oembed_remote_get_args', array());
                                $timeout = (isset($args['timeout'])) ? $args['timeout'] : apply_filters('http_request_timeout', 5);
                            ?>
                            <input id="oembed_cache_option[timout]" name="oembed_cache_option[timout]" class="small-text" value="<?php echo $timeout; ?>" />
                            <p class="description"><?php _e('Enter a time limit in seconds for fetching embed codes.', 'emdcache'); ?></p>
                            <br/>
                            <p><?php _e('If Wordpress doesnt succeed in fetching the embed code within a specific time limit the embed will fail. You might want to increase this limit if you are experiencing a lot of failed embeds.', 'emdcache'); ?></p>
                        </td>
                    </tr>               
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Options', 'emdcache'); ?>" />
            </p>            
        </form>
        <h3><?php _e('Cache actions', 'emdcache'); ?></h3>       
        <table id="tbl-cache-actions" class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Delete specific embed cache', 'emdcache'); ?></th>
                    <td style="position:relative;">
                        <input name="oembed_post_id" id="oembed_post_id" class="small-text" />    
                        <input type="button" class="button-primary" id="clear_specific_oembed_cache" value="<?php _e('Seek and destroy!!', 'emdcache'); ?>" /><img class="loader specific" style="display:none; position: absolute; left: 205px; margin-top: 2px;" src="<?php echo plugins_url('/graphics/load.gif', __FILE__ ); ?>" alt="" />
                        <p class="description"><?php _e('Enter a post id and press this button to clear embed cache for a specific post.', 'emdcache'); ?></p>
                    </td>
                </tr>                 
                <tr>
                    <th scope="row"><?php _e('Delete all embed cache', 'emdcache'); ?></th>
                    <td style="position:relative;">
                        <input type="button" class="button-primary" id="clear_all_oembed_cache" value="<?php _e('Seek and destroy!!', 'emdcache'); ?>" /><img class="loader all" style="display:none; position: absolute; left: 149px; margin-top: 2px;" src="<?php echo plugins_url('/graphics/load.gif', __FILE__ ); ?>" alt="" />
                        <p class="description"><?php _e('Press this button to clear all embed cache for all posts.', 'emdcache'); ?></p>
                    </td>
                </tr>                
            </tbody>
        </table>        
    </div>
    <?php
    }
}

