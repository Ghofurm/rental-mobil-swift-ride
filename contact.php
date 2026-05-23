<?php
// contact.php

// Aktifkan pelaporan error internal
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Sertakan layout Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Contact Section Header -->
<section class="pt-24 pb-12 relative overflow-hidden">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center space-y-4">
        <span class="text-xs font-semibold text-brand-500 tracking-widest uppercase">Hubungi Kami</span>
        <h1 class="text-4xl sm:text-5xl font-display font-bold text-white">Hubungi Tim Resepsionis Kami</h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto">
            Ada pertanyaan atau ingin konsultasi kebutuhan sewa kendaraan bulanan/tahunan korporasi? Kami siap membantu 24/7.
        </p>
    </div>
</section>

<!-- Contact Form & Info Grid -->
<section class="pb-24">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Contact Information (4 columns) -->
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-brand-card/40 border border-slate-800/60 p-8 rounded-3xl space-y-6">
                <h3 class="text-xl font-bold font-display text-white border-b border-slate-800/80 pb-4">Informasi Bisnis</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <span class="text-xl text-brand-500 mt-1">📍</span>
                        <div>
                            <h4 class="font-bold text-white text-xs uppercase tracking-wider">Kantor Pusat</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Jl. Swift Raya No. 42, Kebayoran Baru, Jakarta Selatan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="text-xl text-brand-500 mt-1">✉</span>
                        <div>
                            <h4 class="font-bold text-white text-xs uppercase tracking-wider">Email Resmi</h4>
                            <p class="text-xs text-slate-400 mt-0.5">info@swiftride.id</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="text-xl text-brand-500 mt-1">📞</span>
                        <div>
                            <h4 class="font-bold text-white text-xs uppercase tracking-wider">Telepon & WhatsApp</h4>
                            <p class="text-xs text-slate-400 mt-0.5">+62 812-3456-7890</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="bg-brand-card/20 border border-slate-800/60 p-6 rounded-2xl space-y-3">
                <h4 class="font-bold text-white text-sm">📅 Jam Kerja Layanan</h4>
                <div class="space-y-2 text-xs text-slate-400">
                    <div class="flex justify-between">
                        <span>Senin - Jumat</span>
                        <span class="text-brand-400 font-medium">08.00 - 20.00 WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sabtu - Minggu</span>
                        <span class="text-brand-400 font-medium">09.00 - 17.00 WIB</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-800/80 pt-2">
                        <span>Layanan Darurat WA</span>
                        <span class="text-emerald-400 font-bold">24 Jam Standby</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Contact Form (7 columns) -->
        <div class="lg:col-span-7 bg-brand-card/50 border border-slate-800/80 p-8 rounded-3xl space-y-6">
            <h3 class="text-xl font-bold font-display text-white">Kirim Pesan Langsung</h3>
            <p class="text-xs text-slate-400">Silakan isi formulir di bawah ini, perwakilan kami akan menghubungi Anda dalam waktu maksimal 1 jam.</p>
            
            <form onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah sukses dikirim (Simulasi).'); this.reset();" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400">Nama Lengkap</label>
                        <input 
                            type="text" 
                            required 
                            placeholder="John Doe"
                            class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-600 focus:outline-none focus:border-brand-500 transition-colors"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400">Alamat Email</label>
                        <input 
                            type="email" 
                            required 
                            placeholder="johndoe@email.com"
                            class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-600 focus:outline-none focus:border-brand-500 transition-colors"
                        >
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-400">Subjek Pesan</label>
                    <input 
                        type="text" 
                        required 
                        placeholder="Ingin menyewa bulanan..."
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-600 focus:outline-none focus:border-brand-500 transition-colors"
                    >
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-400">Isi Pesan Anda</label>
                    <textarea 
                        required 
                        rows="4"
                        placeholder="Tuliskan detail kebutuhan kendaraan Anda di sini..."
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-600 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                    ></textarea>
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 text-xs text-emerald-400 font-bold hover:underline">
                        <span>💬 Konsultasi Instan via WhatsApp</span>
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-brand-600 hover:bg-brand-700 font-semibold text-white text-xs rounded-xl transition-colors duration-200"
                    >
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
// Sertakan layout Footer
require_once __DIR__ . '/includes/footer.php';
?>
