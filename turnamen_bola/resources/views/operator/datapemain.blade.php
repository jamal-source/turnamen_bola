<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input & Kelola Data Pemain - Operator SSB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #94a3b8; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2563eb; }
        .doc-badge { font-size: 0.72rem; }
    </style>
</head>
<body>
 <!-- Rofamustofa -->
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR OPERATOR SSB -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="text-center py-3 border-bottom border-slate-700 mb-3">
                <i class="bi bi-shield-shaded text-primary fs-3"></i>
                <h6 class="text-white fw-bold mt-2 mb-0">{{ $operator->name }}</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Operator SSB</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.cetak-dokumen') }}"><i class="bi bi-printer-fill me-2"></i> Cetak Manifes & ID Card</a></li>
                <hr class="border-slate-700 my-3">
                <li class="nav-item">
                    <form action="{{ route('operator.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal menyimpan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i> Kelola Squad Pemain</h2>
                    <p class="text-muted mb-0">Input data pemain, unggah berkas (Akta/KK), dan lihat status verifikasi dari Panitia Pusat.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPemain">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pemain Baru
                    </button>
                </div>
            </div>

            <!-- TABEL DAFTAR PEMAIN -->
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-nested me-2 text-secondary"></i> Daftar Pemain Terdaftar ({{ $players->count() }} Orang)</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="4%">#</th>
                                <th width="13%">No. Registrasi</th>
                                <th width="22%">Nama Lengkap Pemain</th>
                                <th width="14%">NIK & Tgl Lahir</th>
                                <th width="8%">Punggung</th>
                                <th width="10%">Posisi</th>
                                <th width="14%">Dokumen</th>
                                <th width="10%">Status</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($players as $index => $p)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td><code>{{ $p->registration_number ?? '-' }}</code></td>
                                    <td class="fw-bold text-dark">{{ $p->name }}</td>
                                    <td>
                                        <small class="d-block text-muted">NIK: {{ $p->nik }}</small>
                                        <small class="d-block">Tgl: <strong>{{ $p->birth_date ? $p->birth_date->format('d/m/Y') : '-' }}</strong></small>
                                    </td>
                                    <td class="fw-bold text-primary text-center">{{ $p->jersey_number }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $p->position }}</span></td>
                                    <td>
                                        @php
                                            $hasAkta = $p->documents->firstWhere('type', 'akta');
                                            $hasKk = $p->documents->firstWhere('type', 'kk');
                                            $hasFoto = $p->documents->firstWhere('type', 'foto');
                                        @endphp
                                        <span class="badge doc-badge {{ $hasAkta ? 'bg-success' : 'bg-secondary' }} me-1" title="Akta Kelahiran">Akta</span>
                                        <span class="badge doc-badge {{ $hasKk ? 'bg-success' : 'bg-secondary' }} me-1" title="Kartu Keluarga">KK</span>
                                        <span class="badge doc-badge {{ $hasFoto ? 'bg-success' : 'bg-secondary' }}" title="Pas Foto">Foto</span>
                                    </td>
                                    <td>{!! $p->verification?->status_badge ?? '<span class="badge bg-warning text-dark">Pending</span>' !!}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary me-1" 
                                            title="Edit"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditPemain{{ $p->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('operator.datapemain.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pemain ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada pemain diinput. Klik "Tambah Pemain Baru" untuk mendaftarkan squad.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- MODAL TAMBAH PEMAIN -->
<div class="modal fade" id="modalTambahPemain" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('operator.datapemain.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Pemain Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Usia (KU)</label>
                            <select name="age_category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} (Maks. Kelahiran {{ $cat->max_birth_year }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Lengkap Pemain</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama sesuai Akta / KK" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NIK Pemain (16 Digit)</label>
                            <input type="text" name="nik" class="form-control" placeholder="3305xxxxxxxxxxxx" required maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="Kebumen">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nomor Punggung</label>
                            <input type="number" name="jersey_number" class="form-control" min="1" max="99" placeholder="1 - 99" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Posisi Pemain</label>
                            <select name="position" class="form-select" required>
                                <option value="Kiper">Kiper</option>
                                <option value="Bek">Bek</option>
                                <option value="Gelandang">Gelandang</option>
                                <option value="Penyerang" selected>Penyerang</option>
                            </select>
                        </div>
                        <hr class="my-3">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-earmark-arrow-up text-primary me-1"></i> Unggah Dokumen Verifikasi (PDF/JPG):</h6>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Scan Akta Kelahiran</label>
                            <input type="file" name="file_akta" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Scan Kartu Keluarga (KK)</label>
                            <input type="file" name="file_kk" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pas Foto Pemain</label>
                            <input type="file" name="file_foto" class="form-control form-control-sm" accept=".jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Data Pemain</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALS EDIT PEMAIN -->
@foreach($players as $p)
<div class="modal fade" id="modalEditPemain{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('operator.datapemain.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Data: {{ $p->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Usia (KU)</label>
                            <select name="age_category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $p->age_category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} (Maks. Kelahiran {{ $cat->max_birth_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Lengkap Pemain</label>
                            <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NIK Pemain</label>
                            <input type="text" name="nik" class="form-control" value="{{ $p->nik }}" required maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ $p->birth_date?->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="{{ $p->birth_place }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nomor Punggung</label>
                            <input type="number" name="jersey_number" class="form-control" value="{{ $p->jersey_number }}" min="1" max="99" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Posisi Pemain</label>
                            <select name="position" class="form-select" required>
                                @foreach(['Kiper','Bek','Gelandang','Penyerang'] as $pos)
                                    <option value="{{ $pos }}" {{ $p->position == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr class="my-3">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-file-earmark-arrow-up text-warning me-1"></i> Update Dokumen (kosongkan jika tidak ingin mengubah):</h6>
                        @php
                            $hasAkta = $p->documents->firstWhere('type', 'akta');
                            $hasKk   = $p->documents->firstWhere('type', 'kk');
                            $hasFoto = $p->documents->firstWhere('type', 'foto');
                        @endphp
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">
                                Akta Kelahiran 
                                @if($hasAkta) <span class="badge bg-success ms-1">Sudah Ada</span> @else <span class="badge bg-secondary ms-1">Belum</span> @endif
                            </label>
                            <input type="file" name="file_akta" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">
                                Kartu Keluarga (KK)
                                @if($hasKk) <span class="badge bg-success ms-1">Sudah Ada</span> @else <span class="badge bg-secondary ms-1">Belum</span> @endif
                            </label>
                            <input type="file" name="file_kk" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">
                                Pas Foto
                                @if($hasFoto) <span class="badge bg-success ms-1">Sudah Ada</span> @else <span class="badge bg-secondary ms-1">Belum</span> @endif
                            </label>
                            <input type="file" name="file_foto" class="form-control form-control-sm" accept=".jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>