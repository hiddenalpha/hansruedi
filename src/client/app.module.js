;(function(){
	'use strict';


	var module = window.photobook = window.photobook || {};

	Object.defineProperties( module , {
		await: { value: function( name , dependencyNames , callback ){
			if( arguments.length === 3 ){
				// Nothing to do.
			}else if( arguments.length === 2 ){
				// shift arguments.
				callback = dependencyNames;
				dependencyNames = name;
				name = null;
			}else{
				throw Error( "wrong argument count. Signature is: function( [name] , dependencyNames , callback )" );
			}
			if( !Array.isArray(dependencyNames) ) throw Error( "arg 'dependencyNames' must be an array." );;
			if( typeof(callback) !== 'function' ) throw Error( "arg 'callback' must be a function." );
			var limit = 500;
			var stack = Error();
			(function tryResolve(){
				setTimeout(function(){
					for( var i=0,iEnd=dependencyNames.length ; i<iEnd ; ++i ){
						if( module[dependencyNames[i]] === undefined ){
							if( !limit-- ){
								console.error( "Failed to await '"+dependencyNames[i]+"'.", stack );
							}
							tryResolve();
							return;
						}
					}
					// All dependencies available. Trigger callback.
					// Collect dependencies.
					var dependencies = [];
					for( var i=0,iEnd=dependencyNames.length ; i<iEnd ; ++i ){
						dependencies[i] = module[ dependencyNames[i] ];
					}
					var factoryThis = Object.defineProperties( {} , {
						resolve: { value: function( rsp ){
							if( name ){
								if( module[name] === undefined ){
									module[name] = rsp;
								}else{
									throw Error( "'"+name+"' is already defined." );
								}
							}else{
								throw Error( "Cannot define something with no name." );
							}
						}}
					});
					callback.apply( factoryThis , dependencies );
				});
			}());
		}}
	});


}());
