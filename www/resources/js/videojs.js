require("@videojs/http-streaming");
require("videojs-contrib-quality-levels");
require("videojs-hls-stream-selector");

import videojs from "video.js";
window.videojs = videojs;

const listVideoPlayers = [];

videojs.Vhs.xhr.beforeRequest = function (options) {
    const cachebuster = Math.round(new Date().getTime() / 1000);
    options.uri = options.uri + "?no-cache=" + cachebuster;

    options.headers = {
        "Cache-Control": "no-cache",
    };
    return options;
};

window.instantiateVideoPlayer = (id) => {
    if (listVideoPlayers[id] == undefined) {
        listVideoPlayers[id] = videojs(id, {
            html5: {
                vhs: {
                    overrideNative: true,
                },
                nativeAudioTracks: false,
                nativeVideoTracks: false,
            },
        });

        listVideoPlayers[id].removeChild("BigPlayButton");

        listVideoPlayers[id].hlsStreamSelector();
    }
};

window.isPlaying = (id) => listVideoPlayers[id].paused();

window.play = (id) => {
    if (listVideoPlayers[id].paused()) {
        listVideoPlayers[id].play();
    } else {
        listVideoPlayers[id].pause();
    }
};
