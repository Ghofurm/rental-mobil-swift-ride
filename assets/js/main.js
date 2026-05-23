/**
 * Swift Ride - Core Client-Side Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Interactivity
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    if (menuToggle && mobileMenu && menuIcon) {
        menuToggle.addEventListener('click', () => {
            const isExpanded = mobileMenu.classList.contains('hidden');
            
            if (isExpanded) {
                // Tampilkan menu
                mobileMenu.classList.remove('hidden');
                menuToggle.setAttribute('aria-expanded', 'true');
                // Ubah icon ke "X"
                menuIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            } else {
                // Sembunyikan menu
                mobileMenu.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');
                // Ubah icon kembali ke "Hamburger"
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }
        });

        // Tutup menu mobile ketika link di-klik
        const mobileLinks = mobileMenu.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            });
        });
    }

    // 2. Scroll-Dynamic Navigation Bar Styles
    const header = document.querySelector('header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                // Header yang lebih solid dan padat saat di-scroll
                header.classList.add('shadow-lg', 'bg-brand-dark/95', 'border-slate-800/80');
                header.classList.remove('bg-brand-dark/75', 'border-white/5');
            } else {
                // Kembali ke default glassmorphism awal
                header.classList.remove('shadow-lg', 'bg-brand-dark/95', 'border-slate-800/80');
                header.classList.add('bg-brand-dark/75', 'border-white/5');
            }
        });
    }
});
