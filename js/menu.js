document.addEventListener("DOMContentLoaded", function() {
    var items = document.querySelectorAll(".navbar li");

    items.forEach(function(item) {
        item.addEventListener("click", function() {
            var submenu = this.querySelector(".submenu");
            if (submenu) {
                submenu.style.display = submenu.style.display === "none" ? "block" : "none";
            }
        });
    });
});
