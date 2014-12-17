<script type="text/javascript">
var _gaq = _gaq || [];
var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
_gaq.push(['_setAccount', 'UA-57754133-1']);
_gaq.push(['_trackPageview']);
(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>

<!-- Load Twitter JS-API asynchronously -->
<script>
(function(){
  var twitterWidgets = document.createElement('script');
  twitterWidgets.type = 'text/javascript';
  twitterWidgets.async = true;
  twitterWidgets.src = 'http://platform.twitter.com/widgets.js';
  // Setup a callback to track once the script loads.
  twitterWidgets.onload = _ga.trackTwitter;
  document.getElementsByTagName('head')[0].appendChild(twitterWidgets);
})();
</script>

<!-- LinkedIn Share Button tracking -->
<script type="text/javascript">
function LinkedInShare() {
  _gaq.push(['_trackSocial', 'LinkedIn', 'Share']);
}
</script>
