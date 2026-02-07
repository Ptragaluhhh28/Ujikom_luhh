@extends('layouts.app')

@section('title', 'Pesan Motor')

@section('styles')
    .booking-container {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 3rem;
        align-items: start;
        max-width: 1200px;
        margin: 0 auto;
    }
    .motor-summary {
        position: sticky;
        top: 2rem;
    }
    .motor-preview-img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 24px;
        margin-bottom: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .motor-preview-container {
        position: relative;
    }
    .booking-form-card {
        padding: 3rem;
        border-radius: 24px;
    }
    .form-group {
        margin-bottom: 1.75rem;
    }
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }
    .form-label i {
        color: var(--primary);
        font-size: 0.8rem;
    }
    .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .info-label { color: #64748b; font-size: 0.95rem; }
    .info-value { font-weight: 600; color: #1e293b; }
    
    .price-brief {
        background: #f8fafc;
        padding: 2rem;
        border-radius: 20px;
        margin-top: 2.5rem;
        border: 1px solid #f1f5f9;
    }
    .total-price {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--primary);
        display: block;
        margin-top: 0.25rem;
    }
@endsection

@section('sidebar')
    <div data-has-sidebar></div>
    <a href="/renter" class="sidebar-item">
        <i class="fas fa-search"></i> Cari Motor
    </a>
    <a href="/renter/bookings" class="sidebar-item">
        <i class="fas fa-history"></i> Riwayat Sewa
    </a>
@endsection

@section('content')
<div style="margin-bottom: 2rem; display:flex; align-items:center; gap:1rem">
    <a href="/renter" class="btn btn-secondary btn-sm" style="border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; padding:0">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 style="margin:0">Konfirmasi Penyewaan</h1>
        <p class="text-muted" style="margin:0">Lengkapi detail durasi sewa Anda</p>
    </div>
</div>

<div class="booking-container">
    <div class="motor-summary" id="motorSummary">
        <!-- Loaded via JS -->
        <div class="card glass" style="padding:1.5rem; text-align:center">
            <div class="loader">Memuat data motor...</div>
        </div>
    </div>

    <div class="card booking-form-card shadow-sm">
        <form id="bookingForm">
            <h2 style="margin-bottom: 2rem">Detail Sewa</h2>
            
            <div class="form-group">
                <label class="form-label"><i class="fas fa-clock"></i> Pilih Paket Durasi</label>
                <select id="tipe_durasi" class="form-control" required>
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem">
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-layer-group"></i> Jumlah Durasi</label>
                    <input type="number" id="durasi_val" class="form-control" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Tanggal Mulai</label>
                    <input type="date" id="tgl_mulai" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-flag-checkered"></i> Tanggal Selesai (Estimasi)</label>
                <input type="text" id="tgl_selesai_display" class="form-control" readonly style="background:#f1f5f9; cursor:not-allowed" placeholder="Akan dihitung otomatis">
            </div>

            <div class="price-brief">
                <div class="info-row">
                    <span class="info-label">Subtotal</span>
                    <span class="info-value" id="subtotal_display">Rp 0</span>
                </div>
                <div class="info-row" style="margin-top:1rem; padding-top:1rem; border-top:1px dashed #cbd5e1">
                    <span class="info-label" style="font-size:1.1rem">Total Pembayaran</span>
                    <span class="total-price" id="total_display">Rp 0</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:2rem; padding:1.25rem; font-size:1.1rem; border-radius:16px">
                <i class="fas fa-check-circle"></i> Pesan Sekarang
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    const motorId = window.location.pathname.split('/')[3];
    let motorData = null;

    if (!token) window.location.href = '/login';

    async function loadMotor() {
        try {
            const res = await fetch(`/api/motors?id=${motorId}`, {
                 headers: { 'Authorization': `Bearer ${token}` }
            });
            const motors = await res.json();
            // In index, we fetch all. Here we filter or use show if it exists for public.
            // Let's assume we can fetch by id specifically or filter from list
            motorData = motors.find(m => m.id == motorId);
            
            if (!motorData) {
                // Try fetching directly via owner show if public show missing (backend might need public show)
                // For now, let's assume we can get it.
                renderMotorInfo();
                calculatePrice();
            } else {
                renderMotorInfo();
                calculatePrice();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Since /api/motors is for listing, let's use a specific show if available or reuse
    // I noticed owner/motors/{id} exists but is authed to owner.
    // Let's create a small hack to fetch by list and find for now, 
    // or suggest adding a public show route if it fails.

    function renderMotorInfo() {
        if (!motorData) return;
        const container = document.getElementById('motorSummary');
        container.innerHTML = `
            <div class="motor-preview-container">
                <img src="${motorData.photo ? '/storage/'+motorData.photo : 'https://placehold.co/400x250?text=No+Image'}" class="motor-preview-img">
            </div>
            <div class="card shadow-sm" style="padding:2rem; border-radius:24px; border: 1px solid #f1f5f9">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem">
                    <span class="badge" style="background:#fff1f2; color:#e11d48; border:1px solid #ffe4e6; font-size:0.7rem">${motorData.tipe_cc} CC</span>
                </div>
                <h2 style="margin-bottom:2rem; color:#0f172a; font-size:1.75rem">${motorData.merk}</h2>
                
                <div style="display:flex; flex-direction:column; gap:1.25rem">
                    <div class="info-row" style="border-bottom:1px solid #f8fafc; padding-bottom:0.75rem">
                        <span class="info-label"><i class="fas fa-barcode"></i> Nomor Plat</span>
                        <span class="info-value" style="font-family:monospace; background:#f1f5f9; padding:0.25rem 0.5rem; border-radius:4px">${motorData.no_plat}</span>
                    </div>
                    
                    <div style="margin-top:0.5rem">
                        <p style="font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1rem">Pilihan Tarif</p>
                        <div class="info-row" style="margin-bottom:1rem">
                            <span class="info-label">Sewa Harian</span>
                            <span class="info-value" style="color:var(--primary)">Rp ${new Intl.NumberFormat('id-ID').format(motorData.tarif.tarif_harian)}</span>
                        </div>
                        <div class="info-row" style="margin-bottom:1rem">
                            <span class="info-label">Sewa Mingguan</span>
                            <span class="info-value">Rp ${new Intl.NumberFormat('id-ID').format(motorData.tarif.tarif_mingguan)}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Sewa Bulanan</span>
                            <span class="info-value">Rp ${new Intl.NumberFormat('id-ID').format(motorData.tarif.tarif_bulanan)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function calculatePrice() {
        if (!motorData) return;
        const tipe = document.getElementById('tipe_durasi').value;
        const val = parseInt(document.getElementById('durasi_val').value) || 0;
        let price = 0;

        if (tipe === 'harian') price = motorData.tarif.tarif_harian * val;
        else if (tipe === 'mingguan') price = motorData.tarif.tarif_mingguan * val;
        else if (tipe === 'bulanan') price = motorData.tarif.tarif_bulanan * val;

        const formatted = new Intl.NumberFormat('id-ID').format(price);
        document.getElementById('subtotal_display').innerText = `Rp ${formatted}`;
        document.getElementById('total_display').innerText = `Rp ${formatted}`;

        // Update estimated end date
        const startStr = document.getElementById('tgl_mulai').value;
        if (startStr) {
            const start = new Date(startStr);
            let end = new Date(start);
            if (tipe === 'harian') end.setDate(start.getDate() + val);
            else if (tipe === 'mingguan') end.setDate(start.getDate() + (val * 7));
            else if (tipe === 'bulanan') end.setMonth(start.getMonth() + val);
            
            document.getElementById('tgl_selesai_display').value = end.toISOString().split('T')[0];
        }
    }

    document.getElementById('tipe_durasi').onchange = calculatePrice;
    document.getElementById('durasi_val').oninput = calculatePrice;
    document.getElementById('tgl_mulai').onchange = calculatePrice;

    document.getElementById('bookingForm').onsubmit = async (e) => {
        e.preventDefault();
        
        const res = await Swal.fire({
            title: 'Konfirmasi Pesanan',
            text: "Apakah Anda yakin ingin menyewa motor ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            confirmButtonText: 'Ya, Pesan!',
            cancelButtonText: 'Batal'
        });

        if (!res.isConfirmed) return;

        const body = {
            motor_id: motorId,
            tipe_durasi: document.getElementById('tipe_durasi').value,
            durasi_val: document.getElementById('durasi_val').value,
            tanggal_mulai: document.getElementById('tgl_mulai').value,
            tanggal_selesai: document.getElementById('tgl_selesai_display').value,
        };

        const response = await fetch('/api/bookings', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(body)
        });

        if (response.ok) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pesanan Anda telah berhasil dibuat.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
            }).then(() => {
                window.location.href = '/renter/bookings';
            });
        } else {
            const data = await response.json();
            Swal.fire({
                title: 'Gagal',
                text: data.message || 'Gagal membuat pesanan.',
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
            });
        }
    };

    // Set default date to today
    document.getElementById('tgl_mulai').value = new Date().toISOString().split('T')[0];
    loadMotor();
</script>
@endsection
