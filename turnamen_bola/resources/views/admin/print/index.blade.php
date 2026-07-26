<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Cetak Dokumen - Panitia Pusat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        
        .player-card {
            width: 100%;
            max-width: 320px;
            height: 190px;
            border: 2px solid #0d6efd;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f1f4f9 100%);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        .player-card-header {
            background-color: #0d6efd;
            color: white;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        @media print {
            body * { visibility: hidden; }
            .printable-area, .printable-area * { visibility: visible; }
            .printable-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUPER ADMIN -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3 no-print">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <i class="bi bi-trophy-fill text-warning fs-3"></i>
                <h6 class="text-white fw-bold mt-2 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Disdikpora Grassroot Kebumen</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Pusat Cetak Dokumen</a></li>
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
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom no-print">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-printer-fill text-primary me-2"></i> Pusat Cetak Dokumen & Manifes</h2>
                    <p class="text-muted mb-0">Cetak Buku Tim (Manifes Resmi) dan Kartu Identitas Pemain per Sekolah Sepak Bola (SSB).</p>
                </div>
            </div>

            <!-- PANEL KONTROL -->
            <form action="{{ route('admin.print.index') }}" method="GET" class="card border-0 shadow-sm p-4 mb-4 no-print">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-sliders text-secondary me-2"></i> Filter & Pilih Dokumen Cetak</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Pilih Sekolah Sepak Bola (SSB)</label>
                        <select name="team_id" class="form-select" onchange="this.form.submit()">
                            @foreach($teams as $t)
                                <option value="{{ $t->id }}" {{ $selectedTeamId == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->ageCategory?->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Jenis Dokumen</label>
                        <select name="type" class="form-select" id="jenisDokumen" onchange="this.form.submit()">
                            <option value="buku-tim" {{ $documentType == 'buku-tim' ? 'selected' : '' }}>Buku Tim / Manifes Squad Resmi</option>
                            <option value="kartu-pemain" {{ $documentType == 'kartu-pemain' ? 'selected' : '' }}>Kartu Identitas Pemain (ID Card)</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100 fw-bold shadow-sm" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i> Cetak Dokumen Sekarang
                        </button>
                    </div>
                </div>
            </form>

            <!-- AREA CETAK -->
            <div class="card border-0 shadow-sm p-5 printable-area bg-white">
                @if($team)
                    @if($documentType == 'buku-tim')
                        <!-- 1. BUKU TIM / MANIFES SQUAD -->
                        <div id="previewBukuTim">
                            <div class="text-center border-bottom pb-3 mb-4">
                                <h5 class="fw-bold text-uppercase mb-1">{{ $event->organizer ?? 'Dinas Pendidikan, Kepemudaan, dan Olahraga' }}</h5>
                                <h4 class="fw-bold text-uppercase text-primary mb-1">{{ $event->name ?? 'Piala Disdikpora Grassroot Regional Kebumen 2026' }}</h4>
                                <small class="text-muted">{{ $event->location ?? 'Stadion Chandradimuka Kebumen' }} • Resmi Terverifikasi Panitia Pusat</small>
                            </div>

                            <div class="row mb-4 align-items-center bg-light p-3 rounded">
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-shield-fill-check text-primary me-2"></i> {{ strtoupper($team->name) }}</h4>
                                    <p class="text-muted mb-0 small">Kelompok Usia: <strong>{{ $team->ageCategory?->name }}</strong> | Asal Kecamatan: <strong>{{ $team->district ?? 'Kebumen' }}</strong></p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="badge bg-success p-2">Status: Valid ({{ $team->players->count() }} Pemain Terdaftar)</span>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-text me-1"></i> Manifes Resmi Susunan Pemain & Official</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-4">
                                    <thead class="table-dark text-center">
                                        <tr style="font-size: 0.9rem;">
                                            <th width="5%">No</th>
                                            <th width="20%">No. Registrasi</th>
                                            <th width="25%">Nama Lengkap Pemain</th>
                                            <th width="10%">No. Punggung</th>
                                            <th width="15%">Posisi</th>
                                            <th width="15%">Tahun Lahir</th>
                                            <th width="10%">Paraf Panitia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($team->players as $pIndex => $p)
                                            <tr>
                                                <td class="text-center">{{ $pIndex + 1 }}</td>
                                                <td><code>{{ $p->registration_number ?? '-' }}</code></td>
                                                <td class="fw-bold">{{ $p->name }}</td>
                                                <td class="text-center">{{ $p->jersey_number ?? '-' }}</td>
                                                <td class="text-center">{{ $p->position ?? 'Pemain' }}</td>
                                                <td class="text-center">{{ $p->birth_year }}</td>
                                                <td></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center py-3">Belum ada pemain diinput.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-4 text-center">
                                    <p class="mb-5">Manajer Tim,<br><br><strong>{{ $team->manager_name ?? 'Manajer SSB' }}</strong></p>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4 text-center">
                                    <p class="mb-5">Mengetahui,<br>Ketua Panitia Pusat Disdikpora<br><br><strong>Drs. H. Slamet, M.Pd</strong></p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- 2. KARTU PEMAIN -->
                        <div id="previewKartuPemain">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark">Cetak ID Card Pemain Resmi</h4>
                                <p class="text-muted small">ID Card Resmi Turnamen {{ $team->name }} ({{ $team->ageCategory?->name }})</p>
                            </div>
                            <div class="row g-4 justify-content-center">
                                @forelse($team->players as $p)
                                    <div class="col-md-6 mb-3">
                                        <div class="player-card">
                                            <div class="player-card-header d-flex justify-content-between align-items-center">
                                                <span>ID CARD PEMAIN RESMI</span>
                                                <span>{{ $team->ageCategory?->name }}</span>
                                            </div>
                                            <div class="p-3 d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-25 rounded me-3 d-flex align-items-center justify-content-center overflow-hidden" style="width: 70px; height: 90px;">
                                                    @if($p->foto_url)
                                                        <img src="{{ $p->foto_url }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="bi bi-person-fill fs-2 text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $p->name }}</h6>
                                                    <small class="text-muted d-block">No. Punggung: <strong>{{ $p->jersey_number }}</strong></small>
                                                    <small class="text-muted d-block">SSB: {{ $team->name }}</small>
                                                    <small class="text-muted d-block">No. Reg: <code>{{ $p->registration_number }}</code></small>
                                                    <span class="badge bg-success mt-1" style="font-size: 0.65rem;">Lolos Verifikasi Panitia</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted">Belum ada pemain untuk dicetak kartu.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">Silakan pilih SSB peserta untuk mencetak dokumen.</div>
                @endif
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
