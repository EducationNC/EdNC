(function () {
    tinymce.PluginManager.requireLangPack('ospolls');
    tinymce.create("tinymce.plugins.OSPolls", {
        init: function (a, b) {
            a.addButton("ospolls", {
                title: "ospolls.insert_poll",
				onclick : function() {
					// triggers the thickbox
					var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
					W = W - 80;
					H = H - 84;
					tb_show( 'Insert Poll / Set', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=opinionstage-insert-poll-form' );
				},				
                image: b + "/img/os.png"
            });
            a.onNodeChange.add(function (d, c, e) {
                c.setActive("ospolls", e.nodeName == "IMG");
            })
        },
        createControl: function (b, a) {
            return null;
        },
        getInfo: function () {
            return {
                longname: "Polls-by-OpinionStage",
                author: "Opinion Stage",
                authorurl: "http://www.opinionstage.com",
                infourl: "http://www.opinionstage.com/about",
                version: "1.0"
            }
        }
    });
    tinymce.PluginManager.add("ospolls", tinymce.plugins.OSPolls)
})();