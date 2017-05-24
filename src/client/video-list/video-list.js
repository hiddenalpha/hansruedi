photobook.await( 'VideoList',
	[/*njct*/'restService'],
	function( restService ){
		'use strict';


		function VideoList(){
			var that = this;
			if( !that ) throw Error( "Use 'new'" );
			that._element = createView( that );
			that._ui = {
				videoList: $( "[name='videoList']" , that._element )
			};
			// Initially load the list.
			updateVideoList( that );
		}


		VideoList.prototype = Object.create( Object.prototype , {
			'constructor': { value: VideoList },

			'getElement': { value: function(){
				return this._element;
			}},
		});


		function updateVideoList( that ){
			var videoList = that._ui.videoList;
			return restService({ url:"videos/" })
				.then( printVideos )
			;
			function printVideos( videos ){
				videoList.empty(); // <- Clear old entries.
				videos.forEach( printVideo ); // <- Create new entries.
			}
			function printVideo( video ){
				var videoUrl = restService.createRestURL( "videos/"+video.id );
				var box = $('<div class="video-link-box">');
				if( video.thumb.available ){
					var thumbPath = restService.createRestURL ("videos/"+ video.id +"/thumb" );
					var img = $( '<img class="video-thumbnail" src="'+ thumbPath +'">' );
					box.append( img );
				}
				var aTag = $( '<a target="blank" href="'+ videoUrl +'">'+ video.id +'</a>' );
				var bold = $( '<b>' ).append( aTag );
				box.append( bold );
				if( video.description ){
					box.append( $('<p>').text(video.description) );
				}
				videoList.append( box );
			}
		}

		function createView( that ){
			var element = $( '<div>' );
			element	
				.append( '<h1>Videos</h1>' )
				.append( '<div name="videoList"></>' );
			;
			return element;
		}


		this.resolve( VideoList );
	}
);

