'use strict';
var photobook = photobook || {};

photobook.imageService = (function() {
    var _imgBaseUrl = '../api-v1.php/images/';

    function _appendImagesToDom(images) {
        var baseElem = '';

        images.forEach(function(image) {
            var singleElem = '<div class="imgWrapper"><img src="' + _imgBaseUrl + image.id + '"></div>';
            baseElem += singleElem;
        });

        $("#imgContainer").append(baseElem);

        //Testing only!
        console.log(images);
    }

    function getImages() {
        $.ajax('api-v1.php/images').then(_appendImagesToDom);
    }

    return {
        getImages: getImages
    }
})();
