jQuery(document).ready(function () {
    jQuery.ajaxSetup({cache: true});
    var script_name = '//connect.facebook.net/' + WEF.local + '/sdk.js';
    jQuery.getScript(script_name, function () {
        FB.init({
            appId: WEF.fb_id,
            version: WEF.version,
            xfbml: true
        });
        if(!(typeof WEF.ajaxurl === "undefined")){
            FB.Event.subscribe('comment.create', wef_comment_callback);
            FB.Event.subscribe('comment.remove', wef_comment_callback);
        }

    });
});
var wef_comment_callback = function(response) {

    var data = { action : 'wpemfb_comments', response : response };
    jQuery.post(WEF.ajaxurl,data,function(res){
        console.log(res);
    });

};