<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CITRA - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-orange: #E85A20;
            --primary-orange-hover: #FF6B2E;
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

        /* Main Content */
        .main-content {
            padding: 2rem;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            color: var(--text-gray);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(232, 90, 32, 0.3);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.orange { background: rgba(232, 90, 32, 0.15); }
        .stat-icon.green { background: rgba(34, 197, 94, 0.15); }
        .stat-icon.blue { background: rgba(59, 130, 246, 0.15); }
        .stat-icon.purple { background: rgba(168, 85, 247, 0.15); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 0.85rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--text-white);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .action-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--primary-orange);
            transform: translateX(5px);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
        }

        .action-content h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .action-content p {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Panel */
        .panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .panel-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .panel-link {
            color: var(--primary-orange);
            font-size: 0.85rem;
            text-decoration: none;
        }

        .panel-link:hover {
            text-decoration: underline;
        }

        /* Progress Chart */
        .progress-chart {
            height: 200px;
            display: flex;
            align-items: flex-end;
            gap: 1rem;
            padding: 1rem 0;
        }

        .chart-bar {
            flex: 1;
            background: rgba(232, 90, 32, 0.2);
            border-radius: 8px 8px 0 0;
            position: relative;
            min-height: 20px;
            transition: all 0.3s ease;
        }

        .chart-bar:hover {
            background: rgba(232, 90, 32, 0.4);
        }

        .chart-bar-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, var(--primary-orange), #FF8C42);
            border-radius: 8px 8px 0 0;
            transition: height 0.5s ease;
        }

        .chart-label {
            text-align: center;
            font-size: 0.7rem;
            color: var(--text-gray);
            margin-top: 0.5rem;
        }

        /* Character Mastery */
        .mastery-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mastery-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
        }

        .mastery-icon {
            width: 45px;
            height: 45px;
            background: var(--bg-dark);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .mastery-info {
            flex: 1;
        }

        .mastery-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .mastery-level {
            font-size: 0.75rem;
            color: var(--text-gray);
        }

        .mastery-score {
            font-weight: 700;
            color: var(--primary-orange);
        }

        /* Recent Sessions */
        .session-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .session-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
        }

        .session-karakter {
            width: 40px;
            height: 40px;
            background: rgba(232, 90, 32, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .session-info {
            flex: 1;
        }

        .session-title {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .session-meta {
            font-size: 0.75rem;
            color: var(--text-gray);
        }

        .session-score {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .session-score.high { color: var(--success-green); }
        .session-score.medium { color: var(--warning-yellow); }
        .session-score.low { color: var(--error-red); }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1rem;
            }
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-orange);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-orange-hover);
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Selamat Datang, {{ Auth::user()->name ?? 'Pengguna' }}! 👋</h1>
                <p>Lanjutkan perjalanan belajar tari topeng Cirebon Anda.</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon orange">🎯</div>
                    <div class="stat-value">{{ Auth::user()->total_score ?? 0 }}</div>
                    <div class="stat-label">Total Skor</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">📈</div>
                    <div class="stat-value">{{ Auth::user()->practice_count ?? 0 }}</div>
                    <div class="stat-label">Sesi Latihan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">⏱️</div>
                    <div class="stat-value">{{ floor((Auth::user()->practice_count ?? 0) * 5) }}m</div>
                    <div class="stat-label">Waktu Latihan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">🏆</div>
                    <div class="stat-value">{{ Auth::user()->level ?? 'Pemula' }}</div>
                    <div class="stat-label">Level Saat Ini</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('practice') }}" class="action-card">
                    <div class="action-icon">🎭</div>
                    <div class="action-content">
                        <h3>Mulai Latihan</h3>
                        <p>Latih gerakan tari dengan AI real-time</p>
                    </div>
                </a>
                <a href="{{ route('leaderboard') }}" class="action-card">
                    <div class="action-icon">🏆</div>
                    <div class="action-content">
                        <h3>Lihat Peringkat</h3>
                        <p>Bandingkan skor dengan pengguna lain</p>
                    </div>
                </a>
                <a href="{{ route('tutorial') }}" class="action-card">
                    <div class="action-icon">📚</div>
                    <div class="action-content">
                        <h3>Pelajari Karakter</h3>
                        <p>Kenali 5 karakter tari topeng</p>
                    </div>
                </a>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Progress Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">📊 Progres Mingguan</h3>
                        <a href="#" class="panel-link">Lihat Detail</a>
                    </div>
                    <div class="progress-chart">
                        @php
                            $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                            $scores = [45, 60, 55, 70, 85, 75, 0];
                        @endphp
                        @foreach($days as $index => $day)
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                            <div class="chart-bar" style="height: 150px;">
                                <div class="chart-bar-fill" style="height: {{ $scores[$index] }}%;"></div>
                            </div>
                            <div class="chart-label">{{ $day }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Character Mastery -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">🎭 Penguasaan Karakter</h3>
                    </div>
                    <div class="mastery-list">
                        <div class="mastery-item">
                            <div class="mastery-icon">🎭</div>
                            <div class="mastery-info">
                                <div class="mastery-name">Panji</div>
                                <div class="mastery-level">Menengah</div>
                            </div>
                            <div class="mastery-score">75%</div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-icon">👹</div>
                            <div class="mastery-info">
                                <div class="mastery-name">Samba</div>
                                <div class="mastery-level">Pemula</div>
                            </div>
                            <div class="mastery-score">45%</div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-icon">🌸</div>
                            <div class="mastery-info">
                                <div class="mastery-name">Rumyang</div>
                                <div class="mastery-level">Belum dimulai</div>
                            </div>
                            <div class="mastery-score">0%</div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-icon">⚔️</div>
                            <div class="mastery-info">
                                <div class="mastery-name">Tumenggung</div>
                                <div class="mastery-level">Belum dimulai</div>
                            </div>
                            <div class="mastery-score">0%</div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-icon">👺</div>
                            <div class="mastery-info">
                                <div class="mastery-name">Klana</div>
                                <div class="mastery-level">Belum dimulai</div>
                            </div>
                            <div class="mastery-score">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sessions -->
            <div class="panel" style="margin-top: 1.5rem;">
                <div class="panel-header">
                    <h3 class="panel-title">🕐 Sesi Latihan Terakhir</h3>
                    <a href="{{ route('history') }}" class="panel-link">Lihat Semua</a>
                </div>
                <div class="session-list">
                    <div class="session-item">
                        <div class="session-karakter">🎭</div>
                        <div class="session-info">
                            <div class="session-title">Latihan Panji - Sembahan Awal</div>
                            <div class="session-meta">Hari ini, 10:30</div>
                        </div>
                        <div class="session-score high">85</div>
                    </div>
                    <div class="session-item">
                        <div class="session-karakter">👹</div>
                        <div class="session-info">
                            <div class="session-title">Latihan Samba - Gerakan Dasar</div>
                            <div class="session-meta">Kemarin, 14:15</div>
                        </div>
                        <div class="session-score medium">68</div>
                    </div>
                    <div class="session-item">
                        <div class="session-karakter">🎭</div>
                        <div class="session-info">
                            <div class="session-title">Latihan Panji - Nindak</div>
                            <div class="session-meta">2 hari lalu</div>
                        </div>
                        <div class="session-score high">72</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Logout functionality
        document.querySelectorAll('[data-logout]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                // Handle logout
                window.location.href = '/logout';
            });
        });
    </script>
</body>
</html>