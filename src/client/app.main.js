'use strict';


// Common /////////////////////////////////////////////////////////////////////


// Provide our root element to the container.
photobook.await('allContent',
    /*njct*/[],
    function(){
        var resolve = this.resolve;
        $(document).ready(function () {
            var allContent = $('#allContent');
            if (allContent.length != 1) console.warn("'#allContent' is not unique!");
            resolve(allContent);
        });
    }
);


///////////////////////////////////////////////////////////////////////////////


photobook.await( 'main',
    /*njct*/['allContent','imageUi','imageService','largeImage'],
    function( allContent , imageUi , imageService , largeImage ){

        //Get and display all images
        imageService.getImages(function( images ){

            images.forEach(function( image ){
                var imageUrl = imageService.getImageUrlById( image.id );
                var singleElem = $( '<div class="imgWrapper"><img src="' + imageUrl + '"><div class="imageComment"><p>' + image.description + '</p></div></div>' );
                singleElem = $( singleElem );
                singleElem.find( 'img' )
                    .on( 'click' , function(){
                        largeImage.showImage({ src:imageUrl });
                    })
                ;
                console.log( "singleElem", singleElem );
                allContent.find("#imgContainer").append( singleElem );
            });
        });

        //Show EXIF info on img
        allContent.find("#imgContainer").on("mouseover", "img", function () {
            imageUi.showImgInfo($(this)[0]);
        });

        //Remove img info
        allContent.find("#imgContainer").on("mouseout", "img", function () {
            $(this).siblings(".imgInfo").remove();
        });

        this.resolve( "thisIsTheApplicationEntryPointAndThereForeHasNoApi" );
    }
);

photobook.await( 'imageUi',
    /*njct*/[],
    function(){

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

        this.resolve({
            showImgInfo: showImgInfo
        });
    }
);
