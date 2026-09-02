// StyleHub Scripts

// Safe product search listener
document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.getElementById("search");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            let searchText = this.value.toLowerCase();
            let products = document.querySelectorAll(".product, .card");

            products.forEach(function (product) {
                let text = product.innerText.toLowerCase();
                if (text.includes(searchText)) {
                    product.style.display = "";
                } else {
                    product.style.display = "none";
                }
            });
        });
    }

    let chatbotBtn = document.querySelector(".chatbot");
    if (chatbotBtn) {
        chatbotBtn.addEventListener("click", function () {
            let message = prompt("StyleHub Assistant:\nHow can I help you?");
            if (message) {
                alert("You asked: " + message + "\n\nChatbot feature coming soon!");
            }
        });
    }
});

