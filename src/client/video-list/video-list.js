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
			updateVideoList( that );
		}


		VideoList.prototype = Object.create( Object.prototype , {
			'constructor': { value: VideoList },

			'getHtmlElement': { value: function(){
				return this._element;
			}},

			'setUrl': { value: function( url ){
				this._element.url = url;
			}}
		});


		function updateVideoList( that ){
			return restService({ url:"videos/" })
				.then(function( videos ){
					var videoList = that._ui.videoList;
					while( videoList.firstChild ) videoList.removeChild(videoList.firstChild);
					videos.forEach(function( video ){
						var videoUrl = restService.createRestURL( "videos/"+video.id );
						var link = $('<div>');
						link.append( '<a target="blank" href="'+videoUrl+'">'+video.id+'</a>' );
						that._element.appendChild( link[0] );
					});
				})
			;
		}

		function createView( that ){
			var element = $( '<div>' );
			element	
				.append( '<h1>Videos</h1>' )
				.append( '<div name="videoList"></>' );
			;
			return element[0];
		}


		this.resolve( VideoList );
	}
);

