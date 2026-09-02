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
    <title>StyleHub | AI Ethnic Shopping Assistant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- User Session Bridge -->
    <script>
        let sessionUserName = "<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?>";
        localStorage.setItem("name", sessionUserName);
    </script>

    <!-- Sticky Navigation Bar -->
    <header class="site-header">
        <div class="header-container">
            <div class="brand-logo" onclick="window.location='index.php'">
                <h1>StyleHub <span>Boutique</span></h1>
            </div>

            <nav>
                <ul class="nav-menu">
                    <li><a href="#home" class="nav-link active">Home</a></li>
                    <li><a href="#collection" class="nav-link">Kurtis Collection</a></li>
                    <li><a href="#ai-section" class="nav-link">✨ AI Stylist</a></li>
                    <li><a href="#why-us" class="nav-link">Why Us</a></li>
                    
                    <?php if(isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
                        <li>
                            <div class="user-pill">
                                <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                                <a href="login.php" class="logout-btn">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link">Sign In</a></li>
                        <li><a href="registration.php" class="nav-link">Register</a></li>
                    <?php endif; ?>

                    <li>
                        <a href="cart.php" class="cart-nav-btn">
                            🛒 Cart <span id="cartBadge" class="cart-badge-pill">0</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="hero-content">
            <div class="hero-tag">✨ Festive Season Collection 2026</div>
            <h1>Discover Timeless Elegance with Handcrafted Kurtis</h1>
            <p>Blend tradition with contemporary charm. Ask our AI Assistant to find your ideal outfit tailored to your budget and occasion.</p>
            <div class="hero-cta-group">
                <a href="#collection" class="btn-gold">Explore Collection</a>
                <a href="#ai-section" class="btn-outline-white">Ask AI Stylist 🤖</a>
            </div>
        </div>
    </section>

    <!-- AI Shopping Assistant Widget -->
    <div class="ai-assistant-wrapper" id="ai-section">
        <div class="ai-assistant-card">
            <div class="ai-card-header">
                <div class="ai-title-wrap">
                    <div class="ai-robot-badge">🤖</div>
                    <div>
                        <h2>StyleHub AI Personal Shopper</h2>
                        <p>Tell me what you're looking for (occasion, budget, or style)</p>
                    </div>
                </div>
            </div>

            <div class="ai-search-box">
                <input type="text" id="aiInput" class="ai-input" 
                       placeholder="E.g., kurti under 1000, party silk kurti, college outfit..."
                       onkeydown="if(event.key==='Enter'){askAI();}">
                <button type="button" class="ai-search-btn" onclick="askAI()">
                    Search AI ⌕
                </button>
            </div>

            <div class="ai-chips-container">
                <span class="ai-chip-label">Try asking:</span>
                <span class="ai-chip" onclick="askWithChip('kurti under 1000')">💰 Under ₹1000</span>
                <span class="ai-chip" onclick="askWithChip('party wear')">✨ Party Wear</span>
                <span class="ai-chip" onclick="askWithChip('college wear')">🎓 College Daily</span>
                <span class="ai-chip" onclick="askWithChip('festive collection')">🎉 Festive Silk</span>
            </div>

            <div id="aiResponse" class="ai-response-box"></div>
        </div>
    </div>

    <!-- Product Showcase Collection -->
    <section class="section-container" id="collection">
        <div class="section-header">
            <span class="section-tag">Handpicked For You</span>
            <h2>Our Signature Kurti Collection</h2>
            <p>Crafted with premium cotton, silk, and breathable chanderi fabrics designed for all-day comfort and royal elegance.</p>
        </div>

        <div class="products-grid">
            <!-- Product 1 -->
            <div class="product-card">
                <div class="product-image-wrap">
                    <img src="images/kurti1.jpeg" alt="Casual Cotton Daily Kurti">
                    <span class="product-badge sale">Best Value</span>
                </div>
                <div class="product-body">
                    <div class="product-rating">★★★★★ <span>(4.8 • 142 reviews)</span></div>
                    <h3 class="product-title">Casual Daily Cotton Kurti</h3>
                    <div class="product-price-wrap">
                        <span class="current-price">₹999</span>
                        <span class="original-price">₹1,499</span>
                        <span class="discount-tag">33% OFF</span>
                    </div>
                    <button type="button" class="add-cart-btn" onclick="addToCart('Casual Daily Cotton Kurti', 999, 'images/kurti1.jpeg')">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-card">
                <div class="product-image-wrap">
                    <img src="images/kurti2.jpeg" alt="Royal Silk Party Wear Kurti">
                    <span class="product-badge">Bestseller</span>
                </div>
                <div class="product-body">
                    <div class="product-rating">★★★★★ <span>(4.9 • 230 reviews)</span></div>
                    <h3 class="product-title">Royal Party Silk Kurti</h3>
                    <div class="product-price-wrap">
                        <span class="current-price">₹1,499</span>
                        <span class="original-price">₹2,299</span>
                        <span class="discount-tag">35% OFF</span>
                    </div>
                    <button type="button" class="add-cart-btn" onclick="addToCart('Royal Party Silk Kurti', 1499, 'images/kurti2.jpeg')">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="product-card">
                <div class="product-image-wrap">
                    <img src="images/kurti3.jpeg" alt="Festive Embroidered Kurti">
                    <span class="product-badge">Trending</span>
                </div>
                <div class="product-body">
                    <div class="product-rating">★★★★★ <span>(4.7 • 98 reviews)</span></div>
                    <h3 class="product-title">Festive Embroidered Kurti</h3>
                    <div class="product-price-wrap">
                        <span class="current-price">₹1,199</span>
                        <span class="original-price">₹1,899</span>
                        <span class="discount-tag">37% OFF</span>
                    </div>
                    <button type="button" class="add-cart-btn" onclick="addToCart('Festive Embroidered Kurti', 1199, 'images/kurti3.jpeg')">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="product-card">
                <div class="product-image-wrap">
                    <img src="images/kurti4.jpeg" alt="Printed Floral Anarkali Kurti">
                    <span class="product-badge sale">New Arrival</span>
                </div>
                <div class="product-body">
                    <div class="product-rating">★★★★★ <span>(4.6 • 64 reviews)</span></div>
                    <h3 class="product-title">Printed Floral Anarkali Kurti</h3>
                    <div class="product-price-wrap">
                        <span class="current-price">₹899</span>
                        <span class="original-price">₹1,299</span>
                        <span class="discount-tag">31% OFF</span>
                    </div>
                    <button type="button" class="add-cart-btn" onclick="addToCart('Printed Floral Anarkali Kurti', 899, 'images/kurti4.jpeg')">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="features-section" id="why-us">
        <div class="section-header">
            <span class="section-tag">Our Promise</span>
            <h2>Why Shop at StyleHub?</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-box">🚚</div>
                <h3>Express Delivery</h3>
                <p>Free, lightning-fast delivery dispatched straight to your doorstep across India.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-box">🔒</div>
                <h3>100% Safe Payments</h3>
                <p>Encrypted UPI, Cards, and Net Banking options for complete peace of mind.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-box">⭐</div>
                <h3>Artisan Quality</h3>
                <p>Breathable fabrics, colorfast dyes, and meticulous stitching on every piece.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-box">🤖</div>
                <h3>AI Stylist Assistant</h3>
                <p>Instant outfit suggestions matched to your budget, style, and event.</p>
            </div>
        </div>
    </section>

    <!-- Special Offer Card -->
    <div class="offer-banner">
        <div class="offer-card">
            <div class="offer-text">
                <h2>Limited Time Promotion</h2>
                <h1>Flat 20% OFF On Selected Kurtis</h1>
                <p>Upgrade your festive ethnic wardrobe with exclusive designer selections.</p>
                <a href="#collection" class="btn-gold">Shop The Offer Now</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h2>StyleHub Boutique</h2>
                <p>India's premier smart destination for curated ethnic wear powered by AI shopping intelligence.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#collection">Kurtis Collection</a></li>
                    <li><a href="cart.php">My Cart</a></li>
                    <li><a href="login.php">My Account</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Staff Access</h4>
                <ul>
                    <li><a href="admin_login.php">Admin Portal</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 StyleHub Fashion Inc. All rights reserved.</p>
            <p>Made with ❤️ by Nandani & Tanisha</p>
        </div>
    </footer>

    <!-- Universal Cart & AI Stylist Logic -->
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
        updateCartCount();
    }

    function updateCartCount() {
        let cart = getCart();
        let count = cart.length;
        let badge = document.getElementById("cartBadge");
        if (badge) badge.innerText = count;
    }

    function addToCart(productname, price, image) {
        let cart = getCart();
        cart.push({
            name: productname,
            price: Number(price),
            image: image
        });
        saveCart(cart);
        alert("✨ " + productname + " added to your cart! Total items: " + cart.length);
    }

    function askWithChip(text) {
        document.getElementById("aiInput").value = text;
        askAI();
    }

    function askAI() {
        let input = document.getElementById("aiInput").value.toLowerCase().trim();
        let response = document.getElementById("aiResponse");

        if (input === "") {
            response.style.display = "block";
            response.innerHTML = "🤖 Please enter a price, occasion, or style you are looking for!";
            return;
        }

        response.style.display = "block";

        if (input.includes("1000") || input.includes("999") || input.includes("budget") || input.includes("cheap")) {
            response.innerHTML = "✨ <strong>Great Budget Pick:</strong> I recommend our <em>Casual Daily Cotton Kurti</em> at <strong>₹999</strong> (33% OFF) or <em>Printed Floral Anarkali</em> at <strong>₹899</strong>! <a href='#collection' style='color:#7A1C38; font-weight:bold; margin-left:10px;'>View Items ↓</a>";
        }
        else if (input.includes("1500") || input.includes("silk") || input.includes("party")) {
            response.innerHTML = "👗 <strong>Celebration Pick:</strong> For weddings and parties, our <em>Royal Party Silk Kurti</em> at <strong>₹1,499</strong> offers stunning luxury and grace! <a href='#collection' style='color:#7A1C38; font-weight:bold; margin-left:10px;'>View Items ↓</a>";
        }
        else if (input.includes("college") || input.includes("daily") || input.includes("office") || input.includes("casual")) {
            response.innerHTML = "🎓 <strong>Daily Comfort:</strong> For college and daily office wear, our breathable <em>Casual Daily Cotton Kurti</em> at <strong>₹999</strong> is your top choice! <a href='#collection' style='color:#7A1C38; font-weight:bold; margin-left:10px;'>View Items ↓</a>";
        }
        else if (input.includes("festive") || input.includes("diwali") || input.includes("eid") || input.includes("puja")) {
            response.innerHTML = "🎉 <strong>Festive Special:</strong> Check out the <em>Festive Embroidered Kurti</em> at <strong>₹1,199</strong> with intricate hand thread work! <a href='#collection' style='color:#7A1C38; font-weight:bold; margin-left:10px;'>View Items ↓</a>";
        }
        else {
            response.innerHTML = "🤖 <strong>I can help you find:</strong><br>• Kurtis under ₹1000<br>• Royal party wear<br>• Daily college & office outfits<br>Click any chip above or try searching by budget!";
        }
    }

    document.addEventListener("DOMContentLoaded", updateCartCount);
    updateCartCount();
    </script>
</body>
</html>
