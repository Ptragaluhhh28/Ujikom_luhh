@extends('layouts.app')

@section('title', 'Cari Motor')

@section('styles')
    .hero-section {
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        color: white;
        padding: 4rem 2rem;
        border-radius: 24px;
        margin-bottom: 3rem;
        text-align: center;
    }
    .search-bar {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        display: flex;
        gap: 1rem;
        max-width: 800px;
        margin: -2rem auto 0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .motor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    .motor-card {
        overflow: hidden;
        transition: transform 0.3s;
    }
    .motor-card:hover {
        transform: translateY(-8px);
    }
    .motor-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
    }
    .motor-info {
        padding: 1rem 0;
    }
    .price-tag {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
    }
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-cc { background: #fee2e2; color: #ef4444; }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
    }
@endsection

@section('sidebar')
    <div data-has-sidebar></div>
    <a href="/renter" class="sidebar-item active">
        <i class="fas fa-search"></i> Cari Motor
    </a>
    <a href="/renter/bookings" class="sidebar-item">
        <i class="fas fa-history"></i> Riwayat Sewa
    </a>
    <a href="/renter/profile" class="sidebar-item">
        <i class="fas fa-user"></i> Profil
    </a>
@endsection

@section('content')
<div class="hero-section">
    <h1>Cari Motor Impian Anda</h1>
    <p>Sewa motor dengan mudah, cepat, dan aman</p>
</div>

<div class="search-bar">
    <input type="text" id="searchMerk" class="form-control" style="border:none" placeholder="Cari merk motor...">
    <select id="filterCC" class="form-control" style="border:none; width: 150px">
        <option value="">Semua CC</option>
        <option value="100">100 CC</option>
        <option value="125">125 CC</option>
        <option value="150">150 CC</option>
    </select>
    <button class="btn btn-primary" id="btnSearch">Cari</button>
</div>

<div id="motorGrid" class="motor-grid">
    <!-- Motor cards load here -->
</div>

@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }
    
    async function fetchMotors(merk = '', cc = '') {
        try {
            const url = `/api/motors?merk=${merk}&tipe_cc=${cc}`;
            const response = await fetch(url, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            if (response.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/login';
                return;
            }

            const motors = await response.json();
            const grid = document.getElementById('motorGrid');
            
            if (!motors.length) {
                grid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align:center; padding:5rem; color:#94a3b8">
                        <i class="fas fa-motorcycle fa-4x" style="margin-bottom:1rem; opacity:0.3"></i>
                        <h3>Belum ada motor tersedia</h3>
                        <p>Silakan cek kembali nanti atau ubah filter pencarian Anda.</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = motors.map(m => `
                <div class="card motor-card">
                    <div style="position:relative">
                        <img src="${m.photo ? '/storage/'+m.photo : 'https://placehold.co/400x250?text='+m.merk}" class="motor-img">
                        <span class="badge badge-cc" style="position:absolute; top:1rem; right:1rem; background:rgba(255,255,255,0.9); backdrop-filter:blur(4px)">${m.tipe_cc} CC</span>
                    </div>
                    <div class="motor-info" style="padding: 1.25rem">
                        <h3 style="margin-bottom:0.25rem">${m.merk}</h3>
                        <p class="text-muted" style="font-size:0.875rem; margin-bottom:1.25rem">
                            <i class="fas fa-id-card"></i> ${m.no_plat}
                        </p>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:1rem; border-top:1px solid #f1f5f9">
                            <div>
                                <span class="text-muted" style="font-size:0.75rem; display:block">Harga Harian</span>
                                <span class="price-tag">Rp ${new Intl.NumberFormat('id-ID').format(m.tarif.tarif_harian)}</span>
                            </div>
                            <button class="btn btn-primary" onclick="openBooking(${m.id})" style="border-radius:10px; padding:0.6rem 1.2rem">Sewa</button>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    function openBooking(id) {
        window.location.href = `/renter/motors/${id}/book`;
    }

    document.getElementById('btnSearch').onclick = () => {
        fetchMotors(document.getElementById('searchMerk').value, document.getElementById('filterCC').value);
    };

    fetchMotors();
</script>
@endsection
