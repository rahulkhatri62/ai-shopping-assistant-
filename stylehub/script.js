// ==========================================================
// StyleHub Client Engine: Chatbot, Wishlist, QuickView & Cart
// ==========================================================

// 1. Toast Notification System (replaces alert popups)
function showToast(message, type = "success") {
    let container = document.getElementById("toast-container");
    if (!container) {
        container = document.createElement("div");
        container.id = "toast-container";
        document.body.appendChild(container);
    }

    const toast = document.createElement("div");
    toast.className = "toast-item";
    let icon = type === "success" ? "✨" : (type === "info" ? "ℹ️" : "⚠️");
    toast.innerHTML = `<span>${icon}</span><span>${message}</span>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = "opacity 0.4s ease, transform 0.4s ease";
        toast.style.opacity = "0";
        toast.style.transform = "translateX(40px)";
        setTimeout(() => toast.remove(), 400);
    }, 3200);
}

// 2. Wishlist System
function getWishlist() {
    try {
        let raw = localStorage.getItem("stylehub_wishlist");
        return raw ? JSON.parse(raw) : [];
    } catch(e) {
        return [];
    }
}

function saveWishlist(list) {
    localStorage.setItem("stylehub_wishlist", JSON.stringify(list));
    updateWishlistBadge();
}

function updateWishlistBadge() {
    let list = getWishlist();
    let badge = document.getElementById("wishlistBadge");
    if (badge) badge.innerText = list.length;
}

function toggleWishlist(productId, name, price, image) {
    let list = getWishlist();
    let index = list.findIndex(item => item.id == productId);

    if (index > -1) {
        list.splice(index, 1);
        showToast(`Removed "${name}" from Wishlist`, "info");
    } else {
        list.push({ id: productId, name, price, image });
        showToast(`❤️ Added "${name}" to Wishlist!`, "success");
    }

    saveWishlist(list);
    updateWishlistButtons();
}

function updateWishlistButtons() {
    let list = getWishlist();
    document.querySelectorAll(".wishlist-toggle-btn").forEach(btn => {
        let pid = btn.getAttribute("data-id");
        if (list.some(item => item.id == pid)) {
            btn.classList.add("active");
            btn.innerHTML = "❤️";
        } else {
            btn.classList.remove("active");
            btn.innerHTML = "🤍";
        }
    });
}

// 3. Universal Cart Engine
function getCart() {
    let items = [];
    try {
        let raw = localStorage.getItem("stylehub_cart") || localStorage.getItem("cart");
        if (raw) {
            let parsed = JSON.parse(raw);
            if (Array.isArray(parsed) && parsed.length > 0) return parsed;
        }
        let name = localStorage.getItem("name") || "Guest";
        let userRaw = localStorage.getItem("cart_" + name);
        if (userRaw) {
            let parsed = JSON.parse(userRaw);
            if (Array.isArray(parsed) && parsed.length > 0) return parsed;
        }
    } catch(e) {}
    return [];
}

function saveCart(cart) {
    let json = JSON.stringify(cart);
    localStorage.setItem("stylehub_cart", json);
    localStorage.setItem("cart", json);
    let name = localStorage.getItem("name") || "Guest";
    localStorage.setItem("cart_" + name, json);
    updateCartBadge();
}

function updateCartBadge() {
    let cart = getCart();
    let totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    let badge = document.getElementById("cartBadge");
    if (badge) badge.innerText = totalItems;
}

function addToCart(productname, price, image, size = "M") {
    let cart = getCart();
    let existing = cart.find(item => item.name === productname && (item.size || "M") === size);

    if (existing) {
        existing.quantity = (existing.quantity || 1) + 1;
    } else {
        cart.push({
            name: productname,
            price: Number(price),
            image: image,
            size: size,
            quantity: 1
        });
    }

    saveCart(cart);
    showToast(`🛒 "${productname}" (${size}) added to your bag!`);
}

// 4. Floating AI Chatbot (StyleBot)
function toggleChatbot() {
    let win = document.getElementById("stylebotWindow");
    if (win) {
        win.classList.toggle("active");
        if (win.classList.contains("active")) {
            let input = document.getElementById("chatInput");
            if (input) input.focus();
        }
    }
}

function sendChatMessage() {
    let input = document.getElementById("chatInput");
    if (!input) return;
    let text = input.value.trim();
    if (!text) return;

    input.value = "";
    appendChatMessage("user", text);

    // Call AI Backend API
    fetch("api_chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: text })
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.status) {
            appendChatMessage("bot", data.reply, data.products || []);
        } else {
            appendChatMessage("bot", "I am having trouble connecting right now. Please browse our collections!");
        }
    })
    .catch(() => {
        appendChatMessage("bot", "✨ I recommend checking our ₹999 Daily Cotton Kurti or ₹1,499 Royal Silk Kurti!");
    });
}

function appendChatMessage(sender, text, products = []) {
    let pane = document.getElementById("chatMessagesPane");
    if (!pane) return;

    let bubble = document.createElement("div");
    bubble.className = "chat-bubble " + sender;
    bubble.innerHTML = text;

    // If bot returned product recommendations, render mini cards
    if (products && products.length > 0) {
        products.forEach(p => {
            let card = document.createElement("div");
            card.className = "in-chat-card";
            card.innerHTML = `
                <img src="${p.image}" alt="${p.name}">
                <div class="in-chat-card-info">
                    <h5>${p.name}</h5>
                    <div class="price">₹${p.price}</div>
                </div>
                <button type="button" class="btn-quick-add" onclick="addToCart('${p.name}', ${p.price}, '${p.image}')">
                    + Add
                </button>
            `;
            bubble.appendChild(card);
        });
    }

    pane.appendChild(bubble);
    pane.scrollTop = pane.scrollHeight;
}

// 5. Speech-to-Text (Voice Recognition)
function startVoiceRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        showToast("Voice input is not supported in this browser. Try Google Chrome!", "info");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "en-IN";
    showToast("🎙️ Listening... Speak your request now!", "info");

    recognition.onresult = function(event) {
        let spoken = event.results[0][0].transcript;
        let chatInput = document.getElementById("chatInput");
        let aiInput = document.getElementById("aiInput");

        if (chatInput && document.getElementById("stylebotWindow").classList.contains("active")) {
            chatInput.value = spoken;
            sendChatMessage();
        } else if (aiInput) {
            aiInput.value = spoken;
            if (typeof askAI === "function") askAI();
        }
        showToast(`Heard: "${spoken}"`);
    };

    recognition.onerror = function() {
        showToast("Could not recognize voice. Please try again or type your request.", "info");
    };

    recognition.start();
}

// 6. Quick View Modal Engine
let activeModalProduct = null;
let selectedModalSize = "M";

function openQuickView(productId) {
    fetch(`api_products.php?action=detail&id=${productId}`)
    .then(r => r.json())
    .then(res => {
        if (res.status && res.data) {
            activeModalProduct = res.data;
            selectedModalSize = "M";

            document.getElementById("modalImg").src = res.data.image;
            document.getElementById("modalTitle").innerText = res.data.name;
            document.getElementById("modalPrice").innerText = "₹" + res.data.price;
            document.getElementById("modalOriginalPrice").innerText = "₹" + res.data.original_price;
            document.getElementById("modalDiscount").innerText = res.data.discount_percent + "% OFF";
            document.getElementById("modalDesc").innerText = res.data.description;
            document.getElementById("modalFabric").innerText = res.data.fabric || "Pure Cotton";
            document.getElementById("modalRating").innerText = `★ ${res.data.rating} (${res.data.reviews_count} verified reviews)`;

            // Reset size pills
            document.querySelectorAll(".size-pill").forEach(pill => {
                pill.classList.toggle("selected", pill.getAttribute("data-size") === "M");
            });

            document.getElementById("quickViewModal").classList.add("open");
        }
    });
}

function closeQuickView() {
    let modal = document.getElementById("quickViewModal");
    if (modal) modal.classList.remove("open");
}

function selectSize(size) {
    selectedModalSize = size;
    document.querySelectorAll(".size-pill").forEach(pill => {
        pill.classList.toggle("selected", pill.getAttribute("data-size") === size);
    });
}

function addModalProductToCart() {
    if (activeModalProduct) {
        addToCart(activeModalProduct.name, activeModalProduct.price, activeModalProduct.image, selectedModalSize);
        closeQuickView();
    }
}

// Initial state listener
document.addEventListener("DOMContentLoaded", function () {
    updateCartBadge();
    updateWishlistBadge();
    updateWishlistButtons();
});
