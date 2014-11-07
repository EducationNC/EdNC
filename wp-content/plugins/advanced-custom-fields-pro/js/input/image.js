(function($){
	
	acf.fields.image = acf.field.extend({
		
		type: 'image',
		$el: null,
		
		actions: {
			'ready':	'initialize',
			'append':	'initialize'
		},
		
		events: {
			'click [data-name="add"]': 		'add',
			'click [data-name="edit"]': 	'edit',
			'click [data-name="remove"]':	'remove',
			'change input[type="file"]':	'change'
		},
		
		focus: function(){
			
			this.$el = this.$field.find('.acf-image-uploader');
			
			this.settings = acf.get_data( this.$el );
			
		},
		
		initialize: function(){
			
			// add attribute to form
			if( this.$el.hasClass('basic') ) {
				
				this.$el.closest('form').attr('enctype', 'multipart/form-data');
				
			}
				
		},
		
		add: function() {
			
			// reference
			var self = this;
			
			
			// vars
			var field_key = acf.get_data( this.$field, 'key' );
			
			
			// get repeater
			var $repeater = acf.get_closest_field( this.$field, {type:'repeater'} );
			
			
			// popup
			var frame = acf.media.popup({
				'title'		: acf._e('image', 'select'),
				'mode'		: 'select',
				'type'		: 'image',
				'multiple'	: $repeater.exists(),
				'library'	: this.settings.library,
				'select'	: function( attachment, i ) {
					
					// select / add another image field?
			    	if( i > 0 ) {
			    		
						// vars
						var $tr = self.$field.parent(),
							$next = false;
							
						
						// find next image field
						$tr.nextAll('.acf-row:visible').each(function(){
							
							// get next $field
							$next = acf.get_field( field_key, $(this) );
							
							
							// bail early if $next was not found
							if( !$next ) {
								
								return;
								
							}
							
							
							// bail early if next file uploader has value
							if( $next.find('.acf-image-uploader.has-value').exists() ) {
								
								$next = false;
								return;
								
							}
								
								
							// end loop if $next is found
							return false;
							
						});
						
						
						// add extra row if next is not found
						if( !$next ) {
							
							$tr = acf.fields.repeater.doFocus( $repeater ).add();
							
							
							// bail early if no $tr (maximum rows hit)
							if( !$tr ) {
								
								return false;
								
							}
							
							
							// get next $field
							$next = acf.get_field( field_key, $tr );
							
						}
						
						
						// update $el
						self.doFocus( $next );
						
					}
					
					
			    	// add file to field
			        self.render( self.prepare(attachment) );
					
				}
			});
			
			
		},
		
		prepare: function( attachment ) {
		
			// vars
			var image = {
		    	id:		attachment.id,
		    	url:	attachment.attributes.url
	    	};			
			
			
			// check for preview size
			if( acf.isset(attachment.attributes, 'sizes', this.settings.preview_size, 'url') ) {
	    	
		    	image.url = attachment.attributes.sizes[ this.settings.preview_size ].url;
		    	
	    	}
	    	
	    	
	    	// return
	    	return image;
			
		},
		
		render: function( image ){
			
	    	
			// set atts
		 	this.$el.find('[data-name="image"]').attr( 'src', image.url );
			this.$el.find('[data-name="id"]').val( image.id ).trigger('change');
			
			
			// set div class
		 	this.$el.addClass('has-value');
	
		},
		
		edit: function() {
			
			// reference
			var self = this;
			
			
			// vars
			var id = this.$el.find('[data-name="id"]').val();
			
			
			// popup
			var frame = acf.media.popup({
			
				title:		acf._e('image', 'edit'),
				button:		acf._e('image', 'update'),
				mode:		'edit',
				id:			id,
				
				select:	function( attachment, i ) {
				
			    	self.render( self.prepare(attachment) );
					
				}
				
			});
			
		},
		
		remove: function() {
			
			// vars
	    	var attachment = {
		    	id:		'',
		    	url:	''
	    	};
	    	
	    	
	    	// add file to field
	        this.render( attachment );
	        
	        
			// remove class
			this.$el.removeClass('has-value');
			
		},
		
		change: function( e ){
			
			this.$el.find('[data-name="id"]').val( e.$el.val() );
			
		}
		
	});
	

})(jQuery);
