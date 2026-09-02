<?php 
session_start(); 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Bag | StyleHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .qty-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            background: var(--surface-alt);
        }
        .qty-btn {
            background: transparent;
            border: none;
            padding: 4px 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            cursor: pointer;
        }
        .qty-btn:hover {
            background: var(--border);
        }
        .qty-val {
            padding: 0 8px;
            font-weight: 600;
            font-size: 14px;
        }
        .trust-badge-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 14px;
        }
    </style>
</head>
<body>

    <!-- Sticky Navigation Bar -->
    <header class="site-header">
        <div class="header-container">
            <div class="brand-logo" onclick="window.location='index.php'">
                <h1>StyleHub <span>Boutique</span></h1>
            </div>

            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">← Back to Shop</a></li>
                    <li><a href="index.php#collection" class="nav-link">Kurtis</a></li>
                    <?php if(isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
                        <li>
                            <div class="user-pill">
                                <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                                <a href="login.php" class="logout-btn">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link">Sign In</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="section-container" style="max-width:1100px; padding:40px 20px;">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:32px; color:var(--text-main);">Your Shopping Bag</h1>
            <p style="color:var(--text-muted); font-size:15px;">Review items in your cart before secure checkout.</p>
        </div>

        <div class="cart-layout" id="cartLayout">
            <!-- Left: Cart Items List -->
            <div class="cart-items-card">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border); text-align:left;">
                            <th style="padding:12px; color:var(--text-muted); font-size:13px; text-transform:uppercase;">Product</th>
                            <th style="padding:12px; color:var(--text-muted); font-size:13px; text-transform:uppercase;">Price</th>
                            <th style="padding:12px; color:var(--text-muted); font-size:13px; text-transform:uppercase; text-align:center;">Qty</th>
                            <th style="padding:12px; color:var(--text-muted); font-size:13px; text-transform:uppercase; text-align:right;">Subtotal</th>
                            <th style="padding:12px; text-align:center;"></th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody"></tbody>
                </table>
            </div>

            <!-- Right: Order Summary Sticky Card -->
            <div class="order-summary-card">
                <h3 class="summary-title">Order Summary</h3>

                <div class="summary-row">
                    <span>Items Total</span>
                    <span>₹<span id="summarySubtotal">0</span></span>
                </div>

                <div class="summary-row">
                    <span>Standard Shipping</span>
                    <span style="color:var(--success); font-weight:700;">FREE</span>
                </div>

                <div class="summary-row">
                    <span>Estimated Tax (GST)</span>
                    <span style="color:var(--text-muted);">Included</span>
                </div>

                <div class="summary-row total">
                    <span>Total Payable</span>
                    <span>₹<span id="summaryGrandTotal">0</span></span>
                </div>

                <form action="save_order.php" method="POST" onsubmit="return validateOrderSubmission();">
                    <input type="hidden" name="product_name" id="product_name">
                    <input type="hidden" name="quantity" id="quantity">
                    <input type="hidden" name="total_amount" id="total_amount">
                    <button type="submit" id="checkoutBtn" class="checkout-btn">
                        Place Order (Cash On Delivery)
                    </button>
                </form>

                <div class="trust-badge-row">
                    <span>🛡️</span>
                    <span>Safe & Encrypted 256-Bit SSL Checkout</span>
                </div>
                <div class="trust-badge-row">
                    <span>🔄</span>
                    <span>7-Day Hassle-free Exchange Guarantee</span>
                </div>
            </div>
        </div>

        <!-- Empty Cart State -->
        <div id="emptyCartView" style="display:none; text-align:center; padding:70px 20px; background:white; border-radius:var(--radius-lg); border:1px solid var(--border); box-shadow:var(--shadow-sm);">
            <div style="font-size:64px; margin-bottom:16px;">🛍️</div>
            <h2 style="font-size:28px; margin-bottom:10px; color:var(--text-main);">Your Shopping Bag is Empty</h2>
            <p style="color:var(--text-muted); max-width:440px; margin:0 auto 24px; font-size:15px;">Looks like you haven't added any designer kurtis yet. Explore our latest arrivals and fill your wardrobe with elegance.</p>
            <a href="index.php#collection" class="btn-gold" style="display:inline-block;">Browse Kurtis Collection</a>
        </div>
    </main>

    <!-- Cart Script -->
    <script>
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
                if (Array.isArray(parsed) && parsed.length > 0) {
                    saveCart(parsed);
                    return parsed;
                }
            }
            let guestRaw = localStorage.getItem("cart_Guest");
            if (guestRaw) {
                let parsed = JSON.parse(guestRaw);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    saveCart(parsed);
                    return parsed;
                }
            }
            for (let i = 0; i < localStorage.length; i++) {
                let k = localStorage.key(i);
                if (k && k.startsWith("cart_")) {
                    let parsed = JSON.parse(localStorage.getItem(k));
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        saveCart(parsed);
                        return parsed;
                    }
                }
            }
        } catch(e) {
            console.error("Cart error:", e);
        }
        return [];
    }

    function saveCart(cart) {
        let json = JSON.stringify(cart);
        localStorage.setItem("stylehub_cart", json);
        localStorage.setItem("cart", json);
        let name = localStorage.getItem("name") || "Guest";
        localStorage.setItem("cart_" + name, json);
    }

    function renderCart() {
        let cart = getCart();
        let tbody = document.getElementById("cartTableBody");
        let layout = document.getElementById("cartLayout");
        let emptyView = document.getElementById("emptyCartView");
        let subtotalEl = document.getElementById("summarySubtotal");
        let grandTotalEl = document.getElementById("summaryGrandTotal");
        let checkoutBtn = document.getElementById("checkoutBtn");

        if (!cart || cart.length === 0) {
            layout.style.display = "none";
            emptyView.style.display = "block";
            return;
        }

        layout.style.display = "grid";
        emptyView.style.display = "none";

        let html = "";
        let total = 0;
        let totalQty = 0;

        cart.forEach(function(item, index) {
            let qty = item.quantity || 1;
            let unitPrice = Number(item.price) || 0;
            let itemTotal = unitPrice * qty;
            total += itemTotal;
            totalQty += qty;

            let imgHtml = item.image ? `<img src="${item.image}" alt="${item.name}">` : '';

            html += `
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:16px 12px;">
                    <div class="product-cell">
                        ${imgHtml}
                        <div>
                            <div style="font-weight:700; font-size:15px; color:var(--text-main);">${item.name}</div>
                            <small style="color:var(--text-muted);">Standard Size: M • In Stock</small>
                        </div>
                    </div>
                </td>
                <td style="padding:16px 12px; font-weight:600; color:var(--text-muted);">₹${unitPrice}</td>
                <td style="padding:16px 12px; text-align:center;">
                    <div class="qty-control">
                        <button type="button" class="qty-btn" onclick="updateQty(${index}, -1)">−</button>
                        <span class="qty-val">${qty}</span>
                        <button type="button" class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                    </div>
                </td>
                <td style="padding:16px 12px; font-weight:800; color:var(--primary); text-align:right;">₹${itemTotal}</td>
                <td style="padding:16px 12px; text-align:center;">
                    <button type="button" onclick="removeItem(${index})" title="Remove item" 
                            style="background:none; border:none; color:#EF4444; font-size:18px; cursor:pointer; padding:4px 8px;">
                        ✕
                    </button>
                </td>
            </tr>
            `;
        });

        tbody.innerHTML = html;
        subtotalEl.innerText = total.toLocaleString('en-IN');
        grandTotalEl.innerText = total.toLocaleString('en-IN');

        let productNames = cart.map(function(item) {
            let q = item.quantity || 1;
            return item.name + (q > 1 ? " (x" + q + ")" : "");
        }).join(", ");

        document.getElementById("product_name").value = productNames;
        document.getElementById("quantity").value = totalQty;
        document.getElementById("total_amount").value = total;
    }

    function updateQty(index, change) {
        let cart = getCart();
        if (cart[index]) {
            let currentQty = cart[index].quantity || 1;
            let newQty = currentQty + change;
            if (newQty <= 0) {
                if (confirm("Remove " + cart[index].name + " from your cart?")) {
                    cart.splice(index, 1);
                }
            } else {
                cart[index].quantity = newQty;
            }
            saveCart(cart);
            renderCart();
        }
    }

    function removeItem(index) {
        let cart = getCart();
        cart.splice(index, 1);
        saveCart(cart);
        renderCart();
    }

    function validateOrderSubmission() {
        let cart = getCart();
        if (!cart || cart.length === 0) {
            alert("Your cart is empty! Please add products before placing an order.");
            return false;
        }
        return true;
    }

    document.addEventListener("DOMContentLoaded", renderCart);
    renderCart();
    </script>
</body>
</html>
