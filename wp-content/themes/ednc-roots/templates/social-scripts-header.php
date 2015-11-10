<!-- PrintFriendly.com -->
<script type="text/javascript">var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfHideImages = 0;var pfImageDisplayStyle = 'right';var pfDisablePDF = 0;var pfDisableEmail = 0;var pfDisablePrint = 0;var pfCustomCSS = '';var pfBtVersion='1';(function(){var js, pf;pf = document.createElement('script');pf.type = 'text/javascript';if ('https:' === document.location.protocol){js='https://pf-cdn.printfriendly.com/ssl/main.js'}else{js='http://cdn.printfriendly.com/printfriendly.js'}pf.src=js;document.getElementsByTagName('head')[0].appendChild(pf)})();</script>

<!-- Open all social share links in popups -->
<script type="text/javascript">
// create social networking pop-ups
(function() {
  jQuery(document).ready(function($) {
  	// link selector and pop-up window size
  	var Config = {
  		Link: "a.social-share-link",
  		Width: 500,
  		Height: 500
  	};

  	// add handler links
  	var slink = document.querySelectorAll(Config.Link);
  	for (var a = 0; a < slink.length; a++) {
  		slink[a].onclick = PopupHandler;
  	}

  	// create popup
  	function PopupHandler(e) {

  		e = (e ? e : window.event);
  		var t = (e.target ? e.target : e.srcElement);

  		// popup position
  		var
  			px = Math.floor(((screen.availWidth || 1024) - Config.Width) / 2),
  			py = Math.floor(((screen.availHeight || 700) - Config.Height) / 2);

  		// open popup
  		var popup = window.open(t.href, "social",
  			"width="+Config.Width+",height="+Config.Height+
  			",left="+px+",top="+py+
  			",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
  		if (popup) {
  			popup.focus();
  			if (e.preventDefault) e.preventDefault();
  			e.returnValue = false;
  		}

  		return !!popup;
  	}
  });

}());
</script>


<!-- OLD -->
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "9370f123-7244-4151-a639-30ba1d71bf7f", doNotHash: true, doNotCopy: true, hashAddressBar: false, onhover: false});</script>
