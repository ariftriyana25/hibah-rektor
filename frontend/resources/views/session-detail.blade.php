<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Detail Sesi Latihan</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background: var(--bg-card);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            color: var(--text-white);
        }
        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #E85A20 0%, #FF8C42 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: var(--text-gray);
            text-decoration: none;
        }
        .breadcrumb a:hover {
            color: var(--primary-orange);
        }
        .session-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .session-title-group h1 {
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .session-meta {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        .session-actions {
            display: flex;
            gap: 0.75rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: var(--primary-orange);
            color: white;
        }
        .btn-secondary {
            background: var(--bg-card);
            color: white;
        }
        .score-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .score-grid { grid-template-columns: repeat(2, 1fr); }
        }
        .score-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .score-card.total {
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
        }
        .score-label {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        .score-card.total .score-label {
            color: rgba(255,255,255,0.8);
        }
        .score-value {
            font-size: 2.5rem;
            font-weight: 800;
        }
        .score-grade {
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        .grade-a { color: var(--success-green); }
        .grade-b { color: #3B82F6; }
        .grade-c { color: var(--warning-yellow); }
        .grade-d { color: var(--error-red); }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 1.5rem;
        }
        @media (max-width: 900px) {
            .content-grid { grid-template-columns: 1fr; }
        }
        .panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
        }
        .panel-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(255,255,255,0.1);
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 4px;
            width: 12px;
            height: 12px;
            background: var(--primary-orange);
            border-radius: 50%;
        }
        .timeline-item.success::before { background: var(--success-green); }
        .timeline-item.warning::before { background: var(--warning-yellow); }
        .timeline-item.error::before { background: var(--error-red); }
        .timeline-time {
            font-size: 0.75rem;
            color: var(--text-gray);
            margin-bottom: 0.25rem;
        }
        .timeline-content {
            font-size: 0.9rem;
        }
        .comparison-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .comparison-item {
            flex: 1;
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }
        .comparison-label {
            font-size: 0.75rem;
            color: var(--text-gray);
            margin-bottom: 0.25rem;
        }
        .comparison-value {
            font-size: 1.25rem;
            font-weight: 700;
        }
        .comparison-value.improved {
            color: var(--success-green);
        }
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .feedback-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
        }
        .feedback-icon {
            font-size: 1.25rem;
        }
        .feedback-text {
            font-size: 0.85rem;
        }
        .progress-bar {
            height: 8px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-orange), #FF8C42);
            border-radius: 4px;
        }
        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .stat-row:last-child {
            border-bottom: none;
        }
        .chart-placeholder {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">C</div>
            <span>CITRA</span>
        </a>
    </header>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('history') }}">Riwayat</a>
            <span>/</span>
            <span style="color: var(--text-white);">Detail Sesi</span>
        </div>

        <div class="session-header">
            <div class="session-title-group">
                <h1>🎭 Latihan Panji - Sembahan Awal</h1>
                <div class="session-meta">
                    <span>📅 1 Februari 2026</span>
                    <span>⏱️ 10:30 - 10:35</span>
                    <span>⏳ Durasi: 5 menit 23 detik</span>
                </div>
            </div>
            <div class="session-actions">
                <a href="{{ route('practice') }}" class="btn btn-primary">🔄 Ulangi Latihan</a>
                <button class="btn btn-secondary">📤 Bagikan</button>
            </div>
        </div>

        <!-- Score Cards -->
        <div class="score-grid">
            <div class="score-card">
                <div class="score-label">Wiraga (Gerak)</div>
                <div class="score-value">85</div>
                <div class="score-grade grade-b">Grade: B</div>
            </div>
            <div class="score-card">
                <div class="score-label">Wirama (Irama)</div>
                <div class="score-value">78</div>
                <div class="score-grade grade-c">Grade: C+</div>
            </div>
            <div class="score-card">
                <div class="score-label">Wirasa (Ekspresi)</div>
                <div class="score-value">80</div>
                <div class="score-grade grade-b">Grade: B-</div>
            </div>
            <div class="score-card total">
                <div class="score-label">Total Score</div>
                <div class="score-value">82</div>
                <div class="score-grade">Grade: B</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Main Content -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Performance Chart -->
                <div class="panel">
                    <h3 class="panel-title">📈 Grafik Performa</h3>
                    <div class="chart-placeholder">
                        Grafik performa real-time selama sesi latihan
                    </div>
                </div>

                <!-- Comparison -->
                <div class="panel">
                    <h3 class="panel-title">📊 Perbandingan dengan Sesi Sebelumnya</h3>
                    <div class="comparison-row">
                        <div class="comparison-item">
                            <div class="comparison-label">Wiraga</div>
                            <div class="comparison-value improved">+8%</div>
                        </div>
                        <div class="comparison-item">
                            <div class="comparison-label">Wirama</div>
                            <div class="comparison-value improved">+5%</div>
                        </div>
                        <div class="comparison-item">
                            <div class="comparison-label">Wirasa</div>
                            <div class="comparison-value improved">+12%</div>
                        </div>
                        <div class="comparison-item">
                            <div class="comparison-label">Total</div>
                            <div class="comparison-value improved">+8%</div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="panel">
                    <h3 class="panel-title">⏱️ Timeline Feedback</h3>
                    <div class="timeline">
                        <div class="timeline-item success">
                            <div class="timeline-time">00:15</div>
                            <div class="timeline-content">Posisi sembahan awal sudah baik</div>
                        </div>
                        <div class="timeline-item warning">
                            <div class="timeline-time">00:45</div>
                            <div class="timeline-content">Siku terlalu terangkat, rilekskan bahu</div>
                        </div>
                        <div class="timeline-item success">
                            <div class="timeline-time">01:20</div>
                            <div class="timeline-content">Timing dengan gamelan sudah sinkron</div>
                        </div>
                        <div class="timeline-item error">
                            <div class="timeline-time">02:05</div>
                            <div class="timeline-content">Kepala terlalu menunduk, angkat sedikit</div>
                        </div>
                        <div class="timeline-item success">
                            <div class="timeline-time">03:30</div>
                            <div class="timeline-content">Transisi ke nindak sangat halus</div>
                        </div>
                        <div class="timeline-item success">
                            <div class="timeline-time">04:45</div>
                            <div class="timeline-content">Ekspresi wajah sesuai karakter Panji</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- AI Feedback -->
                <div class="panel">
                    <h3 class="panel-title">🤖 Feedback AI</h3>
                    <div class="feedback-list">
                        <div class="feedback-item">
                            <span class="feedback-icon">✅</span>
                            <span class="feedback-text">Posisi tubuh secara keseluruhan sudah sesuai dengan referensi maestro</span>
                        </div>
                        <div class="feedback-item">
                            <span class="feedback-icon">💡</span>
                            <span class="feedback-text">Perhatikan posisi siku saat sembahan, jangan terlalu terangkat</span>
                        </div>
                        <div class="feedback-item">
                            <span class="feedback-icon">⚠️</span>
                            <span class="feedback-text">Irama masih sedikit terlambat 0.3 detik dari beat gamelan</span>
                        </div>
                        <div class="feedback-item">
                            <span class="feedback-icon">🎯</span>
                            <span class="feedback-text">Target berikutnya: Tingkatkan kehalusan transisi gerakan</span>
                        </div>
                    </div>
                </div>

                <!-- Session Stats -->
                <div class="panel">
                    <h3 class="panel-title">📋 Statistik Sesi</h3>
                    <div class="stat-row">
                        <span style="color: var(--text-gray);">Frame Dianalisis</span>
                        <span style="font-weight: 600;">1,847</span>
                    </div>
                    <div class="stat-row">
                        <span style="color: var(--text-gray);">Akurasi Pose</span>
                        <span style="font-weight: 600;">87.3%</span>
                    </div>
                    <div class="stat-row">
                        <span style="color: var(--text-gray);">Sinkronisasi Beat</span>
                        <span style="font-weight: 600;">94.2%</span>
                    </div>
                    <div class="stat-row">
                        <span style="color: var(--text-gray);">Gerakan Benar</span>
                        <span style="font-weight: 600;">24/28</span>
                    </div>
                    <div class="stat-row">
                        <span style="color: var(--text-gray);">Poin XP</span>
                        <span style="font-weight: 600; color: var(--primary-orange);">+82</span>
                    </div>
                </div>

                <!-- Improvement Areas -->
                <div class="panel">
                    <h3 class="panel-title">📈 Area Perbaikan</h3>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.85rem;">Posisi Tangan</span>
                            <span style="font-size: 0.85rem; color: var(--primary-orange);">85%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.85rem;">Timing Irama</span>
                            <span style="font-size: 0.85rem; color: var(--primary-orange);">72%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 72%"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.85rem;">Ekspresi Wajah</span>
                            <span style="font-size: 0.85rem; color: var(--primary-orange);">78%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 78%"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.85rem;">Posisi Kaki</span>
                            <span style="font-size: 0.85rem; color: var(--primary-orange);">90%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
