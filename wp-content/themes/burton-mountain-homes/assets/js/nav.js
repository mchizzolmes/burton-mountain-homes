/**
 * Burton Mountain Homes - Nav JS
 * Handles hamburger toggle and smooth scroll
 */
(function() {
    // Hamburger toggle
    const hamburger = document.getElementById('bmh-hamburger');
    const mobileMenu = document.getElementById('bmh-mobile-menu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('is-open');
            hamburger.classList.toggle('is-open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen);
            mobileMenu.setAttribute('aria-hidden', !isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('is-open');
                hamburger.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', false);
                mobileMenu.setAttribute('aria-hidden', true);
                document.body.style.overflow = '';
            });
        });
    }

    // Navbar scroll effect (homepage transparent nav only)
    const navbar = document.getElementById('bmh-navbar');
    if (navbar && !navbar.classList.contains('bmh-nav-solid')) {
        window.addEventListener('scroll', () => {
            navbar.style.background = window.scrollY > 100
                ? 'rgba(26,39,68,0.97)'
                : 'linear-gradient(to bottom, rgba(26,39,68,0.85) 0%, rgba(26,39,68,0) 100%)';
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
})();
