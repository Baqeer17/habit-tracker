<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ibadah — {{ $user->name }} — {{ $periodLabel }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background: #f8fafc; color: #1e293b; margin: 0; padding: 0; }

        /* ── SCREEN STYLES ── */
        .report-container { max-width: 900px; margin: 0 auto; padding: 32px 24px 80px; }

        .report-header {
            background: linear-gradient(135deg, #0f766e, #0d9488);
            border-radius: 24px;
            padding: 32px;
            color: white;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
        }
        .report-header .brand { font-size: 28px; font-weight: 900; letter-spacing: -0.5px; }
        .report-header .brand span { opacity: 0.6; font-weight: 300; }
        .report-header .meta { text-align: right; }
        .report-header .meta .label { font-size: 10px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; opacity: 0.7; }
        .report-header .meta .value { font-size: 15px; font-weight: 700; }
        .report-header .period-badge {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            margin-top: 8px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 22px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .summary-card .icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px; }
        .summary-card .number { font-size: 32px; font-weight: 900; line-height: 1; }
        .summary-card .label-text { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 4px; }
        .summary-card .sub-text { font-size: 12px; font-weight: 600; margin-top: 6px; }

        .section-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 11px; font-weight: 900; letter-spacing: 0.18em;
            text-transform: uppercase; color: #94a3b8; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .section-title::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

        /* Prayer grid */
        .prayer-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .prayer-table th {
            text-align: center; padding: 8px 6px; font-size: 10px; font-weight: 800;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: #64748b; border-bottom: 2px solid #f1f5f9;
            background: #f8fafc;
        }
        .prayer-table th:first-child { text-align: left; padding-left: 12px; }
        .prayer-table td { text-align: center; padding: 7px 6px; border-bottom: 1px solid #f8fafc; }
        .prayer-table td:first-child { text-align: left; padding-left: 12px; font-weight: 700; font-size: 11px; color: #475569; }
        .prayer-table tr:hover td { background: #f8fafc; }
        .prayer-dot-done { display: inline-flex; width: 20px; height: 20px; border-radius: 50%; background: #0f766e; align-items: center; justify-content: center; }
        .prayer-dot-done i { color: white; font-size: 8px; }
        .prayer-dot-miss { display: inline-flex; width: 20px; height: 20px; border-radius: 50%; background: #fee2e2; align-items: center; justify-content: center; }
        .prayer-dot-miss i { color: #f87171; font-size: 8px; }

        /* Progress bar */
        .progress-bar-track { height: 8px; background: #e2e8f0; border-radius: 99px; overflow: hidden; margin-top: 8px; }
        .progress-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #0f766e, #0d9488); transition: width 0.5s; }

        /* Stat row */
        .stat-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .stat-row:last-child { border-bottom: none; }
        .stat-row .stat-label { font-size: 13px; font-weight: 700; color: #334155; }
        .stat-row .stat-value { font-size: 20px; font-weight: 900; color: #0f766e; }
        .stat-row .stat-sub { font-size: 11px; color: #94a3b8; }

        /* Print action bar */
        .action-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 16px 24px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid #e2e8f0;
            display: flex; justify-content: center; gap: 12px; z-index: 100;
        }
        .btn-print {
            background: #0f766e; color: white; border: none;
            padding: 13px 32px; border-radius: 14px;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800;
            letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 4px 15px rgba(15,118,110,0.35);
            transition: all 0.2s;
        }
        .btn-print:hover { background: #0c5e58; transform: translateY(-1px); }
        .btn-back {
            background: transparent; color: #64748b; border: 2px solid #e2e8f0;
            padding: 11px 28px; border-radius: 14px;
            font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800;
            letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer;
            display: flex; align-items: center; gap: 8px; transition: all 0.2s;
        }
        .btn-back:hover { border-color: #0f766e; color: #0f766e; }

        /* Quran log badges */
        .quran-badge {
            display: inline-block; background: #eff6ff; color: #1d4ed8;
            border-radius: 8px; padding: 2px 10px; font-size: 11px; font-weight: 700;
        }

        /* ── PRINT STYLES ── */
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .report-container { max-width: 100%; padding: 16px; }
            .action-bar { display: none !important; }
            .section-card, .summary-card { break-inside: avoid; box-shadow: none; }
            .report-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .prayer-table { font-size: 10px; }
            .prayer-table th, .prayer-table td { padding: 5px 4px; }
            h1, h2, h3 { break-after: avoid; }
        }

        @page { margin: 15mm; size: A4 portrait; }
    </style>
</head>
<body>

<div class="report-container">

    {{-- ═══════ HEADER ═══════ --}}
    <div class="report-header">
        <div>
            <div class="brand">MahabBa <span>Daily Routine Muslim</span></div>
            <div style="font-size:13px; opacity:0.8; margin-top:4px;">Laporan Perjalanan Spiritual</div>
            <div class="period-badge"><i class="fas fa-calendar-alt" style="margin-right:6px;"></i>{{ $periodLabel }}</div>
        </div>
        <div class="meta">
            <div class="label">Laporan untuk</div>
            <div class="value">{{ $user->name }}</div>
            <div style="font-size:11px; opacity:0.7; margin-top:2px;">{{ $user->email }}</div>
            <div style="font-size:10px; opacity:0.6; margin-top:6px;">
                <i class="fas fa-clock" style="margin-right:4px;"></i>
                Dicetak: {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>

    {{-- ═══════ RINGKASAN ═══════ --}}
    <div class="summary-grid">

        {{-- Completion Rate --}}
        <div class="summary-card">
            <div class="icon" style="background: rgba(15,118,110,0.1); color: #0f766e;">
                <i class="fas fa-mosque"></i>
            </div>
            <div class="number" style="color: #0f766e;">{{ $prayerStats['completion_rate'] }}%</div>
            <div class="label-text">Shalat Wajib</div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" style="width: {{ $prayerStats['completion_rate'] }}%"></div>
            </div>
            <div class="sub-text" style="color: #64748b; font-size: 11px; margin-top: 6px;">
                {{ $prayerStats['perfect_days'] }} hari sempurna dari {{ $prayerStats['total_days'] }} hari
            </div>
        </div>

        {{-- Tilawah --}}
        <div class="summary-card">
            <div class="icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="number" style="color: #6366f1;">{{ $quranStats['total_sessions'] }}</div>
            <div class="label-text">Sesi Tilawah</div>
            <div class="sub-text" style="color: #64748b; font-size: 11px; margin-top: 6px;">
                {{ $quranStats['active_days'] }} hari aktif
                @if($quranStats['total_pages'] > 0)
                    · {{ $quranStats['total_pages'] }} hal.
                @endif
            </div>
        </div>

        {{-- Dzikir --}}
        <div class="summary-card">
            <div class="icon" style="background: rgba(245,158,11,0.1); color: #d97706;">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="number" style="color: #d97706;">{{ $dzikirStats['total_sessions'] }}</div>
            <div class="label-text">Sesi Dzikir</div>
            <div class="sub-text" style="color: #64748b; font-size: 11px; margin-top: 6px;">
                {{ $dzikirStats['active_days'] }} hari aktif
            </div>
        </div>

        {{-- Streak / Total Hari Aktif --}}
        <div class="summary-card">
            <div class="icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                <i class="fas fa-fire"></i>
            </div>
            <div class="number" style="color: #ef4444;">{{ $streak }}</div>
            <div class="label-text">Hari Aktif Ibadah</div>
            <div class="sub-text" style="color: #64748b; font-size: 11px; margin-top: 6px;">
                Dalam periode {{ $periodLabel }}
            </div>
        </div>

    </div>

    {{-- ═══════ DETAIL SHALAT WAJIB ═══════ --}}
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-mosque" style="color: #0f766e;"></i> Detail Shalat Wajib</h3>

        {{-- Statistik per Waktu --}}
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 24px;">
            @php
                $wajibList = ['subuh'=>'Subuh','dzuhur'=>'Dzuhur','asar'=>'Asar','maghrib'=>'Maghrib','isya'=>'Isya'];
                $totalDays = max($prayerStats['total_days'], 1);
            @endphp
            @foreach($wajibList as $key => $label)
            <div style="text-align:center; padding: 14px 8px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="font-size: 22px; font-weight: 900; color: #0f766e;">{{ $prayerStats[$key] }}</div>
                <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px;">{{ $label }}</div>
                <div style="font-size: 11px; color: #0d9488; font-weight: 700; margin-top: 4px;">
                    {{ $totalDays > 0 ? round(($prayerStats[$key] / $totalDays) * 100) : 0 }}%
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tabel Harian (max 60 baris agar PDF tidak terlalu panjang) --}}
        @if($prayers->count() > 0)
        <div style="overflow-x: auto;">
            <table class="prayer-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">Tanggal</th>
                        <th>Subuh</th><th>Dzuhur</th><th>Asar</th><th>Maghrib</th><th>Isya</th>
                        <th style="width:1px;border-left:2px solid #f1f5f9;">Dhuha</th>
                        <th>Tahajud</th><th>Witir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prayers->take(60) as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                        @foreach(['subuh','dzuhur','asar','maghrib','isya'] as $s)
                        <td>
                            @if($row->$s)
                                <span class="prayer-dot-done"><i class="fas fa-check"></i></span>
                            @else
                                <span class="prayer-dot-miss"><i class="fas fa-times"></i></span>
                            @endif
                        </td>
                        @endforeach
                        <td style="border-left: 2px solid #f1f5f9;">
                            @if($row->dhuha) <span class="prayer-dot-done"><i class="fas fa-check"></i></span>
                            @else <span style="color:#cbd5e1; font-size:10px;">—</span> @endif
                        </td>
                        <td>
                            @if($row->tahajud) <span class="prayer-dot-done"><i class="fas fa-check"></i></span>
                            @else <span style="color:#cbd5e1; font-size:10px;">—</span> @endif
                        </td>
                        <td>
                            @if($row->witir) <span class="prayer-dot-done"><i class="fas fa-check"></i></span>
                            @else <span style="color:#cbd5e1; font-size:10px;">—</span> @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($prayers->count() > 60)
            <p style="font-size:11px; color:#94a3b8; text-align:center; margin-top:12px; font-weight:600;">
                + {{ $prayers->count() - 60 }} hari lainnya tidak ditampilkan. Gunakan ekspor CSV untuk data lengkap.
            </p>
            @endif
        </div>
        @else
        <p style="color:#94a3b8; text-align:center; padding:24px 0; font-size:13px;">
            Belum ada data shalat di periode ini.
        </p>
        @endif
    </div>

    {{-- ═══════ SHALAT SUNNAH ═══════ --}}
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-moon" style="color: #8b5cf6;"></i> Shalat Sunnah</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
            @foreach(['dhuha' => ['Dhuha', '#f59e0b'], 'tahajud' => ['Tahajud', '#8b5cf6'], 'witir' => ['Witir', '#0f766e']] as $key => [$label, $color])
            @php $keyVal = $prayerStats[$key]; @endphp
            <div style="text-align:center; padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="font-size: 28px; font-weight: 900; color: {{ $color }};">{{ $keyVal }}</div>
                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">{{ $label }}</div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 4px;">hari pelaksanaan</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════ TILAWAH AL-QURAN ═══════ --}}
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-book-open" style="color: #6366f1;"></i> Tilawah Al-Quran</h3>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px;">
            <div class="stat-row" style="flex-direction: column; align-items: flex-start; padding: 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <div class="stat-value" style="color: #6366f1;">{{ $quranStats['total_sessions'] }}</div>
                <div class="stat-label" style="font-size: 11px;">Sesi Tilawah</div>
            </div>
            <div class="stat-row" style="flex-direction: column; align-items: flex-start; padding: 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                <div class="stat-value" style="color: #6366f1;">{{ $quranStats['active_days'] }}</div>
                <div class="stat-label" style="font-size: 11px;">Hari Aktif</div>
            </div>
            <div class="stat-row" style="flex-direction: column; align-items: flex-start; padding: 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                <div class="stat-value" style="color: #6366f1;">{{ $quranStats['total_ayat'] }}</div>
                <div class="stat-label" style="font-size: 11px;">Total Ayat</div>
            </div>
            <div class="stat-row" style="flex-direction: column; align-items: flex-start; padding: 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                <div class="stat-value" style="color: #6366f1;">~{{ $quranStats['total_pages'] }}</div>
                <div class="stat-label" style="font-size: 11px;">Est. Halaman</div>
            </div>
        </div>

        @if($quranLogs->count() > 0)
        <table class="prayer-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Dari Surah</th>
                    <th>Ayat Mulai</th>
                    <th>Hingga Surah</th>
                    <th>Ayat Selesai</th>
                    <th>Total Ayat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quranLogs->take(40) as $q)
                <tr>
                    <td style="text-align:left;">{{ \Carbon\Carbon::parse($q->date)->format('d M Y') }}</td>
                    <td><span class="quran-badge">Surah {{ $q->start_surah }}</span></td>
                    <td>{{ $q->start_ayat }}</td>
                    <td><span class="quran-badge">Surah {{ $q->end_surah }}</span></td>
                    <td>{{ $q->end_ayat }}</td>
                    <td style="font-weight:800; color:#6366f1;">{{ $q->total_ayat }} ayat</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8; text-align:center; padding:16px 0; font-size:13px;">Belum ada catatan tilawah di periode ini.</p>
        @endif
    </div>

    {{-- ═══════ DZIKIR ═══════ --}}
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-hand-holding-heart" style="color: #d97706;"></i> Dzikir</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
            @php
                $dzikirItems = ['pagi'=>['Dzikir Pagi','#f59e0b'],'petang'=>['Dzikir Petang','#0f766e'],'salat'=>['Dzikir Salat','#6366f1']];
            @endphp
            @foreach($dzikirItems as $type => [$dzLabel, $dzColor])
            <div style="text-align:center; padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="font-size: 28px; font-weight: 900; color: {{ $dzColor }};">{{ $dzikirStats[$type] }}</div>
                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 4px;">{{ $dzLabel }}</div>
            </div>
            @endforeach

            <div style="text-align:center; padding: 20px; background: linear-gradient(135deg, rgba(15,118,110,0.08), rgba(13,148,136,0.08)); border-radius: 16px; border: 1px solid rgba(15,118,110,0.2);">
                <div style="font-size: 28px; font-weight: 900; color: #0f766e;">{{ $dzikirStats['total_sessions'] }}</div>
                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 4px;">Total Sesi</div>
            </div>
        </div>
    </div>

    {{-- ═══════ FOOTER ═══════ --}}
    <div style="text-align:center; padding-top: 12px; margin-bottom: 100px;">
        <p style="font-size:11px; color:#cbd5e1; font-weight:600;">
            Laporan ini dibuat secara otomatis oleh MahabBa · {{ now()->format('d F Y') }}
        </p>
        <p style="font-size:10px; color:#e2e8f0; margin-top:4px;">
            "Dan dirikanlah shalat, tunaikanlah zakat, dan rukuklah beserta orang-orang yang rukuk." — QS Al-Baqarah: 43
        </p>
    </div>

</div>

{{-- ═══════ ACTION BAR (print:hidden) ═══════ --}}
<div class="action-bar" style="print-color-adjust:exact;" id="action-bar">
    <button class="btn-back" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i> Kembali
    </button>
    <button class="btn-print" onclick="window.print()">
        <i class="fas fa-print"></i> Simpan / Cetak PDF
    </button>
    <a href="{{ route('settings.export-csv') }}"
       style="background: transparent; color: #0f766e; border: 2px solid #0f766e; padding: 11px 24px; border-radius: 14px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s;">
        <i class="fas fa-file-csv"></i> Ekspor CSV
    </a>
</div>

<script>
    // Trigger print otomatis jika ada parameter ?autoprint=1
    const params = new URLSearchParams(window.location.search);
    if (params.get('autoprint') === '1') {
        setTimeout(() => window.print(), 800);
    }
</script>

</body>
</html>
