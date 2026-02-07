// Initialize AOS (Animate on Scroll)
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 1000,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Initial Mockup Load
    changeMockup('admin');
});

// Mockup Data / Templates
const mockups = {
    admin: `
        <div class="w-full h-full flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold font-display">Admin Dashboard</h3>
                <div class="flex gap-2">
                    <div class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold font-display italic">Live Stats</div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="border-2 border-gray-100 p-4 rounded-xl">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1 italic">Total Revenue</p>
                    <p class="text-xl font-extrabold text-primary font-display">Rp 12.5M</p>
                </div>
                <div class="border-2 border-gray-100 p-4 rounded-xl">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1 italic">Active Rentals</p>
                    <p class="text-xl font-extrabold text-primary font-display">42 Units</p>
                </div>
                <div class="border-2 border-gray-100 p-4 rounded-xl">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1 italic">New Owners</p>
                    <p class="text-xl font-extrabold text-primary font-display">+12 This Week</p>
                </div>
            </div>
            <div class="flex-1 bg-gray-50 rounded-xl p-4 flex flex-col gap-3">
                <div class="h-4 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-full bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-5/6 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-10 w-full border-t border-gray-200 mt-2 flex items-center justify-between">
                    <span class="text-xs text-gray-400 font-bold italic">Analytics Visualization</span>
                    <div class="flex gap-1">
                        <div class="w-2 h-6 bg-primary rounded-t"></div>
                        <div class="w-2 h-8 bg-blue-400 rounded-t"></div>
                        <div class="w-2 h-4 bg-primary rounded-t"></div>
                        <div class="w-2 h-10 bg-blue-300 rounded-t"></div>
                    </div>
                </div>
            </div>
        </div>
    `,
    owner: `
        <div class="w-full h-full flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold font-display italic">My Motor Fleet</h3>
                <button class="bg-primary text-white text-xs px-4 py-2 rounded-lg font-bold shadow-lg shadow-primary/20">+ Tambah Unit</button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="border-2 border-gray-100 p-3 rounded-2xl flex gap-4 items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center text-primary text-xl">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 font-display">Vespa Sprint</p>
                        <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest italic">Tersedia</p>
                    </div>
                </div>
                <div class="border-2 border-primary/20 bg-blue-50/30 p-3 rounded-2xl flex gap-4 items-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center text-primary text-xl">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 font-display">Honda Vario</p>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest italic">Disewa</p>
                    </div>
                </div>
                <div class="border-2 border-gray-100 p-3 rounded-2xl flex gap-4 items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 text-xl">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 font-display">Yamaha NMax</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Pending</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 p-4 bg-gray-50 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 italic">Total Earnings</p>
                    <p class="text-2xl font-bold text-dark font-display">Rp 2.450.000</p>
                </div>
                <i class="fas fa-wallet text-3xl text-gray-300"></i>
            </div>
        </div>
    `,
    renter: `
        <div class="w-full h-full flex flex-col">
            <div class="relative mb-6">
                <input type="text" placeholder="Cari motor impian Anda..." class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 pl-12 text-sm">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-tighter italic">Motor Tersedia di Sekitar Anda</p>
            <div class="grid grid-cols-2 gap-6 overflow-y-auto pr-2">
                <div class="group">
                    <div class="aspect-[4/3] bg-gray-100 rounded-3xl mb-3 overflow-hidden transition-all group-hover:shadow-xl group-hover:scale-[1.02]">
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                           <i class="fas fa-image text-3xl"></i>
                        </div>
                    </div>
                    <p class="font-bold text-dark font-display">Vespa Primavera</p>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-primary font-bold italic">Rp 150rb<span class="text-[10px] text-gray-400 font-normal">/hari</span></span>
                        <div class="bg-blue-50 text-primary w-8 h-8 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="group">
                    <div class="aspect-[4/3] bg-gray-100 rounded-3xl mb-3 overflow-hidden transition-all group-hover:shadow-xl group-hover:scale-[1.02]">
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                           <i class="fas fa-image text-3xl"></i>
                        </div>
                    </div>
                    <p class="font-bold text-dark font-display">Kawasaki Ninja</p>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-primary font-bold italic">Rp 350rb<span class="text-[10px] text-gray-400 font-normal">/hari</span></span>
                        <div class="bg-blue-50 text-primary w-8 h-8 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `
};

// Mockup Switcher Function
function changeMockup(role) {
    const display = document.getElementById('mockupDisplay');
    const tabs = document.querySelectorAll('.mockup-tab');
    
    // Update Tabs UI
    tabs.forEach(tab => {
        tab.classList.remove('active');
        const iconContainer = tab.querySelector('div');
        iconContainer.classList.remove('bg-primary');
        iconContainer.classList.add('bg-gray-700');
    });

    const activeTab = Array.from(tabs).find(t => t.innerText.toLowerCase().includes(role));
    if (activeTab) {
        activeTab.classList.add('active');
        const activeIcon = activeTab.querySelector('div');
        activeIcon.classList.remove('bg-gray-700');
        activeIcon.classList.add('bg-primary');
    }

    // Animation transition
    display.style.opacity = '0';
    display.style.transform = 'translateY(10px)';
    
    setTimeout(() => {
        display.innerHTML = mockups[role];
        display.style.opacity = '1';
        display.style.transform = 'translateY(0)';
        display.style.transition = 'all 0.4s ease-out';
    }, 200);
}

// Smooth Scrolling for all links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
