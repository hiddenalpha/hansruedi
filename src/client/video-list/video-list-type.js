photobook.await( 'VideoListType',
	[/*njct*/'restService','videoService'],
	function( restService , videoService ){
		'use strict';


		/**
		 * This type represents a visual list of videos.
		 */
		function VideoListType(){
			var that = this;
			if( !that ) throw Error( "Use 'new'" );
			that._element = createView( that );
			that._onVideoActionListeners = [];
			that._ui = {
				videoList: $( "[name='videoList']" , that._element )
			};
			// Initially load the list.
			updateVideoList( that );
		}


		VideoListType.prototype = Object.create( Object.prototype , {
			'constructor': { value: VideoListType },

			'getElement': { value: function(){
				return this._element;
			}},
			'onVideoAction': { value: function( callback ){
				if( typeof(callback) !== 'function' ) throw Error( "Arg 'callback' must be a function." );
				this._onVideoActionListeners.push({ callback:callback });
			}}
		});


		function updateVideoList( that ){
			var videoList = that._ui.videoList;
			return videoService.getVideos()
				.then( printVideos )
			;
			function printVideos( videos ){
				videoList.empty(); // <- Clear old entries.
				videos.forEach( printVideo ); // <- Create new entries.
			}
			function printVideo( video ){
				var videoUrl = video.url;
				var box = $('<div class="video-link-box">');
				if( video.thumb.available ){
					var thumbPath = restService.createRestURL ("videos/"+ video.id +"/thumb" );
					var img = $( '<img class="video-thumbnail" src="'+ thumbPath +'">' );
					box.append( img );
				}
				//var aTag = $( '<a target="blank" href="'+ videoUrl +'">'+ video.id +'</a>' );
				var aTag = $( '<a href="javascript:void(0)">'+ video.id +'</a>' );
				aTag.on( 'click' , onVideoLinkClick.bind(that,video) );
				var bold = $( '<b>' ).append( aTag );
				box.append( bold );
				if( video.description ){
					box.append( $('<p>').text(video.description) );
				}
				videoList.append( box );
			}
		}

		/**
		 * Handles event when video link is clicked.
		 */
		function onVideoLinkClick( video ){
			this._onVideoActionListeners.forEach(function( listener ){
				try{
					listener.callback.call( listener.callback , video );
				}catch( e ){
					console.error( e );
				}
			});
		}

		function createView( that ){
			var element = $( '<div>' );
			element	
				.append( '<h1>Videos</h1>' )
				.append( '<div name="videoList"></>' );
			;
			return element[0];
		}


		this.resolve( VideoListType );
	}
);

