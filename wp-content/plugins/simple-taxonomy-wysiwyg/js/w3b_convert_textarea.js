var j = jQuery.noConflict();
var elem;

j(function() {
	if( j('#wp-w3b_description-wrap').length ) {
		if( j('#tag-description').length )
			elem = j('#tag-description');
		else
			elem = j('#description');

		j('#wp-w3b_description-wrap').detach().insertAfter(elem);
		elem.hide();
	}
});

j(window).load(function() {
	if( j('#wp-w3b_description-wrap').length ) {
		var iframe = j('#w3b_description_ifr');
		j('body', iframe.contents()).on('keyup', function(e) {
			elem.val( tinymce.get('w3b_description').getContent() );
		});

		j('#submit').click(function ( e ) {
			j('body', iframe.contents()).html('');
			return;
		});
	}
});