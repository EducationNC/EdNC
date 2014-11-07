jQuery(document).ready(function(){
  
  
  
  var language_display = jQuery('input[name=googlelanguagetranslator_language_option]:checked').val();
  
      if ( language_display == 'all') {
       jQuery ('.languages').css('display','none');
	   jQuery ('.choose_flags').css('display','none');
		
	} else if (language_display == 'specific') {
	   jQuery ('.languages').css('display','inline');
	   jQuery ('.choose_flags_intro').css('display','none');
	   jQuery ('.choose_flags').css('display','none');
	}
	
  var display = jQuery('select[name=googlelanguagetranslator_display] option:selected').val();
  if ( display == 'Horizontal') {
	//jQuery ('.alignment').css('display','none');
  }
  
  jQuery('select[name=googlelanguagetranslator_display]').change(function() {
	if( jQuery(this).val() == 'Horizontal') {
	  //jQuery ('.alignment').fadeOut("slow");
	} else if ( jQuery(this).val() == 'Vertical') {
      //jQuery ('.alignment').fadeIn("slow");
	}
  });
  
  jQuery('input[name=googlelanguagetranslator_language_option]').change(function(){
      if( jQuery(this).val() == 'all'){
       jQuery ('.languages').fadeOut("slow");
	   jQuery ('.choose_flags_intro').css('display','');
		 var flag_display = jQuery('input[name=googlelanguagetranslator_flags]:checked').val();
		 if ( flag_display == 'show_flags') {
		  jQuery ('.choose_flags').css('display','');
		}
	} else if (jQuery(this).val() == 'specific') {
	   jQuery ('.languages').fadeIn("slow");
	   jQuery ('.choose_flags_intro').css('display','none');
	   jQuery ('.choose_flags').css('display','none');
	}
  });
      
  var language_display = jQuery('input[name=googlelanguagetranslator_language_option]:checked').val();    
  var flag_display = jQuery('input[name=googlelanguagetranslator_flags]:checked').val();
      if ( flag_display == 'hide_flags') {
       jQuery ('.choose_flags').css('display','none');
	} else if (flag_display == 'show_flags') {
	    if ( language_display == 'all') {
	      jQuery ('.choose_flags').css('display','');
	    }
	}
	
  jQuery('input[name=googlelanguagetranslator_flags]').change(function(){
      if( jQuery(this).val() == 'hide_flags'){
       jQuery ('.choose_flags').fadeOut("slow");
	} else if (jQuery(this).val() == 'show_flags') {
	   jQuery ('.choose_flags').fadeIn("slow");
	}
  });
  
    //FadeIn and FadeOut Google Analytics tracking settings
  jQuery('input[name=googlelanguagetranslator_analytics]').change(function() {
	if( jQuery(this).is(':checked')) {
	  jQuery('.analytics').fadeIn("slow");
	} else if ( jQuery(this).not(':checked')) {
	  jQuery('.analytics').fadeOut("slow");
	}
	  });
  
  //Hide or show Google Analytics ID field upon browser refresh  
  var analytics = jQuery('input[name=googlelanguagetranslator_analytics]');
  if ( analytics.is(':checked') )  {
       jQuery ('.analytics').css('display','');
  } else {
	   jQuery ('.analytics').css('display','none');
	}
  
  
  
  //Prevent the translator preview from translating Dashboard text
  jQuery('#adminmenu').addClass('notranslate');
  jQuery('#wp-toolbar').addClass('notranslate');
  jQuery('#setting-error-settings_updated').addClass('notranslate');
  jQuery('.update-nag').addClass('notranslate');
  jQuery('title').addClass('notranslate');
  jQuery('#footer-thankyou').addClass('notranslate');
});


 
