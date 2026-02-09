@extends('layouts.app')

@section('title', 'Edit Motor')

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
    <h1>Edit Unit Motor</h1>
    <p class="text-muted">Perbarui informasi unit motor Anda</p>
</div>

<div class="card">
    <form id="editMotorForm">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem">
            <div class="form-group">
                <label class="form-label">Merk Motor</label>
                <input type="text" id="merk" class="form-control" required>
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
            <label class="form-label">Nomor Plat (Terkunci)</label>
            <input type="text" id="no_plat" class="form-control" disabled style="background:#f8fafc">
        </div>

        <h3 style="margin-top:2rem; margin-bottom:1rem">Dokumen & Foto</h3>
        <p class="text-muted" style="font-size:0.875rem; margin-bottom:1rem">Kosongkan jika tidak ingin mengubah file.</p>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem">
            <div class="form-group">
                <label class="form-label">Foto Unit Motor</label>
                <div id="photoPreviewContainer" style="display:none; margin-bottom:1rem">
                    <img id="photoPreview" src="" style="width:100%; max-height:300px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                </div>
                <div id="current_photo" style="margin-bottom:0.5rem; font-size:0.875rem"></div>
                <input type="file" id="photo" class="form-control" accept="image/*" onchange="previewImage(this, 'photoPreviewContainer', 'photoPreview')">
            </div>
            <div class="form-group">
                <label class="form-label">Foto STNK</label>
                <div id="stnkPreviewContainer" style="display:none; margin-bottom:1rem">
                    <img id="stnkPreview" src="" style="width:100%; max-height:300px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                </div>
                <div id="current_stnk" style="margin-bottom:0.5rem; font-size:0.875rem"></div>
                <input type="file" id="dokumen_kepemilikan" class="form-control" accept=".pdf,image/*" onchange="previewImage(this, 'stnkPreviewContainer', 'stnkPreview')">
            </div>
        </div>
        <div class="form-group" style="margin-top:1rem">
            <label class="form-label">Surat/Dokumen Lainnya (Opsional)</label>
            <div id="current_surat" style="margin-bottom:0.5rem; font-size:0.875rem"></div>
            <input type="file" id="surat_lainnya" class="form-control" accept=".pdf,image/*">
        </div>

        <h3 style="margin-top:2rem; margin-bottom:1rem">Pengaturan Tarif Rental</h3>
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem">
            <div class="form-group">
                <label class="form-label">Tarif Harian (Rp)</label>
                <input type="number" id="harian" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tarif Mingguan (Rp)</label>
                <input type="number" id="mingguan" class="form-control" readonly style="background:#f8fafc">
            </div>
            <div class="form-group">
                <label class="form-label">Tarif Bulanan (Rp)</label>
                <input type="number" id="bulanan" class="form-control" readonly style="background:#f8fafc">
            </div>
        </div>

        <div style="margin-top: 3rem; display:flex; gap:1rem">
            <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center">
                Simpan Perubahan
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
    const motorId = window.location.pathname.split('/')[3];

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
            // Hidden preview if not image file (like PDF)
            container.style.display = 'none';
        }
    }

    // Auto-calculate rates
    document.getElementById('harian').addEventListener('keydown', function(e) {
        if (['e', 'E', '+', '-', '.'].includes(e.key)) {
            e.preventDefault();
        }
    });

    document.getElementById('harian').addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
        const harian = parseFloat(this.value) || 0;
        document.getElementById('mingguan').value = Math.round(harian * 7);
        document.getElementById('bulanan').value = Math.round(harian * 30);
    });

    async function fetchMotor() {
        const res = await fetch(`/api/owner/motors/${motorId}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const m = await res.json();
        
        document.getElementById('merk').value = m.merk;
        document.getElementById('tipe_cc').value = m.tipe_cc;
        document.getElementById('no_plat').value = m.no_plat;
        document.getElementById('harian').value = m.tarif.tarif_harian;
        document.getElementById('mingguan').value = m.tarif.tarif_mingguan;
        document.getElementById('bulanan').value = m.tarif.tarif_bulanan;

        if (m.photo) {
            document.getElementById('photoPreview').src = `/storage/${m.photo}`;
            document.getElementById('photoPreviewContainer').style.display = 'block';
            document.getElementById('current_photo').innerHTML = `<span class="text-success small">Foto saat ini terpasang</span>`;
        }
        if (m.dokumen_kepemilikan) {
            // Only show preview if it's an image
            if (m.dokumen_kepemilikan.match(/\.(jpg|jpeg|png|gif)$/i)) {
                document.getElementById('stnkPreview').src = `/storage/${m.dokumen_kepemilikan}`;
                document.getElementById('stnkPreviewContainer').style.display = 'block';
            }
            document.getElementById('current_stnk').innerHTML = `<span class="text-success small">STNK sudah ada: ${m.dokumen_kepemilikan.split('/').pop()}</span>`;
        }
        if (m.surat_lainnya) {
            document.getElementById('current_surat').innerHTML = `<span class="text-success small">Dokumen sudah ada: ${m.surat_lainnya.split('/').pop()}</span>`;
        }
    }

    document.getElementById('editMotorForm').onsubmit = async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('_method', 'PUT'); // For Laravel to handle PUT with multipart/form-data
        formData.append('merk', document.getElementById('merk').value);
        formData.append('tipe_cc', document.getElementById('tipe_cc').value);
        formData.append('tarif_harian', document.getElementById('harian').value);
        formData.append('tarif_mingguan', document.getElementById('mingguan').value);
        formData.append('tarif_bulanan', document.getElementById('bulanan').value);

        const photoFile = document.getElementById('photo').files[0];
        if (photoFile) formData.append('photo', photoFile);

        const stnkFile = document.getElementById('dokumen_kepemilikan').files[0];
        if (stnkFile) formData.append('dokumen_kepemilikan', stnkFile);

        const suratLainFile = document.getElementById('surat_lainnya').files[0];
        if (suratLainFile) formData.append('surat_lainnya', suratLainFile);

        const response = await fetch(`/api/owner/motors/${motorId}`, {
            method: 'POST', // Use POST with _method=PUT because native PUT doesn't support multipart
            headers: { 
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        if (response.ok) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Perubahan berhasil disimpan!',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
            }).then(() => {
                window.location.href = '/owner/motors';
            });
        } else {
            Swal.fire({
                title: 'Gagal',
                text: 'Gagal memperbarui motor',
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
            });
        }
    };

    fetchMotor();
</script>
<style>
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border); outline: none; }
</style>
@endsection
