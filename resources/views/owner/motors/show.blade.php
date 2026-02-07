@extends('layouts.app')

@section('title', 'Detail Motor')

@section('sidebar')
    <div data-has-sidebar></div>
    <a href="/owner" class="sidebar-item">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="/owner/motors" class="sidebar-item active">
        <i class="fas fa-motorcycle"></i> Motor Saya
    </a>
    <a href="/owner/revenue" class="sidebar-item">
        <i class="fas fa-wallet"></i> Pendapatan
    </a>
@endsection

@section('content')
<div style="margin-bottom: 2rem">
    <a href="/owner/motors" class="nav-link" style="display:inline-flex; align-items:center; gap:0.5rem; margin-bottom:1rem">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center">
        <h1>Detail Motor</h1>
        <div style="display:flex; gap:1rem">
            <a href="/owner/motors/{{ request()->id }}/edit" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Unit
            </a>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:2rem">
    <div class="card">
        <div id="motorInfo">
            <!-- Loading -->
            <p>Memuat informasi...</p>
        </div>
    </div>

    <div class="card">
        <h3>Statistik & Status</h3>
        <div id="statusInfo" style="margin-top:1.5rem">
            <!-- Loading -->
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
    const motorId = window.location.pathname.split('/')[3];

    async function fetchMotor() {
        const res = await fetch(`/api/owner/motors/${motorId}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const m = await res.json();
        
        document.getElementById('motorInfo').innerHTML = `
            ${m.photo ? `<img src="/storage/${m.photo}" style="width:100%; height:200px; object-fit:cover; border-radius:12px; margin-bottom:1.5rem" alt="Motor">` : ''}
            <h2 style="margin-bottom:1.5rem">${m.merk}</h2>
            <div style="display:flex; flex-direction:column; gap:1rem">
                <div class="detail-item">
                    <span class="label">Nomor Plat</span>
                    <span class="value">${m.no_plat}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Kapasitas Mesin</span>
                    <span class="value">${m.tipe_cc} CC</span>
                </div>
                <hr style="border:0; border-top:1px solid var(--border); margin:1rem 0">
                <h3>Tarif Rental</h3>
                <div class="detail-item">
                    <span class="label">Harian</span>
                    <span class="value">Rp ${new Intl.NumberFormat('id-ID').format(m.tarif.tarif_harian)}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Mingguan</span>
                    <span class="value">Rp ${new Intl.NumberFormat('id-ID').format(m.tarif.tarif_mingguan)}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Bulanan</span>
                    <span class="value">Rp ${new Intl.NumberFormat('id-ID').format(m.tarif.tarif_bulanan)}</span>
                </div>
                <hr style="border:0; border-top:1px solid var(--border); margin:1rem 0">
                <h3>Dokumen Unit</h3>
                <div style="display:flex; flex-direction:column; gap:0.5rem">
                    ${m.dokumen_kepemilikan ? `
                        <a href="/storage/${m.dokumen_kepemilikan}" target="_blank" class="nav-link" style="color:var(--primary)">
                            <i class="fas fa-file-pdf"></i> Lihat STNK
                        </a>
                    ` : '<span class="text-muted">STNK Belum diunggah</span>'}
                    ${m.surat_lainnya ? `
                        <a href="/storage/${m.surat_lainnya}" target="_blank" class="nav-link" style="color:var(--primary)">
                            <i class="fas fa-file-alt"></i> Lihat Dokumen Lainnya
                        </a>
                    ` : '<span class="text-muted">Dokumen lainnya tidak ada</span>'}
                </div>
            </div>
        `;

        document.getElementById('statusInfo').innerHTML = `
            <div style="display:flex; flex-direction:column; gap:1.5rem">
                <div class="card glass" style="padding:1rem; text-align:center">
                    <p class="text-muted">Status Saat Ini</p>
                    <span class="status-pill status-${m.status}" style="font-size:1.2rem; padding:0.5rem 1.5rem">${m.status.toUpperCase()}</span>
                </div>
                <p class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    ${m.status === 'pending' ? 'Motor Anda sedang dalam antrean verifikasi oleh Admin.' : 'Motor Anda siap untuk disewakan.'}
                </p>
            </div>
        `;
    }

    fetchMotor();
</script>
<style>
    .detail-item { display: flex; justify-content: space-between; align-items: center; }
    .label { color: var(--text-muted); font-weight: 500; }
    .value { font-weight: 600; color: var(--text-main); }
    .status-pill { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-available { background: #dcfce7; color: #16a34a; }
    .status-rented { background: #dbeafe; color: #2563eb; }
</style>
@endsection
