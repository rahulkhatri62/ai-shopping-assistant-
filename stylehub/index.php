<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

// Fetch products dynamically from DB
$productsQuery = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");
$allProducts = [];
while($p = mysqli_fetch_assoc($productsQuery)) {
    $allProducts[] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleHub | AI Ethnic Shopping Assistant & Boutique</title>
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
                    <li><a href="#collection" class="nav-link">Collection</a></li>
                    <li><a href="#ai-section" class="nav-link">✨ AI Stylist</a></li>
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
                        <li><a href="registration.php" class="nav-link">Register</a></li>
                    <?php endif; ?>

                    <li>
                        <span class="nav-link" style="cursor:pointer;" onclick="showToast('❤️ You have ' + getWishlist().length + ' saved favorites in your Wishlist!', 'info')">
                            ❤️ <span id="wishlistBadge" style="font-weight:700;">0</span>
                        </span>
                    </li>

                    <li>
                        <a href="cart.php" class="cart-nav-btn">
                            🛒 Bag <span id="cartBadge" class="cart-badge-pill">0</span>
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
            <p>Experience smart shopping with our conversational AI Shopping Assistant. Speak or type your style, budget, and occasion.</p>
            <div class="hero-cta-group">
                <a href="#collection" class="btn-gold">Explore Collection</a>
                <button type="button" class="btn-outline-white" onclick="toggleChatbot()">Chat with StyleBot 🤖</button>
            </div>
        </div>
    </section>

    <!-- AI Shopping Assistant Top Banner -->
    <div class="ai-assistant-wrapper" id="ai-section">
        <div class="ai-assistant-card">
            <div class="ai-card-header">
                <div class="ai-title-wrap">
                    <div class="ai-robot-badge">🤖</div>
                    <div>
                        <h2>StyleHub AI Personal Shopper</h2>
                        <p>Ask anything: "Kurti under 1000", "Party wear silk", "College casual"...</p>
                    </div>
                </div>
                <button type="button" class="btn-gold" style="padding:8px 16px; font-size:13px;" onclick="startVoiceRecognition()">
                    🎙️ Voice Search
                </button>
            </div>

            <div class="ai-search-box">
                <input type="text" id="aiInput" class="ai-input" 
                       placeholder="E.g., kurti under 1000, party silk kurti, floral anarkali..."
                       onkeydown="if(event.key==='Enter'){askAI();}">
                <button type="button" class="ai-search-btn" onclick="askAI()">
                    Search AI ⌕
                </button>
            </div>

            <div class="ai-chips-container">
                <span class="ai-chip-label">Quick Prompts:</span>
                <span class="ai-chip" onclick="askWithChip('kurti under 1000')">💰 Under ₹1000</span>
                <span class="ai-chip" onclick="askWithChip('party wear silk')">✨ Party Silk</span>
                <span class="ai-chip" onclick="askWithChip('college wear cotton')">🎓 College Daily</span>
                <span class="ai-chip" onclick="askWithChip('festive embroidered')">🎉 Festive Special</span>
                <span class="ai-chip" onclick="askWithChip('floral anarkali')">🌸 Flowy Anarkali</span>
            </div>

            <div id="aiResponse" class="ai-response-box"></div>
        </div>
    </div>

    <!-- Product Showcase Collection -->
    <section class="section-container" id="collection">
        <div class="section-header">
            <span class="section-tag">Curated Boutique</span>
            <h2>Our Signature Kurti Collection</h2>
            <p>Every piece is tailored with premium breathable fabrics and certified artisan craftsmanship.</p>
        </div>

        <!-- Storefront Toolbar: Category Tabs & Sort -->
        <div class="store-toolbar">
            <div class="category-tabs" id="categoryTabs">
                <button class="cat-tab active" onclick="filterCategory('All', this)">All Kurtis</button>
                <button class="cat-tab" onclick="filterCategory('Daily Wear', this)">Daily Cotton</button>
                <button class="cat-tab" onclick="filterCategory('Party Wear', this)">Party Silk</button>
                <button class="cat-tab" onclick="filterCategory('Festive', this)">Festive</button>
                <button class="cat-tab" onclick="filterCategory('Anarkali', this)">Anarkali</button>
            </div>

            <div style="display:flex; align-items:center; gap:12px;">
                <input type="text" id="storeSearchInput" placeholder="🔍 Search kurtis..." 
                       style="padding:8px 14px; border:1px solid var(--border); border-radius:20px; font-size:13px;"
                       oninput="filterSearch(this.value)">
                
                <select class="sort-select" onchange="sortProducts(this.value)">
                    <option value="featured">Sort by: Featured</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating">Top Customer Rated</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            <?php foreach($allProducts as $p): ?>
                <div class="product-card" data-id="<?php echo $p['id']; ?>" data-category="<?php echo htmlspecialchars($p['category']); ?>" data-price="<?php echo $p['price']; ?>" data-rating="<?php echo $p['rating']; ?>" data-name="<?php echo htmlspecialchars(strtolower($p['name'])); ?>">
                    <div class="product-image-wrap">
                        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" loading="lazy">
                        <span class="product-badge <?php echo $p['badge'] === 'Best Value' ? 'sale' : ''; ?>"><?php echo htmlspecialchars($p['badge']); ?></span>
                        
                        <button type="button" class="wishlist-toggle-btn" data-id="<?php echo $p['id']; ?>" 
                                onclick="toggleWishlist(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['name'])); ?>', <?php echo $p['price']; ?>, '<?php echo htmlspecialchars(addslashes($p['image'])); ?>')">
                            🤍
                        </button>
                    </div>
                    <div class="product-body">
                        <div class="product-rating">★★★★★ <span>(<?php echo $p['rating']; ?> • <?php echo $p['reviews_count']; ?> reviews)</span></div>
                        <h3 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                        
                        <div class="product-price-wrap">
                            <span class="current-price">₹<?php echo number_format($p['price']); ?></span>
                            <span class="original-price">₹<?php echo number_format($p['original_price']); ?></span>
                            <span class="discount-tag"><?php echo $p['discount_percent']; ?>% OFF</span>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <small style="color:var(--text-muted);">Fabric: <?php echo htmlspecialchars($p['fabric']); ?></small>
                            <button type="button" class="quick-view-btn" onclick="openQuickView(<?php echo $p['id']; ?>)">Quick View 👁️</button>
                        </div>

                        <button type="button" class="add-cart-btn" onclick="addToCart('<?php echo htmlspecialchars(addslashes($p['name'])); ?>', <?php echo $p['price']; ?>, '<?php echo htmlspecialchars(addslashes($p['image'])); ?>', 'M')">
                            🛒 Add to Bag
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
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
                <p>Cash on Delivery (COD), UPI, and Cards with bank-grade 256-bit encryption.</p>
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
                <h1>Use Coupon "STYLE20" for Flat 20% OFF</h1>
                <p>Apply code <strong>STYLE20</strong> during checkout to claim instant 20% savings on orders above ₹500.</p>
                <a href="#collection" class="btn-gold">Shop The Offer Now</a>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal-overlay" id="quickViewModal" onclick="if(event.target===this)closeQuickView();">
        <div class="modal-box">
            <button type="button" class="modal-close-btn" onclick="closeQuickView()">✕</button>
            <div class="modal-img-container">
                <img id="modalImg" src="" alt="Kurti Preview">
            </div>
            <div class="modal-info-pane">
                <span id="modalRating" style="color:var(--gold); font-size:13px; margin-bottom:6px; font-weight:600;"></span>
                <h2 id="modalTitle" style="font-size:24px; color:var(--text-main); margin-bottom:10px;"></h2>
                
                <div class="product-price-wrap" style="margin-bottom:14px;">
                    <span id="modalPrice" class="current-price"></span>
                    <span id="modalOriginalPrice" class="original-price"></span>
                    <span id="modalDiscount" class="discount-tag"></span>
                </div>

                <p id="modalDesc" style="color:var(--text-muted); font-size:14px; line-height:1.6; margin-bottom:16px;"></p>

                <div style="font-size:13px; color:var(--text-main); margin-bottom:12px;">
                    🧵 <strong>Fabric:</strong> <span id="modalFabric"></span>
                </div>

                <div class="size-selector-wrap">
                    <label style="font-size:13px; font-weight:700; color:var(--text-main);">Select Size:</label>
                    <div class="size-pills-list">
                        <div class="size-pill" data-size="S" onclick="selectSize('S')">S</div>
                        <div class="size-pill selected" data-size="M" onclick="selectSize('M')">M</div>
                        <div class="size-pill" data-size="L" onclick="selectSize('L')">L</div>
                        <div class="size-pill" data-size="XL" onclick="selectSize('XL')">XL</div>
                        <div class="size-pill" data-size="XXL" onclick="selectSize('XXL')">XXL</div>
                    </div>
                </div>

                <button type="button" class="checkout-btn" style="margin-top:auto;" onclick="addModalProductToCart()">
                    🛒 Add to Shopping Bag
                </button>
            </div>
        </div>
    </div>

    <!-- Floating AI Chatbot Launcher -->
    <div class="chatbot-launcher" onclick="toggleChatbot()" title="Chat with StyleBot">
        🤖
        <span class="chatbot-launcher-badge">AI</span>
    </div>

    <!-- Floating AI Chatbot Window -->
    <div class="chatbot-window" id="stylebotWindow">
        <div class="chat-header">
            <div class="chat-header-title">
                <div style="font-size:24px;">🤖</div>
                <div>
                    <h4>StyleBot AI</h4>
                    <small>● Online • Instant Stylist</small>
                </div>
            </div>
            <button type="button" class="chat-close-btn" onclick="toggleChatbot()">✕</button>
        </div>

        <div class="chat-messages-pane" id="chatMessagesPane">
            <div class="chat-bubble bot">
                Namaste! 🙏 I am <strong>StyleBot</strong>, your smart shopping companion.<br><br>
                Tell me what you're looking for, such as:
                <br>• <em>"Kurtis under ₹1000"</em>
                <br>• <em>"Party wear silk"</em>
                <br>• <em>"What is your delivery time?"</em>
            </div>
        </div>

        <div class="chat-input-bar">
            <button type="button" class="chat-voice-btn" onclick="startVoiceRecognition()" title="Voice search">🎙️</button>
            <input type="text" id="chatInput" class="chat-text-input" 
                   placeholder="Ask StyleBot..." 
                   onkeydown="if(event.key==='Enter'){sendChatMessage();}">
            <button type="button" class="chat-send-btn" onclick="sendChatMessage()" title="Send">➤</button>
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
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="my_orders.php">Track Order Status</a></li>
                    <li><a href="cart.php">My Shopping Bag</a></li>
                    <li><a href="#why-us">Returns & Shipping</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Staff Access</h4>
                <ul>
                    <li><a href="admin_login.php">Admin Portal</a></li>
                    <li><a href="dashboard.php">Analytics Dashboard</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 StyleHub Fashion Inc. All rights reserved.</p>
            <p>Made with ❤️ by Nandani & Tanisha</p>
        </div>
    </footer>

    <!-- Storefront Filtering & AI Script -->
    <script src="script.js"></script>
    <script>
    function filterCategory(cat, btn) {
        document.querySelectorAll(".cat-tab").forEach(t => t.classList.remove("active"));
        btn.classList.add("active");

        let cards = document.querySelectorAll(".product-card");
        cards.forEach(card => {
            let cardCat = card.getAttribute("data-category");
            if (cat === "All" || cardCat === cat) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }

    function filterSearch(query) {
        let q = query.toLowerCase().trim();
        let cards = document.querySelectorAll(".product-card");
        cards.forEach(card => {
            let name = card.getAttribute("data-name") || "";
            let cat = (card.getAttribute("data-category") || "").toLowerCase();
            if (name.includes(q) || cat.includes(q)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }

    function sortProducts(criteria) {
        let grid = document.getElementById("productsGrid");
        let cards = Array.from(grid.getElementsByClassName("product-card"));

        cards.sort((a, b) => {
            let priceA = Number(a.getAttribute("data-price"));
            let priceB = Number(b.getAttribute("data-price"));
            let ratingA = Number(a.getAttribute("data-rating"));
            let ratingB = Number(b.getAttribute("data-rating"));

            if (criteria === "price_asc") return priceA - priceB;
            if (criteria === "price_desc") return priceB - priceA;
            if (criteria === "rating") return ratingB - ratingA;
            return Number(a.getAttribute("data-id")) - Number(b.getAttribute("data-id"));
        });

        cards.forEach(card => grid.appendChild(card));
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
            response.innerHTML = "🤖 Please enter what you are looking for!";
            return;
        }

        // Call backend chat API
        fetch("api_chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: input })
        })
        .then(r => r.json())
        .then(data => {
            response.style.display = "block";
            let html = data.reply;
            if (data.products && data.products.length > 0) {
                html += `<div style="display:flex; gap:12px; margin-top:10px; flex-wrap:wrap;">`;
                data.products.forEach(p => {
                    html += `
                        <div style="background:white; padding:8px 12px; border-radius:8px; border:1px solid var(--border); display:flex; align-items:center; gap:8px;">
                            <img src="${p.image}" width="40" height="40" style="object-fit:cover; border-radius:4px;">
                            <div>
                                <strong>${p.name}</strong><br>
                                <span style="color:var(--primary); font-weight:700;">₹${p.price}</span>
                            </div>
                            <button type="button" style="margin-left:8px; padding:4px 8px; font-size:11px; background:var(--primary); color:white; border:none; border-radius:4px; cursor:pointer;" onclick="addToCart('${p.name}', ${p.price}, '${p.image}')">+ Add</button>
                        </div>
                    `;
                });
                html += `</div>`;
            }
            response.innerHTML = html;
        });
    }
    </script>
</body>
</html>
