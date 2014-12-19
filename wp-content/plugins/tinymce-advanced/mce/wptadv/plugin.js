/*global tinymce:true */

tinymce.PluginManager.add( 'wptadv', function( editor ) {
	editor.on( 'init', function() {
		if ( ! editor.settings.wpautop && editor.settings.tadv_noautop ) {
			editor.on( 'SaveContent', function( event ) {
				var regex = [
					new RegExp('https?://(www\\.)?youtube\\.com/(watch|playlist).*', 'i'),
					new RegExp('https?://youtu.be/.*', 'i'),
					new RegExp('https?://blip.tv/.*', 'i'),
					new RegExp('https?://(www\\.)?vimeo\\.com/.*', 'i'),
					new RegExp('https?://(www\\.)?dailymotion\\.com/.*', 'i'),
					new RegExp('https?://dai.ly/.*', 'i'),
					new RegExp('https?://(www\\.)?flickr\\.com/.*', 'i'),
					new RegExp('https?://flic.kr/.*', 'i'),
					new RegExp('https?://(.+\\.)?smugmug\\.com/.*', 'i'),
					new RegExp('https?://(www\\.)?hulu\\.com/watch/.*', 'i'),
					new RegExp('https?://(www\\.)?viddler\\.com/.*', 'i'),
					new RegExp('https?://qik.com/.*', 'i'),
					new RegExp('https?://revision3.com/.*', 'i'),
					new RegExp('https?://i*.photobucket.com/albums/.*', 'i'),
					new RegExp('https?://gi*.photobucket.com/groups/.*', 'i'),
					new RegExp('https?://(www\\.)?scribd\\.com/.*', 'i'),
					new RegExp('https?://wordpress.tv/.*', 'i'),
					new RegExp('https?://(.+\\.)?polldaddy\\.com/.*', 'i'),
					new RegExp('https?://poll\\.fm/.*', 'i'),
					new RegExp('https?://(www\\.)?funnyordie\\.com/videos/.*', 'i'),
					new RegExp('https?://(www\\.)?twitter\\.com/.+?/status(es)?/.*', 'i'),
					new RegExp('https?://vine\\.co/v/.*', 'i'),
					new RegExp('https?://(www\\.)?soundcloud\\.com/.*', 'i'),
					new RegExp('https?://(www\\.)?slideshare\\.net/.*', 'i'),
					new RegExp('https?://instagr(\\.am|am\\.com)/p/.*', 'i'),
					new RegExp('https?://(www\\.)?rdio\\.com/.*', 'i'),
					new RegExp('https?://rd\\.io/x/.*', 'i'),
					new RegExp('https?://(open|play)\\.spotify\\.com/.*', 'i'),
					new RegExp('https?://(.+\\.)?imgur\\.com/.*', 'i'),
					new RegExp('https?://(www\\.)?meetu(\\.ps|p\\.com)/.*', 'i'),
					new RegExp('https?://(www\\.)?issuu\\.com/.+/docs/.*', 'i'),
					new RegExp('https?://(www\\.)?collegehumor\\.com/video/.*', 'i'),
					new RegExp('https?://(www\\.)?collegehumor\\.com/video/.*', 'i'),
					new RegExp('https?://(www\\.)?mixcloud\\.com/.*', 'i'),
					new RegExp('https?://(www\\.|embed\\.)?ted\\.com/talks/.*', 'i'),
					new RegExp('https?://(www\\.)(animoto|video214)\\.com/play/.*', 'i')
				];

				event.content = event.content.replace( /<p>(https?:\/\/[^<> "]+?)<\/p>/ig, function( all, match ) {
					for( var i in regex ) {
						if ( regex[i].test( match ) ) {
							return '\n' + match + '\n';
						}
					}
					return all;
				})
				.replace( /caption\](\s|<br[^>]*>|<p>&nbsp;<\/p>)*\[caption/g, 'caption] [caption' )
				.replace( /<(object|audio|video)[\s\S]+?<\/\1>/g, function( match ) {
					return match.replace( /[\r\n]+/g, ' ' );
				}).replace( /<pre[^>]*>[\s\S]+?<\/pre>/g, function( match ) {
					match = match.replace( /<br ?\/?>(\r\n|\n)?/g, '\n' );
					return match.replace( /<\/?p( [^>]*)?>(\r\n|\n)?/g, '\n' );
				});
			});
		}
	});
});
