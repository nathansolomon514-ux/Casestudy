
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