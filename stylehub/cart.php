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
        <th>product</th>
        <th>price</th>  
        <th>quantity</th>
        <th>total</th>
    </tr>
    <tbody id="cartItems"></tbody>

</table>

<div class="total">
    Grand Total : ₹<span id="grand">0</span>
</div>

<form action="save_order.php" method="POST">

    <input type="hidden" name="product_name" id="product_name">
    <input type="hidden" name="quantity" id="quantity">
    <input type="hidden" name="total_amount" id="total_amount">

    <button type="submit">Place Order</button>
</form>


<script>

let name = localStorage.getItem("name");
let cart = JSON.parse(localStorage.getItem("cart_" + name)) || [];
    
let output = "";
let grand = 0;

cart.forEach(function(item, index){

   grand += Number(item.price);

   output += `
   <tr>
       <td>${item.name}</td>
       <td>₹${item.price}</td>
       <td>1</td>
       <td>₹${item.price}</td>
       <td><button style="background:pink; color:white;"  onclick="removeItem(${index})">cancle</button></td>
   </tr>
   `;

});    

document.getElementById("cartItems").innerHTML = output;
document.getElementById("grand").innerHTML = grand;

if(cart.length > 0){

document.getElementById("product_name").value = cart[0].name;
document.getElementById("quantity").value = cart.length;
document.getElementById("total_amount").value = grand;
}

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