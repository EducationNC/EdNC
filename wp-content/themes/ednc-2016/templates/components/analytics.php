<!-- Google Analytics -->
<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-57754133-1', {'siteSpeedSampleRate': 50});
ga('require', 'displayfeatures');
<?php if (!is_home()) { ?>
ga('set', 'dimension1', '<?php echo str_replace(" ", "_", get_the_author()); ?>');
<?php } ?>
ga('send', 'pageview');
</script>

<!-- Hotjar Analytics -->
<script type="text/javascript">
(function(f,b,g){
  var xo=g.prototype.open,xs=g.prototype.send,c;
  f.hj=f.hj||function(){(f.hj.q=f.hj.q||[]).push(arguments)};
  f._hjSettings={hjid:2614, hjsv:2};
  function ls(){f.hj.documentHtml=b.documentElement.outerHTML;c=b.createElement("script");c.async=1;c.src="//static.hotjar.com/c/hotjar-2614.js?sv=2";b.getElementsByTagName("head")[0].appendChild(c);}
  if(b.readyState==="interactive"||b.readyState==="complete"||b.readyState==="loaded"){ls();}else{if(b.addEventListener){b.addEventListener("DOMContentLoaded",ls,false);}}
  if(!f._hjPlayback && b.addEventListener){
    g.prototype.open=function(l,j,m,h,k){this._u=j;xo.call(this,l,j,m,h,k)};
    g.prototype.send=function(e){var j=this;function h(){if(j.readyState===4){f.hj("_xhr",j._u,j.status,j.response)}}this.addEventListener("readystatechange",h,false);xs.call(this,e)};
  }
})(window,document,window.XMLHttpRequest);
</script>
