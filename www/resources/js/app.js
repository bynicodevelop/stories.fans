require("./bootstrap");
require("./prism");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

if (window.configJS == undefined) {
    window.configJS = {
        websocket_url: null,
        disable_context_menu: "production",
    };
}

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.configJS.websocket_url,
});

window.Alpine = Alpine;

Alpine.start();

Prism.highlightAll();

if (window.configJS.disable_context_menu == true) {
    document.addEventListener("contextmenu", (e) => {
        e.preventDefault();
    });
}
