@extends('layouts.app')

@section('title', 'Login')

@section('styles')
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }
    .auth-card {
        width: 100%;
        max-width: 400px;
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
    .auth-header p {
        color: var(--text-muted);
    }
    .form-group {
        margin-bottom: 1.5rem;
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
    .alert {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: none;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        border: 1px solid transparent;
        position: relative;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .alert i {
        font-size: 1.125rem;
    }
    .alert-close {
        position: absolute;
        right: 1rem;
        cursor: pointer;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    .alert-close:hover {
        opacity: 1;
    }
@endsection

@section('content')
<div class="auth-container">
    <div class="card auth-card glass">
        <div class="auth-header">
            <h1>Selamat Datang</h1>
            <p>Masuk ke akun MotoRent Anda</p>
        </div>
        <div id="loginAlert" class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <span id="alertMessage">Email atau password salah.</span>
            <i class="fas fa-times alert-close" onclick="this.parentElement.style.display='none'"></i>
        </div>
        <form id="loginForm">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="nama@email.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary auth-btn">Masuk</button>
        </form>
        <div class="auth-footer">
            Belum punya akun? <a href="/register">Daftar di sini</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const loginAlert = document.getElementById('loginAlert');
            const alertMessage = document.getElementById('alertMessage');
            loginAlert.style.display = 'none';

            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();
            if (response.ok) {
                localStorage.setItem('token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));
                
                // Redirect based on role
                if (data.user.role === 'admin') window.location.href = '/admin';
                else if (data.user.role === 'owner') window.location.href = '/owner';
                else window.location.href = '/renter';
            } else {
                alertMessage.innerText = (data.error === 'Unauthorized' || !data.error) ? 'Email atau password salah.' : data.error;
                loginAlert.style.display = 'flex';
            }
        } catch (error) {
            console.error('Error:', error);
            const loginAlert = document.getElementById('loginAlert');
            const alertMessage = document.getElementById('alertMessage');
            alertMessage.innerText = 'Terjadi kesalahan pada server';
            loginAlert.style.display = 'flex';
        }
    });
</script>
@endsection
