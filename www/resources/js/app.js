require("./bootstrap");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.configJS.websocket_url,
});

window.Alpine = Alpine;

Alpine.start();
