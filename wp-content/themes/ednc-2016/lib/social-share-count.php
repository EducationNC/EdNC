<?php

namespace Roots\Sage\ShareCount;

use Roots\Sage\Facebook;

/**
 *  Get Social Share Counts for a URL from the major Social Networks
 * @var Mixed String or Array $options['url'] = URL that we want to get the social share counts for
 * @var $options['facebook'] = if Array Key set, then return share count from this social network
 * @var $options['twitter'] = if Array Key set, then return share count from this social network
 * @var $options['buffer'] = if Array Key set, then return share count from this social network
 * @var $options['pinterest'] = if Array Key set, then return share count from this social network
 * @var $options['linkedin'] = if Array Key set, then return share count from this social network
 *
 * http://www.codedevelopr.com/articles/get-social-network-share-counts-for-a-url-with-my-php-class/
 *
 * If no Social Network Array keys are passed then it will return the counts from all the networks.
 */

class socialNetworkShareCount{

    public $shareUrl;
    public $socialCounts = array();
    public $facebookShareCount = 0;
    public $twitterShareCount = 0;
    public $bufferShareCount = 0;
    public $pinterestShareCount = 0;
    public $linkedInShareCount = 0;


    public function __construct($options){

        if(is_array($options)){
            if(array_key_exists('url', $options) && $options['url'] != ''){
                $this->shareUrl = $options['url'];
            }else{
                die('URL must be set in constructor parameter array!');
            }

            // Get Facebook Shares
            if(array_key_exists('facebook', $options)){
                $this->getFacebookShares();
            }

            // Get Twitter Shares
            if(array_key_exists('twitter', $options)){
                $this->getTwitterShares();
            }

            // Get Pinterest Shares
            if(array_key_exists('pinterest', $options)){
                $this->getPinterestShares();
            }

            // Get LinkedIn Shares
            if(array_key_exists('linkedin', $options)){
                $this->getLinkedInShares();
            }

            // Get Buffer Shares
            if(array_key_exists('buffer', $options)){
                $this->getBufferShares();
            }

        }elseif(is_string($options) && $options != ''){
            $this->shareUrl = $options;

            // Get all Social Network share counts if they are not set individually in the options
            $this->getFacebookShares();
            $this->getTwitterShares();
            $this->getPinterestShares();
            $this->getLinkedInShares();
            $this->getBufferShares();

        }else{
            die('URL must be set in constructor parameter!');
        }
    }


    public function getShareCounts(){
        $totalShares = $this->getTotalShareCount($this->socialCounts);
        $this->socialCounts['total'] = $totalShares;




        return json_encode($this->socialCounts);
    }

    public function getTotalShareCount(array $shareCountsArray){
        return array_sum($shareCountsArray);
    }


    public function getFacebookShares(){
        $auth = Facebook\get_facebook_auth();
        $access_token = urlencode($auth['app_id'] . '|' . $auth['app_secret']);
        $url = 'https://graph.facebook.com/?id=' . $this->shareUrl . '&access_token=' . $access_token;
        $api = file_get_contents( $url );
        $count = json_decode( $api );
        if(isset($count->share->share_count) && $count->share->share_count != '0'){
            $this->facebookShareCount = $count->share->share_count;
        }
        if(isset($count->share->comment_count) && $count->share->comment_count != '0'){
            $this->facebookShareCount += $count->share->comment_count;
        }
        if (isset($count->og_object->engagement->count) && $count->og_object->engagement->count != '0') {
            $this->facebookShareCount += $count->og_object->engagement->count;
        }
        $this->socialCounts['facebookshares'] = $this->facebookShareCount;
        return $this->facebookShareCount;
    }

    public function getTwitterShares(){
        $api = file_get_contents( 'http://opensharecount.com/count.json?url=' . $this->shareUrl );
        $count = json_decode( $api );
        if(isset($count->count) && $count->count != '0'){
            $this->twitterShareCount = $count->count;
        }
        $this->socialCounts['twittershares'] = $this->twitterShareCount;
        return $this->twitterShareCount;
    }


    public function getBufferShares(){
        $api = file_get_contents( 'https://api.bufferapp.com/1/links/shares.json?url=' . $this->shareUrl );
        $count = json_decode( $api );
        if(isset($count->shares) && $count->shares != '0'){
            $this->bufferShareCount = $count->shares;
        }
        $this->socialCounts['buffershares'] = $this->bufferShareCount;
        return $this->bufferShareCount;
    }


    public function getPinterestShares(){
        $api = file_get_contents( 'http://api.pinterest.com/v1/urls/count.json?callback%20&url=' . $this->shareUrl );
         $body = preg_replace( '/^receiveCount\((.*)\)$/', '\\1', $api );
         $count = json_decode( $body );
         if(isset($count->count) && $count->count != '0'){
            $this->pinterestShareCount = $count->count;
         }
         $this->socialCounts['pinterestshares'] = $this->pinterestShareCount;
         return $this->pinterestShareCount;
    }


    public function getLinkedInShares(){
         $api = file_get_contents( 'https://www.linkedin.com/countserv/count/share?url=' . $this->shareUrl . '&format=json' );
        $count = json_decode( $api );
        if(isset($count->count) && $count->count != '0'){
            $this->linkedInShareCount = $count->count;
        }
        $this->socialCounts['linkedinshares'] = $this->linkedInShareCount;
        return $this->linkedInShareCount;
    }

}
