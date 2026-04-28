let totalItems = 0; // Global counter

function toggleSidebar(btn) {
    btn.classList.toggle("active");
    document.getElementById("sidebar").classList.toggle("active");
}

function handleNavbarScroll() {
    const navbar = document.querySelector('.header-primary');

    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
}

// Add this: It tells the browser to run the function every time the user scrolls
window.addEventListener('scroll', handleNavbarScroll);

// Also run it once on page load in case the user refreshes mid-page
handleNavbarScroll();

function toggleDropdown() {
    const dropdown = document.getElementById("categoriesMenu");
    const btn = document.querySelector(".dropdown-btn");
    
    dropdown.classList.toggle("show");
    btn.classList.toggle("active");
}

function searchSidebar() {
    let input = document.getElementById('sidebarSearchInput').value.toUpperCase();
    let sidebar = document.getElementById('sidebar');
    let links = sidebar.getElementsByTagName('a');

    for (let i = 0; i < links.length; i++) {
        let textValue = links[i].textContent || links[i].innerText;
        if (textValue.toUpperCase().indexOf(input) > -1) {
            links[i].style.display = "";
        } else {
            links[i].style.display = "none";
        }
    }
}

// List of items the user can search for
const searchItems = [
    { name: "KRNK Classic Tee", link: "#" },
    { name: "Shoes", link: "shoes.html" },
    { name: "Jerseys", link: "jerseys.html" },
    { name: "New Releases", link: "#" }
];

function searchSidebar() {
    const input = document.getElementById('sidebarSearchInput').value.toUpperCase();
    const resultsDiv = document.getElementById('searchResults');
    
    // Clear previous results
    resultsDiv.innerHTML = '';

    if (input.length > 0) {
        // Find items that include the typed letters
        const filtered = searchItems.filter(item => 
            item.name.toUpperCase().includes(input)
        );

        if (filtered.length > 0) {
            resultsDiv.style.display = "block"; // Show the box
            filtered.forEach(item => {
                const a = document.createElement('a');
                a.href = item.link;
                a.textContent = item.name;
                resultsDiv.appendChild(a);
            });
        } else {
            resultsDiv.style.display = "none"; // Hide if no matches
        }
    } else {
        resultsDiv.style.display = "none"; // Hide if input is empty
    }
}

let count = 0;

function addToCart() {
    count++;
    const badge = document.getElementById("cart-count");
    badge.innerText = count;
    
    // Optional: Add a little "pop" animation when something is added
    badge.style.transform = "scale(1.2)";
    setTimeout(() => badge.style.transform = "scale(1)", 200);
}

// Make sure your "Add to Cart" buttons in HTML call this function:
// <button onclick="addToCart()">Add to Cart</button>


function showProducts(sectionId) {
    const sections = document.querySelectorAll('.KRNK-PRODUCTS');
    
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    const selected = document.getElementById(sectionId);
    if (selected) {
        selected.classList.add('active');
        
        // DELETE OR COMMENT OUT THE LINE BELOW:
        // selected.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
    }
}

function filterCategory(category) {
    const links = document.querySelectorAll('.sub-nav-link');
    links.forEach(link => link.classList.remove('active'));
    event.currentTarget.classList.add('active');
    
    // This allows you to integrate with your existing showProducts logic
    console.log("Category selected: " + category);
}

function changeImage(thumbnail) {
    // Find the closest men-product-card parent
    const card = thumbnail.closest('.men-product-card');
    const mainImg = card.querySelector('.main-product-img');
    
    // Switch the image
    mainImg.src = thumbnail.src;
    
    // Toggle active border
    const thumbs = card.querySelectorAll('.thumb');
    thumbs.forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

function addToCart() {
    // 1. GET QUANTITY & PRODUCT DATA
    const qtyInput = document.getElementById("productQty");
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    
    const itemName = document.getElementById("viewTitle").innerText;
    const itemPrice = document.getElementById("viewPrice").innerText;
    const itemImg = document.getElementById("viewImage").src;

    // 2. UPDATE GLOBAL COUNTER & BADGE
    totalItems += quantity;
    const badge = document.getElementById("cart-count");
    if (badge) {
        badge.innerText = totalItems;
        badge.style.display = "flex";
        
        // Pop animation
        badge.style.transform = "scale(1.2)";
        setTimeout(() => badge.style.transform = "scale(1)", 200);
    }

    // 3. ADD ITEM(S) TO SIDEBAR LIST
    const cartList = document.getElementById("cartItemsList");
    
    // Clear "Empty Bag" message if this is the first addition
    if (totalItems === quantity) {
        cartList.innerHTML = ''; 
    }

    // Create the row with quantity info
    const cartItem = document.createElement("div");
    cartItem.classList.add("cart-item-row");
    cartItem.innerHTML = `
        <img src="${itemImg}" class="cart-item-img">
        <div class="cart-item-info">
            <p class="cart-item-name anton-font">${itemName} ${quantity > 1 ? `(x${quantity})` : ''}</p>
            <p class="cart-item-price">${itemPrice}</p>
        </div>
        <button class="remove-item" onclick="removeFromCart(this, ${quantity})">REMOVE</button>
    `;
    
    cartList.appendChild(cartItem);

    // 4. RESET QUANTITY INPUT FOR NEXT TIME
    if (qtyInput) qtyInput.value = 1;

    // 5. UPDATE SUBTOTAL MATH
    updateCartTotal();

    // 6. SHOW NOTIFICATION (TOAST)
    const toast = document.getElementById("cartToast");
    const toastItemName = document.getElementById("toastItemName");

    if (toast && toastItemName) {
        toastItemName.innerText = quantity > 1 ? `${quantity}x ${itemName}` : itemName;
        
        // Reset animation trigger
        toast.classList.remove("show");
        setTimeout(() => {
            toast.classList.add("show");
        }, 50);

        // Hide after 4 seconds
        setTimeout(() => {
            toast.classList.remove("show");
        }, 4000);
    }
}


function updateCartTotal() {
    const cartItems = document.querySelectorAll(".cart-item-row");
    const totalElement = document.getElementById("cartTotalText");
    let total = 0;

    cartItems.forEach(item => {
        const priceElement = item.querySelector(".cart-item-price");
        const nameElement = item.querySelector(".cart-item-name");

        if (priceElement && nameElement) {
            // 1. Clean the price (e.g., "₱ 1,200.00" -> 1200)
            const priceText = priceElement.innerText.replace(/[^\d.]/g, '');
            const price = parseFloat(priceText);

            // 2. Find the Quantity in the name (e.g., "Classic Tee (x2)" -> 2)
            // We look for a number inside parentheses preceded by 'x'
            const qtyMatch = nameElement.innerText.match(/\(x(\d+)\)/);
            const quantity = qtyMatch ? parseInt(qtyMatch[1]) : 1;

            if (!isNaN(price)) {
                // 3. Multiply Price by Quantity
                total += (price * quantity);
            }
        }
    });

    // Update the HTML with the calculated total
    if (totalElement) {
        totalElement.innerText = `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }
}

function toggleLogin() {
    const modal = document.getElementById("loginModal");
    modal.style.display = (modal.style.display === "flex") ? "none" : "flex";
}

/*This function talks to your PHP without leaving the page
function submitLogin(event) {
    event.preventDefault(); // Stops the page from refreshing
    
    const formData = new FormData(document.getElementById("loginForm"));

    fetch('http://localhost/ForProject/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data.includes("success")) {
            alert("WELCOME TO THE ARENA");
            location.reload(); // Refresh to show logged-in state
        } else {
            document.getElementById("loginError").innerText = data;
        }
    });
}
*/

function submitLogin(event) {
    event.preventDefault(); 
    
    const formData = new FormData(document.getElementById("loginForm"));

    // NO absolute path - just the filename
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data); 
        
        // Use trim() to clean up any hidden spaces from PHP
        if(data.trim() === "success") {
            alert("WELCOME TO THE ARENA");
            location.reload(); 
        } else {
            document.getElementById("loginError").innerText = data;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Connection failed. Are you using http://localhost?");
    });
}


function switchAuth(type) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (type === 'login') {
        loginForm.style.display = 'block';
        loginForm.style.visibility = 'visible';
        
        registerForm.style.display = 'none';
        registerForm.style.visibility = 'hidden';
        
        document.getElementById('loginTab').classList.add('active');
        document.getElementById('registerTab').classList.remove('active');
    } else {
        loginForm.style.display = 'none';
        loginForm.style.visibility = 'hidden';
        
        registerForm.style.display = 'block';
        registerForm.style.visibility = 'visible';
        
        document.getElementById('loginTab').classList.remove('active');
        document.getElementById('registerTab').classList.add('active');
    }
}

async function handleAuth(event, type) {
    event.preventDefault();
    
    const formId = (type === 'login') ? 'loginForm' : 'registerForm';
    const formElement = document.getElementById(formId);
    const formData = new FormData(formElement);

    // NO ABSOLUTE PATH NEEDED NOW
    const targetUrl = type + '.php'; 

    try {
        const response = await fetch(targetUrl, {
            method: 'POST',
            body: formData
        });

        const result = await response.text();
        
        if (result.trim() === "success") {
            alert(type.toUpperCase() + " SUCCESSFUL!");
            location.reload(); 
        } else {
            const errorId = (type === 'login') ? 'loginError' : 'regError';
            document.getElementById(errorId).innerText = result;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

function selectOption(btn, type) {
    // Finds all buttons in the same group (gender or size)
    const parent = btn.parentElement;
    const buttons = parent.querySelectorAll('button');
    
    // Remove 'active' from all, add to the clicked one
    buttons.forEach(b => b.style.background = "transparent");
    buttons.forEach(b => b.style.color = "#fff");
    
    btn.style.background = "#fff";
    btn.style.color = "#000";
}

// Ensure your open/close functions are present
function openProductView(name, price, img) {
    document.getElementById("viewTitle").innerText = name;
    document.getElementById("viewPrice").innerText = price;
    document.getElementById("viewImage").src = img;
    document.getElementById("productQuickView").style.display = "flex";
}

function closeProductView() {
    document.getElementById("productQuickView").style.display = "none";
}

function selectOption(btn, group) {
    // 1. Get all buttons in this specific group (gender or size)
    const container = btn.parentElement;
    const buttons = container.querySelectorAll('button');
    
    // 2. Remove the 'active' class from all of them
    buttons.forEach(b => b.classList.remove('active'));
    
    // 3. Add 'active' to the one we just clicked
    btn.classList.add('active');
}

let currentVariants = [];
let currentIndex = 0;

function openProductView(name, price, img) {
    const modal = document.getElementById("productQuickView");
    
    // Define the variants
    currentVariants = [
        { name: "KRNK CLASSIC TEE BLACK", img: 'Resources/Images/KRNK-Classic-Tee-Black.png' },
        { name: "KRNK CLASSIC TEE WHITE", img: 'Resources/Images/KRNK-Classic-Tee-White.png' },
        { name: "KRNK CLASSIC TEE NAVY", img: 'Resources/Images/KRNK-Classic-Tee-Navy.png' },
        { name: "KRNK CLASSIC TEE RED", img: 'Resources/Images/KRNK-Classic-Tee-Red.png' },
        { name: "KRNK CLASSIC TEE GRAY", img: 'Resources/Images/KRNK-Classic-Tee-Gray.png' }
    ];

    currentIndex = currentVariants.findIndex(v => v.name.toUpperCase() === name.toUpperCase());
    if (currentIndex === -1) currentIndex = 0;

    document.getElementById("viewPrice").innerText = price;
    updateDisplay(); // This fills the image and title
    
    modal.style.display = "flex";

    // Re-attach the cursor logic now that the modal is visible
    attachCursorLogic(); 
}

function changeVariant(direction) {
    currentIndex += direction;
    
    // Loop logic
    if (currentIndex >= currentVariants.length) currentIndex = 0;
    if (currentIndex < 0) currentIndex = currentVariants.length - 1;

    updateDisplay();
}

function updateDisplay() {
    const current = currentVariants[currentIndex];
    
    // Update the Image AND the Title
    document.getElementById("viewImage").src = current.img;
    document.getElementById("viewTitle").innerText = current.name;
    
    // Update thumbnails if you have them
    updateThumbnails();
}

function updateThumbnails() {
    const container = document.getElementById("variantScroll");
    if (!container) return;
    
    container.innerHTML = ""; 
    currentVariants.forEach((variant, index) => {
        const img = document.createElement("img");
        img.src = variant.img;
        // This adds the 'active' class to the thumbnail that matches the big image
        img.className = `variant-thumb ${index === currentIndex ? 'active' : ''}`;
        
        img.onclick = () => {
            currentIndex = index;
            updateDisplay(); // Refreshes the big image and titles
        };
        container.appendChild(img);
    });
}


function changeVariant(step) {
    // currentVariants is the array of images for that jersey
    currentIndex += step;

    // Loop logic
    if (currentIndex >= currentVariants.length) {
        currentIndex = 0; // Go back to first image
    }
    if (currentIndex < 0) {
        currentIndex = currentVariants.length - 1; // Go to last image
    }

    updateDisplay(); // This function updates the #viewImage src
}

function attachCursorLogic() {
    const imageBox = document.querySelector('.product-image-box');
    const customCursor = document.getElementById('custom-cursor');
    const arrowSpan = document.getElementById('cursor-arrow');

    if (imageBox && customCursor && arrowSpan) {
        imageBox.addEventListener('mousemove', (e) => {
            customCursor.style.display = 'block';

            // Move the main container
            customCursor.style.left = e.clientX + 'px';
            customCursor.style.top = e.clientY + 'px';

            const rect = imageBox.getBoundingClientRect();
            const middle = rect.left + (rect.width / 2);

            if (e.clientX < middle) {
                // Point Left (Original state)
                arrowSpan.style.transform = "rotate(0deg)";
            } else {
                // Turn to point Right (180 degree spin)
                arrowSpan.style.transform = "rotate(180deg)";
            }
        });

        imageBox.addEventListener('mouseleave', () => {
            customCursor.style.display = 'none';
        });

        // Hide cursor when over variant thumbnails
        const variantScroll = document.getElementById('variantScroll');
        if (variantScroll) {
            variantScroll.addEventListener('mouseenter', () => {
                customCursor.style.display = 'none';
            });
            variantScroll.addEventListener('mouseleave', () => {
                customCursor.style.display = 'block';
            });
        }
    }
}

// Hide the custom arrow if the mouse moves over the thumbnails
const thumbnailContainer = document.getElementById('variantScroll');
if (thumbnailContainer) {
    thumbnailContainer.addEventListener('mouseenter', () => {
        customCursor.style.display = 'none';
    });
    thumbnailContainer.addEventListener('mouseleave', () => {
        customCursor.style.display = 'block';
    });
}

function toggleCart() {
    const sidebar = document.getElementById("cartSidebar");
    const overlay = document.getElementById("cartOverlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    
    // Kill the custom cursor while shopping in the bag
    const customCursor = document.getElementById('custom-cursor');
    if (sidebar.classList.contains("active")) {
        if (customCursor) customCursor.style.display = 'none';
    }
}

// Updated Remove Function to handle quantity subtraction
function removeFromCart(button, quantityToRemove) {
    button.parentElement.remove();
    totalItems -= quantityToRemove;
    
    const badge = document.getElementById("cart-count");
    if (badge) {
        badge.innerText = totalItems;
        if (totalItems <= 0) {
            badge.style.display = "none";
            totalItems = 0; // Prevent negative numbers
        }
    }

    updateCartTotal();
    
    if (totalItems === 0) {
        document.getElementById("cartItemsList").innerHTML = `
            <div class="empty-bag">
                <p>YOUR BAG IS CURRENTLY EMPTY.</p>
                <a href="#" onclick="toggleCart()" class="continue-link">CONTINUE BROWSING</a>
            </div>`;
    }
}

function updateQty(change) {
    const qtyInput = document.getElementById("productQty");
    let currentQty = parseInt(qtyInput.value);
    currentQty += change;
    
    if (currentQty < 1) currentQty = 1; // Prevent going below 1
    qtyInput.value = currentQty;
}

function closeLoginModal() {
    const modal = document.getElementById("loginModal");
    if (modal) {
        modal.style.display = "none";
    }
}
