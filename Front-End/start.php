<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krnk Athletics</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
</head>
    <div id="loginModal" class="modal-overlay">
        <div class="login-card">
            <button class="close-auth" onclick="closeLoginModal()">&times;</button>

            <div class="auth-visual">
                <img src="Resources/Images/messi.gif" alt="KRNK Visual">
            </div>

            <div class="auth-content">
                <div class="auth-tabs">
                    <span id="loginTab" class="active" onclick="switchAuth('login')">LOGIN</span>
                    <span id="registerTab" onclick="switchAuth('register')">REGISTER</span>
                </div>

                <div class="form-wrapper">
                    <form id="loginForm" onsubmit="handleAuth(event, 'login')">
                        <p id="loginError" class="error-msg"></p>
                        <input type="email" name="email" placeholder="EMAIL" required>
                        <input type="password" name="password" placeholder="PASSWORD" required>
                        <button type="submit" class="login-submit">ENTER</button>
                    </form>

                    <form id="registerForm" onsubmit="handleAuth(event, 'register')" style="display:none;">
                        <input type="text" name="first_name" placeholder="FIRST" required>
                        <input type="text" name="last_name" placeholder="LAST" required>
                        <input type="email" name="email" placeholder="EMAIL" required>
                        <input type="password" name="password" placeholder="PASSWORD" required>
                        <button type="submit" class="login-submit">CREATE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<body>

    <!---NAVIGATION BAR-->
    <div class="nav-bar">
       <nav class="header-primary">
            <button class="menu-btn" onclick="toggleSidebar(this)">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <a href="#">
                <img src="Resources/Images/Logo.png" class="logo-white" alt="White Logo">
                <img src="Resources/Images/Logo(1).png" class="logo-black"alt="Black Logo">
            </a>

            <div class="nav-right">
                <a href="javascript:void(0)" class="cart-btn" onclick="toggleCart()">
                    <div class="cart-icon-wrapper">
                        <svg class="bag-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4H6zM3 6h18M16 10a4 4 0 01-8 0" />
                        </svg>
                        <span id="cart-count" class="cart-badge">0</span>
                    </div>
                </a>

               <a href="javascript:void(0)" class="profile-btn" onclick="toggleLogin()">
                    <svg class="profile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </div>
        </nav>

        <div class="hero-section">
            <div class="background-btn">
                <a href="Men.html" class="Men">
                    <span>Men</span>
                    <span class="arrow">→</span>
                </a>
                <a href="Women.html" class="Women">
                    <span>Women</span>
                    <span class="arrow">→</span>
                </a>
            </div>
        </div>
        <!--SIDEBAR-->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-search">
                <input type="text" id="sidebarSearchInput" placeholder="SEARCH..." onkeyup="searchSidebar()">
                <span class="search-icon">🔍︎</span>
                <div id="searchResults" class="search-dropdown"></div>
            </div>
            <!--SIDEBAR LIST-->
            <a href="#">Shop Now</a>
            <div class="dropdown-container">
                <a href="javascript:void(0)" class="dropdown-btn" onclick="toggleDropdown()">
                    Categories <span class="arrow-down">▾</span>
                </a>
                <div class="dropdown-content" id="categoriesMenu">
                    <a href="Clothing.html">Clothing</a>
                    <a href="Footwear.html">Footwear</a>
                    <a href="Accesories.html">Accessories</a>
                    <a href="Equipment.html">Equipment / Gear</a>
                    <a href="Bags.html">Bags</a>
                    <a href="Outerwear.html">Outerwear</a>
                </div>
            </div>

            <a href="shoe.html">Try Now</a>
            <a href="#">Releases</a>
        </div>

    <!--CONTAINERS-->
    <div class="First-container">
        <div class="promo-banner">
            <h2 class="promo-title"><span>MEMBERS GET MORE</span></h2>
            <p class="promo-subtitle">
                <span>Members-only Products, Prizes, Points Benefits & Experiences starts now.</span>
            </p>

            <div class="promo-buttons">
                <a href="javascript:void(0)" class="promo-btn" onclick="showProducts('classics')">The KRNK Classic Tee Collection</a>
                <a href="javascript:void(0)" class="promo-btn" onclick="showProducts('shoes')">KRNK Shoes</a>
            </div>
            <!--PRODUCT CONTAINERS-->
            <div class="KRNK-PRODUCTS active" id="classics">
                <div class="product-card" onclick="openProductView('KRNK Classic Tee Black', '$19.99', 'Resources/Images/KRNK-Classic-Tee-Black.png')">
                    <img src="Resources/Images/KRNK-Classic-Tee-Black.png" alt="product">
                    <h3>KRNK Classic Tee Black</h3>
                    <p>$19.99</p>
                </div>
                
                <div class="product-card" onclick="openProductView('KRNK Classic Tee White', '$19.99', 'Resources/Images/KRNK-Classic-Tee-White.png')">
                    <img src="Resources/Images/KRNK-Classic-Tee-White.png" alt="product">
                    <h3>KRNK Classic Tee White</h3>
                    <p>$19.99</p>
                </div>

                <div class="product-card" onclick="openProductView('KRNK Classic Tee Gray', '$19.99', 'Resources/Images/KRNK-Classic-Tee-Gray.png')">
                    <img src="Resources/Images/KRNK-Classic-Tee-Gray.png" alt="product">
                    <h3>KRNK Classic Tee Gray</h3>
                    <p>$19.99</p>
                </div>

                <div class="product-card" onclick="openProductView('KRNK Classic Tee Navy', '$19.99', 'Resources/Images/KRNK-Classic-Tee-Navy.png')">
                    <img src="Resources/Images/KRNK-Classic-Tee-Navy.png" alt="product">
                    <h3>KRNK Classic Tee Navy</h3>
                    <p>$19.99</p>
                </div>
                
                <div class="product-card" onclick="openProductView('KRNK Classic Tee Red', '$19.99', 'Resources/Images/KRNK-Classic-Tee-Red.png')">
                    <img src="Resources/Images/KRNK-Classic-Tee-Red.png" alt="product">
                    <h3>KRNK Classic Tee Red</h3>
                    <p>$19.99</p>
                </div>
            </div>

            <div class="KRNK-PRODUCTS" id="shoes">
                <div class="product-card">
                    <img src="Resources/Images/shoe-image.png" alt="product">
                    <h3>KRNK Pro Runner</h3>
                    <p>$89.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
                </div>
                </div>
        </div>

    </div>


    <h1>This is a Heading</h1>
    <p>This is a paragraph.</p>

    <!-- JS should be here (IMPORTANT) -->
    <script src="script.js"></script>

    <!--PRODUCT VIEWING -->
    <div id="productQuickView" class="modal-overlay">
        <div class="product-detail-card">
            <button class="close-view" onclick="closeProductView()">✕</button>
            
            <div class="product-layout">
                <div class="product-image-box">
                    <div class  ="slider-container">
                        <img id="viewImage" src="" alt="Product">

                        <div class="nav-hitbox left" onclick="changeVariant(-1)"></div>
                        <div class="nav-hitbox right" onclick="changeVariant(1)"></div>

                        <div id="variantScroll" class="variant-scroll">
                            </div>
                    </div>
                </div>
                
                <div class="product-info-box">
                    <h1 id="viewTitle" class="anton-font" style="font-size: 60px; margin-bottom: 10px;"></h1>
                    <p id="viewPrice" class="price-tag" style="font-size: 24px; margin-bottom: 40px;"></p>

                    <div class="option-group">
                        <label style="letter-spacing: 2px;">SELECT GENDER</label>
                        <div class="gender-options" style="display: flex; gap: 15px; margin-top: 15px;">
                            <button type="button" class="option-btn" onclick="selectOption(this, 'gender')">MEN</button>
                            <button type="button" class="option-btn" onclick="selectOption(this, 'gender')">WOMEN</button>
                        </div>
                    </div>

                    <div class="option-group" style="margin-top: 40px;">
                        <label style="letter-spacing: 2px;">SELECT SIZE</label>
                        <div class="size-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 15px;">
                            <button type="button" class="option-btn" onclick="selectOption(this, 'size')">S</button>
                            <button type="button" class="option-btn" onclick="selectOption(this, 'size')">M</button>
                            <button type="button" class="option-btn" onclick="selectOption(this, 'size')">L</button>
                            <button type="button" class="option-btn" onclick="selectOption(this, 'size')">XL</button>
                            <button type="button" class="option-btn" onclick="selectOption(this, 'size')">XXL</button>
                        </div>
                    </div>

                    <div class="option-group" style="margin-top: 20px;">
                        <p class="option-title">QUANTITY</p>
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                            <input type="number" id="productQty" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                        </div>
                    </div>

                    <button class="login-submit" style="margin-top: 50px; height: 60px;" onclick="addToCart()">ADD TO BAG</button>
                </div>
            </div>
        </div>
    </div>

    <!--NOTIFICATION-->
    <div id="cartToast" class="cart-toast">
        <div class="toast-content">
            <div class="toast-header">
                <span class="toast-check">✔</span>
                <p class="toast-msg">ADDED TO ARENA</p>
            </div>
            <p id="toastItemName" class="toast-item">KRNK CLASSIC TEE</p>
        </div>
    </div>
    
    <!--MOUSE CURSOR-->
    <div id="custom-cursor" style="position: fixed; pointer-events: none; z-index: 999999; display: none; color: white; font-size: 40px; font-weight: bold;">
        <div class="cursor-circle">
            <span id="cursor-arrow">←</span>
        </div>
    </div>

    <!--CART CONTAINER-->
    <div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>

    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2 class="anton-font">YOUR BAG</h2>
            <button class="close-cart" onclick="toggleCart()">CLOSE</button>
        </div>

        <div id="cartItemsList" class="cart-body">
            <div class="empty-bag">
                <p>YOUR BAG IS CURRENTLY EMPTY.</p>
                <a href="#" onclick="toggleCart()" class="continue-link">CONTINUE BROWSING</a>
            </div>
        </div>

        <div class="cart-footer">
            <div class="total-section">
                <span>SUBTOTAL</span>
                <span id="cartTotalText" class="anton-font">$0.00</span>
            </div>
            <button class="checkout-btn anton-font">PROCEED TO CHECKOUT</button>
        </div>
    </div>
</body>
</html>

