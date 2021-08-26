require("./bootstrap");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":6001",
});

window.Alpine = Alpine;

Alpine.start();
