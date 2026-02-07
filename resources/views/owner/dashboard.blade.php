@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('styles')
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .icon-blue { background: #dbeafe; color: #2563eb; }
    .icon-green { background: #dcfce7; color: #16a34a; }

    .table-container {
        margin-top: 2rem;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        color: var(--text-muted);
        font-weight: 600;
    }
    td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }
    .status-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-available { background: #dcfce7; color: #16a34a; }
    .status-rented { background: #dbeafe; color: #2563eb; }
@endsection

@section('sidebar')
    <div data-has-sidebar></div>
    <a href="/owner" class="sidebar-item active">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="/owner/motors" class="sidebar-item">
        <i class="fas fa-motorcycle"></i> Motor Saya
    </a>
    <a href="/owner/revenue" class="sidebar-item">
        <i class="fas fa-wallet"></i> Pendapatan
    </a>
@endsection

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Dashboard Pemilik</h1>
        <p class="text-muted">Ringkasan armada dan pendapatan Anda</p>
    </div>
    <div style="display:flex; gap:1rem">
        <a href="/owner/motors/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Motor
        </a>
    </div>
</div>

<div class="stat-grid">
    <div class="card stat-card">
        <div class="stat-icon icon-blue">
            <i class="fas fa-motorcycle"></i>
        </div>
        <div>
            <p class="text-muted">Total Motor</p>
            <h2 id="totalMotors">0</h2>
            <a href="/owner/motors" style="font-size:0.875rem; text-decoration:none; color:var(--primary)">Kelola Semua &rarr;</a>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon icon-green">
            <i class="fas fa-wallet"></i>
        </div>
        <div>
            <p class="text-muted">Total Pendapatan</p>
            <h2 id="totalRevenue">Rp 0</h2>
            <a href="/owner/revenue" style="font-size:0.875rem; text-decoration:none; color:var(--success)">Lihat Detail &rarr;</a>
        </div>
    </div>
</div>

<div class="grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem">
            <h3>Motor Terbaru</h3>
            <a href="/owner/motors" class="nav-link" style="font-size:0.875rem">Lihat Semua</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Motor</th>
                        <th>No. Plat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="motorTableBody">
                    <!-- Data sets here -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>Bantuan & Tips</h3>
        <div style="margin-top:1.5rem; display:flex; flex-direction:column; gap:1rem">
            <div style="padding:1rem; background:#f8fafc; border-radius:12px">
                <h4 style="margin-bottom:0.5rem">Verifikasi Motor</h4>
                <p style="font-size:0.875rem; color:var(--text-muted)">Setiap motor baru harus diverifikasi admin sebelum bisa disewa.</p>
            </div>
            <div style="padding:1rem; background:#f8fafc; border-radius:12px">
                <h4 style="margin-bottom:0.5rem">Pendapatan</h4>
                <p style="font-size:0.875rem; color:var(--text-muted)">Anda akan menerima 90% dari total biaya rental per transaksi.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }

    async function fetchData() {
        try {
            const resMotor = await fetch('/api/owner/motors', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            if (resMotor.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/login';
                return;
            }

            const motors = await resMotor.json();
            document.getElementById('totalMotors').innerText = motors.length || 0;
            
            const body = document.getElementById('motorTableBody');
            body.innerHTML = motors.slice(0, 5).map(m => `
                <tr>
                    <td><strong>${m.merk}</strong></td>
                    <td>${m.no_plat}</td>
                    <td><span class="status-pill status-${m.status}">${m.status}</span></td>
                </tr>
            `).join('') || '<tr><td colspan="3" style="text-align:center">Belum ada motor</td></tr>';

            const resRevenue = await fetch('/api/owner/revenue', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (resRevenue.ok) {
                const revenue = await resRevenue.json();
                const total = revenue.reduce((acc, curr) => acc + parseFloat(curr.bagi_hasil_pemilik), 0);
                document.getElementById('totalRevenue').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
            }
        } catch (error) {
            console.error(error);
        }
    }

    fetchData();
</script>
@endsection
