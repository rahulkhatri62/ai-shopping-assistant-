<?php 
session_start(); 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$userName = $_SESSION['name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Bag & Checkout | StyleHub</title>
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
        .coupon-section {
            background: var(--surface-alt);
            border: 1px dashed var(--border);
            padding: 16px;
            border-radius: var(--radius-sm);
            margin: 18px 0;
        }
        .coupon-input-group {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .coupon-input {
            flex: 1;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            text-transform: uppercase;
        }
        .coupon-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        .shipping-form-box {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .payment-radio-wrap {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 16px 0;
        }
        .payment-option-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }
        .payment-option-card.active {
            border-color: var(--primary);
            background: var(--primary-light);
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
                    <li><a href="index.php#collection" class="nav-link">Collection</a></li>
                    <li><a href="my_orders.php" class="nav-link">📦 My Orders</a></li>
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

    <main class="section-container" style="max-width:1160px; padding:40px 20px;">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:32px; color:var(--text-main);">Your Shopping Bag & Checkout</h1>
            <p style="color:var(--text-muted); font-size:15px;">Review items, enter your shipping address, and confirm your order.</p>
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

            <!-- Right: Order Summary & Checkout Form -->
            <div class="order-summary-card">
                <h3 class="summary-title">Order Summary</h3>

                <div class="summary-row">
                    <span>Items Subtotal</span>
                    <span>₹<span id="summarySubtotal">0</span></span>
                </div>

                <div class="summary-row" id="discountRow" style="display:none; color:var(--success); font-weight:700;">
                    <span>Coupon Discount (<span id="appliedCouponCode"></span>)</span>
                    <span>−₹<span id="summaryDiscount">0</span></span>
                </div>

                <div class="summary-row">
                    <span>Standard Shipping</span>
                    <span style="color:var(--success); font-weight:700;">FREE 🎉</span>
                </div>

                <div class="summary-row total">
                    <span>Total Payable</span>
                    <span>₹<span id="summaryGrandTotal">0</span></span>
                </div>

                <!-- Coupon Section -->
                <div class="coupon-section">
                    <label style="font-size:13px; font-weight:700; color:var(--text-main); display:flex; justify-content:space-between;">
                        <span>🏷️ Apply Promo Code:</span>
                        <small style="color:var(--primary); cursor:pointer;" onclick="setCoupon('STYLE20')">Try: STYLE20</small>
                    </label>
                    <div class="coupon-input-group">
                        <input type="text" id="couponInput" class="coupon-input" placeholder="Enter coupon code">
                        <button type="button" class="coupon-btn" onclick="applyCoupon()">Apply</button>
                    </div>
                    <div id="couponMsg" style="font-size:12px; margin-top:6px; font-weight:600;"></div>
                </div>

                <!-- Shipping Address Form -->
                <form action="save_order.php" method="POST" id="checkoutForm" onsubmit="return validateOrderSubmission();">
                    <input type="hidden" name="product_name" id="product_name">
                    <input type="hidden" name="quantity" id="quantity">
                    <input type="hidden" name="total_amount" id="total_amount">
                    <input type="hidden" name="discount_amount" id="discount_amount" value="0">
                    <input type="hidden" name="coupon_code" id="coupon_code" value="">

                    <div class="shipping-form-box">
                        <h4 style="font-size:16px; margin-bottom:14px; color:var(--text-main);">📍 Shipping & Contact Details</h4>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Full Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-input" 
                                   value="<?php echo htmlspecialchars($userName); ?>" placeholder="Enter recipient full name" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-input" 
                                   placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Delivery Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-input" 
                                      rows="2" placeholder="House no, Street, Landmark" required></textarea>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:600;">City</label>
                                <input type="text" name="city" id="city" class="form-input" placeholder="City" required>
                            </div>
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:600;">PIN Code</label>
                                <input type="text" name="pincode" id="pincode" class="form-input" placeholder="PIN Code" required>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <h4 style="font-size:14px; margin:16px 0 10px; color:var(--text-main);">💳 Select Payment Method</h4>
                        <div class="payment-radio-wrap">
                            <label class="payment-option-card active" onclick="selectPayment(this)">
                                <input type="radio" name="payment_method" value="Cash On Delivery" checked>
                                <div>
                                    <strong style="font-size:13px;">💵 Cash on Delivery (COD)</strong>
                                    <div style="font-size:11px; color:var(--text-muted);">Pay securely in cash or UPI when delivered</div>
                                </div>
                            </label>

                            <label class="payment-option-card" onclick="selectPayment(this)">
                                <input type="radio" name="payment_method" value="UPI / QR Code">
                                <div>
                                    <strong style="font-size:13px;">📱 UPI / QR Code (GPay, PhonePe)</strong>
                                    <div style="font-size:11px; color:var(--text-muted);">Instant zero-fee scan & pay</div>
                                </div>
                            </label>

                            <label class="payment-option-card" onclick="selectPayment(this)">
                                <input type="radio" name="payment_method" value="Debit / Credit Card">
                                <div>
                                    <strong style="font-size:13px;">💳 Online Card / Net Banking</strong>
                                    <div style="font-size:11px; color:var(--text-muted);">All major bank cards supported</div>
                                </div>
                            </label>
                        </div>

                        <button type="submit" id="checkoutBtn" class="checkout-btn">
                            Place Order (Confirm Purchase) →
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Empty Cart State -->
        <div id="emptyCartView" style="display:none; text-align:center; padding:70px 20px; background:white; border-radius:var(--radius-lg); border:1px solid var(--border); box-shadow:var(--shadow-sm);">
            <div style="font-size:64px; margin-bottom:16px;">🛍️</div>
            <h2 style="font-size:28px; margin-bottom:10px; color:var(--text-main);">Your Shopping Bag is Empty</h2>
            <p style="color:var(--text-muted); max-width:440px; margin:0 auto 24px; font-size:15px;">Looks like you haven't added any designer kurtis yet. Explore our collection to find your perfect ethnic look.</p>
            <a href="index.php#collection" class="btn-gold" style="display:inline-block;">Browse Kurtis Collection</a>
        </div>
    </main>

    <!-- Cart Scripts -->
    <script src="script.js"></script>
    <script>
    let appliedDiscount = 0;
    let appliedCoupon = "";
    let rawSubtotal = 0;

    function renderCart() {
        let cart = getCart();
        let tbody = document.getElementById("cartTableBody");
        let layout = document.getElementById("cartLayout");
        let emptyView = document.getElementById("emptyCartView");
        let subtotalEl = document.getElementById("summarySubtotal");
        let grandTotalEl = document.getElementById("summaryGrandTotal");

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

            let sizeTag = item.size ? `Size: ${item.size}` : 'Standard';
            let imgHtml = item.image ? `<img src="${item.image}" alt="${item.name}">` : '';

            html += `
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:16px 12px;">
                    <div class="product-cell">
                        ${imgHtml}
                        <div>
                            <div style="font-weight:700; font-size:15px; color:var(--text-main);">${item.name}</div>
                            <small style="color:var(--primary); font-weight:600; background:var(--primary-light); padding:1px 6px; border-radius:4px;">${sizeTag}</small>
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
        rawSubtotal = total;
        subtotalEl.innerText = total.toLocaleString('en-IN');

        let finalAmount = Math.max(0, total - appliedDiscount);
        grandTotalEl.innerText = finalAmount.toLocaleString('en-IN');

        let productNames = cart.map(function(item) {
            let q = item.quantity || 1;
            let s = item.size ? ` (${item.size})` : '';
            return item.name + s + (q > 1 ? " x" + q : "");
        }).join(", ");

        document.getElementById("product_name").value = productNames;
        document.getElementById("quantity").value = totalQty;
        document.getElementById("total_amount").value = finalAmount;
        document.getElementById("discount_amount").value = appliedDiscount;
        document.getElementById("coupon_code").value = appliedCoupon;
    }

    function updateQty(index, change) {
        let cart = getCart();
        if (cart[index]) {
            let currentQty = cart[index].quantity || 1;
            let newQty = currentQty + change;
            if (newQty <= 0) {
                if (confirm("Remove " + cart[index].name + " from your bag?")) {
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

    function setCoupon(code) {
        document.getElementById("couponInput").value = code;
        applyCoupon();
    }

    function applyCoupon() {
        let code = document.getElementById("couponInput").value.trim();
        let msgEl = document.getElementById("couponMsg");

        if (!code) {
            msgEl.innerText = "Please enter a coupon code.";
            msgEl.style.color = "#DC2626";
            return;
        }

        fetch(`api_coupon.php?code=${encodeURIComponent(code)}&subtotal=${rawSubtotal}`)
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                appliedDiscount = data.discount_amount;
                appliedCoupon = data.code;
                
                document.getElementById("discountRow").style.display = "flex";
                document.getElementById("appliedCouponCode").innerText = data.code;
                document.getElementById("summaryDiscount").innerText = appliedDiscount.toLocaleString('en-IN');
                
                msgEl.innerText = data.msg;
                msgEl.style.color = "#16A34A";
                renderCart();
                showToast(data.msg);
            } else {
                msgEl.innerText = data.msg;
                msgEl.style.color = "#DC2626";
            }
        });
    }

    function selectPayment(card) {
        document.querySelectorAll(".payment-option-card").forEach(c => c.classList.remove("active"));
        card.classList.add("active");
        card.querySelector("input[type='radio']").checked = true;
    }

    function validateOrderSubmission() {
        let cart = getCart();
        if (!cart || cart.length === 0) {
            showToast("Your cart is empty! Please add products before placing an order.", "info");
            return false;
        }
        return true;
    }

    document.addEventListener("DOMContentLoaded", renderCart);
    renderCart();
    </script>
</body>
</html>
