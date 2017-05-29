photobook.await( 'VideoPlayerType',
	/*njct*/[],
	function(){
		'use strict';


		/**
		 * Constructor to create a VideoPlayer.
		 */
		function VideoPlayer(){
			if( !this ) throw Error( "Use 'new'" );
			this._element = createView();
		}


		/**
		 * Prototype of the videoPlayer. Means instance methods and properties.
		 */
		VideoPlayer.prototype = Object.create( VideoPlayer , {

			constructor: { value: VideoPlayer },

			getElement: { value: function(){
				return this._element;
			}},

			setUrl: { value: function( url , mime ){
				// Stop the old video.
				this._element.pause();
				// Drop all old sources.
				$(this._element).find("source").each(function(i,e){ $(this).remove() });
				// Create a new source.
				var srcElem = $( '<source>' )
					.attr( "type" , mime )
					.attr( "src" , url )
					[0]
				;
				setTimeout(function(){
					// Attach the created source.
					this._element.appendChild( srcElem );
					// Load the new video.
					this._element.load();
					// Play the new video.
					this._element.play();
				}.bind(this));
			}}

		});


		/**
		 * Creates and returns the html element of this component.
		 */
		function createView(){
			var view = $( '<video>' )
				.attr( "controls" , true )
				.css( "width" , "100%" )
				.css( "backgroundColor" , "#000" )
			;
			return view[0];
		}


		this.resolve( VideoPlayer );
	}
);
