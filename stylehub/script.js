// StyleHub Client Utilities & Animations

document.addEventListener("DOMContentLoaded", function () {
    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener("click", function(e) {
            let targetId = this.getAttribute("href");
            if (targetId && targetId !== "#") {
                let targetEl = document.querySelector(targetId);
                if (targetEl) {
                    e.preventDefault();
                    targetEl.scrollIntoView({ behavior: "smooth", block: "start" });
                }
            }
        });
    });

    // Optional Live Search for items
    let searchInput = document.getElementById("search");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            let query = this.value.toLowerCase().trim();
            let cards = document.querySelectorAll(".product-card");
            cards.forEach(function (card) {
                let text = card.innerText.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }
});
