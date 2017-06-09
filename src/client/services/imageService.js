'use strict';
var photobook = photobook || {};

photobook.imageService = (function () {
    var _imgBaseUrl = 'api-v1.php/images/';

    function _appendImagesToDom(images) {
        var baseElem = '';

        images.forEach(function (image) {
            var singleElem = '<div class="imgWrapper"><img src="' + _imgBaseUrl + image.id + '"><div class="imageComment"><p>' + image.description + '</p></div></div>';
            baseElem += singleElem;
        });

        $("#imgContainer").append(baseElem);
    }

    //Gets all image urls from server, then appends them to the dom
    function getImages() {
        var endpointUrl = 'api-v1.php/images';
        $.get(endpointUrl).then(_appendImagesToDom);
    }

    return {
        getImages: getImages
    }
})();