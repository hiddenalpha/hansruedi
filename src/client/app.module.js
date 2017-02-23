;(function(){
	'use strict';


	/**
	 *
	 * This file contains a really simple dependency injector. Which provides a minimal interface to
	 * await other modules.
	 *
	 */


	// Choose the object to use as module.
	var module = window.photobook = window.photobook || {};

	/** How many times to re-try to resolve the dependencies for each registered callback. */
	var maxRetryCount = 100;

	/** Time in milli seconds to wait before try to resolve again. */
	var retryInterval = 10;


	Object.defineProperties( module , {

		/**
		 * Registers a callback which depends on some other modules.
		 *
		 * This function is overloaded the following ways:
		 *
		 * function( name , dependencyNames , callback );
		 * function( dependencyNames , callback );
		 *
		 * @param name
		 * 		The name of this module.
		 * @param dependencyNames
		 * 		An array of strings which names the modules this module depends on.
		 * @param callback
		 * 		The factory function which gets called as soon all dependencies are available. The
		 * 		dependencies get injected as arguments to this function in the same order as they're
		 * 		named in the 'dependencyNames' array.
		 */
		await: { value: function(){
			var args = prepareArgs( arguments );
			var tries = 0;
			var stack = Error();
			(function tryResolve(){
				setTimeout(function(){
					for( var i=0,iEnd=args.dependencyNames.length ; i<iEnd ; ++i ){
						if( module[args.dependencyNames[i]] === undefined ){
							// At least one module missing.
							if( tries++ > maxRetryCount ){
								// Give it up.
								var name = !args.name ? "<no name>" : args.name;
								var problematicDependency = args.dependencyNames[i];
								console.error( "Failed to run '"+name+"'. Because '"+problematicDependency+"' is missing.", stack );
								return;
							}
							// Retry it later again.
							tryResolve();
							return;
						}
					}
					// All dependencies available. Trigger callback.
					// Collect dependencies.
					var dependencies = [];
					for( var i=0,iEnd=args.dependencyNames.length ; i<iEnd ; ++i ){
						dependencies[i] = module[ args.dependencyNames[i] ];
					}
					var callbackThis = Object.defineProperties( {} , {
						resolve: { value: function( rsp ){
							if( args.name ){
								if( module[args.name] === undefined ){
									module[args.name] = rsp;
								}else{
									throw Error( "'"+args.name+"' is already defined." );
								}
							}else{
								throw Error( "Cannot define something with no name." );
							}
						}}
					});
					args.callback.apply( callbackThis , dependencies );
				}, retryInterval );
			}());
		}}

	});


	/**
	 * Parses the overloaded arguments.
	 */
	function prepareArgs( origArgs ){
		var ans;

		if( origArgs.length === 3 ){
			// Overloading with name called.
			ans = {
				name: origArgs[0],
				dependencyNames: origArgs[1],
				callback: origArgs[2]
			};
			if( typeof(ans.name) !== 'string' ) throw Error( "Argument 'name' must be a string." );
		}else if( origArgs.length === 2 ){
			// Overloading without name called.
			ans = {
				name: null,
				dependencyNames: origArgs[0],
				callback: origArgs[1]
			};
		}else{
			throw Error( "IllegalArguments." );
		}
		// Check which counts for both overloadings.
		if( !Array.isArray(ans.dependencyNames) ) throw Error( "Argument 'dependencyNames' must be an array." );
		if( typeof(ans.callback) !== 'function' ) throw Error( "Argument 'callback' must be a function." );
		return ans;
	}


}());
