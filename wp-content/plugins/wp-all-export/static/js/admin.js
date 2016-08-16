/**
 * plugin admin area javascript
 */
(function($){$(function () {

	if ( ! $('body.wpallexport-plugin').length) return; // do not execute any code if we are not on plugin page

	// fix layout position
	setTimeout(function () {
		$('table.wpallexport-layout').length && $('table.wpallexport-layout td.left h2:first-child').css('margin-top',  $('.wrap').offset().top - $('table.wpallexport-layout').offset().top);
	}, 10);	
	
	// help icons
	$('a.wpallexport-help').tipsy({
		gravity: function() {
			var ver = 'n';
			if ($(document).scrollTop() < $(this).offset().top - $('.tipsy').height() - 2) {
				ver = 's';
			}
			var hor = '';
			if ($(this).offset().left + $('.tipsy').width() < $(window).width() + $(document).scrollLeft()) {
				hor = 'w';
			} else if ($(this).offset().left - $('.tipsy').width() > $(document).scrollLeft()) {
				hor = 'e';
			}
	        return ver + hor;
	    },
		live: true,
		html: true,
		opacity: 1
	}).live('click', function () {
		return false;
	}).each(function () { // fix tipsy title for IE
		$(this).attr('original-title', $(this).attr('title'));
		$(this).removeAttr('title');
	});	

	if ($('#wp_all_export_code').length){
		var editor = CodeMirror.fromTextArea(document.getElementById("wp_all_export_code"), {
	        lineNumbers: true,
	        matchBrackets: true,
	        mode: "application/x-httpd-php",
	        indentUnit: 4,
	        indentWithTabs: true,
	        lineWrapping: true
	    });
	    editor.setCursor(1);
	    $('.CodeMirror').resizable({
		  resize: function() {
		    editor.setSize("100%", $(this).height());
		  }
		}); 
	}
	
	// swither show/hide logic
	$('input.switcher').live('change', function (e) {	

		if ($(this).is(':radio:checked')) {
			$(this).parents('form').find('input.switcher:radio[name="' + $(this).attr('name') + '"]').not(this).change();
		}
		var $switcherID = $(this).attr('id');
		var $targets = $('.switcher-target-' + $switcherID);

		var is_show = $(this).is(':checked'); if ($(this).is('.switcher-reversed')) is_show = ! is_show;
		if (is_show) {
			$targets.fadeIn('fast', function(){
				if ($switcherID == 'coperate_php'){								
					editor.setCursor(1);
				}
			});
		} else {
			$targets.hide().find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).change();

	// swither show/hide logic
	$('input.switcher-horizontal').live('change', function (e) {	
		
		if ($(this).is(':checked')) {
			$(this).parents('form').find('input.switcher-horizontal[name="' + $(this).attr('name') + '"]').not(this).change();
		}
		var $targets = $('.switcher-target-' + $(this).attr('id'));

		var is_show = $(this).is(':checked'); if ($(this).is('.switcher-reversed')) is_show = ! is_show;
		
		if (is_show) {
			$targets.animate({width:'205px'}, 350);
		} else {
			$targets.animate({width:'0px'}, 1000).find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).change();
	
	// autoselect input content on click
	$('input.selectable').live('click', function () {
		$(this).select();
	});	

	$('.pmxe_choosen').each(function(){
		$(this).find(".choosen_input").select2({tags: $(this).find('.choosen_values').html().split(',')});
	});
	
	// choose file form: option selection dynamic
	// options form: highlight options of selected post type
	$('form.choose-post-type input[name="type"]').click(function() {		
		var $container = $(this).parents('.file-type-container');		
		$('.file-type-container').not($container).removeClass('selected').find('.file-type-options').hide();
		$container.addClass('selected').find('.file-type-options').show();
	}).filter(':checked').click();		

	$('.wpallexport-collapsed').each(function(){

		if ( ! $(this).hasClass('closed')) $(this).find('.wpallexport-collapsed-content:first').slideDown();

	});

	$('.wpallexport-collapsed').find('.wpallexport-collapsed-header').live('click', function(){
		var $parent = $(this).parents('.wpallexport-collapsed:first');
		if ($parent.hasClass('closed')){			
			$parent.removeClass('closed');
			$parent.find('.wpallexport-collapsed-content:first').slideDown();
		}
		else{
			$parent.addClass('closed');			
			$parent.find('.wpallexport-collapsed-content:first').slideUp();			
		}
	});	

	// [ Helper functions ]		
	
	var get_valid_ajaxurl = function()
	{
		var $URL = ajaxurl;
	    if (typeof export_id != "undefined")
	    {
	    	if ($URL.indexOf("?") == -1)
	    	{
	    		$URL += '?id=' + export_id;
	    	}
	    	else
	    	{
	    		$URL += '&id=' + export_id;
	    	}
	    }
	    return $URL;
	}

	// generate warning on a fly when required fields deleting from the export template
	var trigger_warnings = function()
	{

		var missing_fields = ['id'];

		if ( $('#is_product_export').length ) missing_fields = missing_fields.concat(['_sku', 'product_type']);
		if ( $('#is_wp_query').length ) missing_fields.push('post_type'); 

		$('#columns').find('li:not(.placeholder)').each(function(i, e){
			$(this).find('div.custom_column:first').attr('rel', i + 1);
			if ($(this).find('input[name^=cc_type]').val() == 'id'){
				var index = missing_fields.indexOf('id');
				if (index > -1) {
				    missing_fields.splice(index, 1);
				}
			}
			if ($(this).find('input[name^=cc_label]').val() == '_sku'){				
				var index = missing_fields.indexOf('_sku');
				if (index > -1) {
				    missing_fields.splice(index, 1);
				}

			}
			if ($(this).find('input[name^=cc_label]').val() == 'product_type'){				
				var index = missing_fields.indexOf('product_type');
				if (index > -1) {
				    missing_fields.splice(index, 1);
				}
			}
			if ($(this).find('input[name^=cc_label]').val() == 'post_type'){				
				var index = missing_fields.indexOf('post_type');
				if (index > -1) {
				    missing_fields.splice(index, 1);
				}
			}
		});

		if ( missing_fields.length )
		{
			var fields = '';
			switch (missing_fields.length)
			{
				case 1:
					fields = missing_fields.shift();
					break;
				case 2:
					fields = missing_fields.join(" and ");
					break;
				default:
					var latest_field = missing_fields.pop();
					fields = missing_fields.join(", ") + ", and " + latest_field;
					break;
			}

			var warning_template = $('#warning_template').length ? $('#warning_template').val().replace("%s", fields) : '';

			$('.wp-all-export-warning').find('p').html(warning_template);
			$('.wp-all-export-warning').show();
		}
		else
		{
			$('.wp-all-export-warning').hide();
		}
		
	}
	
	// Get a valid filtering rules for selected field type		
	var init_filtering_fields = function(){

		var wp_all_export_rules_config = {
	      '#wp_all_export_xml_element' : {width:"98%"},
	      '#wp_all_export_rule' : {width:"98%"},    
	    }

	    for (var selector in wp_all_export_rules_config) {

	    	$(selector).chosen(wp_all_export_rules_config[selector]);
	    	
	    	if (selector == '#wp_all_export_xml_element'){

		    	$(selector).on('change', function(evt, params) {

		    		$('#wp_all_export_available_rules').html('<div class="wp_all_export_preloader" style="display:block;"></div>');

		    		var date_fields = ['post_date', 'comment_date', 'user_registered'];

	    			if ( date_fields.indexOf(params.selected) > -1 )
		    		{
		    			$('#date_field_notice').show();
		    		}
		    		else
		    		{
		    			$('#date_field_notice').hide();
		    		}

		    		var request = {
						action: 'wpae_available_rules',	
						data: {'selected' : params.selected},				
						security: wp_all_export_security				
				    }; 
				    $.ajax({
						type: 'POST',
						url: ajaxurl,
						data: request,
						success: function(response) {	
							$('#wp_all_export_available_rules').html(response.html);
							$('#wp_all_export_rule').chosen({width:"98%"});
							$('#wp_all_export_rule').on('change', function(evt, params) {
								if (params.selected == 'is_empty' || params.selected == 'is_not_empty')
									$('#wp_all_export_value').hide();
								else
									$('#wp_all_export_value').show();
							});							
						},
						dataType: "json"
					});
		    	});
		    }						    
	    }					

	    $('.wp_all_export_filtering_rules').pmxe_nestedSortable({
	        handle: 'div',
	        items: 'li.dragging',
	        toleranceElement: '> div',
	        update: function () {	        
	        	$('.wp_all_export_filtering_rules').find('.condition').removeClass('last_condition').show();
	        	$('.wp_all_export_filtering_rules').find('.condition:last').addClass('last_condition'); 
	        	liveFiltering();    								
		    }
	    });

	}

	var is_first_load = true;

	var filtering = function(postType){

		var is_preload = $('.wpallexport-preload-post-data').val();
		var filter_rules_hierarhy = parseInt(is_preload) ? $('input[name=filter_rules_hierarhy]').val() : '';

		$('.wpallexport-preload-post-data').val(0);

		var request = {
			action: 'wpae_filtering',	
			data: {'cpt' : postType, 'export_type' : 'specific', 'filter_rules_hierarhy' : filter_rules_hierarhy, 'product_matching_mode' : 'strict'},				
			security: wp_all_export_security				
	    };    

	    if (is_first_load == false || postType != '') $('.wp_all_export_preloader').show();	    

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: request,
			success: function(response) {	

				$('.wp_all_export_preloader').hide();

				var export_type = $('input[name=export_type]').val();

				if (export_type == 'advanced')
				{
					$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();
					$('.wpallexport-choose-file').find('.wp_all_export_continue_step_two').html(response.btns);
					$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').show();
				}
				else
				{
					if (postType != '')
					{

						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').html(response.html);										
						$('.wpallexport-choose-file').find('.wp_all_export_continue_step_two').html(response.btns);

						init_filtering_fields();
						liveFiltering(is_first_load);
					}
					else
					{
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();
					}
				}				

				is_first_load = false;

			},
			error: function( jqXHR, textStatus ) {	
				
				$('.wp_all_export_preloader').hide();

			},
			dataType: "json"
		});

	}	

	var liveFiltering = function(first_load, after_filtering){

		// serialize filters
		$('.hierarhy-output').each(function(){
			var sortable = $('.wp_all_export_filtering_rules.ui-sortable');
			if (sortable.length){
				$(this).val(window.JSON.stringify(sortable.pmxe_nestedSortable('toArray', {startDepthCount: 0})));								
			}				
		});

		var postType = $('input[name=cpt]').length ? $('input[name=cpt]').val() : $('input[name=selected_post_type]').val();

		// prepare data for ajax request to get post count after filtering
		var request = {
			action: 'wpae_filtering_count',	
			data: {
				'cpt' : postType, 
				'filter_rules_hierarhy' : $('input[name=filter_rules_hierarhy]').val(), 
				'product_matching_mode' : $('select[name=product_matching_mode]').length ? $('select[name=product_matching_mode]').val() : '',
				'is_confirm_screen' : $('.wpallexport-step-4').length,
				'is_template_screen' : $('.wpallexport-step-3').length,
				'export_only_new_stuff' : $('#export_only_new_stuff').is(':checked') ? 1 : 0,		
				'export_type' : $('input[name=export_type]').val()				
			},				
			security: wp_all_export_security				
	    };    

	    $('.wp_all_export_preloader').show();	    
	    $('.wp_all_export_filter_preloader').show();	    	    

		$.ajax({
			type: 'POST',
			url: get_valid_ajaxurl(),
			data: request,
			success: function(response) {	

				$('.wp_all_export_filter_preloader').hide();				

				$('#filtering_result').html(response.html);

				$('.wpallexport-choose-file').find('.wpallexport-filtering-wrapper').slideDown(400, function(){
					if (typeof first_load != 'undefined')
					{
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideDown();
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').addClass('closed');
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').show();
					}
				});

				$('.wp_all_export_preloader').hide();	  

				if (typeof after_filtering != 'undefined')
				{
					after_filtering(response);
				}				

		    	if ( $('.wpallexport-step-4').length && typeof wp_all_export_L10n != 'undefined'){
	    		
	    			if (response.found_records)
	    			{
	    				$('.wp_all_export_confirm_and_run').show();
	    				$('.confirm_and_run_bottom').val(wp_all_export_L10n.confirm_and_run);
	    				$('#filtering_result').removeClass('nothing_to_export');
	    			}
	    			else
	    			{
	    				$('.wp_all_export_confirm_and_run').hide();
	    				$('.confirm_and_run_bottom').val(wp_all_export_L10n.save_configuration);
	    				$('#filtering_result').addClass('nothing_to_export');
	    			}
		    	}

		    	if ( $('.wpallexport-step-3').length ){
	    			
	    			$('.founded_records').html(response.html);

	    			if (response.found_records)
		    		{
		    			$('.founded_records').removeClass('nothing_to_export');
		    		}
		    		else
		    		{
		    			$('.founded_records').addClass('nothing_to_export');
		    		}
		    	}

		    	if ( $('.wpallexport-step-1').length)
		    	{
		    		if (response.found_records)
		    		{
		    			$('.founded_records').removeClass('nothing_to_export');
		    		}
		    		else
		    		{
		    			$('.founded_records').addClass('nothing_to_export');
		    		}
		    	}

			},
			error: function( jqXHR, textStatus ) {	
				
				$('.wp_all_export_filter_preloader').hide();
				$('.wp_all_export_preloader').hide();	    

			},
			dataType: "json"
		}).fail(function(xhr, textStatus, error) {		    
			$('.wpallexport-header').next('.clear').after("<div class='error inline'><p>" + textStatus + " " + error + "</p></div>");		    
		});

	}
	// [ \Helper functions ]			


	// [ Step 1 ( chose & filter export data ) ]
	$('.wpallexport-step-1').each(function(){		
						
		var $wrap = $('.wrap');

		var formHeight = $wrap.height();

		$('.wpallexport-import-from').click(function(){			
			
			var showImportType = false;
			
			var postType = $('input[name=cpt]').val();

			switch ($(this).attr('rel')){				
				case 'specific_type':
					
					$('.wpallexport-user-export-notice').hide();
					$('.wpallexport-shop_customer-export-notice').hide();
		    		$('.wpallexport-comments-export-notice').hide();

					if (postType != '')
					{
						if (postType == 'users'){							
							$('.wpallexport-user-export-notice').show();
							showImportType = false; 						
							$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideDown();
						}						
						else if (postType == 'comments')
						{
							$('.wpallexport-comments-export-notice').show();
							showImportType = false; 						
							$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideDown();
						}
						else if (postType == 'shop_customer')
						{
							$('.wpallexport-customer-export-notice').show();
							showImportType = false; 						
							$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideDown();
						}
						else
						{							
							showImportType = true; 						
							$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideDown();
						}						
						
						$('.wpallexport-filtering-wrapper').show();				
					}											
					break;
				case 'advanced_type':		

					$('.wpallexport-user-export-notice').hide();
		    		$('.wpallexport-comments-export-notice').hide();
		    		$('.wpallexport-shop_customer-export-notice').hide();

					if ($('input[name=wp_query_selector]').val() == 'wp_user_query')
					{
						$('.wpallexport-user-export-notice').show();
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();					
						showImportType = false; 
					}
					else if ($('input[name=wp_query_selector]').val() == 'wp_comment_query')
					{
						$('.wpallexport-comments-export-notice').show();
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();					
						showImportType = false; 
					}
					else
					{						
						$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();					
						showImportType = true; 
					}										
					$('.wpallexport-filtering-wrapper').hide();
					filtering();
					break;
			}			
			
			$('.wpallexport-import-from').removeClass('selected').addClass('bind');			
			$(this).addClass('selected').removeClass('bind');
			$('.wpallexport-choose-file').find('.wpallexport-upload-type-container').hide();			
			$('.wpallexport-choose-file').find('.wpallexport-upload-type-container[rel=' + $(this).attr('rel') + ']').show();			
			$('.wpallexport-choose-file').find('input[name=export_type]').val( $(this).attr('rel').replace('_type', '') );
			
			if ( ! showImportType)
			{						
				$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();						
			}
			else{						
				$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').show();						
			}

		});

		$('.wpallexport-import-from.selected').click();			

		$('#file_selector').ddslick({
			width: 600,	
			onSelected: function(selectedData){											

				$('.wpallexport-user-export-notice').hide();
		    	$('.wpallexport-comments-export-notice').hide();
		    	$('.wpallexport-shop_customer-export-notice').hide();

		    	if (selectedData.selectedData.value != ""){
		    		
		    		$('#file_selector').find('.dd-selected').css({'color':'#555'});

		    		var i = 0;
					var postType = selectedData.selectedData.value;
					$('#file_selector').find('.dd-option-value').each(function(){
						if (postType == $(this).val()) return false;
						i++;
					});

					$('.wpallexport-choose-file').find('input[name=cpt]').val(postType);										

					if (postType == 'users')
					{
						$('.wpallexport-user-export-notice').show();
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();	
					}
					else if (postType == 'comments')
					{
						$('.wpallexport-comments-export-notice').show();
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();	
					}
					else if (postType == 'shop_customer')
					{
						$('.wpallexport-shop_customer-export-notice').show();
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();	
					}
					else
					{						
						$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').show();	
					}					

					filtering(postType);					
					
		    	}
		    	else
		    	{		    				    		
		    		$('.wpallexport-choose-file').find('input[name=cpt]').val('');	
		    		$('#file_selector').find('.dd-selected').css({'color':'#cfceca'});
		    		$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();		
		    		$('.wpallexport-choose-file').find('.wpallexport-filtering-wrapper').slideUp();    		
			
					switch ($('.wpallexport-import-from.selected').attr('rel')){				
						case 'specific_type':
							filtering($('input[name=cpt]').val());								
							break;
						case 'advanced_type':					
							
							break;
					}						
		    	}
		    } 
		});										
	
		$('a.auto-generate-template').live('click', function(){
			$('input[name^=auto_generate]').val('1');
			
			$('.hierarhy-output').each(function(){
				var sortable = $('.wp_all_export_filtering_rules.ui-sortable');
				if (sortable.length){
					$(this).val(window.JSON.stringify(sortable.pmxe_nestedSortable('toArray', {startDepthCount: 0})));								
				}				
			});			

			$(this).parents('form:first').submit();
		});

		$('form.wpallexport-choose-file').find('input[type=submit]').click(function(e){
			e.preventDefault();			
			
			$('.hierarhy-output').each(function(){
				var sortable = $('.wp_all_export_filtering_rules.ui-sortable');
				if (sortable.length){
					$(this).val(window.JSON.stringify(sortable.pmxe_nestedSortable('toArray', {startDepthCount: 0})));								
				}				
			});			

			$(this).parents('form:first').submit();
		});

		$('#wp_query_selector').ddslick({
			width: 600,	
			onSelected: function(selectedData){											
				
				$('.wpallexport-user-export-notice').hide();
		    	$('.wpallexport-comments-export-notice').hide();
		    	$('.wpallexport-shop_customer-export-notice').hide();
		    	
		    	$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();

		    	if (selectedData.selectedData.value != ""){
		    		
		    		$('#wp_query_selector').find('.dd-selected').css({'color':'#555'});		
		    		var queryType = selectedData.selectedData.value;    					
		    		if (queryType == 'wp_query'){
		    			$('textarea[name=wp_query]').attr("placeholder", "'post_type' => 'post', 'post_status' => array( 'pending', 'draft', 'future' )");
		    			$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').show();
		    		}
		    		if(queryType == 'wp_user_query')
		    		{
		    			$('.wpallexport-user-export-notice').show();		    			
		    			$('textarea[name=wp_query]').attr("placeholder", "'role' => 'Administrator'");
		    			$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();	
		    		}		    		
		    		else if(queryType == 'wp_comment_query')
		    		{		    			
		    			$('.wpallexport-comments-export-notice').show();
		    			$('textarea[name=wp_query]').attr("placeholder", "'meta_key' => 'featured', 'meta_value' => '1'");		    			
		    			$('.wpallexport-choose-file').find('.wpallexport-submit-buttons').hide();	
		    		}
					$('input[name=wp_query_selector]').val(queryType);
		    	}
		    	else{
		    		
		    		$('#wp_query_selector').find('.dd-selected').css({'color':'#cfceca'});
		    		$('.wpallexport-choose-file').find('.wpallexport-upload-resource-step-two').slideUp();		    		
			
		    	}
		    } 
		});	

	});	
	// [ \Step 1 ( chose & filter export data ) ]


	// [ Step 2 ( export template ) ]
	$('.wpallexport-export-template').each(function(){

		trigger_warnings();

		var $sortable = $( "#columns" );

		$( "#available_data li:not(.available_sub_section, .wpallexport_disabled)" ).draggable({
			appendTo: "body",
			helper: "clone"
		});

		var outsideContainer = 0;

		// this one control if the draggable is outside the droppable area
		$('#columns_to_export').droppable({
		    accept      : '.ui-sortable-helper'		    
		});

		$( "#columns_to_export" ).on( "dropout", function( event, ui ) {
			outsideContainer = 1;
			ui.draggable.find('.custom_column').css('background', 'white');			
		} );

		$( "#columns_to_export" ).on( "dropover", function( event, ui ) {
			outsideContainer = 0;
			ui.draggable.find('.custom_column').css('background', 'white');			
		} );

		// this one control if the draggable is dropped
		$('body, form.wpallexport-template').droppable({
		    accept      : '.ui-sortable-helper',
		    drop        : function(event, ui){
		        if(outsideContainer == 1){		        	
		            ui.draggable.remove();
		            trigger_warnings();
		            
		            if ( $('#columns').find('li:not(.placeholder)').length === 1)
		            {
						$('#columns').find( ".placeholder" ).show();					
					}
		        }else{
		            ui.draggable.find('.custom_column').css('background', 'none');
		        }
		    }
		});		

		$( "#columns_to_export ol" ).droppable({
			activeClass: "pmxe-state-default",
			hoverClass: "pmxe-state-hover",
			accept: ":not(.ui-sortable-helper)",
			drop: function( event, ui ) {				
				$( this ).find( ".placeholder" ).hide();					
				
				if (ui.draggable.find('input[name^=rules]').length){						
					$('li.' + ui.draggable.find('input[name^=rules]').val()).each(function(){				
						var $value = $(this).find('input[name^=cc_value]').val();
						var $is_media_field = false;
						if ( $(this).find('input[name^=cc_type]').val().indexOf('image_') !== -1 || $(this).find('input[name^=cc_type]').val().indexOf('attachment_') !== -1 )
						{
							$value = $(this).find('input[name^=cc_type]').val();
							$is_media_field = true;
						}
						var $add_field = true;
						$('#columns').find('li').each(function(){
							if ( $is_media_field )
							{
								if ($(this).find('input[name^=cc_type]').val() == $value){
									$add_field = false;
								}
							}
							else
							{
								if ($(this).find('input[name^=cc_value]').val() == $value){
									$add_field = false;
								}
							}
						});
						if ($add_field)
						{
							$( "<li></li>" ).html( $(this).html() ).appendTo( $( "#columns_to_export ol" ) );
							var $just_added = $('#columns').find('li:last').find('div:first');
							$just_added.attr('rel', $('#columns').find('li:not(.placeholder)').length);						
							if ( $just_added.find('input[name^=cc_type]').val().indexOf('image_') !== -1 )
							{
								$just_added.find('.wpallexport-xml-element').html('Image ' + $just_added.find('input[name^=cc_name]').val());
								$just_added.find('input[name^=cc_name]').val('Image ' + $just_added.find('input[name^=cc_name]').val());
							}
							if ( $just_added.find('input[name^=cc_type]').val().indexOf('attachment_') !== -1 )
							{
								$just_added.find('.wpallexport-xml-element').html('Attachment ' + $just_added.find('input[name^=cc_name]').val());
								$just_added.find('input[name^=cc_name]').val('Attachment ' + $just_added.find('input[name^=cc_name]').val());
							}
						}
					});
				}
				else{
					$( "<li></li>" ).html( ui.draggable.html() ).appendTo( this );
					var $just_added = $('#columns').find('li:last').find('div:first');
					$just_added.attr('rel', $('#columns').find('li:not(.placeholder)').length);
					if ( $just_added.find('input[name^=cc_type]').val().indexOf('image_') !== -1 )
					{
						$just_added.find('.wpallexport-xml-element').html('Image ' + $just_added.find('input[name^=cc_name]').val());
						$just_added.find('input[name^=cc_name]').val('Image ' + $just_added.find('input[name^=cc_name]').val());
					}
					if ( $just_added.find('input[name^=cc_type]').val().indexOf('attachment_') !== -1 )
					{
						$just_added.find('.wpallexport-xml-element').html('Attachment ' + $just_added.find('input[name^=cc_name]').val());
						$just_added.find('input[name^=cc_name]').val('Attachment ' + $just_added.find('input[name^=cc_name]').val());
					}
				}				

				trigger_warnings();

			}
		}).sortable({
			items: "li:not(.placeholder)",
			sort: function() {
				// gets added unintentionally by droppable interacting with sortable
				// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
				$( this ).removeClass( "ui-state-default" );
			}
		});		

		var $this = $(this);
		var $addAnother = $this.find('input.add_column');
		var $addAnotherForm = $('fieldset.wp-all-export-edit-column');
		var $template = $(this).find('.custom_column.template');		

		if (typeof wpPointerL10n != "undefined") wpPointerL10n.dismiss = 'Close';

		// Add Another btn click
		$addAnother.click(function(){
			
			$addAnotherForm.find('form')[0].reset();
			$addAnotherForm.find('input[type=checkbox]').removeAttr('checked');		
			
			$addAnotherForm.removeAttr('rel');
			$addAnotherForm.removeClass('dc').addClass('cc');			
			$addAnotherForm.find('.cc_field').hide();
			
			$addAnotherForm.find('.wpallexport-edit-row-title').hide();
			$addAnotherForm.find('.wpallexport-add-row-title').show();
			$addAnotherForm.find('div[class^=switcher-target]').hide();
			$addAnotherForm.find('#coperate_php').removeAttr('checked');
			$addAnotherForm.find('input.column_name').parents('div.input:first').show();
			
			$('.custom_column').removeClass('active');

			$addAnotherForm.find('select[name=column_value_type]').find('option').each(function(){
				if ($(this).val() == 'id') 
					$(this).attr({'selected':'selected'}).click();
				else
					$(this).removeAttr('selected');
			});  

			$('.wp-all-export-chosen-select').trigger('chosen:updated');			
			$('.wp_all_export_saving_status').html('');

			$('.wpallexport-overlay').show();		
			$addAnotherForm.find('input.switcher').change();
			$addAnotherForm.show();					
			
		});
		
		// Delete custom column action
		$addAnotherForm.find('.delete_action').click(function(){			
			
			$('.custom_column').removeClass('active');

			$('.custom_column[rel='+ $addAnotherForm.attr('rel') +']').parents('li:first').fadeOut().remove();			

			if ( ! $('#columns').find('li:visible').length ){
				$('#columns').find( ".placeholder" ).show();					
			}

			trigger_warnings();

			$addAnotherForm.fadeOut();
			$('.wpallexport-overlay').hide();
		});		

		// Add/Edit custom column action
		$addAnotherForm.find('.save_action').click(function(){						

			var $save = true;			

			// element name in exported file
			var $elementName = $addAnotherForm.find('input.column_name');	
			
			// element name validation
			if ($elementName.val() == '')
			{
				$save = false;
				$elementName.addClass('error');
				return false;
			}

			// get PHP function name
			var $phpFunction = $addAnotherForm.find('.php_code:visible');	

			// validation passed, prepare field data
			var $elementIndex = $addAnotherForm.attr('rel');
			// element type
			var $elementType = $addAnotherForm.find('select[name=column_value_type]');
			// element label, options and other stuff
			var $elementDetails = $elementType.find('option:selected');

			var $clone = ( $elementIndex ) ? $('#columns').find('.custom_column[rel='+ $elementIndex +']') : $template.clone(true);

			// if new field adding
			if ( ! parseInt( $elementIndex ) ) 
			{
				// new column added, increase element Index
				$clone.attr('rel', $('#columns').find('.custom_column').length + 1);
			}

			// add element label
			$clone.find('label.wpallexport-xml-element').html( $elementName.val() );
			// wrap field value into PHP function
			$clone.find('input[name^=cc_php]').val( $addAnotherForm.find('#coperate_php').is(':checked') ? '1' : '0' );
			// save PHP function name
			$clone.find('input[name^=cc_code]').val( $phpFunction.val() );
			// save SQL code
			$clone.find('input[name^=cc_sql]').val( $addAnotherForm.find('textarea.column_value').val() );
 			// save element name
			$clone.find('input[name^=cc_name]').val( $elementName.val() == "ID" ? "id" : $elementName.val() );
			// save element type
			$clone.find('input[name^=cc_type]').val( $elementType.val() );	
			// save element value
			$clone.find('input[name^=cc_value]').val( $elementDetails.attr('label') );
			// save element label
			$clone.find('input[name^=cc_label]').val( $elementDetails.attr('label') );	
			// save element options
			$clone.find('input[name^=cc_options]').val( $elementDetails.attr('options') );	

			// if new field adding append element to the export template
			if ( ! parseInt( $elementIndex ) ) 
			{
				$( "#columns" ).find( ".placeholder" ).hide();							
				$sortable.append('<li></li>');
				$sortable.find('li:last').append($clone.removeClass('template').fadeIn());				
			}			

			// set up additional element settings by element type
			switch ( $elementType.val() )
			{
				// save post date field format
				case 'date':											
					var $dateType = $addAnotherForm.find('select.date_field_export_data').val();
					if ($dateType == 'unix')
						$clone.find('input[name^=cc_settings]').val('unix');
					else
						$clone.find('input[name^=cc_settings]').val($('.pmxe_date_format').val());
					break;				
				// set up additional settings for repeater field
				case 'acf':				
					// determine is repeater field selected in dropdown				
					if ( $clone.find('input[name^=cc_options]').val().indexOf('s:4:"type";s:8:"repeater"') !== -1 )
					{
						var obj = {};
						obj['repeater_field_item_per_line'] = $addAnotherForm.find('#repeater_field_item_per_line').is(':checked');
						obj['repeater_field_fill_empty_columns'] = $addAnotherForm.find('#repeater_field_fill_empty_columns').is(':checked');							
						$clone.find('input[name^=cc_settings]').val(window.JSON.stringify(obj));							
					}
					break;
				case 'woo':					
					switch ( $clone.find('input[name^=cc_value]').val() )
					{
						case '_upsell_ids':
						case '_crosssell_ids':
						case 'item_data___upsell_ids':
						case 'item_data___crosssell_ids':
							$clone.find('input[name^=cc_settings]').val($addAnotherForm.find('select.linked_field_export_data').val());
							break;
					}
					break;
				default:
					// save option for media images field types
					if ( $clone.find('input[name^=cc_type]').val().indexOf('image_') !== -1 )
					{
						var obj = {};
						obj['is_export_featured'] = $addAnotherForm.find('#is_image_export_featured').is(':checked');
						obj['is_export_attached'] = $addAnotherForm.find('#is_image_export_attached_images').is(':checked');
						obj['image_separator'] = $addAnotherForm.find('input[name=image_field_separator]').val();
						$clone.find('input[name^=cc_options]').val(window.JSON.stringify(obj));							
					}					
											
					break;
			}

			trigger_warnings();

			$addAnotherForm.hide();

			$('.wpallexport-overlay').hide();

			$('.custom_column').removeClass('active');
			
		});		
		
		// Clicking on column for edit
		$('#columns').find('.custom_column').live('click', function(){
			
			$addAnotherForm.find('form')[0].reset();	
			$addAnotherForm.find('input[type=checkbox]').removeAttr('checked');					
			
			$addAnotherForm.removeClass('dc').addClass('cc');
			$addAnotherForm.attr('rel', $(this).attr('rel'));

			$addAnotherForm.find('.wpallexport-add-row-title').hide();
			$addAnotherForm.find('.wpallexport-edit-row-title').show();			

			$addAnotherForm.find('input.column_name').parents('div.input:first').show();

			$addAnotherForm.find('.cc_field').hide();			
			$('.custom_column').removeClass('active');
			$(this).addClass('active');
			
			var $elementType  = $(this).find('input[name^=cc_type]');	
			var $elementLabel = $(this).find('input[name^=cc_label]');			


			$('.wp_all_export_saving_status').html('');			

			$addAnotherForm.find('select[name=column_value_type]').find('option').each(function(){
				if ($(this).attr('label') == $elementLabel.val() && $(this).val() == $elementType.val()) 
					$(this).attr({'selected':'selected'}).click();
				else
					$(this).removeAttr('selected');
			}); 

			$('.wp-all-export-chosen-select').trigger('chosen:updated');

			// set php snipped
			var $php_code = $(this).find('input[name^=cc_code]');
			var $is_php = parseInt($(this).find('input[name^=cc_php]').val());
			
			if ($is_php){ 
				$addAnotherForm.find('#coperate_php').attr({'checked':'checked'}); 
				$addAnotherForm.find('#coperate_php').parents('div.input:first').find('div[class^=switcher-target]').show();
			}
			else{ 
				$addAnotherForm.find('#coperate_php').removeAttr('checked');
				$addAnotherForm.find('#coperate_php').parents('div.input:first').find('div[class^=switcher-target]').hide();
			}

			$addAnotherForm.find('#coperate_php').parents('div.input:first').find('.php_code').val($php_code.val());					

			var $options = $(this).find('input[name^=cc_options]').val();
			var $settings = $(this).find('input[name^=cc_settings]').val();			

			switch ( $elementType.val() ){
				case 'sql':
					$addAnotherForm.find('textarea.column_value').val($(this).find('input[name^=cc_sql]').val());
					$addAnotherForm.find('.sql_field_type').show();
					break;				
				case 'acf':					
					if ($options.indexOf('s:4:"type";s:8:"repeater"') !== -1)
					{						
						$addAnotherForm.find('.repeater_field_type').show();						
						if ($settings != "")
						{
							var $field_options = window.JSON.parse($settings);
							if ($field_options.repeater_field_item_per_line) $addAnotherForm.find('#repeater_field_item_per_line').attr('checked','checked');
							if ($field_options.repeater_field_fill_empty_columns) $addAnotherForm.find('#repeater_field_fill_empty_columns').attr('checked','checked');							
						}
					}
					break;
				case 'woo':					
					$woo_type = $(this).find('input[name^=cc_value]');		
					switch ($woo_type.val())
					{
						case '_upsell_ids':
						case '_crosssell_ids':
						case 'item_data___upsell_ids':
						case 'item_data___crosssell_ids':

							$addAnotherForm.find('select.linked_field_export_data').find('option').each(function(){
								if ($(this).val() == $settings) 
									$(this).attr({'selected':'selected'}).click();
								else
									$(this).removeAttr('selected');
							});
							$addAnotherForm.find('.linked_field_type').show();
							break;
					}
					break;				
				case 'date':
					$addAnotherForm.find('select.date_field_export_data').find('option').each(function(){
						if ($(this).val() == $settings || $settings != 'unix' && $(this).val() == 'php') 
							$(this).attr({'selected':'selected'}).click();
						else
							$(this).removeAttr('selected');
					});			

					if ($settings != 'php' && $settings != 'unix'){
						$('.pmxe_date_format').val($settings);
						$('.pmxe_date_format_wrapper').show();
					}
					else
						$('.pmxe_date_format').val('');
					$addAnotherForm.find('.date_field_type').show();
					break;				
				default:					
					
					if ( $elementType.val().indexOf('image_') !== -1 )
					{
						$addAnotherForm.find('.image_field_type').show();

						if ($options != "")
						{
							var $field_options = window.JSON.parse($options);

							if ($field_options.is_export_featured) $addAnotherForm.find('#is_image_export_featured').attr('checked','checked');
							if ($field_options.is_export_attached) $addAnotherForm.find('#is_image_export_attached_images').attr('checked','checked');

							$addAnotherForm.find('input[name=image_field_separator]').val($field_options.image_separator);
						}						
					}

					break;
			}
			
			$addAnotherForm.find('input.switcher').change();

			var $column_name = $(this).find('input[name^=cc_name]').val();
			if ($column_name == "ID") $column_name = "id";

			$addAnotherForm.find('input.column_name').val($column_name);
			$addAnotherForm.show();
			$('.wpallexport-overlay').show();			

		});			
		
		// Preview export file
		var doPreview = function( ths, tagno ){			

			$('.wpallexport-overlay').show();					

			ths.pointer({
	            content: '<div class="wpallexport-preview-preload wpallexport-pointer-preview"></div>',
	            position: {
	                edge: 'right',
	                align: 'center'                
	            },
	            pointerWidth: 715,
	            close: function() {
	                $.post( ajaxurl, {
	                    pointer: 'pksn1',
	                    action: 'dismiss-wp-pointer'
	                });
	                $('.wpallexport-overlay').hide();
	            }
	        }).pointer('open');

	        var $pointer = $('.wpallexport-pointer-preview').parents('.wp-pointer').first();	 

	        var $leftOffset = ($(window).width() - 715)/2;

	        $pointer.css({'position':'fixed', 'top' : '15%', 'left' : $leftOffset + 'px'});	        

			var request = {
				action: 'wpae_preview',	
				data: $('form.wpallexport-step-3').serialize(),
				tagno: tagno,
				security: wp_all_export_security				
		    };    		    

			$.ajax({
				type: 'POST',
				url: get_valid_ajaxurl(),
				data: request,
				success: function(response) {						

					ths.pointer({'content' : response.html});

					$pointer.css({'position':'fixed', 'top' : '15%', 'left' : $leftOffset + 'px'});
				
					var $preview = $('.wpallexport-preview');		

					$preview.parent('.wp-pointer-content').removeClass('wp-pointer-content').addClass('wpallexport-pointer-content');

					$preview.find('.navigation a').unbind('click').die('click').live('click', function () {

						tagno += '#prev' == $(this).attr('href') ? -1 : 1;						

						doPreview(ths, tagno);

					});

				},
				error: function( jqXHR, textStatus ) {	

					ths.pointer({'content' : jqXHR.responseText});													

				},
				dataType: "json"
			});

		};

		$(this).find('.preview_a_row').click( function(){ 			
			doPreview($(this), 1); 
		});		

		$('.wpae-available-fields-group').click(function(){
			var $mode = $(this).find('.wpae-expander').text();
			$(this).next('div').slideToggle();
			if ($mode == '+') $(this).find('.wpae-expander').text('-'); else $(this).find('.wpae-expander').text('+');
		});

		$('.pmxe_remove_column').live('click', function(){			
			$(this).parents('li:first').remove();
		});

		$('.close_action').click(function(){
			$(this).parents('fieldset:first').hide();
			$('.wpallexport-overlay').hide();
			$('#columns').find('div.active').removeClass('active');
		});		

		$('.date_field_export_data').change(function(){
			if ($(this).val() == "unix")
				$('.pmxe_date_format_wrapper').hide();
			else
				$('.pmxe_date_format_wrapper').show();
		});

		$('.xml-expander').live('click', function () {
			var method;
			if ('-' == $(this).text()) {
				$(this).text('+');
				method = 'addClass';
			} else {
				$(this).text('-');
				method = 'removeClass';
			}
			// for nested representation based on div
			$(this).parent().find('> .xml-content')[method]('collapsed');
			// for nested representation based on tr
			var $tr = $(this).parent().parent().filter('tr.xml-element').next()[method]('collapsed');
		});

		$('.wp-all-export-edit-column').css('left', ($( document ).width()/2) - 355 );    

	    var wp_all_export_config = {
	      '.wp-all-export-chosen-select' : {width:"50%"}    
	    }

	    for (var selector in wp_all_export_config) {
	    	$(selector).chosen(wp_all_export_config[selector]);
	    	$(selector).on('change', function(evt, params) {
				$('.cc_field').hide();
				var selected_value = $(selector).find('option:selected').attr('label');
				var ftype = $(selector).val();												
										
				switch (ftype){					
					case 'date':
						$('.date_field_type').show();
						break;
					case 'sql':
						$('.sql_field_type').show();
						break;										
					case 'woo':
							switch (selected_value){
								case 'item_data___upsell_ids':
								case 'item_data___crosssell_ids':
								case '_upsell_ids':
								case '_crosssell_ids':
									$addAnotherForm.find('.linked_field_type').show();
									break;
							}
						break;
					default:
						if ( $(selector).val().indexOf('image_') !== -1)
						{
							$('.image_field_type').show();
						}
						break;
				}
			});
	    }    	    	 

	    $('.wp-all-export-advanced-field-options').click(function(){
	    	if ($(this).find('span').html() == '+'){
	    		$(this).find('span').html('-');
	    		$('.wp-all-export-advanced-field-options-content').fadeIn('fast', function(){	    			
	    			if ($('#coperate_php').is(':checked')) editor.setCursor(1);
	    		});	    		
	    	}
	    	else{
	    		$(this).find('span').html('+');
	    		$('.wp-all-export-advanced-field-options-content').hide();
	    	}    	
	    });

	    // Auto generate available data
	    $('.wp_all_export_auto_generate_data').click(function(){
	    	
	    	$('ol#columns').find('li:not(.placeholder)').fadeOut().remove();
	    	$('ol#columns').find('li.placeholder').fadeOut();

	    	if ($('#available_data').find('li.wp_all_export_auto_generate').length)
	    	{
	    		$('#available_data').find('li.wp_all_export_auto_generate, li.pmxe_cats').each(function(i, e){
		    		var $clone = $(this).clone();
		    		$clone.attr('rel', i);
		    		$( "<li></li>" ).html( $clone.html() ).appendTo( $( "#columns_to_export ol" ) );				
		    	});
	    	}	   
	    	else
	    	{
	    		$('#available_data').find('div.custom_column').each(function(i, e){
	    			var $parent = $(this).parent('li');
		    		var $clone = $parent.clone();
		    		$clone.attr('rel', i);

		    		if ( $clone.find('input[name^=cc_type]').val().indexOf('image_') !== -1 )
					{
						$clone.find('.wpallexport-xml-element').html('Image ' + $clone.find('input[name^=cc_name]').val());
						$clone.find('input[name^=cc_name]').val('Image ' + $clone.find('input[name^=cc_name]').val());
					}

					if ( $clone.find('input[name^=cc_type]').val().indexOf('attachment_') !== -1 )
					{
						$clone.find('.wpallexport-xml-element').html('Attachment ' + $clone.find('input[name^=cc_name]').val());
						$clone.find('input[name^=cc_name]').val('Attachment ' + $clone.find('input[name^=cc_name]').val());
					}

		    		$( "<li></li>" ).html( $clone.html() ).appendTo( $( "#columns_to_export ol" ) );				
		    	});
	    	} 	

	    	trigger_warnings();

	    });	 

		$('.wp_all_export_clear_all_data').live('click', function(){
			$('ol#columns').find('li:not(.placeholder)').remove();
			$('ol#columns').find('li.placeholder').fadeIn();
		});

	    if ($('input[name^=selected_post_type]').length){

    		var postType = $('input[name^=selected_post_type]').val();

    		init_filtering_fields();

    		// if ($('form.edit').length){

    			liveFiltering();    				

    		// } 

		    $('form.wpallexport-template').find('input[type=submit]').click(function(e){
				e.preventDefault();			
				
				$('.hierarhy-output').each(function(){
					var sortable = $('.wp_all_export_filtering_rules.ui-sortable');
					if (sortable.length){
						$(this).val(window.JSON.stringify(sortable.pmxe_nestedSortable('toArray', {startDepthCount: 0})));								
					}
				});			

				$(this).parents('form:first').submit();
			});			
    	}   	     	    

    	$('.wpallexport-import-to-format').click(function(){

    		$('.wpallexport-import-to-format').removeClass('selected');
    		$(this).addClass('selected');

    		if ($(this).hasClass('wpallexport-csv-type'))
    		{
    			$('.wpallexport-csv-options').show();
    			$('.wpallexport-xml-options').hide();
    			$('input[name=export_to]').val('csv');
    		}
    		else
    		{
    			$('.wpallexport-csv-options').hide();
    			$('.wpallexport-xml-options').show();
    			$('input[name=export_to]').val('xml');
    		}
    	});

    	// template form: auto submit when `load template` list value is picked
		$(this).find('select[name="load_template"]').live('change', function () {
			$(this).parents('form').submit();
		});

		var height = $(window).height();
		$('#available_data').find('.wpallexport-xml').css({'max-height': height - 125});

	});	
	// [ \Step 2 ( export template ) ]			

	
	// [ Step 3 ( export options ) ]
    if ( $('.wpallexport-export-options').length ){

    	if ($('input[name^=selected_post_type]').length){

    		var postType = $('input[name^=selected_post_type]').val();

    		init_filtering_fields();

    		// if ($('form.edit').length){

    			liveFiltering();    				

    		// } 

		    $('form.choose-export-options').find('input[type=submit]').click(function(e){
				e.preventDefault();			
				
				$('.hierarhy-output').each(function(){
					var sortable = $('.wp_all_export_filtering_rules.ui-sortable');
					if (sortable.length){
						$(this).val(window.JSON.stringify(sortable.pmxe_nestedSortable('toArray', {startDepthCount: 0})));								
					}
				});			

				$(this).parents('form:first').submit();
			});			
    	}

    	$('.wp_all_export_confirm_and_run').click(function(e){
			e.preventDefault();
			$('form.choose-export-options').submit();
		});

    }   
    // if we are on re-run export screen
    if ($('.wpallexport-re-run-export').length)
	{
		$('#export_only_new_stuff').click(function(){
			$(this).attr('disabled','disabled');
			$('label[for=export_only_new_stuff]').addClass('loading');
			liveFiltering(null, function(){
				$('label[for=export_only_new_stuff]').removeClass('loading');
				$('#export_only_new_stuff').removeAttr('disabled');
			});
		});
	}    	
    // [ \Step 3 ( export options ) ]


    // [ Step 4 ( export completed ) ]
    $('.download_data').click(function(){
    	window.location.href = $(this).attr('rel');
    });
    // [ \Step 4 ( export completed ) ]


    // [ Additional functionality ]

    // Add new filtering rule
    $('#wp_all_export_add_rule').live('click', function(){    	

    	var $el   = $('#wp_all_export_xml_element');
    	var $rule = $('#wp_all_export_rule');
    	var $val  = $('#wp_all_export_value');

    	if ($el.val() == "" || $rule.val() == "") return;    	

    	var relunumber = $('.wp_all_export_filtering_rules').find('li').length + 1;

    	var html = '<li id="item_'+ relunumber +'" class="dragging"><div class="drag-element">';
    		html += '<input type="hidden" value="'+ $el.val() +'" class="wp_all_export_xml_element" name="wp_all_export_xml_element['+relunumber+']"/>';
    		html += '<input type="hidden" value="'+ $el.find('option:selected').html() +'" class="wp_all_export_xml_element_title" name="wp_all_export_xml_element_title['+relunumber+']"/>';
    		html += '<input type="hidden" value="'+ $rule.val() +'" class="wp_all_export_rule" name="wp_all_export_rule['+relunumber+']"/>';
    		html += '<input type="hidden" value="'+ $val.val() +'" class="wp_all_export_value" name="wp_all_export_value['+relunumber+']"/>';
    		html += '<span class="rule_element">' + $el.find('option:selected').html() + '</span> <span class="rule_as_is">' + $rule.find('option:selected').html() + '</span> <span class="rule_condition_value">"' + $val.val() +'"</span>';
    		html += '<span class="condition"> <label for="rule_and_'+relunumber+'">AND</label><input id="rule_and_'+relunumber+'" type="radio" value="and" name="rule['+relunumber+']" checked="checked" class="rule_condition"/><label for="rule_or_'+relunumber+'">OR</label><input id="rule_or_'+relunumber+'" type="radio" value="or" name="rule['+relunumber+']" class="rule_condition"/> </span>';
    		html += '</div><a href="javascript:void(0);" class="icon-item remove-ico"></a></li>';

    	$('#wpallexport-filters, #wp_all_export_apply_filters').show();
    	$('#no_options_notice').hide();    	

    	$('.wp_all_export_filtering_rules').append(html);

    	$('.wp_all_export_filtering_rules').find('.condition:hidden').each(function(){
    		$(this).show();
    		$(this).find('.rule_condition:first').attr('checked', 'checked');
    	});
    	$('.wp_all_export_filtering_rules').find('.condition').removeClass('last_condition');
        $('.wp_all_export_filtering_rules').find('.condition:last').addClass('last_condition');

        $('.wp_all_export_product_matching_mode').show();

    	$el.prop('selectedIndex',0).trigger('chosen:updated');;	
    	$rule.prop('selectedIndex',0).trigger('chosen:updated');;	

    	$val.val('');	    	
    	$('#wp_all_export_value').show();	    	    	    	

		$('#date_field_notice').hide();

    	liveFiltering();

    });

	// Re-count posts when clicking "OR" | "AND" clauses
	$('input[name^=rule]').live('click', function(){
		liveFiltering();
	});
	// Re-count posts when changing product matching mode in filtering section
	$('select[name^=product_matching_mode]').live('change', function(){
		liveFiltering();
	});		
	// Re-count posts when deleting a filtering rule
	$('.wp_all_export_filtering_rules').find('.remove-ico').live('click', function(){
		$(this).parents('li:first').remove();
		if ( ! $('.wp_all_export_filtering_rules').find('li').length)
		{	
			$('#wp_all_export_apply_filters').hide();				
    		$('#no_options_notice').show();	
    		$('.wp_all_export_product_matching_mode').hide();		    		
		}
		else
		{
			$('.wp_all_export_filtering_rules').find('li:last').find('.condition').addClass('last_condition');
		}				

		liveFiltering();
	});
	// hide "value" input when "Is Empty" or "Is Not Empty" rule is selected
	$('#wp_all_export_rule').change(function(){
		if ($(this).val() == 'is_empty' || $(this).val() == 'is_not_empty')
			$('#wp_all_export_value').hide();
		else
			$('#wp_all_export_value').show();
	});
    // saving & validating function editor
    $('.wp_all_export_save_functions').click(function(){
    	var request = {
			action: 'save_functions',	
			data: editor.getValue(),				
			security: wp_all_export_security				
	    };    
	    $('.wp_all_export_functions_preloader').show();
	    $('.wp_all_export_saving_status').html('');

		$.ajax({
			type: 'POST',
			url: get_valid_ajaxurl(),
			data: request,
			success: function(response) {						
				$('.wp_all_export_functions_preloader').hide();
				
				if (response.result)
				{
					$('.wp_all_export_saving_status').css({'color':'green'});
					setTimeout(function() {
						$('.wp_all_export_saving_status').html('').fadeOut();
					}, 3000);
				}
				else
				{
					$('.wp_all_export_saving_status').css({'color':'red'});
				}

				$('.wp_all_export_saving_status').html(response.msg).show();
									
			},
			error: function( jqXHR, textStatus ) {						
				$('.wp_all_export_functions_preloader').hide();
			},
			dataType: "json"
		});
    }); 
    // auot-generate zapier API key
    $('input[name=pmxe_generate_zapier_api_key]').click(function(e){
    	
    	e.preventDefault();

    	var request = {
			action: 'generate_zapier_api_key',
			security: wp_all_export_security				
	    };    
	    
		$.ajax({
			type: 'POST',
			url: get_valid_ajaxurl(),
			data: request,
			success: function(response) {						
				$('input[name=zapier_api_key]').val(response.api_key);
			},
			error: function( jqXHR, textStatus ) {						
				
			},
			dataType: "json"
		});
    });

    $('.wpallexport-overlay').click(function(){
		$('.wp-pointer').hide();		
		$('#columns').find('div.active').removeClass('active');
		$('fieldset.wp-all-export-edit-column').hide();
		$(this).hide();        
	});		    		

    if ( $('.wpallexport-template').length )
    {    	
    	setTimeout(function(){			
			$('.wpallexport-template').slideDown();
		}, 1000);	    	
    }	
	// [ \Additional functionality ]
	
});})(jQuery);
