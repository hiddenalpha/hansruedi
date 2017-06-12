'use strict';

photobook.await( 'imageService',
    /*njct*/['restService'],
    function( restService ){

        var _imgBaseUrl = 'api-v1.php/images/';

        //Gets all image urls from server, then appends them to the dom
        function getImages( then ){
            var endpointUrl = 'api-v1.php/images';
            $.get(endpointUrl).then( then );
        }

        function getImageUrlById( imageId ){
            return restService.createRestURL( 'images/'+imageId );
        }

        this.resolve({
            getImages: getImages,
			getImageUrlById: getImageUrlById
        });
    }
);
