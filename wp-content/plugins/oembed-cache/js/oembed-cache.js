var OembedCache = (function($){
    var api = {};
    var embedopts;
	var doingAjax = false;

    api.init = function(opts) {
        embedopts = opts;
        setupBindings();
    }
    
    function setupBindings() {
        $(document).ready(function() {
            $("#clear_all_oembed_cache").click(function(){
                if(doingAjax == false){
					showLoader('all');
					clear("", function(){
						hideLoader('all');
					});
				}
            });
            $("#clear_specific_oembed_cache").click(function(){
                var postId = $.trim($("#oembed_post_id").val());
                if($.isNumeric(postId) && doingAjax == false){
                    showLoader('specific');
                    clear(postId, function(){
                        hideLoader('specific');
                    });
                }
            });
            $(document).on('click','#cache-clear-response .notice-dismiss',function(e){
                hideUiResponse();
            });            
        });
    }    
    
    function clear(postId, doneCallback) {
        doingAjax = true;
		$.ajax({
            url: embedopts.siteurl,
            data: {embedCacheClear: "true", postId: postId},
            cache: false,
            dataType: 'json',
            success: function(response) {
                if(response.status == "success"){
                    showUiResponse(embedopts.mess_cache_dest, 'updated');
                }
                else{
                    showUiResponse(embedopts.mess_error_db, 'error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                showUiResponse(ajaxErrorResp(jqXHR, textStatus, errorThrown), 'error');
            },
            complete: function(){
                doingAjax = false;
				doneCallback();
            }
        });
    }
    
    function ajaxErrorResp(request, type, errorThrown){
        var message = "";
        switch (type) {
            case 'timeout':
                message += embedopts.mess_error_timeout;
                break;
            case 'error':
                if(request.status === 0){
                    message += embedopts.mess_error_network;
                }
                else{
                    message += embedopts.mess_error + " " + request.status +".";
                }
                break;
            case 'parsererror':
                message += embedopts.mess_error_parse;
                break;
            default:
                message += embedopts.mess_error_unknown;
        }
        return message;
    }
    
    function showLoader(loaderId){
        $("#tbl-cache-actions .loader."+loaderId).show();
    }
    
    function hideLoader(loaderId){
        $("#tbl-cache-actions .loader."+loaderId).hide();
    }    
    
    function hideUiResponse(callback) {
        if(callback && $("#cache-clear-response").length == 0){
            callback();
        }     
        else{
            $("#cache-clear-response").animate({
                opacity: 0,
                height: 0
            }, 100, function() {
                $("#cache-clear-response").remove();
                if(callback){
                    callback();
                }
            });
        }
    }    
    
    function showUiResponse(message, statusClass) {
        hideUiResponse(function(){
            $('<div id="cache-clear-response" class="'+statusClass+' notice is-dismissible"></div>').insertBefore("#tbl-cache-actions");
            $("#cache-clear-response").html('<p><strong class="text">'+message+'</strong></p>');
            $("#cache-clear-response").append('<button type="button" class="notice-dismiss"><span class="screen-reader-text">'+embedopts.mess_dismiss_notice+'</span></button>');            
        });
    }     

    return api;
 }(jQuery));