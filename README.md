# StyleHub — AI-Powered Ethnic Shopping Assistant 👗✨

**StyleHub** is a full-featured e-commerce fashion boutique web application built with PHP and MariaDB/MySQL, enhanced by an intelligent AI Shopping Assistant (`StyleBot`) and voice search.

---

## 🌟 Key Features

* **🤖 Conversational AI Stylist (`StyleBot`)**:
  * Natural language understanding for price constraints (*"under ₹1000"*, *"below ₹1500"*), occasions (*"party wear"*, *"college daily"*), and fabrics (*"pure cotton"*, *"chanderi silk"*).
  * Direct in-chat recommendation cards with **"⚡ Quick Add"** to cart.
  * Customer service FAQs (shipping time, returns, COD/payment).
  * **Voice Search (🎙️)**: Speak your search query directly using speech recognition.

* **👗 Dynamic Product Catalog**:
  * Powered by MySQL database with real-time category filtering (*All, Daily Cotton, Party Silk, Festive, Anarkali*).
  * Sorting options (*Price: Low to High, Price: High to Low, Top Rated, Featured*).
  * Live instant search bar.
  * **Product Quick View Modal** with size selector (**S, M, L, XL, XXL**).

* **❤️ Wishlist & Modern Toasts**:
  * Heart button on every product to save favorites with live counter badge.
  * Smooth, animated toast notifications in place of standard browser popups.

* **🏷️ Promo Coupons & Real Checkout**:
  * Active promo discount codes:
    * `STYLE20` — Flat 20% OFF on orders above ₹500
    * `FESTIVE100` — ₹100 Instant Flat OFF on orders above ₹999
    * `WELCOME10` — 10% Welcome discount
  * Full customer shipping address form (Name, Phone, Address, City, PIN Code).
  * Multiple payment methods: Cash on Delivery (COD), UPI / QR Code preview, and Credit/Debit Cards.

* **📦 Customer Order History (`my_orders.php`)**:
  * Customers can track their placed orders with delivery status (*Pending, Processing, Shipped, Delivered*).

* **🛡️ Executive Admin Dashboard (`dashboard.php`)**:
  * 4 KPI metric cards: Total Orders, Gross Sales (₹), Catalog Items, and Shoppers.
  * **Orders Management Tab**: View customer addresses, phone numbers, and update order delivery status in real-time.
  * **Product Catalog Tab (Full CRUD)**: Add new kurtis with custom price, image, fabric, and stock; delete items with one click.
  * **Customer Accounts Tab**: View registered customer names, email addresses, and lifetime spend.

---

## 🛠️ Technology Stack

* **Backend**: PHP 8.2 (mysqli, session authentication, JSON APIs)
* **Database**: MariaDB / MySQL (`stylehub` database)
* **Frontend**: Vanilla JavaScript (ES6+), HTML5, Custom CSS3 with CSS Variables & Glassmorphism
* **Typography**: Google Fonts (*Playfair Display* & *Plus Jakarta Sans*)
* **APIs**:
  * `api_products.php`: Dynamic catalog JSON endpoint
  * `api_chat.php`: AI NLP recommendation engine
  * `api_coupon.php`: Real-time promo code validation engine

---

## 🚀 How to Run Locally

1. **Start MariaDB Database**:
   ```powershell
   & "C:\Users\khatr\mariadb\bin\mariadbd.exe" --console --datadir="C:\Users\khatr\mariadb\data"
   ```

2. **Initialize Database**:
   ```powershell
   & "C:\Users\khatr\mariadb\bin\mysql.exe" -u root < schema.sql
   ```

3. **Start the PHP Web Server**:
   ```powershell
   cd stylehub
   php -S localhost:8000
   ```

4. **Open in Browser**:
   * Storefront: `http://localhost:8000/index.php`
   * Admin Portal: `http://localhost:8000/admin_login.php` (Username: `admin`, Password: `2710`)

---

## 👥 Contributors

* **Nandani** (`nandani_branch`)
* **Tanisha**

*StyleHub v2.0 • Updated September 2026*
