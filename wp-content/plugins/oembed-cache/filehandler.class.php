<?php
namespace Sixtyonedegrees\plugins\oembedCache;

class FilterHandler{
    
    public function __construct() {
        add_filter('oembed_ttl', array(&$this, 'cacheTtl'), 99);
        add_filter('oembed_remote_get_args', array(&$this, 'fetchTimeout'), 99); 
    }
    
    public function cacheTtl($seconds){
        $options = get_option('oembed_cache_option');
        if(!empty($options['ttl']) && is_numeric($options['ttl'])){
            return $options['ttl'];
        }
        return $seconds;
    }
    
    public function fetchTimeout($args){
        $options = get_option('oembed_cache_option');
        if(!empty($options['timout']) && is_numeric($options['timout'])){
            $args['timeout'] = $options['timout'];
        }
        return $args;        
    }
}

