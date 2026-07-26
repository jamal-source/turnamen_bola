<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tim & Verifikasi Pemain - Panitia Pusat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUPER ADMIN -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <i class="bi bi-trophy-fill text-warning fs-3"></i>
                <h6 class="text-white fw-bold mt-2 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Disdikpora Grassroot Kebumen</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Pusat Cetak Dokumen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.export.index') }}"><i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor Data</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear-fill me-2"></i> Pengaturan Event</a></li>
                <hr class="border-secondary my-3">
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST">
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
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-shield-shaded text-primary me-2"></i> Manajemen Tim & Verifikasi Pemain</h2>
                    <p class="text-muted mb-0">Pilih baris Sekolah Sepak Bola (SSB) untuk mengelola squad pemain dan verifikasi berkas.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form action="{{ route('admin.verify.auto_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Verifikasi Otomatis Dari Tgl Lahir
                        </button>
                    </form>
                </div>
            </div>

            <!-- FILTER & PENCARIAN -->
            <form action="{{ route('admin.teams.index') }}" method="GET" class="card border-0 shadow-sm p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Filter Kategori</label>
                        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori Usia</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} (Maks. {{ $cat->max_birth_year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">Pencarian Tim (SSB)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama SSB atau kecamatan...">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TABEL DIREKTORI TIM -->
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-nested text-secondary me-2"></i> Daftar Entitas SSB Peserta</h5>
                    <small class="text-muted">Klik "Kelola Squad & Verifikasi" untuk memeriksa pemain per tim</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="30%">Nama Sekolah Sepak Bola (SSB)</th>
                                <th width="15%">Kategori</th>
                                <th width="15%">Total Pemain</th>
                                <th width="25%">Status Verifikasi Squad</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teams as $index => $team)
                                @php
                                    $summary = $team->verification_summary;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $teams->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $team->name }}</span><br>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>Kec. {{ $team->district ?? '-' }}</small>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $team->ageCategory?->name ?? 'KU-12' }}</span></td>
                                    <td>{{ $summary['total'] }} Pemain</td>
                                    <td>
                                        <span class="badge bg-success">{{ $summary['approved'] }} Lolos</span>
                                        @if($summary['pending'] > 0)
                                            <span class="badge bg-warning text-dark">{{ $summary['pending'] }} Pending</span>
                                        @endif
                                        @if($summary['rejected'] > 0)
                                            <span class="badge bg-danger">{{ $summary['rejected'] }} Revisi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetailTim{{ $team->id }}">
                                            <i class="bi bi-check2-square me-1"></i> Squad
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data tim SSB.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $teams->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

<!-- ALL MODALS PLACED OUTSIDE THE TABLE -->
@foreach($teams as $team)
    <div class="modal fade" id="modalDetailTim{{ $team->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <div>
                        <h5 class="modal-title fw-bold"><i class="bi bi-shield-shaded me-2 text-warning"></i> Squad Pemain: {{ $team->name }}</h5>
                        <small class="text-light opacity-75">Pemeriksaan Dokumen & Auto-Verifikasi Umur Dari Tgl Lahir</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <strong>Manajer Tim:</strong> {{ $team->manager_name ?? '-' }} | <strong>Kontak:</strong> {{ $team->manager_phone ?? '-' }} | <strong>Jersey:</strong> {{ $team->jersey_color ?? '-' }}
                        </div>
                        <span class="badge bg-dark">Kategori: {{ $team->ageCategory?->name }}</span>
                    </div>

                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill text-primary me-1"></i> Daftar Pemain Dalam Squad:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th width="25%">Nama Pemain & No. Punggung</th>
                                    <th width="25%">NIK & Tanggal Lahir (Kalkulasi Usia)</th>
                                    <th width="15%">No. Registrasi</th>
                                    <th width="15%">Status Verifikasi</th>
                                    <th class="text-center" width="15%">Aksi Manual Panitia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($team->players as $pIndex => $p)
                                    @php
                                        $ageValid = $p->checkAgeValidity();
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $pIndex + 1 }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $p->name }}</span><br>
                                            <small class="text-muted">No. {{ $p->jersey_number ?? '-' }} • {{ $p->position ?? 'Pemain' }}</small>
                                        </td>
                                        <td>
                                            <small class="d-block text-muted">NIK: {{ $p->nik ?? '-' }}</small>
                                            <small class="d-block">Tgl Lahir: <strong>{{ $p->birth_date ? $p->birth_date->format('d/m/Y') : '-' }}</strong> (Thn {{ $p->birth_year }})</small>
                                            @if($ageValid)
                                                <span class="badge bg-success" style="font-size: 0.65rem;"><i class="bi bi-check-circle"></i> Umur Sesuai {{ $team->ageCategory?->name }}</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 0.65rem;"><i class="bi bi-exclamation-triangle"></i> Melebihi Usia</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $p->registration_number ?? '-' }}</code></td>
                                        <td>{!! $p->verification?->status_badge ?? '<span class="badge bg-warning text-dark">Pending</span>' !!}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <form action="{{ route('admin.verify.player', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-success py-1 px-2" title="Loloskan Verifikasi"><i class="bi bi-check-lg"></i> Lolos</button>
                                                </form>
                                                <form action="{{ route('admin.verify.player', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <input type="hidden" name="notes" value="Dokumen permohonan perlu revisi.">
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2" title="Tolak / Minta Revisi"><i class="bi bi-x-lg"></i> Tolak</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-3">Belum ada pemain diinput oleh operator SSB ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.print.index', ['team_id' => $team->id, 'type' => 'buku-tim']) }}" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Cetak Manifes Tim Ini</a>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
