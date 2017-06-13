photobook.await('VideoListType', [ /*njct*/ 'restService', 'videoService'],
    function (restService, videoService) {
        'use strict';


        /**
         * This type represents a visual list of videos.
         */
        function VideoListType() {
            var that = this;
            if (!that) throw Error("Use 'new'");
            that._element = createView(that);
            that._onVideoActionListeners = [];
            that._ui = {
                videoList: $("[name='videoList']", that._element)
            };
            // Initially load the list.
            updateVideoList(that);
        }


        VideoListType.prototype = Object.create(Object.prototype, {
            'constructor': {
                value: VideoListType
            },

            'getElement': {
                value: function () {
                    return this._element;
                }
            },
            'onVideoAction': {
                value: function (callback) {
                    if (typeof (callback) !== 'function') throw Error("Arg 'callback' must be a function.");
                    this._onVideoActionListeners.push({
                        callback: callback
                    });
                }
            }
        });


        function updateVideoList(that) {
            var videoList = that._ui.videoList;
            return videoService.getVideos()
                .then(printVideos);

            function printVideos(videos) {
                videoList.empty(); // <- Clear old entries.
                videos.forEach(printVideo); // <- Create new entries.
            }

            function printVideo(video) {
                var videoUrl = video.url;
                var box = $('<div class="video-box">');
                if (video.thumb.available) {
                    var videoPath = restService.createRestURL("videos/" + video.id);
                    var player = $('<video preload="none" controls poster="' + videoPath + '/thumb">');
                    player.append('<source src="' + videoPath + '" type="video/mp4">')
                    box.append(player);
                }
                var infoWrapper = $('<div class="infoWrapper">');

                var videoId = $('<p><b>' + video.id + '</b></p>');
                infoWrapper.append(videoId).append($('<p>').text(video.description));
                box.append(infoWrapper);

                videoList.append(box);

            }
        }

        function createView(that) {
            var element = $('<div>');
            element
                .append('<h1>Videos</h1>')
                .append('<div name="videoList"></>');;
            return element[0];
        }


        this.resolve(VideoListType);
    }
);