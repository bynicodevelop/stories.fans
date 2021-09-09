require("./bootstrap");
require("./prism");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

if (window.configJS == undefined) {
    window.configJS = {
        websocket_url: null,
        disable_context_menu: true,
    };
}

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.configJS.websocket_url,
});

window.Alpine = Alpine;

Alpine.start();

Prism.highlightAll();

const listClipBoard = [];

window.clipboardInstantiate = (id) => {
    if (listClipBoard[id] == undefined) {
        listClipBoard[id] = new ClipboardJS(id);

        listClipBoard[id].on("success", function (e) {
            window.livewire.emit("copied");
        });
    }
};

if (window.configJS.disable_context_menu == true) {
    document.addEventListener("contextmenu", (e) => {
        e.preventDefault();
    });
}
