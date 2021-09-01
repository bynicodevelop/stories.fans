require("./bootstrap");
require("./prism");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

if (window.configJS != undefined) {
    window.Echo = new Echo({
        broadcaster: "socket.io",
        host: window.configJS.websocket_url,
    });
}

window.Alpine = Alpine;

Alpine.start();

Prism.highlightAll();
