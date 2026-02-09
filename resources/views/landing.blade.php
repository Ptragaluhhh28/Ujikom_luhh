<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyRent - Sistem Penyewaan Motor Modern</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/documents/image/logo motor.jpg') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#64748b',
                        accent: '#f59e0b',
                        dark: '#0f172a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('landing_style.css') }}">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-slate-950 text-dark dark:text-gray-100 overflow-x-hidden transition-colors duration-300">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-4 transition-colors duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="#" class="flex items-center gap-2 text-2xl font-display font-bold text-primary">
                <img src="{{ asset('storage/documents/image/logo motor.jpg') }}" alt="Logo" style="height: 48px; width: auto; border-radius: 10px;">
                <span>MyRent</span>
            </a>
            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#about" class="hover:text-primary transition-colors">Tentang</a>
                <a href="#features" class="hover:text-primary transition-colors">Fitur</a>
                <a href="#flow" class="hover:text-primary transition-colors">Alur</a>
                <a href="#preview" class="hover:text-primary transition-colors">Preview</a>
            </div>
            <div class="flex items-center gap-4">
                <button id="themeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Toggle dark mode">
                    <i class="fas fa-sun text-xl text-yellow-500 dark:hidden"></i>
                    <i class="fas fa-moon text-xl text-blue-400 hidden dark:inline"></i>
                </button>
                <a href="/login" class="hidden sm:block font-semibold hover:text-primary transition-colors">Login</a>
                <a href="/register" class="bg-primary text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-48 md:pb-32 overflow-hidden">
        <div class="container mx-auto px-6 flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-primary px-4 py-2 rounded-full text-sm font-bold mb-6" data-aos="fade-down">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                Sistem Penyewaan Motor Terintegrasi
            </div>
            <h1 class="text-4xl md:text-7xl font-display font-extrabold leading-[1.1] mb-6 max-w-4xl" data-aos="fade-up">
                Aplikasi Sistem Penyewaan <br> <span class="text-primary italic">Motor Berbasis Web</span>
            </h1>
            <p class="text-lg md:text-xl text-secondary max-w-2xl mb-10" data-aos="fade-up" data-aos-delay="100">
                Kelola Persewaan, Pembayaran, dan Bagi Hasil dalam Satu Sistem. Solusi modern untuk pemilik rental dan penyewa motor.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto" data-aos="fade-up" data-aos-delay="200">
                <a href="#features" class="bg-primary text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl shadow-primary/25 hover:bg-blue-700 transition-colors">
                    Lihat Fitur <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#preview" class="bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-gray-700 px-8 py-4 rounded-2xl font-bold text-lg hover:border-primary dark:hover:border-primary transition-colors">
                    Demo Aplikasi
                </a>
            </div>
            
        </div>
        
        <!-- Background Elements -->
        <div class="absolute bottom-0 left-0 right-0 top-0 -z-10 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)] dark:bg-none dark:[background:radial-gradient(125%_125%_at_50%_10%,#000_40%,#63e_100%)]"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="w-full md:w-1/2" data-aos="fade-right">
                    <div class="relative">
                        <div class="bg-primary/10 rounded-[3rem] p-4">
                            <img src="{{ asset('storage/documents/image/OIP (1).jpg') }}" alt="Motor rental" class="rounded-[2.5rem] shadow-2xl w-full">
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2" data-aos="fade-left">
                    <span class="text-primary font-bold tracking-widest uppercase">Tentang Aplikasi</span>
                    <h2 class="text-3xl md:text-5xl font-display font-bold mt-4 mb-6 leading-tight">
                        Solusi Cerdas untuk Manajemen <br> Rental Motor Anda
                    </h2>
                    <p class="text-secondary dark:text-gray-400 text-lg mb-8 leading-relaxed">
                        Manajemen persewaan motor konvensional seringkali mengalami kendala dalam pendataan unit, transparansi bagi hasil dengan pemilik motor, dan pelacakan pembayaran dari penyewa. 
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50 dark:bg-slate-950 overflow-hidden transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase">Fitur Role-Based</span>
                <h2 class="text-3xl md:text-5xl font-display font-bold mt-4 mb-6 italic">Satu Sistem, Berbagai Peran</h2>
                <p class="text-secondary dark:text-gray-400 text-lg">Platform kami dirancang khusus untuk memenuhi kebutuhan unik setiap pengguna dalam ekosistem persewaan motor.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-4xl mx-auto px-4">
                <!-- Owner Features -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 group hover:bg-orange-500 dark:hover:bg-orange-500 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 group-hover:text-white">Pemilik Motor</h3>
                    <ul class="space-y-3 text-secondary group-hover:text-white/80">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Daftarkan Unit Baru</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Pantau Status Unit</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Laporan Bagi Hasil</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Upload Dokumen Unit</li>
                    </ul>
                </div>

                <!-- Renter Features -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 group hover:bg-emerald-600 dark:hover:bg-emerald-600 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl mb-6 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 group-hover:text-white">Penyewa</h3>
                    <ul class="space-y-3 text-secondary group-hover:text-white/80">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Pencarian Motor Real-time</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Filter Tipe & Harga</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Histori Penyewaan</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-500 group-hover:text-white"></i> Pembayaran Terintegrasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Flow Section -->
    <section id="flow" class="py-24 bg-white dark:bg-slate-900 relative transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase">Alur Kerja</span>
                <h2 class="text-3xl md:text-5xl font-display font-bold mt-4">Bagaimana Sistem Bekerja?</h2>
            </div>
            
            <div class="relative max-w-4xl mx-auto">
                <!-- Line -->
                <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-gray-100 -translate-x-1/2 hidden md:block"></div>
                
                <div class="space-y-16">
                    <!-- Step 1 -->
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0" data-aos="fade-up">
                        <div class="md:w-1/2 md:pr-16 text-left md:text-right">
                            <h4 class="text-2xl font-bold mb-2 italic">1. Pendaftaran Unit</h4>
                            <p class="text-secondary dark:text-gray-400">Pemilik motor mendaftarkan unit motor lengkap dengan foto dan dokumen STNK.</p>
                        </div>
                        <div class="relative z-10 w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl shadow-lg">1</div>
                        <div class="md:w-1/2 md:pl-16"></div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0" data-aos="fade-up">
                        <div class="md:w-1/2 md:pr-16"></div>
                        <div class="relative z-10 w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl shadow-lg">2</div>
                        <div class="md:w-1/2 md:pl-16">
                            <h4 class="text-2xl font-bold mb-2 italic">2. Verifikasi Dokumen</h4>
                            <p class="text-secondary dark:text-gray-400">Sistem memvalidasi dokumen dan menetapkan tarif (Harian, Mingguan, Bulanan).</p>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0" data-aos="fade-up">
                        <div class="md:w-1/2 md:pr-16 text-left md:text-right">
                            <h4 class="text-2xl font-bold mb-2 italic">3. Pemesanan Renter</h4>
                            <p class="text-secondary dark:text-gray-400">Penyewa mencari motor yang tersedia, memilih durasi, dan melakukan booking.</p>
                        </div>
                        <div class="relative z-10 w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl shadow-lg">3</div>
                        <div class="md:w-1/2 md:pl-16"></div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0" data-aos="fade-up">
                        <div class="md:w-1/2 md:pr-16"></div>
                        <div class="relative z-10 w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl shadow-lg">4</div>
                        <div class="md:w-1/2 md:pl-16">
                            <h4 class="text-2xl font-bold mb-2 italic">4. Transaksi & Laporan</h4>
                            <p class="text-secondary dark:text-gray-400">Setelah pembayaran dikonfirmasi, sistem otomatis membuat laporan bagi hasil secara real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Preview Section -->
    <section id="preview" class="py-24 bg-dark text-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/3" data-aos="fade-right">
                    <span class="text-primary font-bold tracking-widest uppercase">Preview Aplikasi</span>
                    <h2 class="text-3xl md:text-5xl font-display font-bold mt-4 mb-8">Antarmuka Modern & Intuitif</h2>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        Nikmati pengalaman pengguna yang lancar dengan desain dashboard yang bersih, grafik data yang informatif, dan navigasi yang mudah dimengerti bahkan oleh pengguna awam.
                    </p>
                    <div class="space-y-4 pr-0 lg:pr-12">
                        <button onclick="changeMockup('owner')" class="mockup-tab active flex items-center gap-4 w-full p-4 rounded-2xl border border-gray-700 hover:border-primary transition-all">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white"><i class="fas fa-home"></i></div>
                            <span class="font-bold text-xl">Owner Fleet</span>
                        </button>
                        <button onclick="changeMockup('renter')" class="mockup-tab flex items-center gap-4 w-full p-4 rounded-2xl border border-gray-700 hover:border-primary transition-all">
                            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center"><i class="fas fa-search"></i></div>
                            <span class="font-bold text-xl text-gray-400">Renter Search</span>
                        </button>
                    </div>
                </div>
                
                <div class="w-full lg:w-2/3" data-aos="zoom-in">
                    <div class="relative">
                        <!-- Browser Shell Mockup -->
                        <div class="bg-gray-800 rounded-t-2xl p-4 flex gap-2 border-b border-gray-700">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="bg-gray-900 rounded-b-2xl overflow-hidden shadow-2xl border border-gray-700 aspect-video flex items-center justify-center p-8">
                           <div id="mockupDisplay" class="w-full h-full bg-white rounded-lg flex flex-col items-center justify-center text-dark p-6">
                                <i class="fas fa-window-maximize text-6xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400 font-medium">Memuat Antarmuka...</p>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="bg-primary rounded-[3rem] p-10 md:p-20 text-center text-white relative overflow-hidden shadow-2xl shadow-primary/40" data-aos="zoom-in">
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-6xl font-display font-bold mb-8 italic">Siap Digitalisasi Rental Motor Anda?</h2>
                    <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-12">
                        Bergabunglah dengan ratusan pengusaha rental motor yang telah sukses mengelola bisnisnya dengan MyRent.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/register" class="bg-white text-primary px-10 py-5 rounded-2xl font-bold text-xl hover:scale-105 transition-transform">
                            Mulai Gunakan Aplikasi
                        </a>
                        <a href="#" class="bg-blue-600 text-white border-2 border-white/20 px-10 py-5 rounded-2xl font-bold text-xl hover:bg-blue-700 transition-colors">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                <!-- Decor -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/3 translate-y-1/3"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 text-gray-400 py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <a href="#" class="flex items-center gap-2 text-3xl font-display font-bold text-white mb-6">
                        <img src="{{ asset('storage/documents/image/logo motor.jpg') }}" alt="Logo" style="height: 50px; width: auto; border-radius: 12px;">
                        <span>MyRent</span>
                    </a>
                    <p class="max-w-md leading-relaxed">
                        Aplikasi Sistem Penyewaan Motor Berbasis Web yang memberikan kemudahan dalam pengelolaan, transparansi, dan efisiensi operasional bisnis rental Anda.
                    </p>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6 italic">Navigasi</h5>
                    <ul class="space-y-4">
                        <li><a href="#about" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                        <li><a href="#features" class="hover:text-primary transition-colors">Fitur Sistem</a></li>
                        <li><a href="#flow" class="hover:text-primary transition-colors">Alur Kerja</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Demo</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6 italic">Kontak Kami</h5>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3"><i class="fas fa-envelope text-primary"></i> support@myrent.id</li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone text-primary"></i> +62 821 1234 5678</li>
                        <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-primary"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <p>&copy; 2026 MyRent System - v1.0.0. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('landing_script.js') }}"></script>
</body>
</html>
