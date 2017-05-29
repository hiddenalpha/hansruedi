photobook.await( 'defaultVideoPlayer',
	/*njct*/['VideoPlayer','allContent'],
	function( VideoPlayer , allContent ){
		'use strict';


		/**
		 * Our exported service.
		 */
		var defaultVideoPlayer = {

			/**
			 * Set visibility of our player.
			 */
			'setVisible': function( visible ){
				if( visible ){
					allContent.append( getVideoPlayer().getElement() );
				}else{
					allContent.remove( getVideoPlayer().getElement() );
				}
			},

			/**
			 * Sets the video to play.
			 * @param video { url:string }
			 */
			'setVideo': function( video ){
				getVideoPlayer().setUrl( video.url , video.mime );
			}
		};


		/**
		 * Singleton for a videoPlayer instance.
		 */
		var getVideoPlayer = (function(){
			var videoPlayer;
			return function(){
				if( !videoPlayer ) videoPlayer = new VideoPlayer();
				return videoPlayer;
			}
		}());


		// Export our service.
		this.resolve( defaultVideoPlayer );
	}
);
