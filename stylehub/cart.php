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
<title>Shopping Cart - StyleHub</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#f0ecec;
    margin:0;
    padding:20px;
}
.container{
    max-width:800px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
h2{
    text-align:center;
    color:#8b3a3a;
    margin-bottom:20px;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    border:1px solid #ddd;
    padding:12px;
    text-align:center;
    vertical-align:middle;
}
th{
    background:#8b3a3a;
    color:white;
}
button{
    padding:8px 15px;
    background:#8b3a3a;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:5px;
    font-weight:bold;
}
button:hover{
    background:#b84c4c;
}
.total{
    text-align:right;
    font-size:22px;
    margin-top:20px;
    font-weight:bold;
    color:#8b3a3a;
}
.product-cell{
    display:flex;
    align-items:center;
    gap:12px;
    text-align:left;
}
.product-cell img{
    width:50px;
    height:50px;
    object-fit:cover;
    border-radius:6px;
    border:1px solid #ddd;
}
</style>
</head>

<body>

<div class="container">

<h2>🛍️ Your Shopping Cart</h2>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>  
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="cartItems"></tbody>
</table>

<div class="total">
    Grand Total : ₹<span id="grand">0</span>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-top:30px;">
    <a href="index.php" style="background:#666; color:white; text-decoration:none; padding:12px 20px; border-radius:6px; font-weight:bold;">← Continue Shopping</a>

    <form action="save_order.php" method="POST" onsubmit="return validateCheckout();">
        <input type="hidden" name="product_name" id="product_name">
        <input type="hidden" name="quantity" id="quantity">
        <input type="hidden" name="total_amount" id="total_amount">
        <button type="submit" id="orderBtn" style="padding:12px 25px; font-size:16px;">Place Order</button>
    </form>
</div>

</div>

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
}

function renderCart() {
    let cart = getCart();
    let tbody = document.getElementById("cartItems");
    let grandEl = document.getElementById("grand");
    let orderBtn = document.getElementById("orderBtn");

    if (cart.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="padding:30px; text-align:center; font-size:16px; color:#666;">
                    Your cart is empty! <br><br>
                    <a href="index.php#products-title" style="color:#8b3a3a; font-weight:bold;">Browse our Kurtis collection</a>
                </td>
            </tr>
        `;
        grandEl.innerText = "0";
        orderBtn.disabled = true;
        orderBtn.style.opacity = "0.5";
        orderBtn.style.cursor = "not-allowed";
        return;
    }

    orderBtn.disabled = false;
    orderBtn.style.opacity = "1";
    orderBtn.style.cursor = "pointer";

    let output = "";
    let grand = 0;

    cart.forEach(function(item, index) {
        let price = Number(item.price) || 0;
        grand += price;
        let imgHtml = item.image ? `<img src="${item.image}" alt="${item.name}">` : '';

        output += `
        <tr>
            <td>
                <div class="product-cell">
                    ${imgHtml}
                    <span><strong>${item.name}</strong></span>
                </div>
            </td>
            <td>₹${price}</td>
            <td>1</td>
            <td>₹${price}</td>
            <td><button type="button" style="background:#d9534f; color:white; padding:6px 12px;" onclick="removeItem(${index})">Remove</button></td>
        </tr>
        `;
    });

    tbody.innerHTML = output;
    grandEl.innerText = grand;

    let productNames = cart.map(function(item){ return item.name; }).join(", ");
    document.getElementById("product_name").value = productNames;
    document.getElementById("quantity").value = cart.length;
    document.getElementById("total_amount").value = grand;
}

function removeItem(index) {
    let cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCart();
}

function validateCheckout() {
    let cart = getCart();
    if (cart.length === 0) {
        alert("Your cart is empty! Please add products first.");
        return false;
    }
    return true;
}

// Initial render
document.addEventListener("DOMContentLoaded", renderCart);
renderCart();
</script>

</body>
</html>