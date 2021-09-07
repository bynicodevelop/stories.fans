require("@videojs/http-streaming");
require("videojs-contrib-quality-levels");
require("videojs-hls-stream-selector");

import videojs from "video.js";
window.videojs = videojs;

const listVideoPlayers = [];

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

        listVideoPlayers[id].hlsStreamSelector();
    }
};
