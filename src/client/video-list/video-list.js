photobook.await( 'videoList',
	[/*njct*/'VideoListType','allContent'],
	function( VideoListType , allContent ){
		'use strict';

		/*
		 * This is the default instance of our video list.
		 */

		// Create a new video list.
		var videoList = new VideoListType( $('<div>')[0] );

		// Append the element of our list to the DOM.
		allContent.append( videoList.getElement() );

		this.resolve( videoList );
	}
);
