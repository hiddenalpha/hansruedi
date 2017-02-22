;(function(){
	'use strict';


	window.photobook = Object.defineProperties( {} , {
		await: { value: function( dependencyNames , callback ){
			if( Array.isArray(dependencyNames) ) throw Error( "arg 'dependencyNames' must be an array." );;
			if( typeof(callback) !== 'function' ) throw Error( "arg 'callback' must be a function." );
			(function tryResolve(){
				setTimeout(function(){
					for( var i=0,iEnd=dependencyNames.length ; i<iEnd ; ++i ){
						if( !dependencyNames[i] ){
							tryResolve();
							return;
						}
					}
				});
			}());
		}}
	});


}());
