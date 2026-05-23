<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>Swift Ride - Rental Mobil Premium Minimalis</title>
    <meta name="description" content="Swift Ride menyediakan layanan rental mobil premium yang modern, cepat, dan terpercaya dengan armada terbaik untuk kenyamanan perjalanan Anda.">
    <meta name="keywords" content="rental mobil, sewa mobil, rental mobil premium, swift ride, sewa mobil murah, rental mobil mewah">
    
    <!-- Google Fonts: Outfit for Display, Inter for Body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            500: '#6366f1', // Indigo Accent
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                            dark: '#0b0f19', // Sleek dark backgrounds
                            card: '#131926', // Premium card color
                            neutral: '#64748b'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Scrollbar & Micro-interactions Style -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b0f19; /* Default premium dark bg */
            color: #f8fafc;
        }
        .glassmorphism {
            background: rgba(11, 15, 25, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0b0f19;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">
    
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 glassmorphism transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="index.php" class="flex items-center space-x-2 font-display text-2xl font-bold tracking-tight">
                <span class="bg-gradient-to-r from-brand-500 to-indigo-400 bg-clip-text text-transparent">Swift</span>
                <span class="text-white">Ride</span>
            </a>
            
            <!-- Desktop Navbar Menu -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Home</a>
                <a href="#fleet" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Armada</a>
                <a href="#features" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Keunggulan</a>
                <a href="#contact" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Hubungi Kami</a>
            </nav>
            
            <!-- Desktop Call to Action -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="#fleet" class="relative group px-5 py-2.5 rounded-full overflow-hidden transition-all duration-300">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-brand-600 to-indigo-500"></span>
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-500 to-brand-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span class="relative text-sm font-semibold text-white tracking-wide">Pesan Sekarang</span>
                </a>
            </div>
            
            <!-- Mobile Menu Trigger Button -->
            <button id="menu-toggle" type="button" class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/50 focus:outline-none transition-colors duration-200" aria-label="Toggle Navigation">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Slide-out/Drop-down Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800/80 bg-brand-dark/95 backdrop-blur-lg">
            <div class="px-6 py-5 space-y-4 flex flex-col">
                <a href="#home" class="mobile-nav-link text-lg font-medium text-slate-300 hover:text-white transition-colors py-2">Home</a>
                <a href="#fleet" class="mobile-nav-link text-lg font-medium text-slate-300 hover:text-white transition-colors py-2">Armada</a>
                <a href="#features" class="mobile-nav-link text-lg font-medium text-slate-300 hover:text-white transition-colors py-2">Keunggulan</a>
                <a href="#contact" class="mobile-nav-link text-lg font-medium text-slate-300 hover:text-white transition-colors py-2">Hubungi Kami</a>
                <a href="#fleet" class="mobile-nav-link text-center w-full px-5 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-500 font-semibold text-white tracking-wide mt-4 block">Pesan Sekarang</a>
            </div>
        </div>
    </header>
