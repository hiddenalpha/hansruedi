;(function(){
	'use strict';


	var global = window;

	// Startup the application.
	document.addEventListener( 'DOMContentLoaded' , function(){
		var appRootElement = document.querySelector( "[x-name='photobook']" );
		global.photobook.dom.root = appRootElement;
	});


}());
