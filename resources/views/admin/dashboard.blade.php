@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    .admin-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    .chart-container {
        height: 300px;
        margin-top: 1rem;
    }
    .verify-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }
    .verify-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }
    .verify-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .verify-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
    .verify-content {
        padding: 1.25rem;
    }
    .verify-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
    .verify-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }
@endsection

@section('sidebar')
    <div data-has-sidebar></div>
    <a href="/admin" class="sidebar-item active">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="/admin/motors" class="sidebar-item">
        <i class="fas fa-motorcycle"></i> Verifikasi Motor
    </a>
    <a href="/admin/bookings" class="sidebar-item">
        <i class="fas fa-calendar-check"></i> Pesanan
    </a>
    <a href="/admin/reports" class="sidebar-item">
        <i class="fas fa-chart-line"></i> Laporan
    </a>
    <a href="/admin/settings" class="sidebar-item">
        <i class="fas fa-cog"></i> Pengaturan
    </a>
@endsection

@section('content')
<div style="margin-bottom: 2rem">
    <h1>Panel Administrasi</h1>
    <p class="text-muted">Kelola sistem dan verifikasi transaksi</p>
</div>

<div class="stat-grid" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.5rem; margin-bottom:2rem">
    <div class="card stat-card">
        <div>
            <p class="text-muted">Total Pendapatan Admin</p>
            <h2 id="totalAdminRevenue">Rp 0</h2>
        </div>
    </div>
</div>

<div class="admin-grid">
    <div class="card" style="grid-column: 1 / -1">
        <h3>Motor Perlu Verifikasi</h3>
        <div id="verifyMotorGrid" class="verify-grid">
            <!-- Cards set here -->
        </div>
    </div>

    <div class="card">
        <h3>Grafik Pendapatan</h3>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');

    async function loadAdminData() {
        try {
            // Load Revenue Report
            const resRevenue = await fetch('/api/admin/reports/revenue', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (resRevenue.ok) {
                const revenueData = await resRevenue.json();
                document.getElementById('totalAdminRevenue').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(revenueData.total_admin_revenue || 0)}`;
                if (revenueData.reports) initChart(revenueData.reports);
            }
        } catch (e) { console.error('Revenue load failed', e); }

        // Load Pending Motors
        fetchPendingMotors();
    }

    async function fetchPendingMotors() {
        const res = await fetch('/api/admin/motors/pending', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const motors = await res.json();
        
        const grid = document.getElementById('verifyMotorGrid');
        grid.innerHTML = motors.length ? motors.map(m => `
            <div class="verify-card">
                <div class="verify-img">
                    ${m.photo ? `<img src="/storage/${m.photo}" style="width:100%; height:100%; object-fit:cover">` : '<i class="fas fa-motorcycle fa-3x"></i>'}
                </div>
                <div class="verify-content">
                    <span class="verify-badge">${m.tipe_cc} CC</span>
                    <h4 style="margin: 0.25rem 0">${m.merk}</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 0.75rem">
                        <i class="fas fa-user-circle"></i> ${m.pemilik.nama}
                    </p>
                    <div style="background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 8px; font-family: monospace; font-size: 0.9rem; color: #475569; display: inline-block">
                        ${m.no_plat}
                    </div>
                    
                    <div class="verify-actions">
                        <div style="display:flex; gap:0.25rem; flex:1">
                            ${m.dokumen_kepemilikan ? `<a href="/storage/${m.dokumen_kepemilikan}" target="_blank" class="btn btn-sm" style="background:#f1f5f9; flex:1; justify-content:center" title="STNK"><i class="fas fa-file-pdf"></i></a>` : ''}
                            ${m.surat_lainnya ? `<a href="/storage/${m.surat_lainnya}" target="_blank" class="btn btn-sm" style="background:#f1f5f9; flex:1; justify-content:center" title="Surat Lain"><i class="fas fa-file-alt"></i></a>` : ''}
                        </div>
                        <button class="btn btn-primary btn-sm" style="flex: 2; justify-content:center" onclick="verifyMotor(${m.id})">Verifikasi</button>
                    </div>
                </div>
            </div>
        `).join('') : '<div style="grid-column: 1/-1; text-align:center; padding:3rem; color:#94a3b8"><i class="fas fa-check-circle fa-3x" style="margin-bottom:1rem"></i><p>Semua motor telah terverifikasi</p></div>';
    }

    async function verifyMotor(id) {
        const result = await Swal.fire({
            title: 'Verifikasi Motor?',
            text: "Pastikan semua dokumen sudah sesuai.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        const res = await fetch(`/api/admin/motors/${id}/verify`, {
            method: 'PATCH',
            headers: { 'Authorization': `Bearer ${token}` }
        });

        if (res.ok) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Motor telah diverifikasi dan siap disewa.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
            });
            fetchPendingMotors();
        } else {
            Swal.fire({
                title: 'Gagal',
                text: 'Terjadi kesalahan saat verifikasi.',
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
            });
        }
    }

    function initChart(reports) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        // Clear old chart if exists (Chart.js doesn't auto-clear)
        if (window.myChart) window.myChart.destroy();
        
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: reports.slice(-7).map(r => r.tanggal.split('T')[0]),
                datasets: [{
                    label: 'Pendapatan Admin',
                    data: reports.slice(-7).map(r => r.bagi_hasil_admin),
                    backgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    loadAdminData();
</script>
@endsection
