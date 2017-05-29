photobook.await( 'videoList',
	[/*njct*/'VideoListType','allContent','videoPlayer'],
	function( VideoListType , allContent , videoPlayer ){
		'use strict';

		/*
		 * This is the default instance of our video list.
		 */

		// Create a new video list.
		var videoList = new VideoListType( $('<div>')[0] );

		videoList.onVideoAction(function( video ){
			videoPlayer.setVideo( video );
		});

		// Append the element of our list to the DOM.
		allContent.append( videoList.getElement() );

		// Make video player visible.
		videoPlayer.setVisible( true );

		this.resolve( videoList );
	}
);
