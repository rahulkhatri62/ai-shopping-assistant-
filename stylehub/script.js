function login(){
let user = prompt("Enter username");

if(user)
    alert("Welcome " + user);
}

function pay(){
    alert("Payment gateway placeholder connected.");
}

document.getElementById("search").addEventListener("input", function(){

let text = this.value.toLowerCase();

document.querySelectorAll(".card").forEach(c => {

c.style.display =
c.innerText.toLowerCase().includes(text)
? "block"
: "none";

});

});
// Login function
function login() {
let username = prompt("Enter your username:");

if(username && username.trim() !== ""){
alert("Welcome to StyleHub, " + username + "!");
}
else{
alert("Please enter a valid username");
}
}

// Payment button
function pay(){
alert("Redirecting to payment gateway...");
}

// Product Search
document.getElementById("search").addEventListener("input", function(){

let searchText = this.value.toLowerCase();

let products = document.querySelectorAll(".card");

products.forEach(function(product){

let text = product.innerText.toLowerCase();

if(text.includes(searchText)){
product.style.display = "block";
}
else{
product.style.display = "none";
}

});

});

// Chatbot placeholder
document.querySelector(".chatbot").addEventListener("click", function(){

let message = prompt("StyleHub Assistant:\nHow can I help you?");

if(message){
alert("You asked: " + message + "\n\nChatbot feature coming soon!");
}

});

// Dashboard demo
let totalOrders = 12;
let customers = 58;

console.log("Orders:", totalOrders);
console.log("Customers:", customers);

function addToCart(name, price, image) {
    
    let username = localStorage.getItem("username");
    let cart = JSON.parse(localStorage.getItem("cart_" + username)) || [];

    cart.push({
        name: name,
        price: Number(price),
        image: image
    });

    localStorage.setItem("cart_" + username, JSON.stringify(cart));

    alert("Product Added Successfully!");
}

