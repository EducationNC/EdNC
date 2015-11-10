<?php
/**
 *  Get Social Share Counts for a URL from the major Social Networks
 * @var Mixed String or Array $options['url'] = URL that we want to get the social share counts for
 * @var $options['facebook'] = if Array Key set, then return share count from this social network
 * @var $options['twitter'] = if Array Key set, then return share count from this social network
 * @var $options['buffer'] = if Array Key set, then return share count from this social network
 * @var $options['pinterest'] = if Array Key set, then return share count from this social network
 * @var $options['linkedin'] = if Array Key set, then return share count from this social network
 * @var $options['google'] = if Array Key set, then return share count from this social network
 *
 * http://www.codedevelopr.com/articles/get-social-network-share-counts-for-a-url-with-my-php-class/
 *
 * If no Social Network Array keys are passed then it will return the counts from all the networks.
 */

class socialNetworkShareCount{

    public $shareUrl;
    public $socialCounts = array();
    public $facebookShareCount = 0;
    public $facebookLikeCount = 0;
    public $twitterShareCount = 0;
    public $bufferShareCount = 0;
    public $pinterestShareCount = 0;
    public $linkedInShareCount = 0;
    public $googlePlusOnesCount = 0;


    public function __construct($options){

        if(is_array($options)){
            if(array_key_exists('url', $options) && $options['url'] != ''){
                $this->shareUrl = $options['url'];
            }else{
                die('URL must be set in constructor parameter array!');
            }

            // Get Facebook Shares and Likes
            if(array_key_exists('facebook', $options)){
                $this->getFacebookShares();
                $this->getFacebookLikes();
            }

            // Get Twitter Shares
            if(array_key_exists('twitter', $options)){
                $this->getTwitterShares();
            }

            // Get Twitter Shares
            if(array_key_exists('pinterest', $options)){
                $this->getPinterestShares();
            }

            // Get Twitter Shares
            if(array_key_exists('linkedin', $options)){
                $this->getLinkedInShares();
            }

            // Get Twitter Shares
            if(array_key_exists('google', $options)){
                $this->getGooglePlusOnes();
            }

            // Get Buffer Shares
            if(array_key_exists('buffer', $options)){
                $this->getBufferShares();
            }

        }elseif(is_string($options) && $options != ''){
            $this->shareUrl = $options;

            // Get all Social Network share counts if they are not set individually in the options
            $this->getFacebookShares();
            $this->getFacebookLikes();
            $this->getTwitterShares();
            $this->getPinterestShares();
            $this->getLinkedInShares();
            $this->getGooglePlusOnes();
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
        $api = file_get_contents( 'http://graph.facebook.com/?id=' . $this->shareUrl );
        $count = json_decode( $api );
        if(isset($count->shares) && $count->shares != '0'){
            $this->facebookShareCount = $count->shares;
        }
        $this->socialCounts['facebookshares'] = $this->facebookShareCount;
        return $this->facebookShareCount;
    }

    public function getFacebookLikes(){
        $api = file_get_contents( 'http://graph.facebook.com/?id=' . $this->shareUrl );
        $count = json_decode( $api );
        if(isset($count->likes) && $count->likes != '0'){
            $this->facebookLikeCount = $count->likes;
        }
        $this->socialCounts['facebooklikes'] = $this->facebookLikeCount;
        return $this->facebookLikeCount;
    }

    public function getTwitterShares(){
        $api = file_get_contents( 'https://cdn.api.twitter.com/1/urls/count.json?url=' . $this->shareUrl );
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

    public function getGooglePlusOnes(){

        if(function_exists('curl_version')){

            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, "https://clients6.google.com/rpc" );
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $this->shareUrl . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
            $curl_results = curl_exec( $curl );
            curl_close( $curl );
            $json = json_decode( $curl_results, true );
            $this->googlePlusOnesCount = intval( $json[0]['result']['metadata']['globalCounts']['count'] );

        }else{

            $content = file_get_contents("https://plusone.google.com/u/0/_/+1/fastbutton?url=".urlencode($_GET['url'])."&count=true");
            $doc = new DOMdocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($content);
            $doc->saveHTML();
            $num = $doc->getElementById('aggregateCount')->textContent;

            if($num){
                $this->googlePlusOnesCount = intval($num);
            }
        }

        $this->socialCounts['googleplusones'] = $this->googlePlusOnesCount;
        return $this->googlePlusOnesCount;
    }

}
