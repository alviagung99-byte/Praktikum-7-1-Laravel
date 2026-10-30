<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SmartEduController extends Controller
{
    // ----------------------------------------------------------
    // Landing & Auth
    // ----------------------------------------------------------

    public function landing()
    {
        $fitur = [
            ['ikon' => '📚', 'judul' => 'Materi Inklusif',    'deskripsi' => 'Konten pembelajaran yang disesuaikan dengan kebutuhan setiap siswa.'],
            ['ikon' => '✏️', 'judul' => 'Latihan Interaktif', 'deskripsi' => 'Soal latihan terstruktur untuk mengukur pemahaman siswa.'],
            ['ikon' => '📊', 'judul' => 'Pantau Perkembangan', 'deskripsi' => 'Orang tua dapat memantau progres belajar anak secara real-time.'],
            ['ikon' => '🏫', 'judul' => 'Manajemen Sekolah',  'deskripsi' => 'Staff TU mengelola kelas, guru, dan siswa dengan mudah.'],
        ];

        $roles = ['Staff TU', 'Guru', 'Siswa', 'Orang Tua'];

        return view('smartedu.landing', compact('fitur', 'roles'));
    }

    public function loginForm()
    {
        if (session('smartedu_user')) {
            $role = session('smartedu_user.role');
            $redirectMap = [
                'staff-tu' => 'staff-tu.dashboard',
                'guru' => 'guru.dashboard',
                'siswa' => 'siswa.dashboard',
                'orang-tua' => 'orang-tua.dashboard',
            ];

            if (isset($redirectMap[$role])) {
                return redirect()->route($redirectMap[$role]);
            }
        }

        return view('smartedu.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($validated['email']));
        $users = $this->allPengguna();

        foreach ($users as $user) {
            if (strtolower($user['email']) !== $email) {
                continue;
            }

            if (($user['password'] ?? '') !== $validated['password']) {
                continue;
            }

            $role = strtolower(str_replace(' ', '-', $user['role']));
            $redirectMap = [
                'staff-tu' => 'staff-tu.dashboard',
                'guru' => 'guru.dashboard',
                'siswa' => 'siswa.dashboard',
                'orang-tua' => 'orang-tua.dashboard',
            ];

            session([
                'smartedu_user' => [
                    'email' => $email,
                    'name' => $user['nama'],
                    'role' => $role,
                ],
            ]);

            return redirect()->route($redirectMap[$role] ?? 'home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        session()->forget('smartedu_user');

        return redirect()->route('login');
    }

    // ----------------------------------------------------------
    // Dashboard
    // ----------------------------------------------------------

    public function staffDashboard()
    {
        $statistik = [
            'total_siswa' => 128,
            'guru_aktif'  => 14,
            'kelas'       => 8,
        ];

        $aktivitas = [
            ['nama' => 'Budi Santoso',    'role' => 'Siswa',    'aksi' => 'Menyelesaikan materi Matematika Bab 3',      'waktu' => '2 menit yang lalu'],
            ['nama' => 'Ibu Dewi',        'role' => 'Guru',     'aksi' => 'Menambahkan soal latihan IPA',               'waktu' => '15 menit yang lalu'],
            ['nama' => 'Ahmad Fauzi',     'role' => 'Siswa',    'aksi' => 'Mendaftar ke kelas VII-A',                   'waktu' => '1 jam yang lalu'],
            ['nama' => 'Pak Rahmat',      'role' => 'Staff TU', 'aksi' => 'Memperbarui data kelas VIII-B',             'waktu' => '2 jam yang lalu'],
        ];

        return view('smartedu.staff-tu.dashboard', compact('statistik', 'aktivitas'));
    }

    public function guruDashboard()
    {
        $data = [
            'nama_guru'    => 'Ibu Dewi Lestari',
            'mata_pelajaran' => 'Ilmu Pengetahuan Alam (IPA)',
            'total_siswa'  => 32,
            'total_materi' => 10,
            'total_soal'   => 25,
        ];

        return view('smartedu.guru.dashboard', compact('data'));
    }

    public function siswaDashboard()
    {
        $data = [
            'nama_siswa'     => 'Andi Prasetyo',
            'kelas'          => 'VII-A',
            'progress'       => 65,
            'materi_selesai' => 5,
            'total_materi'   => 8,
        ];

        $materi_hari_ini = [
            ['kode' => 'MTK-01', 'judul' => 'Bilangan Bulat',      'mapel' => 'Matematika', 'status' => 'Selesai'],
            ['kode' => 'IPA-02', 'judul' => 'Ekosistem Hutan',     'mapel' => 'IPA',        'status' => 'Belum'],
            ['kode' => 'BIN-03', 'judul' => 'Menulis Puisi',       'mapel' => 'Bhs. Indonesia', 'status' => 'Belum'],
        ];

        return view('smartedu.siswa.dashboard', compact('data', 'materi_hari_ini'));
    }

    public function orangTuaDashboard()
    {
        $data = [
            'nama_ortu'      => 'Bapak Prasetyo',
            'nama_anak'      => 'Andi Prasetyo',
            'kelas'          => 'VII-A',
            'progress'       => 65,
            'materi_selesai' => 5,
            'total_materi'   => 8,
        ];

        $progres_mingguan = [
            ['hari' => 'Sen', 'aktivitas' => 3],
            ['hari' => 'Sel', 'aktivitas' => 5],
            ['hari' => 'Rab', 'aktivitas' => 2],
            ['hari' => 'Kam', 'aktivitas' => 4],
            ['hari' => 'Jum', 'aktivitas' => 1],
        ];

        $tugas_pending = [
            ['judul' => 'Soal Latihan Ekosistem',  'mapel' => 'IPA'],
            ['judul' => 'Menulis Puisi Bebas',      'mapel' => 'Bhs. Indonesia'],
        ];

        return view('smartedu.orang-tua.dashboard', compact('data', 'progres_mingguan', 'tugas_pending'));
    }

    // ----------------------------------------------------------
    // Staff TU — CRUD Pages
    // ----------------------------------------------------------

    public function pengguna()
    {
        $pengguna = $this->allPengguna();

        return view('smartedu.staff-tu.pengguna', compact('pengguna'));
    }

    public function createPengguna()
    {
        return view('smartedu.staff-tu.pengguna-create');
    }

    public function storePengguna(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:Staff TU,Guru,Siswa,Orang Tua'],
        ]);

        $pengguna = $this->allPengguna();

        $duplicateIndex = collect($pengguna)->search(fn ($item) => strtolower($item['email']) === strtolower($validated['email']) || $item['id'] === $validated['id']);

        if ($duplicateIndex !== false) {
            return back()->withErrors(['email' => 'ID atau email sudah terdaftar.'])->withInput();
        }

        $pengguna[] = [
            'id' => $validated['id'],
            'nama' => $validated['nama'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
            'role' => $validated['role'],
        ];

        session(['smartedu_pengguna' => $pengguna]);

        return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editPengguna($id)
    {
        $pengguna = $this->allPengguna();
        $user = collect($pengguna)->firstWhere('id', $id);

        if (!$user) {
            abort(404);
        }

        return view('smartedu.staff-tu.pengguna-edit', compact('user'));
    }

    public function updatePengguna(Request $request, $id)
    {
        $validated = $request->validate([
            'id' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:Staff TU,Guru,Siswa,Orang Tua'],
        ]);

        $pengguna = $this->allPengguna();
        foreach ($pengguna as $index => $item) {
            if ($item['id'] === $id) {
                $duplicate = collect($pengguna)->contains(fn ($value, $key) => $key !== $index && ($value['email'] === strtolower($validated['email']) || $value['id'] === $validated['id']));

                if ($duplicate) {
                    return back()->withErrors(['email' => 'ID atau email sudah digunakan oleh pengguna lain.'])->withInput();
                }

                $pengguna[$index] = [
                    'id' => $validated['id'],
                    'nama' => $validated['nama'],
                    'email' => strtolower($validated['email']),
                    'password' => $validated['password'],
                    'role' => $validated['role'],
                ];

                session(['smartedu_pengguna' => $pengguna]);
                return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil diperbarui.');
            }
        }

        abort(404);
    }

    public function destroyPengguna($id)
    {
        $pengguna = $this->allPengguna();
        $filtered = collect($pengguna)->reject(fn ($item) => $item['id'] === $id)->values()->all();

        session(['smartedu_pengguna' => $filtered]);

        return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil dihapus.');
    }

    protected function allPengguna(): array
    {
        $pengguna = session('smartedu_pengguna');

        if (!is_array($pengguna) || empty($pengguna)) {
            return $this->defaultPengguna();
        }

        $defaultPengguna = $this->defaultPengguna();
        $existingEmails = collect($pengguna)->map(fn ($user) => strtolower((string) ($user['email'] ?? '')))->filter()->all();

        foreach ($defaultPengguna as $user) {
            if (!in_array(strtolower((string) ($user['email'] ?? '')), $existingEmails, true)) {
                $pengguna[] = $user;
                $existingEmails[] = strtolower((string) $user['email']);
            }
        }

        return array_values($pengguna);
    }

    protected function defaultPengguna(): array
    {
        return [
            ['id' => 'TU-001', 'nama' => 'Admin Sekolah', 'email' => 'admin@smartedu.id', 'password' => 'password', 'role' => 'Staff TU'],
            ['id' => 'GR-0012', 'nama' => 'Ibu Dewi', 'email' => 'dewi@smartedu.id', 'password' => 'password', 'role' => 'Guru'],
            ['id' => '20240001', 'nama' => 'Andi Prasetyo', 'email' => 'andi@smartedu.id', 'password' => 'password', 'role' => 'Siswa'],
            ['id' => 'OT-20240001', 'nama' => 'Bapak Prasetyo', 'email' => 'prasetyo@gmail.com', 'password' => 'password', 'role' => 'Orang Tua'],
        ];
    }

    public function kelas()
    {
        $kelas = [
            ['kode' => 'VII-A',  'nama' => 'Kelas VII A'],
            ['kode' => 'VII-B',  'nama' => 'Kelas VII B'],
            ['kode' => 'VIII-A', 'nama' => 'Kelas VIII A'],
            ['kode' => 'VIII-B', 'nama' => 'Kelas VIII B'],
            ['kode' => 'IX-A',   'nama' => 'Kelas IX A'],
            ['kode' => 'IX-B',   'nama' => 'Kelas IX B'],
        ];

        return view('smartedu.staff-tu.kelas', compact('kelas'));
    }

    public function mapel()
    {
        $mapel = DB::table('mapel_uploads')
            ->select('id', 'kode_mapel as kode', 'nama_mapel as nama', 'nama_asli', 'path_file')
            ->whereNotNull('kode_mapel')
            ->whereNotNull('nama_mapel')
            ->orderBy('kode_mapel')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'nama_file' => $item->nama_asli,
                'path_file' => $item->path_file,
            ])
            ->all();

        if (empty($mapel)) {
            $mapel = [
                ['kode' => 'MTK', 'nama' => 'Matematika'],
                ['kode' => 'IPA', 'nama' => 'Ilmu Pengetahuan Alam'],
                ['kode' => 'IPS', 'nama' => 'Ilmu Pengetahuan Sosial'],
                ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia'],
                ['kode' => 'BIG', 'nama' => 'Bahasa Inggris'],
                ['kode' => 'PKN', 'nama' => 'Pendidikan Kewarganegaraan'],
            ];
        }

        return view('smartedu.staff-tu.mapel', compact('mapel'));
    }

    public function uploadMapelFile(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20'],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png', 'max:2048'],
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/mapel', $filename, 'public');

        DB::table('mapel_uploads')->insert([
            'kode_mapel' => strtoupper(trim($validated['kode_mapel'])),
            'nama_mapel' => trim($validated['nama_mapel']),
            'nama_file' => $filename,
            'nama_asli' => $file->getClientOriginalName(),
            'path_file' => Storage::url($path),
            'mime_type' => $file->getMimeType(),
            'ukuran' => $file->getSize(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('staff-tu.mapel')->with('success', 'Mapel berhasil ditambahkan: ' . trim($validated['nama_mapel']));
    }

    // ----------------------------------------------------------
    // Siswa — Materi & Latihan
    // ----------------------------------------------------------

    public function materi()
    {
        $materi = [
            ['kode' => 'MTK-01', 'judul' => 'Bilangan Bulat',       'mapel' => 'Matematika',        'status' => 'Selesai', 'halaman' => 5],
            ['kode' => 'IPA-02', 'judul' => 'Ekosistem Hutan',      'mapel' => 'IPA',               'status' => 'Belum',   'halaman' => 8],
            ['kode' => 'BIN-03', 'judul' => 'Menulis Puisi',        'mapel' => 'Bhs. Indonesia',    'status' => 'Belum',   'halaman' => 4],
            ['kode' => 'IPS-04', 'judul' => 'Keragaman Budaya',     'mapel' => 'IPS',               'status' => 'Selesai', 'halaman' => 6],
            ['kode' => 'BIG-05', 'judul' => 'Simple Present Tense', 'mapel' => 'Bhs. Inggris',      'status' => 'Belum',   'halaman' => 7],
        ];

        return view('smartedu.siswa.materi', compact('materi'));
    }

    public function detailMateri($kodeMateri)
    {
        $contoh = [
            'MTK-01' => ['judul' => 'Bilangan Bulat',       'mapel' => 'Matematika',     'isi' => 'Bilangan bulat adalah bilangan yang terdiri dari bilangan negatif, nol, dan bilangan positif. Contoh: ..., -3, -2, -1, 0, 1, 2, 3, ...'],
            'IPA-02' => ['judul' => 'Ekosistem Hutan',      'mapel' => 'IPA',            'isi' => 'Ekosistem hutan adalah suatu sistem ekologi yang terbentuk oleh hubungan timbal balik antara makhluk hidup dengan lingkungannya di kawasan hutan.'],
            'BIN-03' => ['judul' => 'Menulis Puisi',        'mapel' => 'Bhs. Indonesia', 'isi' => 'Puisi adalah karya sastra yang mengungkapkan pikiran dan perasaan penyair secara imajinatif dengan mengutamakan keindahan bahasa.'],
        ];

        $materi = $contoh[$kodeMateri] ?? [
            'judul' => 'Materi ' . $kodeMateri,
            'mapel' => 'Umum',
            'isi'   => 'Konten materi ini akan segera tersedia.',
        ];

        return view('smartedu.siswa.detail-materi', compact('materi', 'kodeMateri'));
    }

    public function latihan()
    {
        $latihan = [
            ['kode' => 'MTK-01', 'judul' => 'Soal Bilangan Bulat',       'mapel' => 'Matematika',     'total_soal' => 10, 'status' => 'Selesai'],
            ['kode' => 'IPA-02', 'judul' => 'Soal Ekosistem',            'mapel' => 'IPA',            'total_soal' => 8,  'status' => 'Belum'],
            ['kode' => 'BIN-03', 'judul' => 'Soal Puisi dan Sastra',     'mapel' => 'Bhs. Indonesia', 'total_soal' => 5,  'status' => 'Belum'],
        ];

        return view('smartedu.siswa.latihan', compact('latihan'));
    }

    public function detailLatihan($kodeMateri)
    {
        $contoh = [
            'MTK-01' => [
                'judul' => 'Soal Latihan Bilangan Bulat',
                'mapel' => 'Matematika',
                'soal'  => [
                    ['no' => 1, 'pertanyaan' => 'Berapakah hasil dari -5 + 3?',    'pilihan' => ['-8', '-2', '2', '8'],       'jawaban' => 'B'],
                    ['no' => 2, 'pertanyaan' => 'Manakah yang merupakan bilangan bulat negatif?', 'pilihan' => ['5', '0', '-3', '2.5'], 'jawaban' => 'C'],
                    ['no' => 3, 'pertanyaan' => 'Hasil dari (-4) × (-3) adalah?',  'pilihan' => ['-12', '-7', '7', '12'],     'jawaban' => 'D'],
                ],
            ],
        ];

        $latihan = $contoh[$kodeMateri] ?? [
            'judul' => 'Soal Latihan ' . $kodeMateri,
            'mapel' => 'Umum',
            'soal'  => [],
        ];

        return view('smartedu.siswa.detail-latihan', compact('latihan', 'kodeMateri'));
    }
}
