jQuery(document).ready(function() {
	resize_cover();
  	var v_width = jQuery(".wef-measure").outerWidth();
	jQuery(".fb-post").attr("data-width",v_width+"px");   	
});
jQuery(window).resize(function(){
	resize_cover();	
});
function resize_cover(){
    jQuery(".wef-measure > .cover").each(function(){
        jQuery(this).css("height",0.368 * jQuery(this).width());
    });
}