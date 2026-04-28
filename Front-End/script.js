
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
