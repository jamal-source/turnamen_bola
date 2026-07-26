<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator SSB - Piala Disdikpora Regional Kebumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #94a3b8; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2563eb; }
        .stat-card { border: none; border-radius: 10px; transition: transform 0.2s; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .stat-card:hover { transform: translateY(-3px); }
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
                <small class="text-muted" style="font-size: 0.75rem;">Operator SSB • Kec. {{ $operator->district ?? 'Kebumen' }}</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
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

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Operator {{ $operator->name }}</h2>
                    <p class="text-muted mb-0">Kelola pendaftaran squad pemain dan unggah berkas persyaratan turnamen.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('operator.datapemain') }}" class="btn btn-primary shadow-sm"><i class="bi bi-person-plus-fill me-1"></i> Tambah Pemain Baru</a>
                </div>
            </div>

            <!-- STATISTIK KARTU UTAMA -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-primary border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Pemain Diinput</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $playersCount }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-4">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-success border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Lolos Verifikasi</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $approvedCount }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-4">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Menunggu Verifikasi</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $pendingCount }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-4">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-danger border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Perlu Revisi / Ditolak</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $rejectedCount }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 p-3 rounded text-danger fs-4">
                                <i class="bi bi-exclamation-octagon-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JADWAL PERTANDINGAN TIM INI -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event-fill text-warning me-2"></i> Jadwal Pertandingan Tim Anda</h5>
                <div class="list-group list-group-flush">
                    @forelse($upcomingMatches as $m)
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-info text-dark mb-1">{{ $m->ageCategory?->name }} • {{ $m->round_label }}</span>
                                <h6 class="mb-1 fw-bold">{{ $m->homeTeam?->name }} vs {{ $m->awayTeam?->name }}</h6>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $m->location }} • {{ $m->match_date ? $m->match_date->format('d M Y') : '-' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-dark fs-6">{{ $m->match_time }} WIB</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada jadwal pertandingan yang dirilis untuk tim Anda.</div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>