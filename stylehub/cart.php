<?php 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#f0ecec;
    margin:0;
    padding:20px;
}
.container{
    max-width:700px;
    margin:auto;
    background:#c7e53f;
    padding:20px;
    border-radius:10px;
}
h2{
    text-align:center;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    border:1px solid #091c20;
    padding:10px;
    text-align:center;
}
th{
    background:#9f5f5f;
    color:rgb(59, 5, 5);
}
button{
    padding:8px 15px;
    background:#c67c4b;
    color:rgb(127, 8, 8);
    border:none;
    cursor:pointer;
    border-radius:5px;
}
button:hover{
    background:#e65c00;
}
.total{
    text-align:right;
    font-size:20px;
    margin-top:20px;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">

<h2>Shopping Cart</h2>

<table>
    <tr>
        <th>Product</th>
        <th>Price</th>  
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <tbody id="cartItems"></tbody>

</table>

<div class="total">
    Grand Total : ₹<span id="grand">0</span>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
    <a href="index.php" style="background:#8b3a3a; color:white; text-decoration:none; padding:10px 15px; border-radius:5px; font-weight:bold;">← Continue Shopping</a>

    <form action="save_order.php" method="POST" onsubmit="if(cart.length === 0){ alert('Your cart is empty!'); return false; }">
        <input type="hidden" name="product_name" id="product_name">
        <input type="hidden" name="quantity" id="quantity">
        <input type="hidden" name="total_amount" id="total_amount">
        <button type="submit" id="orderBtn">Place Order</button>
    </form>
</div>

<script>

let name = localStorage.getItem("name") || "Guest";
let cart = JSON.parse(localStorage.getItem("cart_" + name)) || [];
    
let output = "";
let grand = 0;

if(cart.length === 0){
    output = `<tr><td colspan="5" style="padding:20px; font-size:16px;">Your cart is currently empty!</td></tr>`;
    document.getElementById("orderBtn").disabled = true;
    document.getElementById("orderBtn").style.opacity = "0.5";
    document.getElementById("orderBtn").style.cursor = "not-allowed";
} else {
    cart.forEach(function(item, index){
       grand += Number(item.price);
       output += `
       <tr>
           <td>${item.name}</td>
           <td>₹${item.price}</td>
           <td>1</td>
           <td>₹${item.price}</td>
           <td><button style="background:#e05252; color:white;" onclick="removeItem(${index})">Remove</button></td>
       </tr>
       `;
    });

    let productNames = cart.map(function(item){ return item.name; }).join(", ");
    document.getElementById("product_name").value = productNames;
    document.getElementById("quantity").value = cart.length;
    document.getElementById("total_amount").value = grand;
}

document.getElementById("cartItems").innerHTML = output;
document.getElementById("grand").innerHTML = grand;

function removeItem(index){
    cart.splice(index, 1);
    localStorage.setItem("cart_" + name, JSON.stringify(cart));
    location.reload();
}

function checkout(){
    alert("Order Placed Successfully!");
}

</script>
<br>



</div>


</body>
</html>