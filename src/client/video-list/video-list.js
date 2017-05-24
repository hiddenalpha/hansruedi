photobook.await( 'VideoList',
	[/*njct*/'restService'],
	function( restService ){
		'use strict';


		function VideoList(){
			var that = this;
			if( !that ) throw Error( "Use 'new'" );
			that._element = createView( that );
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
			return restService({
					url: "videos/"
				})
				.then(function(){
					console.log( "sdfasdfas" );
				})
			;
		}

		function createView( that ){
			var element = $( '<div>' );
			element	
				.append( '<p>Hallo Welt</p>' )
			;
			return element[0];
		}


		this.resolve( VideoList );
	}
);

