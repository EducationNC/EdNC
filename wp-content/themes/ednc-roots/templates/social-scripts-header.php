<!-- Facebook Scripts -->
<script>
window.fbAsyncInit = function() {
  FB.init({
    appId      : '880236482017135',
    xfbml      : true,
    version    : 'v2.1'
  });

  // Track Sharing
  FB.Event.subscribe('message.send', function(href, widget) {
    ga('send', {
      'hitType': 'social',
      'socialNetwork': 'Facebook',
      'socialAction': 'Share',
      'socialTarget': '<?php echo get_permalink(); ?>',
      'page': '<?php the_title(); ?>'
    });
  });
};

// Load the SDK asynchronously
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<!-- Twitter Scripts -->
<script type="text/javascript">
window.twttr = (function (d,s,id) {
  var t, js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return; js=d.createElement(s); js.id=id;
  js.src="https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
  return window.twttr || (t = { _e: [], ready: function(f){ t._e.push(f) } });
}(document, "script", "twitter-wjs"));

// Track sharing
twttr.ready(function (twttr) {
  twttr.events.bind('tweet', function ( event ) {
    if ( event ) {
      ga('send', {
        'hitType': 'social',
        'socialNetwork': 'Twitter',
        'socialAction': 'Tweet',
        'socialTarget': '<?php echo get_permalink(); ?>',
        'page': '<?php the_title(); ?>'
      });
    }
  });
});
</script>

<!-- Google+ Scripts -->
<script type="text/javascript">
(function() {
  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
  po.src = 'https://apis.google.com/js/plusone.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>

<!-- LinkedIn Share Button tracking -->
<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>

<script type="text/javascript">
// Track sharing
function track_linkedin_share() {
  ga('send', {
    'hitType': 'social',
    'socialNetwork': 'LinkedIn',
    'socialAction': 'Share',
    'socialTarget': '<?php echo get_permalink(); ?>',
    'page': '<?php the_title(); ?>'
  });
}
</script>
