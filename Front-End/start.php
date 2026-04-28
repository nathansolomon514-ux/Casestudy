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

                <a href="profile.html" class="profile-btn">
                    <svg class="profile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </div>
        </nav>

        <div class="hero-section">
            <div class="background-btn">
                <a href="#" class="Men">
                    <span>Men</span>
                    <span class="arrow">→</span>
                </a>
                <a href="#" class="Women">
                    <span>Women</span>
                    <span class="arrow">→</span>
                </a>
            </div>
        </div>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-search">
                <input type="text" id="sidebarSearchInput" placeholder="SEARCH..." onkeyup="searchSidebar()">
                <span class="search-icon">🔍︎</span>
                <div id="searchResults" class="search-dropdown"></div>
            </div>

            <a href="#">Shop Now</a>
            <div class="dropdown-container">
                <a href="javascript:void(0)" class="dropdown-btn" onclick="toggleDropdown()">
                    Categories <span class="arrow-down">▾</span>
                </a>
                <div class="dropdown-content" id="categoriesMenu">
                    <a href="Men.html">Men</a>
                    <a href="Women.html">Women</a>
                    <a href="Kids.html">Kids</a>
                    <a href="AG.html">Accesories & Gear</a>
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

            <div class="KRNK-PRODUCTS active" id="classics">
                <div class="product-card">
                    <img src="Resources/Images/KRNK-Classic-Tee-Black.png" alt="product">
                    <h3>KRNK Classic Tee Black</h3>
                    <p>$19.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
                </div>
                
                <div class="product-card">
                    <img src="Resources/Images/KRNK-Classic-Tee-White.png" alt="product">
                    <h3>KRNK Classic Tee White</h3>
                    <p>$19.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="Resources/Images/KRNK-Classic-Tee-Gray.png" alt="product">
                    <h3>KRNK Classic Tee Gray</h3>
                    <p>$19.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
                </div>

                <div class="product-card">
                    <img src="Resources/Images/KRNK-Classic-Tee-Navy.png" alt="product">
                    <h3>KRNK Classic Tee Navy Blue</h3>
                    <p>$19.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
                </div>
                
                <div class="product-card">
                    <img src="Resources/Images/KRNK-Classic-Tee-Red.png" alt="product">
                    <h3>KRNK Classic Tee Red</h3>
                    <p>$19.99</p>
                    <button onclick="addToCart()">Add to Cart</button>
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

</body>
</html>

