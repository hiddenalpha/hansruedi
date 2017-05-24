;(function(){
	'use strict';


	/*
	 * This is an example for how to use the injector.
	 */


	// Argument 'name' (first argument) is only required if we want to register us to the module.
	// And because we don't need to register that one here we use only 2 arguments. (We could also
	// use three of course)
	photobook.await(
		[        'exampleComponent'],
		function( exampleComponent ){
			console.log( "entry point is ready now. Let's use injected instance to print a message:" );
			exampleComponent.printSomething();
		}
	);


	// Lets defer this definition somewhat to show that the loading is asynchronous.
	setTimeout(function(){
		// First argument is the name of this definition. So here our export will be named 'exampleComponent'.
		// As second argument we list 'exampleService' as a dependency.
		// And the last argument is the factory which gets called with the required dependencies.
		photobook.await( 'exampleComponent',
			/*njct*/['exampleService'],
			function( exampleService ){ // <-- required dependencies get injected as arguments here.

				// Create an example service.
				var myComponent = {
					printSomething: function(){
						exampleService.sayHello();
					}
				};

				// There is a 'resolve' method provided on the 'this' element of the factory. We can
				// use this to provide the value which then will get injected into the other
				// definitions.
				this.resolve( myComponent );
			}
		);
	}, 200 );


	photobook.await( 'exampleService',
		/*njct*/[],
		function(){
			this.resolve({

				sayHello: function(){
					console.log( "exampleService says hello." );
				}

			});
		}
	);



///////////////////////////////////////////////////////////////////////////////
// Debugging by ändu //////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


photobook.await(
	[/*njct*/'VideoList','allContent'],
	function( VideoList , allContent ){

		// Create new videoList instance
		var videoList = new VideoList( $('<div>')[0] );

		// Append the element of our list.
		allContent.appendChild( videoList.getHtmlElement() );
	}
);


}());
