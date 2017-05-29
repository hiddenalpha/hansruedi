photobook.await( 'restService',
	/*njct*/[],
	function(){


		/*
		 * A middleware service to configure the remote API endpoint.
		 */


		var baseUrl = "api-v1.php/";


		var restService = function( options ){
			// Intercept call and apply base URL then delegate work.
			options.url = createRestURL(options.url);
			return $.ajax( options );
		};


		restService.createRestURL = createRestURL.bind(restService);


		function createRestURL( url ){
			return baseUrl + url;
		};


		this.resolve( restService );
	}
);
