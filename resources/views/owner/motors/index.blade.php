@extends('layouts.app')

@section('title', 'Daftar Motor')

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
<div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem">
    <div>
        <h1>Motor Saya</h1>
        <p class="text-muted">Kelola armada motor Anda secara mendetail</p>
    </div>
    <a href="/owner/motors/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Motor Baru
    </a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Motor</th>
                    <th>No. Plat</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Harga Harian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="motorTableBody">
                <!-- Data sets here -->
            </tbody>
        </table>
    </div>
    <div class="table-footer" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border)">
        <p class="text-muted" id="paginationInfo" style="font-size: 0.875rem">Menampilkan 0 dari 0 data</p>
        <div id="paginationButtons" class="pagination-container">
            <!-- Buttons here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    let allMotors = [];
    let currentPage = 1;
    const itemsPerPage = 4;

    if (!token) {
        window.location.href = '/login';
    }

    async function fetchData() {
        try {
            const res = await fetch('/api/owner/motors', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            if (res.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/login';
                return;
            }

            allMotors = await res.json();
            if (!res.ok) throw new Error('Gagal mengambil data');
            renderTable();
        } catch (error) {
            console.error(error);
            document.getElementById('motorTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding:2rem; color:var(--danger)">Gagal memuat data motor. Silakan refresh halaman.</td></tr>';
        }
    }

    function renderTable() {
        const body = document.getElementById('motorTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageItems = allMotors.slice(start, end);

        body.innerHTML = pageItems.length ? pageItems.map(m => `
            <tr>
                <td><strong>${m.merk}</strong></td>
                <td><code style="background:#f1f5f9; padding:0.25rem 0.5rem; border-radius:4px">${m.no_plat}</code></td>
                <td>${m.tipe_cc} CC</td>
                <td><span class="status-pill status-${m.status}">${m.status}</span></td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(m.tarif.tarif_harian)}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" onclick="toggleDropdown(event, ${m.id})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="dropdown-${m.id}" class="dropdown-menu">
                            <a href="/owner/motors/${m.id}" class="dropdown-item"><i class="fas fa-eye"></i> Lihat Detail</a>
                            <a href="/owner/motors/${m.id}/edit" class="dropdown-item"><i class="fas fa-edit"></i> Edit Unit</a>
                            <button onclick="deleteMotor(${m.id})" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Hapus Motor</button>
                        </div>
                    </div>
                </td>
            </tr>
        `).join('') : '<tr><td colspan="6" style="text-align:center; padding:2rem">Belum ada motor yang terdaftar</td></tr>';

        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(allMotors.length / itemsPerPage);
        const container = document.getElementById('paginationButtons');
        const info = document.getElementById('paginationInfo');
        
        info.innerText = `Menampilkan ${Math.min(currentPage * itemsPerPage, allMotors.length)} dari ${allMotors.length} motor`;

        if (allMotors.length <= itemsPerPage) {
            container.innerHTML = '';
            return;
        }

        let buttons = `
            <button class="btn btn-sm btn-secondary" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;

        for (let i = 1; i <= totalPages; i++) {
            buttons += `
                <button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-secondary'}" onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        }

        buttons += `
            <button class="btn btn-sm btn-secondary" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;

        container.innerHTML = `<div style="display:flex; gap:0.5rem">${buttons}</div>`;
    }

    function changePage(page) {
        if (page < 1 || page > Math.ceil(allMotors.length / itemsPerPage)) return;
        currentPage = page;
        renderTable();
    }

    async function deleteMotor(id) {
        const result = await Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Motor yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--danger)',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;
        
        const res = await fetch(`/api/owner/motors/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        
        if (res.ok) {
            Swal.fire({
                title: 'Terhapus!',
                text: 'Motor berhasil dihapus.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
            });
            fetchData();
        } else {
            Swal.fire({
                title: 'Gagal',
                text: 'Gagal menghapus motor',
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
            });
        }
    }

    function toggleDropdown(event, id) {
        event.stopPropagation();
        const menus = document.querySelectorAll('.dropdown-menu');
        menus.forEach(m => {
            if (m.id !== `dropdown-${id}`) m.classList.remove('show');
        });
        document.getElementById(`dropdown-${id}`).classList.toggle('show');
    }

    window.onclick = () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
    };

    fetchData();
</script>
<style>
    .status-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-available { background: #dcfce7; color: #16a34a; }
    .status-rented { background: #dbeafe; color: #2563eb; }
    
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 1rem; background: #f8fafc; border-bottom: 1px solid var(--border); color: var(--text-muted); font-weight: 600; }
    td { padding: 1rem; border-bottom: 1px solid var(--border); }
    .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.875rem; }
    .btn-danger { background: var(--danger); color: white; }
    .btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    
    /* Dropdown Styles */
    .dropdown { position: relative; display: inline-block; }
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        min-width: 160px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        z-index: 100;
        border: 1px solid #f1f5f9;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    .dropdown-menu.show { display: block; animation: slideIn 0.2s ease; }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.875rem;
        width: 100%;
        border: none;
        background: none;
        text-align: left;
        cursor: pointer;
        transition: background 0.2s;
    }
    .dropdown-item:hover { background: #f8fafc; color: var(--primary); }
    .dropdown-item.text-danger:hover { background: #fff1f2; color: var(--danger); }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Pagination Styles */
    .pagination-container .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    .pagination-container .btn-primary {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .pagination-container .btn-secondary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection
