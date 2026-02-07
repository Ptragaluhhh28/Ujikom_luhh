@extends('layouts.app')

@section('title', 'Tambah Motor')

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
    <h1>Tambah Unit Motor</h1>
    <p class="text-muted">Daftarkan unit motor baru Anda ke sistem</p>
</div>

<div class="card">
    <form id="addMotorForm">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem">
            <div class="form-group">
                <label class="form-label">Merk Motor</label>
                <input type="text" id="merk" class="form-control" placeholder="Contoh: Honda PCX 160" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tipe (CC)</label>
                <select id="tipe_cc" class="form-control" required>
                    <option value="100">100 CC</option>
                    <option value="125">125 CC</option>
                    <option value="150">150 CC</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-top:1.5rem">
            <label class="form-label">Nomor Plat</label>
            <input type="text" id="no_plat" class="form-control" placeholder="Z 1234 ABC" required>
        </div>

        <h3 style="margin-top:2rem; margin-bottom:1rem">Dokumen & Foto</h3>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem">
            <div class="form-group">
                <label class="form-label">Foto Unit Motor</label>
                <div id="photoPreviewContainer" style="display:none; margin-bottom:1rem">
                    <img id="photoPreview" src="" style="width:100%; max-height:300px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                </div>
                <input type="file" id="photo" class="form-control" accept="image/*" onchange="previewImage(this, 'photoPreviewContainer', 'photoPreview')">
                <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
            </div>
            <div class="form-group">
                <label class="form-label">Foto STNK</label>
                <div id="stnkPreviewContainer" style="display:none; margin-bottom:1rem">
                    <img id="stnkPreview" src="" style="width:100%; max-height:300px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                </div>
                <input type="file" id="dokumen_kepemilikan" class="form-control" accept=".pdf,image/*" onchange="previewImage(this, 'stnkPreviewContainer', 'stnkPreview')">
                <small class="text-muted">Format: PDF, JPG, PNG. Maks 2MB</small>
            </div>
        </div>
        <div class="form-group" style="margin-top:1rem">
            <label class="form-label">Surat/Dokumen Lainnya (Opsional)</label>
            <input type="file" id="surat_lainnya" class="form-control" accept=".pdf,image/*">
            <small class="text-muted">Misal: Bukti Pajak, Asuransi, dll.</small>
        </div>

        <h3 style="margin-top:2rem; margin-bottom:1rem">Pengaturan Tarif Rental</h3>
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem">
            <div class="form-group">
                <label class="form-label">Tarif Harian (Rp)</label>
                <input type="number" id="harian" class="form-control" placeholder="75000" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tarif Mingguan (Rp)</label>
                <input type="number" id="mingguan" class="form-control" placeholder="450000" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tarif Bulanan (Rp)</label>
                <input type="number" id="bulanan" class="form-control" placeholder="1500000" required>
            </div>
        </div>

        <div style="margin-top: 3rem; display:flex; gap:1rem">
            <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center">
                Daftarkan Motor
            </button>
            <a href="/owner/motors" class="btn btn-secondary" style="flex:1; justify-content:center; background:#e2e8f0">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }

    function previewImage(input, containerId, imageId) {
        const file = input.files[0];
        const container = document.getElementById(containerId);
        const img = document.getElementById(imageId);
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            container.style.display = 'none';
        }
    }

    document.getElementById('addMotorForm').onsubmit = async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('merk', document.getElementById('merk').value);
        formData.append('tipe_cc', document.getElementById('tipe_cc').value);
        formData.append('no_plat', document.getElementById('no_plat').value);
        formData.append('tarif_harian', document.getElementById('harian').value);
        formData.append('tarif_mingguan', document.getElementById('mingguan').value);
        formData.append('tarif_bulanan', document.getElementById('bulanan').value);
        
        const photoFile = document.getElementById('photo').files[0];
        if (photoFile) formData.append('photo', photoFile);

        const stnkFile = document.getElementById('dokumen_kepemilikan').files[0];
        if (stnkFile) formData.append('dokumen_kepemilikan', stnkFile);

        const suratLainFile = document.getElementById('surat_lainnya').files[0];
        if (suratLainFile) formData.append('surat_lainnya', suratLainFile);

        const response = await fetch('/api/owner/motors', {
            method: 'POST',
            headers: { 
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        if (response.ok) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Motor berhasil didaftarkan! Menunggu verifikasi admin.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
            }).then(() => {
                window.location.href = '/owner/motors';
            });
        } else {
            const data = await response.json();
            let errorMessage = 'Gagal mendaftarkan motor';
            
            if (data.no_plat) errorMessage = 'Nomor plat sudah terdaftar!';
            else if (typeof data === 'object') {
                // Collect other validation errors
                errorMessage = Object.values(data).flat().join('\n');
            }

            Swal.fire({
                title: 'Gagal',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
            });
        }
    };
</script>
<style>
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border); outline: none; }
</style>
@endsection
