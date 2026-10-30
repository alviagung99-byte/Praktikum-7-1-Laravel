@extends('layouts.dashboard')

@section('title', 'Manajemen Mata Pelajaran')
@section('sidebar-subtitle', 'Admin Panel')
@section('page-title', 'Manajemen Mata Pelajaran')
@section('user-initials', 'TU')

@section('sidebar-menu')
    <a href="{{ route('staff-tu.dashboard') }}">🏠 Beranda</a>
    <a href="{{ route('staff-tu.pengguna') }}">👥 Manajemen Pengguna</a>
    <a href="{{ route('staff-tu.kelas') }}">🏫 Manajemen Kelas</a>
    <a href="{{ route('staff-tu.mapel') }}" class="active">📚 Manajemen Mapel</a>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="font-size:1.6rem;font-weight:800;">📚 Manajemen Mata Pelajaran</h2>
        <p style="color:#64748b;margin-top:.25rem;">Kelola mata pelajaran yang diajarkan di sekolah.</p>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:.8rem 1rem;margin-bottom:1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="margin-bottom:1.5rem;">
    <h3 style="margin-bottom:1rem;">📁 Tambah Mata Pelajaran</h3>
    <form action="{{ route('staff-tu.mapel.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1.5fr;gap:1rem;align-items:end;">
            <div class="form-group">
                <label class="form-label" for="kode_mapel">Kode Mapel</label>
                <input type="text" id="kode_mapel" name="kode_mapel" class="form-control" placeholder="contoh: MTK" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama_mapel">Nama Mata Pelajaran</label>
                <input type="text" id="nama_mapel" name="nama_mapel" class="form-control" placeholder="contoh: Matematika" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="file">Upload File</label>
                <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">⬆️ Simpan Mapel</button>
        </div>
    </form>
</div>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:1rem;">
        <h3 style="margin:0;">Daftar Mata Pelajaran</h3>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mapel as $m)
                <tr>
                    <td><code style="font-size:.85rem;background:#f1f5f9;padding:.25rem .6rem;border-radius:5px;font-weight:700;">{{ $m['kode'] ?? '' }}</code></td>
                    <td><strong>{{ $m['nama'] ?? '' }}</strong></td>
                    <td>
                        <a href="#" style="color:#2563eb;font-weight:600;font-size:.85rem;margin-right:.75rem;">✏️ Edit</a>
                        <a href="#" style="color:#dc2626;font-weight:600;font-size:.85rem;" onclick="return confirm('Hapus mapel ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
