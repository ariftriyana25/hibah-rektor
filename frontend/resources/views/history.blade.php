<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Riwayat Latihan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary-orange: #E85A20;
            --bg-dark: #0D0D0D;
            --bg-card: #1A1A1A;
            --bg-card-hover: #252525;
            --text-white: #FFFFFF;
            --text-gray: #A0A0A0;
            --success-green: #22C55E;
            --warning-yellow: #EAB308;
            --error-red: #EF4444;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
        }
        /* Layout handled by sidebar partial */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
        }
        .filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .filter-select {
            padding: 0.5rem 1rem;
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }
        .stat-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-orange);
        }
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-gray);
        }
        .history-table {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
        }
        .table-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 100px;
            padding: 1rem 1.5rem;
            background: rgba(255,255,255,0.03);
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        @media (max-width: 900px) {
            .table-header { display: none; }
        }
        .table-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 100px;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            align-items: center;
            transition: background 0.3s;
        }
        .table-row:hover {
            background: rgba(255,255,255,0.02);
        }
        @media (max-width: 900px) {
            .table-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }
        .session-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .session-icon {
            width: 40px;
            height: 40px;
            background: rgba(232, 90, 32, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .session-details h4 {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .session-details p {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        .score-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .score-high { background: rgba(34, 197, 94, 0.1); color: var(--success-green); }
        .score-medium { background: rgba(234, 179, 8, 0.1); color: var(--warning-yellow); }
        .score-low { background: rgba(239, 68, 68, 0.1); color: var(--error-red); }
        .btn-detail {
            padding: 0.5rem 1rem;
            background: var(--bg-card-hover);
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }
        .btn-detail:hover {
            background: var(--primary-orange);
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .page-btn {
            padding: 0.5rem 1rem;
            background: var(--bg-card);
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        .page-btn:hover, .page-btn.active {
            background: var(--primary-orange);
        }
        .mobile-label {
            display: none;
            color: var(--text-gray);
            font-size: 0.75rem;
        }
        @media (max-width: 900px) {
            .mobile-label { display: inline; margin-right: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <main class="main-content" style="padding: 0; overflow-y: auto;">
    <div class="container">
        <div class="page-header">
            <h1>📋 Riwayat Latihan</h1>
            <div class="filters">
                <select class="filter-select">
                    <option value="">Semua Karakter</option>
                    <option value="panji">Panji</option>
                    <option value="samba">Samba</option>
                    <option value="rumyang">Rumyang</option>
                    <option value="tumenggung">Tumenggung</option>
                    <option value="klana">Klana</option>
                </select>
                <select class="filter-select">
                    <option value="">Semua Waktu</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value">{{ $totalSessions ?? 47 }}</div>
                <div class="stat-label">Total Sesi</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $avgScore ?? 72 }}</div>
                <div class="stat-label">Rata-rata Skor</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalMinutes ?? 235 }}m</div>
                <div class="stat-label">Total Waktu</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $bestScore ?? 95 }}</div>
                <div class="stat-label">Skor Tertinggi</div>
            </div>
        </div>

        <!-- History Table -->
        <div class="history-table">
            <div class="table-header">
                <div>Sesi Latihan</div>
                <div>Wiraga</div>
                <div>Wirama</div>
                <div>Wirasa</div>
                <div>Total</div>
                <div>Durasi</div>
                <div></div>
            </div>

            @php
            $sessions = [
                ['karakter' => 'panji', 'icon' => '🎭', 'gerakan' => 'Sembahan Awal', 'date' => 'Hari ini, 10:30', 'wiraga' => 85, 'wirama' => 78, 'wirasa' => 80, 'total' => 82, 'duration' => '5:23'],
                ['karakter' => 'samba', 'icon' => '👹', 'gerakan' => 'Nindak Dasar', 'date' => 'Kemarin, 14:15', 'wiraga' => 72, 'wirama' => 68, 'wirasa' => 65, 'total' => 68, 'duration' => '4:45'],
                ['karakter' => 'panji', 'icon' => '🎭', 'gerakan' => 'Tanjak', 'date' => '2 hari lalu', 'wiraga' => 88, 'wirama' => 82, 'wirasa' => 85, 'total' => 85, 'duration' => '6:12'],
                ['karakter' => 'rumyang', 'icon' => '🌸', 'gerakan' => 'Sembahan', 'date' => '3 hari lalu', 'wiraga' => 65, 'wirama' => 60, 'wirasa' => 62, 'total' => 62, 'duration' => '3:55'],
                ['karakter' => 'panji', 'icon' => '🎭', 'gerakan' => 'Ngigel', 'date' => '4 hari lalu', 'wiraga' => 78, 'wirama' => 75, 'wirasa' => 72, 'total' => 75, 'duration' => '5:08'],
                ['karakter' => 'klana', 'icon' => '👺', 'gerakan' => 'Tanjak Klana', 'date' => '5 hari lalu', 'wiraga' => 55, 'wirama' => 50, 'wirasa' => 48, 'total' => 51, 'duration' => '4:20'],
                ['karakter' => 'samba', 'icon' => '👹', 'gerakan' => 'Gerak Lucu', 'date' => '1 minggu lalu', 'wiraga' => 70, 'wirama' => 72, 'wirasa' => 68, 'total' => 70, 'duration' => '5:30'],
            ];
            @endphp

            @foreach($sessions as $session)
            <div class="table-row">
                <div class="session-info">
                    <div class="session-icon">{{ $session['icon'] }}</div>
                    <div class="session-details">
                        <h4>{{ ucfirst($session['karakter']) }} - {{ $session['gerakan'] }}</h4>
                        <p>{{ $session['date'] }}</p>
                    </div>
                </div>
                <div><span class="mobile-label">Wiraga:</span>{{ $session['wiraga'] }}%</div>
                <div><span class="mobile-label">Wirama:</span>{{ $session['wirama'] }}%</div>
                <div><span class="mobile-label">Wirasa:</span>{{ $session['wirasa'] }}%</div>
                <div>
                    <span class="score-badge {{ $session['total'] >= 80 ? 'score-high' : ($session['total'] >= 60 ? 'score-medium' : 'score-low') }}">
                        {{ $session['total'] }}
                    </span>
                </div>
                <div><span class="mobile-label">Durasi:</span>{{ $session['duration'] }}</div>
                <div>
                    <button class="btn-detail">Detail</button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn">← Prev</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">Next →</button>
        </div>
    </div>
        </main>
    </div>
</body>
</html>
