;(function(){
	'use strict';


	photobook.await(
		/*njct*/['foo','bar'],
		function( foo , bar ){
			console.log( "ready with args: ", arguments );
		}
	);

	photobook.await( 'exampleComponent',
		/*njct*/['exampleService'],
		function( exampleService ){
			console.log( "injected exampleService is: ", exampleService );

			var myComponent = {
				foo: "bar",
				doSomething: function(){
					exampleService.sayHello();
				}
			};

			this.resolve( myComponent );
		}
	);

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

	setTimeout(function(){
		photobook.foo = function MyFoo(){};
		photobook.bar = function MyBar(){};
	});


}());
