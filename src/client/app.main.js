var photobook = photobook || {};


// Commons ////////////////////////////////////////////////////////////////////

// Provide our root element to the container.
photobook.await( 'allContent',
	[/*njct*/],
	function(){
		var resolve = this.resolve;
		$(document).ready(function(){
			resolve( $('#allContent')[0] );
		});
	}
);


///////////////////////////////////////////////////////////////////////////////

;
(function () {
    'use strict';

    // Startup the application and attach event handlers
    $(document).ready(function () {
        //Get and display all images
        photobook.imageService.getImages();

        //Show EXIF info on img
        $("#imgContainer").on("mouseover", "img", function () {
            photobook.ui.showImgInfo($(this)[0]);
        });

        //Remove img info
        $("#imgContainer").on("mouseout", "img", function () {
            $(this).siblings(".imgInfo").remove();
        });
    });

}());


photobook.ui = (function () {
    'use strict';

    function showImgInfo($this) {
        EXIF.getData($this, function () {
            //Get all info from img
            var imgInfo = EXIF.getAllTags($this);

            //Make sure info is available then prepare info
            if (Object.keys(imgInfo).length > 0) {
                var makeModel = imgInfo.Make + " " + imgInfo.Model;
                var isoValue = "ISO " + imgInfo.ISOSpeedRatings;
                var apertureValue = "F " + (imgInfo.FNumber.numerator / imgInfo.FNumber.denominator).toFixed(2);
                var exposureTime = imgInfo.ExposureTime.numerator + "/" + imgInfo.ExposureTime.denominator + "s";

                //Append info to img
                $($this).after("<p class='imgInfo'>" + makeModel + " - " + isoValue + " - " + apertureValue + " - " + exposureTime + "</p>");
            }
        });
    }

    return {
        showImgInfo: showImgInfo
    }
})();
