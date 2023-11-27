import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.onload = () => {

    if (document.getElementById("user-menu-button")) {
        let menuButton = document.getElementById("user-menu-button");
        let dropdownMenu = document.querySelector("[aria-labelledby='user-menu-button']");

        menuButton.addEventListener("click", function() {
            dropdownMenu.classList.remove("hidden");
        });

        document.addEventListener("click", function(event) {
            if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });
    }

}