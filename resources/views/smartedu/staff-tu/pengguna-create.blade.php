@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')
@section('sidebar-subtitle', 'Admin Panel')
@section('page-title', 'Tambah Pengguna')
@section('user-initials', 'TU')

@section('sidebar-menu')
    <a href="{{ route('staff-tu.dashboard') }}">🏠 Beranda</a>
    <a href="{{ route('staff-tu.pengguna') }}" class="active">👥 Manajemen Pengguna</a>
    <a href="{{ route('staff-tu.kelas') }}">🏫 Manajemen Kelas</a>
    <a href="{{ route('staff-tu.mapel') }}">📚 Manajemen Mapel</a>
@endsection

@section('content')
<div style="max-width:700px; margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="font-size:1.6rem;font-weight:800;">➕ Tambah Pengguna</h2>
            <p style="color:#64748b;margin-top:.25rem;">Masukkan data pengguna baru untuk Staff TU, Guru, Siswa, atau Orang Tua.</p>
        </div>
        <a href="{{ route('staff-tu.pengguna') }}" class="btn btn-outline">← Kembali</a>
    </div>

    <div class="card">
        <form action="{{ route('staff-tu.pengguna.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="id" class="form-label">ID / NIP / NISN</label>
                <input type="text" id="id" name="id" class="form-control" value="{{ old('id') }}" placeholder="contoh: TU-005 atau 20240012" required>
            </div>

            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@smartedu.id" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Login</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password untuk login" required>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="Staff TU" {{ old('role') == 'Staff TU' ? 'selected' : '' }}>Staff TU</option>
                    <option value="Guru" {{ old('role') == 'Guru' ? 'selected' : '' }}>Guru</option>
                    <option value="Siswa" {{ old('role') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="Orang Tua" {{ old('role') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                </select>
            </div>

            @if ($errors->any())
                <div style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:.85rem;">
                    <ul style="margin:0; padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary" style="width:100%;">💾 Simpan Pengguna</button>
        </form>
    </div>
</div>
@endsection
