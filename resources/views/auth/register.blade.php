@extends('layouts.app')

@section('title', 'Register')

@section('styles')
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 2rem 0;
    }
    .auth-card {
        width: 100%;
        max-width: 500px;
        padding: 2.5rem;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.875rem;
        margin-bottom: 0.5rem;
        color: var(--text-main);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--text-main);
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        transition: all 0.3s;
        outline: none;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .auth-btn {
        width: 100%;
        justify-content: center;
        margin-top: 1rem;
    }
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }
    .auth-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }
    #guestLinks, #authLinks {
        display: none !important;
    }
@endsection

@section('content')
<div class="auth-container">
    <div class="card auth-card glass">
        <div class="auth-header">
            <h1>Buat Akun</h1>
            <p>Bergabunglah dengan komunitas rental motor kami</p>
        </div>
        <form id="registerForm">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="nama@email.com" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" id="no_tlpn" class="form-control" placeholder="0812..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Daftar Sebagai</label>
                    <select id="role" class="form-control" required>
                        <option value="renter">Penyewa</option>
                        <option value="owner">Pemilik Motor</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary auth-btn">Daftar Sekarang</button>
        </form>
        <div class="auth-footer">
            Sudah punya akun? <a href="/login">Masuk sekarang</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = {
            nama: document.getElementById('nama').value,
            email: document.getElementById('email').value,
            no_tlpn: document.getElementById('no_tlpn').value,
            role: document.getElementById('role').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
        };

        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            if (response.ok) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Registrasi berhasil! Silakan login untuk melanjutkan.',
                    icon: 'success',
                    confirmButtonColor: 'var(--primary)',
                }).then(() => {
                    window.location.href = '/login';
                });
            } else {
                let errorMessage = 'Registrasi gagal';
                if (typeof data === 'object') {
                    errorMessage = Object.values(data).flat().join('<br>');
                }
                Swal.fire({
                    title: 'Gagal',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonColor: 'var(--primary)',
                });
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        }
    });
</script>
@endsection
