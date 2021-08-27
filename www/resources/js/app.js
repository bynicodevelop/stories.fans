require("./bootstrap");

import Alpine from "alpinejs";
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":2053",
});

window.Alpine = Alpine;

Alpine.start();
