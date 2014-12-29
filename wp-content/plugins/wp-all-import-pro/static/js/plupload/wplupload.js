(function(window, $, undefined) {
	
var _parent = window.dialogArguments || opener || parent || top;

$.fn.wplupload  = function($options) {
	var $up, $defaults = { 
		runtimes : 'gears,browserplus,html5,flash,silverlight,html4',
		browse_button_hover: 'hover',
		browse_button_active: 'active'
	};
	
	$options = $.extend({}, $defaults, $options);
	
	return this.each(function() {
		var $this = $(this);
				
		$up = new plupload.Uploader($options);
					
		$up.bind('FilesAdded', function(up, files) {
			$.each(files, function(i, file) {
				// Create a progress bar containing the filename
				$('#progress').css({'visibility':'visible', 'display':'block'});
				$('#select-files').hide();
			})
		});
		
		$up.init();				
		
		$up.bind('Error', function(up, err) {									
			//$('#upload_process').html(err.message);
			$('.wpallimport-header').next('.clear').after(err.message);
		});
		
		$up.bind('FilesAdded', function(up, files) {
			// Disable submit and enable cancel
			
			$('.error.inline').remove();

			$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideUp();

			$('#cancel-upload').removeAttr('disabled');
			
			$up.start();
		});
		
		$up.bind('UploadFile', function(up, file, r) {			
				
		});
		
		$up.bind('UploadProgress', function(up, file) {
			// Lengthen the progress bar
			$('#progressbar').html('<span>Uploading</span> ' + file.name + ' ' + file.percent + '%');
			$('#upload_process').progressbar({value:file.percent});

		});
		
		
		$up.bind('FileUploaded', function(up, file, r) {
			var fetch = typeof(shortform) == 'undefined' ? 1 : 2;			
			
			r = _parseJSON(r.response);		
			
			if (r.error !== null){

				$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();										
				/*$('.wpallimport-choose-file').find('.wpallimport-uploaded-file-name').html('');
				$('.wpallimport-choose-file').find('.wpallimport-uploaded-file-size').html('');		*/		
				$('.wpallimport-import-from.selected').click();
				$('#wpallimport-url-upload-status').html('');

				$('#progress').hide();
				$('#progressbar').html('<span></span>');
				$('#select-files').fadeIn();

				$('.wpallimport-header').next('.clear').after(r.error.message);

			}
			else{

				$('#filepath').val(r.name);

				$('#progressbar').html('<span>Upload Complete</span> - ' + file.name + ' (' + ( (file.size / (1024*1024) >= 1) ? (file.size / (1024*1024)).toFixed(2) + 'mb' : (file.size / (1024)).toFixed(2) + 'kb') + ')');					

				setTimeout(function() {
					
					$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideDown(400, function(){
						$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();			
					});			

					if (r.OK) {					

					} else if (r.error != undefined && '' != r.error.message) {
						$('#progressbar').html(r.error.message);
					}

				}, 1000);			 			
			}
			
		});
		
		$up.bind('UploadComplete', function(up) {
			$('#cancel-upload').attr('disabled', 'disabled');			
			$('#advanced_upload').show();
		});
		
		$('#cancel-upload').click(function() {
			var i, file;
			
			$up.stop();		
						
			i = $up.files.length;
			for (i = $up.files.length - 1; i >= 0; i--) {
				file = $up.files[i];
				if ($.inArray(file.status, [plupload.QUEUED, plupload.UPLOADING]) !== -1) {
					$up.removeFile($up.getFile(file.id));					
				}
			}
			
			$('#cancel-upload').attr('disabled', 'disabled');
			
		});
		
	});	
};

function _parseJSON(r) {
	var obj;
	try {
		var matches = r.match(/{.*}/);		
		obj = $.parseJSON(matches[0]);
	} catch (e) {		
		obj = { OK : 0 };	
	}	
	return obj;
}

}(window, jQuery));