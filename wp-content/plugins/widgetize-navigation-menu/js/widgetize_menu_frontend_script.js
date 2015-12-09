/* ----------------------------------------------------------------------------------
	FORMAT MAIN MENU
---------------------------------------------------------------------------------- */
	function widgetize_menu_options_plugin(){
	if(window.innerWidth >= 900) {
		jQuery("nav.widgetized-nav").width('auto');
		jQuery("nav.widgetized-nav").height('auto');
		jQuery("nav.widgetized-nav").parent().removeClass("widgetized-mobile-nav-parent");
		/*show main menu*/
		jQuery("nav.widgetized-nav").css('display','inline-block');
		/*hide all submenus*/
		jQuery("nav.widgetized-nav .main-nav .sub-menu").hide();
		/*add position relative to li's with normal submenu*/
		jQuery("nav.widgetized-nav .main-nav .sub-menu.widgetized-menu").parent().css('position','static');
		/*---------FOR 2ND LEVEL MENU ITEMS--------*/
		/* Add smooth dropdown effect */
		jQuery("nav.widgetized-nav .main-nav.first-level-menu > li.item").mouseover(function(){
			jQuery(this).find('.sub-menu.second-level-menu').stop(true).slideDown(400);
		});
		jQuery("nav.widgetized-nav .main-nav.first-level-menu > li.item").mouseleave(function(){
			jQuery(this).find('.sub-menu.second-level-menu').stop(true).slideUp(100);
		});
		/*---------FOR THIRD LEVEL MENU ITEMS--------*/
		/* Add smooth dropdown effect */
		jQuery("nav.widgetized-nav .sub-menu.second-level-menu > li.sub-item").mouseover(function(){
			jQuery(this).find('.sub-menu.third-level-menu').stop(true).slideDown(400);
		});
		jQuery("nav.widgetized-nav .sub-menu.second-level-menu > li.sub-item").mouseleave(function(){
			jQuery(this).find('.sub-menu.third-level-menu').stop(true).slideUp(100);
		});
	}//end if width >=900
	else{
		//to fix slidetoggle issue on resize
		jQuery(".widgetized-menu-button").unbind();
		jQuery(".menu-item-arrow").unbind();
		//make parent of navbar static & width 100% in order for mobile nav to be full width of screen
		jQuery("nav.widgetized-nav").parent().addClass("widgetized-mobile-nav-parent");
		var windowwidth=jQuery(window).width();
		jQuery("nav.widgetized-nav").width(windowwidth);	
		/*remove hover functions*/
		jQuery("nav.widgetized-nav .main-nav.first-level-menu > li.item").unbind('mouseleave mouseover');
		jQuery("nav.widgetized-nav .sub-menu.second-level-menu > li.sub-item").unbind('mouseleave mouseover');
		/*alter css for widgetized menu*/
		jQuery("nav.widgetized-nav .main-nav .sub-menu.widgetized-menu").parent().css('position','relative');	
	}
	jQuery("nav.widgetized-nav .sub-menu").height('auto');
	//for all sizes
		/*---------CLICK MENU BUTTON--------*/
		jQuery(".widgetized-menu-button").click(function(){
			jQuery("nav.widgetized-nav").stop(true,false).slideToggle('fast');
			if (window.showWidgetizedMobileNav!=true){
			window.showWidgetizedMobileNav=true;
		}
		else{window.showWidgetizedMobileNav=false;}
		});
		/*------CHECK ON CHEVRON ARROW--------*/
		jQuery(".menu-item-arrow").click(function(){
			jQuery(this).parent().find("> .sub-menu").stop(true, false).slideToggle(200);
		});
		

}//end function

jQuery(document).ready(function() {
	jQuery("nav.widgetized-nav .main-nav > li > a").height('auto');
	jQuery("nav.widgetized-nav .sub-menu.non-wgt-menu li > a").height('auto');
if (jQuery("nav.widgetized-nav")[0]){
		/*---------FOR 2ND LEVEL MENU ITEMS--------*/
		/*add class to main buttons with dropdown*/
		jQuery("nav.widgetized-nav .main-nav .sub-menu").parent().addClass('hasChildren');
		/*add class to main button with dropdown with current menu item*/
		jQuery("nav.widgetized-nav .current-menu-sub-item").parent().parent().addClass("current-menu-item");
		/*---------FOR THIRD LEVEL MENU ITEMS--------*/
		/*add class to submenu buttons with dropdown*/
		jQuery("nav.widgetized-nav .main-nav .sub-menu .sub-menu").parent().addClass('hasChildren');
		/*add class to submenu with dropdown with current menu item*/
		jQuery("nav.widgetized-nav .current-menu-sub-item").parent().parent().parent().parent().addClass("current-menu-item");
		/*hide any widgetized menus in first level submenu (if there was a duplicate menu name*/
		jQuery("nav.widgetized-nav .main-nav .item").each(function() {
			jQuery(this).find("> .second-level-menu").not(':first').remove();
		});

	/*add chevron arrow to main buttons with dropdown*/
	jQuery("nav.widgetized-nav .main-nav .sub-menu").parent().append('<span class="menu-item-arrow"><i class="icowdgt-chevron-arrow-down"></i></span>');
	//initially remoe all handlers from ul's and li's
	jQuery("nav.widgetized-nav ul, nav.widgetized-nav li").unbind(); 
		//position menu items
		window.needPositionDropDownMenu=true;
		if(window.innerWidth >= 900) {
			setTimeout(function() { positonMenuItems(); }, 100);
		}
		//main jquery functionality
	widgetize_menu_options_plugin();
 }//end if widgetized-nav class exists
});

jQuery(window).resize(function() {
if (jQuery("nav.widgetized-nav")[0]){
	//position menu items
	if(window.innerWidth >= 900 && window.needPositionDropDownMenu==true) {
		setTimeout(function() { positonMenuItems(); }, 100);
	}
	if(window.innerWidth >= 900){
	jQuery("nav.widgetized-nav").show();
	window.showWidgetizedMobileNav=false;
	}
	if(window.innerWidth <= 899 && window.showWidgetizedMobileNav!=true){
	jQuery("nav.widgetized-nav").hide();
	}
	//main jquery functionality
	widgetize_menu_options_plugin();
}//end if widgetized-nav class exists
});
function positonMenuItems(){			
		jQuery("nav.widgetized-nav").addClass("nav-testrelativity");
			var navWidth = jQuery("nav.widgetized-nav").width();		
			var navPosition_middle=navWidth / 2;		
		jQuery(".first-level-menu > li").each(function() {

			var itemPosition=jQuery(this).position().left;
			if (itemPosition <= navPosition_middle){
				//console.log("less than middle -"+itemPosition+" "+navPosition_middle);
				jQuery(this).find("> .sub-menu").removeClass('subPositionRight');
				jQuery(this).find("> .sub-menu").addClass('subPositionLeft');
			}
			else{
				//console.log("more than middle -"+itemPosition+" "+navPosition_middle );
				jQuery(this).find("> .sub-menu").removeClass('subPositionLeft');
				jQuery(this).find("> .sub-menu").addClass('subPositionRight');
			}
		});
		jQuery("nav.widgetized-nav").removeClass("nav-testrelativity");
		window.needPositionDropDownMenu=false;
	
}