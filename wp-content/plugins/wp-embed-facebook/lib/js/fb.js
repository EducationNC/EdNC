jQuery(document).ready(function() {
  	jQuery.ajaxSetup({ cache: true });
  	var script_name = '//connect.facebook.net/'+ WEF.local +'/all.js';
    jQuery.getScript(script_name, function(){
        FB.init({
            appId: WEF.fb_id,
            status: true,
            cookie: true,
            xfbml: true
        });
    });
});
