photobook.await('largeImage',
    /*njct*/
    ['allContent'],
    function (allContent) {


        var view = (function () {
            return $('' +
                '<div class="largeImage" style="display:none; transition: 1s ease-in-out;">' +
                '  <div class="overlay">' +
                '    <div>' +
                '      <img x-name="image">' +
                '    </div>' +
                '  </div>' +
                '</div>'
            );
        }());

        var overlay = view.find(".overlay");
        overlay.on('click', function () {
            largeImage.setVisible(false);
        });
        var img = view.find("[x-name='image']");


        var largeImage, that;
        largeImage = that = {

            setVisible: function (makeVisible) {
                if (makeVisible === undefined) makeVisible = true;
                if (!isVisible() && makeVisible) {
                    var originWidth = view.css("width");
                    var originHeight = view.css("height");
                    document.body.style.overflow = "hidden"
                    setVisible(true);
                } else if (isVisible() && !makeVisible) {
                    document.body.style.overflow = "initial"
                    setVisible(false);
                }
            },

            showImage: function (image) {
                if (!image || !image.src) throw Error("IllegalArgument");
                img[0].src = image.src;
                that.setVisible();
            }

        };

        function isVisible() {
            return view.css("display") == "block";
        }

        function setVisible(visible) {
            if (visible === undefined) visible = true;
            // TODO: Work with animation
            view.css("display", visible ? "block" : "none");
        }


        // Append view to DOM.
        allContent[0].replaceChild(view[0], allContent.find("#largeImagePlaceholder")[0]);

        // Do our exports
        this.resolve(largeImage);
    }
);