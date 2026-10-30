@extends('layouts.dashboard')

@section('title', 'Edit Pengguna')
@section('sidebar-subtitle', 'Admin Panel')
@section('page-title', 'Edit Pengguna')
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
            <h2 style="font-size:1.6rem;font-weight:800;">✏️ Edit Pengguna</h2>
            <p style="color:#64748b;margin-top:.25rem;">Ubah data pengguna yang sudah ada.</p>
        </div>
        <a href="{{ route('staff-tu.pengguna') }}" class="btn btn-outline">← Kembali</a>
    </div>

    <div class="card">
        <form action="{{ route('staff-tu.pengguna.update', $user['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="id" class="form-label">ID / NIP / NISN</label>
                <input type="text" id="id" name="id" class="form-control" value="{{ old('id', $user['id']) }}" required>
            </div>

            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama', $user['nama']) }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user['email']) }}" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Login</label>
                <input type="password" id="password" name="password" class="form-control" value="{{ old('password', $user['password'] ?? '') }}" placeholder="Masukkan password baru" required>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-control" required>
                    @foreach (['Staff TU', 'Guru', 'Siswa', 'Orang Tua'] as $role)
                        <option value="{{ $role }}" {{ old('role', $user['role']) == $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
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

            <button type="submit" class="btn btn-primary" style="width:100%;">💾 Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
