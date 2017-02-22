var photobook = photobook || {};

;
(function() {
    'use strict';

    // Startup the application.
    $(document).ready(function() {
        photobook.imageService.getImages();
        //more logic :)
    });

}());
