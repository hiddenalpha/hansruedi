photobook.await( 'videoService',
	/*njct*/['restService'],
	function( restService ){

		/*
		 * Provides an API to work with videos. For example to get the list of videos from the
		 * server or for helper functions.
		 */
		var videoService = {


			/**
			 * @return
			 * 		A list of the available videos.
			 */
			getVideos: function(){
				return restService({ url:"videos/" })
					.then(function( videos ){
						videos.forEach( attachUrl );
						return videos;
					})
				;
			},

			getVideoUrlById: function( videoId ){
				return restService.createRestURL( 'videos/'+videoId );
			}
		};


		function attachUrl( video ){
			video.url = videoService.getVideoUrlById( video.id );
		}


		this.resolve( videoService );
	}
);
