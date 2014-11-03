(function(){
	jQuery(function($){
		$("#content_ospolls").click(function() {
			$("os_insert_poll").click();
			return false;
		});
		$(document).ready(function() {				
			$('#opinionstage-submit').click(function(){
				var poll_id = $('#opinionstage-poll-id').val();
				var ed;	  
				if (typeof(tinymce) != 'undefined' && tinymce.isIE && ( ed = tinymce.get(wpActiveEditor) ) && !ed.isHidden()) {
					ed.focus();
					ed.windowManager.insertimagebookmark = ed.selection.getBookmark();
				}
				l = window.dialogArguments || opener || parent || top;
				l.send_to_editor("[socialpoll id=\"" + poll_id.toString() + "\"]");
				tb_remove();
			});		
		});
	});	
})();