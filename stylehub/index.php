<?php
session_start();
?>
<html>
    <head>
        <title>ty bca project</title>

        <!--css connect-->
        <link rel="stylesheet" href="style.css">
        
    </head>
    <body>


        <!-- javaScript connect-->
         <script>
         let name = "<?php echo $_SESSION['name']; ?>";
         localStorage.setItem("name",name);
         </script>
         <script src="script.js"></script>

<div class="logo">

    <h1>Kurti Gallery</h1>
</div>

<nav>
<a href="#home">Home</a>
<a href="#products">Products</a>
<a onclick="location.href='login.php'">log out</a>
</nav>

<section class="ai-assistant">
    <h2>🤖 AI Shopping Assistant</h2>

    <input type="text" id="aiInput" 
           placeholder="search anything in your budget">

    <button onclick="askAI()">search ⌕</search></button>

    <div id="aiResponse"></div>
</section>

<section class="hero" id="home">
<p>Discover trendy clothes with premium quality</p>
</section>


<section id="products-title">
    <h2>Our Kurties collection</h2>

<div class="products">    

 <div class="product">
  <img src="images/kurti1.jpeg" alt="kurti" width="150">
  <h3>Kurti</h3>
  <p>₹999</p>
  <button onclick="addToCart('kurti',999,'images/kurti1.jpeg')">add to carts</button>
 </div>

 <div class="product">
  <img src="images/kurti2.jpeg" alt="kurti" width="150">
  <h3>kurti</h3>
  <p>₹1499</p>
  <button onclick="addToCart('kurti',1499,'images/kurti2.jpeg')">add to carts</button>
 </div>

 <div class="product">
  <img src="images/kurti3.jpeg" alt="kurti" width="150">
  <h3>kurti</h3>
  <p>₹1199</p>
  <button onclick="addToCart('kurti',1199,'images/kurti3.jpeg')">add to carts</button>
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
function addToCart(productname, price, image){

      let name = localStorage.getItem("name");

      let cart = JSON.parse(localStorage.getItem("cart_" + name)) || [];

      cart.push({
        name: name,
        price: price,
        image: image
    });

    localStorage.setItem("cart_" + name, JSON.stringify(cart));

    alert("Product added successfully!");
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

</script>
<div class="cart-link">
<a href="cart.php">🛒 cart</a>
</div>
</body>
</html>