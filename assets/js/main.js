/**
 * Swift Ride - Core Client-Side Logic & Seamless AJAX Page Router
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inisialisasi Event Standar Halaman
    initMobileMenu();
    initScrollHeader();
    initPageRouter();
});

/**
 * Mobile Menu Interactivity
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    if (menuToggle && mobileMenu && menuIcon) {
        // Hapus event listener lama jika ada untuk mencegah double bind
        const newMenuToggle = menuToggle.cloneNode(true);
        menuToggle.parentNode.replaceChild(newMenuToggle, menuToggle);

        newMenuToggle.addEventListener('click', () => {
            const isExpanded = mobileMenu.classList.contains('hidden');
            
            if (isExpanded) {
                mobileMenu.classList.remove('hidden');
                newMenuToggle.setAttribute('aria-expanded', 'true');
                menuIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            } else {
                mobileMenu.classList.add('hidden');
                newMenuToggle.setAttribute('aria-expanded', 'false');
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }
        });

        // Tutup menu mobile ketika link di-klik
        const mobileLinks = mobileMenu.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                newMenuToggle.setAttribute('aria-expanded', 'false');
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            });
        });
    }
}

/**
 * Scroll-Dynamic Navigation Bar Styles
 */
function initScrollHeader() {
    const header = document.querySelector('header');
    
    if (header) {
        window.removeEventListener('scroll', handleHeaderScroll);
        window.addEventListener('scroll', handleHeaderScroll);
    }
}

function handleHeaderScroll() {
    const header = document.querySelector('header');
    if (!header) return;
    
    if (window.scrollY > 50) {
        header.classList.add('shadow-lg', 'bg-brand-dark/95', 'border-slate-800/80');
        header.classList.remove('bg-brand-dark/75', 'border-white/5');
    } else {
        header.classList.remove('shadow-lg', 'bg-brand-dark/95', 'border-slate-800/80');
        header.classList.add('bg-brand-dark/75', 'border-white/5');
    }
}

/**
 * AJAX Page Router (Seamless Navigation)
 */
function initPageRouter() {
    const pageContent = document.getElementById('page-content');
    if (!pageContent) return;

    // Intercept semua klik pada link dengan atribut 'data-link'
    document.removeEventListener('click', handleLinkClick);
    document.addEventListener('click', handleLinkClick);

    // Tangani navigasi tombol "Back" / "Forward" di browser
    window.removeEventListener('popstate', handlePopState);
    window.addEventListener('popstate', handlePopState);
}

function handleLinkClick(e) {
    const link = e.target.closest('a[data-link]');
    if (!link) return;

    const href = link.getAttribute('href');
    
    // Pastikan link mengarah ke halaman internal
    if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
        e.preventDefault();
        loadPage(href);
    }
}

function handlePopState() {
    loadPage(window.location.pathname + window.location.search, false);
}

/**
 * Memuat halaman secara asinkronus menggunakan Fetch API
 */
async function loadPage(url, pushState = true) {
    const pageContent = document.getElementById('page-content');
    if (!pageContent) return;

    // 1. Transisi Fade-Out
    pageContent.classList.add('opacity-0');

    // Tunggu animasi fade-out selesai (300ms sesuai durasi kelas CSS Tailwind)
    await new Promise(resolve => setTimeout(resolve, 200));

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const htmlText = await response.text();

        // Parse HTML yang diambil
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');
        const newContent = doc.getElementById('page-content');
        const newTitle = doc.querySelector('title');

        if (newContent) {
            // 2. Ganti konten dan judul halaman
            pageContent.innerHTML = newContent.innerHTML;
            if (newTitle) {
                document.title = newTitle.textContent;
            }

            // 3. Update status history browser
            if (pushState) {
                history.pushState(null, null, url);
            }

            // 4. Perbarui status Menu Aktif (Active State)
            updateActiveNavMenu(url);

            // 5. Scroll ke atas agar halaman baru dimulai dari atas
            window.scrollTo({ top: 0, behavior: 'instant' });

            // 6. Jalankan kembali inisialisasi jika ada elemen baru yang membutuhkan binding
            initMobileMenu();
        }
    } catch (error) {
        console.error("Gagal melakukan transisi seamless halaman: ", error);
        // Fallback: Lakukan full page reload jika fetch gagal
        window.location.href = url;
    } finally {
        // 7. Transisi Fade-In
        pageContent.classList.remove('opacity-0');
    }
}

/**
 * Memperbarui active class pada navigasi header dan footer
 */
function updateActiveNavMenu(url) {
    // Ambil nama file dari URL
    const parser = document.createElement('a');
    parser.href = url;
    const filename = parser.pathname.split('/').pop() || 'index.php';

    // Perbarui link navigasi di desktop & mobile
    const allLinks = document.querySelectorAll('a[data-link]');
    allLinks.forEach(link => {
        const href = link.getAttribute('href');
        const linkFilename = href ? href.split('/').pop() : '';

        // Jika ini tautan tombol utama 'Pesan Sekarang', jangan ubah stylenya
        if (link.classList.contains('bg-gradient-to-r') && !link.classList.contains('mobile-nav-link')) {
            return;
        }

        if (linkFilename === filename) {
            link.className = link.className
                .replace('text-slate-300 hover:text-white', 'text-brand-500 font-semibold')
                .replace('hover:text-brand-500', 'text-brand-500 font-semibold');
        } else {
            link.className = link.className
                .replace('text-brand-500 font-semibold', 'text-slate-300 hover:text-white')
                .replace('text-brand-500 font-semibold', 'hover:text-brand-500');
        }
    });
}
