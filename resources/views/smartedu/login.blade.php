@extends('layouts.main')

@section('title', 'Masuk')

@push('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 130px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #1e40af 0%, #0ea5e9 60%, #38bdf8 100%);
    }

    .login-box {
        background: #fff;
        border-radius: 22px;
        padding: 2.75rem 2.25rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 16px 48px rgba(0,0,0,.18);
    }

    .login-logo { text-align: center; margin-bottom: 2rem; }

    .login-logo .icon {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #1e40af, #0ea5e9);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto .875rem;
    }

    .login-logo h1 { font-size: 1.4rem; font-weight: 800; color: #1e293b; }
    .login-logo p  { font-size: .85rem; color: #64748b; margin-top: .25rem; }

    .role-info {
        background: #eff6ff;
        border-radius: 10px;
        padding: .875rem 1rem;
        margin-bottom: 1.5rem;
        font-size: .82rem;
        color: #1e40af;
    }
    .role-info p  { margin-bottom: .35rem; font-weight: 700; }
    .role-info ul { padding-left: 1.25rem; }
    .role-info li { margin-bottom: .25rem; }

    .btn-login {
        width: 100%;
        padding: .9rem;
        background: linear-gradient(135deg, #1e40af, #0ea5e9);
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: opacity .2s;
        margin-top: .5rem;
    }
    .btn-login:hover { opacity: .9; }

    .demo-roles {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .5rem;
        margin-top: 1.5rem;
    }

    .demo-role-form {
        margin: 0;
    }

    .demo-role-btn {
        width: 100%;
        padding: .6rem .75rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        color: #475569;
        text-decoration: none;
        transition: background .15s;
        display: block;
    }

    .demo-role-btn:hover { background: #f1f5f9; text-decoration: none; color: #1e40af; }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo">
            <div class="icon">🎓</div>
            <h1>SmartEduInclusion</h1>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <div class="role-info">
            <p>Demo Route — Pilih dashboard:</p>
            <ul>
                <li>Staff TU: mengelola data sekolah</li>
                <li>Guru: unggah materi & soal</li>
                <li>Siswa: belajar & latihan</li>
                <li>Orang Tua: pantau perkembangan</li>
            </ul>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">📧 Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="contoh@sekolah.id" value="{{ old('email', 'admin@smartedu.id') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">🔒 Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" value="password">
            </div>
            <button type="submit" class="btn-login">🚀 Masuk</button>
        </form>

        <div style="margin-top:1.5rem; text-align:center; font-size:.82rem; color:#94a3b8; font-weight:600;">
            atau akses langsung ke:
        </div>

        @php
            $demoAccounts = [
                ['label' => 'Staff TU', 'icon' => '🏢', 'email' => 'admin@smartedu.id', 'password' => 'password'],
                ['label' => 'Guru', 'icon' => '👨‍🏫', 'email' => 'dewi@smartedu.id', 'password' => 'password'],
                ['label' => 'Siswa', 'icon' => '🎒', 'email' => 'andi@smartedu.id', 'password' => 'password'],
                ['label' => 'Orang Tua', 'icon' => '👨‍👩‍👦', 'email' => 'prasetyo@gmail.com', 'password' => 'password'],
            ];
        @endphp

        <div class="demo-roles">
            @foreach ($demoAccounts as $account)
                <form action="{{ route('login.submit') }}" method="POST" class="demo-role-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ $account['email'] }}">
                    <input type="hidden" name="password" value="{{ $account['password'] }}">
                    <button type="submit" class="demo-role-btn">{{ $account['icon'] }} {{ $account['label'] }}</button>
                </form>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:1.25rem; font-size:.88rem; color:#64748b;">
            <a href="{{ route('home') }}" style="color:#2563eb; font-weight:600;">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
