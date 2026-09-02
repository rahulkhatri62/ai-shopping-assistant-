<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
    <head>
        <title>StyleHub - AI Shopping Assistant</title>

        <!--css connect-->
        <link rel="stylesheet" href="style.css">
        
    </head>
    <body>

        <!-- javaScript connect-->
         <script>
         let name = "<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?>";
         localStorage.setItem("name", name);
         </script>
         <script src="script.js"></script>

<div class="logo">
    <h1>Kurti Gallery</h1>
</div>

<nav>
<a href="#home">Home</a>
<a href="#products-title">Products</a>
<a href="cart.php">🛒 Cart <span id="cartBadge" style="background:#8b3a3a; color:white; border-radius:12px; padding:2px 8px; font-size:14px; font-weight:bold;">0</span></a>
<?php if(isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
    <span style="color:#fff; padding:0 10px;">👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
    <a href="login.php">Log out</a>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="registration.php">Register</a>
<?php endif; ?>
</nav>

<section class="ai-assistant">
    <h2>🤖 AI Shopping Assistant</h2>

    <input type="text" id="aiInput" 
           placeholder="search anything in your budget">

    <button onclick="askAI()">Search ⌕</button>

    <div id="aiResponse"></div>
</section>

<section class="hero" id="home">
<p>Discover trendy clothes with premium quality</p>
</section>


<section id="products-title">
    <h2>Our Kurties collection</h2>

<div class="products">    

 <div class="product">
  <img src="images/kurti1.jpeg" alt="Daily Wear Kurti" width="150">
  <h3>Daily Wear Kurti</h3>
  <p>₹999</p>
  <button onclick="addToCart('Daily Wear Kurti', 999, 'images/kurti1.jpeg')">Add to Cart</button>
 </div>

 <div class="product">
  <img src="images/kurti2.jpeg" alt="Party Silk Kurti" width="150">
  <h3>Party Silk Kurti</h3>
  <p>₹1499</p>
  <button onclick="addToCart('Party Silk Kurti', 1499, 'images/kurti2.jpeg')">Add to Cart</button>
 </div>

 <div class="product">
  <img src="images/kurti3.jpeg" alt="Festive Embroidered Kurti" width="150">
  <h3>Festive Embroidered Kurti</h3>
  <p>₹1199</p>
  <button onclick="addToCart('Festive Embroidered Kurti', 1199, 'images/kurti3.jpeg')">Add to Cart</button>
 </div>

 <div class="product">
  <img src="images/kurti4.jpeg" alt="Printed Cotton Kurti" width="150">
  <h3>Printed Cotton Kurti</h3>
  <p>₹899</p>
  <button onclick="addToCart('Printed Cotton Kurti', 899, 'images/kurti4.jpeg')">Add to Cart</button>
 </div>
 
 <div id="cart"></div>

 </section>

 <!-- Why Choose Us -->
<section class="why-us">
    <h2>✨ Why Choose Us? ✨</h2>

    <div class="why-container">

        <div class="why-card">
            <div class="why-icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>Get your order delivered quickly to your doorstep.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">🔒</div>
            <h3>Secure Payment</h3>
            <p>Safe and secure payment options for every order.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">⭐</div>
            <h3>Quality Products</h3>
            <p>Premium quality kurtis with beautiful designs.</p>
        </div>

        <div class="why-card">
            <div class="why-icon">🤖</div>
            <h3>AI Shopping Assistant</h3>
            <p>Get smart suggestions to find your perfect kurti</p>
        </div>

    </div>
</section>


<!-- Special Offer -->
<section class="offer">
    <div class="offer-content">
        <h2>✨ Special Offer ✨</h2>
        <h1>Flat 20% OFF</h1>
        <p>On Selected Kurtis</p>

        <button onclick="location.href='#products'">
            Shop Now
        </button>
    </div>

    <div class="offer-badge">
        <span>20%</span>
        <small>OFF</small>
    </div>
</section>

</div>

</section>

<script>
// Universal Cart helper functions (handles all legacy and current keys)
function getCart() {
    let items = [];
    try {
        let raw = localStorage.getItem("stylehub_cart") || localStorage.getItem("cart");
        if (raw) {
            let parsed = JSON.parse(raw);
            if (Array.isArray(parsed) && parsed.length > 0) return parsed;
        }
        let userName = localStorage.getItem("name") || "Guest";
        let userRaw = localStorage.getItem("cart_" + userName);
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
    let userName = localStorage.getItem("name") || "Guest";
    localStorage.setItem("cart_" + userName, json);
    updateCartCount();
}

function updateCartCount() {
    let cart = getCart();
    let count = cart.length;
    let badge = document.getElementById("cartBadge");
    if (badge) badge.innerText = count;
    let linkCounts = document.querySelectorAll(".cartBadgeCount");
    linkCounts.forEach(function(el) { el.innerText = count; });
}

function addToCart(productname, price, image){
    let cart = getCart();
    cart.push({
        name: productname,
        price: Number(price),
        image: image
    });
    saveCart(cart);
    alert(productname + " added to cart successfully! (Total items in cart: " + cart.length + ")");
}

function askAI() {
    let input = document.getElementById("aiInput").value.toLowerCase();
    let response = document.getElementById("aiResponse");

    if (input.trim() === "") {
        response.innerHTML = "🤖 Please tell me what you're looking for.";
        return;
    }

    if (input.includes("1000")) {
        response.innerHTML =
            "✨ I found a kurti for you! Check our ₹999 kurti.";
    }
    else if (input.includes("1500")) {
        response.innerHTML =
            "✨ You can choose from our ₹999, ₹1199 and ₹1499 kurtis.";
    }
    else if (input.includes("party")) {
        response.innerHTML =
            "👗 For a party, I recommend our premium ₹1499 kurti.";
    }
    else if (input.includes("college")) {
        response.innerHTML =
            "🎓 For college, I recommend our affordable ₹999 kurti.";
    }
    else {
        response.innerHTML =
            "🤖 I can help you find kurtis by budget, occasion or style. Try: 'kurti under ₹1000' or 'party kurti'.";
    }
}

// Update cart badge immediately upon page load
document.addEventListener("DOMContentLoaded", updateCartCount);
updateCartCount();
</script>
<div class="cart-link" style="text-align:center; margin:30px 0;">
<a href="cart.php" style="background:#8b3a3a; color:white; padding:12px 25px; border-radius:8px; text-decoration:none; font-size:18px;">🛒 View Shopping Cart (<span class="cartBadgeCount">0</span>)</a>
</div>
</body>
</html>