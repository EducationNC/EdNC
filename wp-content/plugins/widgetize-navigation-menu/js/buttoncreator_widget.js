    function button_creator_options_widget(){
    //apply color picker to widget options
    jQuery('#widgets-right .my-color-field').wpColorPicker();
	//check to see if remove links are visible are not
    jQuery(".button_creator_img").each(function(){
        if (jQuery(this).attr('src')!=""){
    	jQuery(this).parent().find(".delete_button_creator_img").show();
    	}
        else{
            jQuery(this).parent().find(".delete_button_creator_img").hide();
        }
    });
	
	//if click on remove image
	jQuery(".delete_button_creator_img").click(function(){
	jQuery(".button_creator_img_input").val("");
	jQuery(".button_creator_img").attr("src","");
	jQuery(this).hide();
    });

 
 
    // Runs when the image button is clicked.
    jQuery('.btnctr_meta-button').click(function(e){
		window.currentImageMetaBtn=this;
		
        // Prevents the default action from occuring.
        e.preventDefault();
          // Opens the media library frame.
            window.advbuttonwdgt_meta_image_frame.open();
    });
        // Runs when an image is selected.
        window.advbuttonwdgt_meta_image_frame.on('select', function(){
			 
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = advbuttonwdgt_meta_image_frame.state().get('selection').first().toJSON();
 			
            // Sends the attachment URL to our custom image input field.
            jQuery(window.currentImageMetaBtn).parent().find("input:first").val(media_attachment.url);
			//console.log(jQuery(window.currentImageMetaBtn).prev());
			jQuery(window.currentImageMetaBtn).parent().find("img").attr('src',media_attachment.url);
			//show delete img link
			jQuery(window.currentImageMetaBtn).parent().find(".delete_button_creator_img").show();
			 advbuttonwdgt_meta_image_frame.close();
        });
 
      
    }//end function
jQuery(document).ready(function(){
          // Sets up the media library frame
        window.advbuttonwdgt_meta_image_frame = wp.media.frames.advbuttonwdgt_meta_image_frame = wp.media({
            title: 'Select an image for widget',
            //button: { text:  'select' },
            library: { type: 'image' }
        });
    button_creator_options_widget();
});


jQuery(document).ajaxSuccess(function(e, xhr, settings) {
    button_creator_options_widget();
});