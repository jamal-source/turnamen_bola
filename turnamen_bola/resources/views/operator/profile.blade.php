<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Tim SSB - Operator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #94a3b8; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2563eb; }
    </style>
</head>
<body>

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
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
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

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-building text-primary me-2"></i> Profil Informasi Tim SSB</h2>
                    <p class="text-muted mb-0">Pengaturan data resmi Sekolah Sepak Bola (SSB) dan identitas manajer tim.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Formulir Profil SSB</h5>

                        <form action="{{ route('operator.profile.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Resmi Sekolah Sepak Bola (SSB)</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $team->name ?? $operator->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Kecamatan Domisili</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district', $team->district ?? $operator->district) }}" placeholder="Contoh: Kebumen / Sruweng" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Kategori Kelompok Usia</label>
                                <select name="age_category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ ($team->age_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} (Batas Kelahiran {{ $cat->max_birth_year }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Warna Jersey Utama & Cadangan</label>
                                <input type="text" name="jersey_color" class="form-control" value="{{ old('jersey_color', $team->jersey_color ?? 'Kuning-Hitam') }}" placeholder="Contoh: Kuning-Hitam" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Manajer / Penanggung Jawab Tim</label>
                                <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $team->manager_name ?? $operator->pic_name) }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Nomor WhatsApp Aktif</label>
                                <input type="text" name="manager_phone" class="form-control" value="{{ old('manager_phone', $team->manager_phone ?? $operator->phone) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Profil SSB
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>