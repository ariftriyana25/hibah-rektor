<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Profil Saya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .avatar-large {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 800;
            position: relative;
        }
        .avatar-badge {
            position: absolute;
            bottom: -5px;
            right: -5px;
            background: var(--success-green);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .profile-info h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .profile-info .email {
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        .profile-badges {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-level {
            background: var(--primary-orange);
        }
        .badge-member {
            background: rgba(255,255,255,0.1);
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .stat-box {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-orange);
        }
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        .panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .panel-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }
        .achievement-item {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
        }
        .achievement-item.unlocked {
            background: rgba(232, 90, 32, 0.1);
            border: 1px solid var(--primary-orange);
        }
        .achievement-item.locked {
            opacity: 0.5;
        }
        .achievement-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .achievement-name {
            font-size: 0.75rem;
            font-weight: 500;
        }
        .mastery-bar {
            margin-bottom: 1rem;
        }
        .mastery-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .mastery-label {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .mastery-value {
            font-weight: 600;
            color: var(--primary-orange);
        }
        .progress-bar {
            height: 8px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-orange), #FF8C42);
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: var(--text-gray);
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: var(--text-white);
            font-size: 0.95rem;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary-orange);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background: var(--primary-orange);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-orange-hover);
        }
        .btn-danger {
            background: var(--error-red);
            color: white;
        }
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            background: rgba(232, 90, 32, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .activity-info {
            flex: 1;
        }
        .activity-title {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .activity-time {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        .activity-score {
            font-weight: 700;
            color: var(--success-green);
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <main class="main-content" style="padding: 0; overflow-y: auto;">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar-large">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                <span class="avatar-badge">Online</span>
            </div>
            <div class="profile-info">
                <h1>{{ Auth::user()->name ?? 'Pengguna' }}</h1>
                <p class="email">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                <div class="profile-badges">
                    <span class="badge badge-level">{{ Auth::user()->level ?? 'Pemula' }}</span>
                    <span class="badge badge-member">Member sejak {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'Jan 2024' }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-value">{{ Auth::user()->total_score ?? 0 }}</div>
                <div class="stat-label">Total Skor</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ Auth::user()->practice_count ?? 0 }}</div>
                <div class="stat-label">Sesi Latihan</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ floor((Auth::user()->practice_count ?? 0) * 5) }}</div>
                <div class="stat-label">Menit Latihan</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">#{{ $rank ?? 42 }}</div>
                <div class="stat-label">Ranking</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Achievements -->
            <div class="panel">
                <h3 class="panel-title">🏆 Pencapaian</h3>
                <div class="achievement-grid">
                    <div class="achievement-item unlocked">
                        <div class="achievement-icon">🌟</div>
                        <div class="achievement-name">Pemula</div>
                    </div>
                    <div class="achievement-item unlocked">
                        <div class="achievement-icon">🎯</div>
                        <div class="achievement-name">10 Latihan</div>
                    </div>
                    <div class="achievement-item unlocked">
                        <div class="achievement-icon">🔥</div>
                        <div class="achievement-name">3 Hari Berturut</div>
                    </div>
                    <div class="achievement-item locked">
                        <div class="achievement-icon">🎭</div>
                        <div class="achievement-name">Master Panji</div>
                    </div>
                    <div class="achievement-item locked">
                        <div class="achievement-icon">💯</div>
                        <div class="achievement-name">Skor 100</div>
                    </div>
                    <div class="achievement-item locked">
                        <div class="achievement-icon">👑</div>
                        <div class="achievement-name">Top 10</div>
                    </div>
                </div>
            </div>

            <!-- Character Mastery -->
            <div class="panel">
                <h3 class="panel-title">🎭 Penguasaan Karakter</h3>
                <div class="mastery-bar">
                    <div class="mastery-header">
                        <span class="mastery-label">🎭 Panji</span>
                        <span class="mastery-value">75%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                </div>
                <div class="mastery-bar">
                    <div class="mastery-header">
                        <span class="mastery-label">👹 Samba</span>
                        <span class="mastery-value">45%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%"></div>
                    </div>
                </div>
                <div class="mastery-bar">
                    <div class="mastery-header">
                        <span class="mastery-label">🌸 Rumyang</span>
                        <span class="mastery-value">20%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 20%"></div>
                    </div>
                </div>
                <div class="mastery-bar">
                    <div class="mastery-header">
                        <span class="mastery-label">⚔️ Tumenggung</span>
                        <span class="mastery-value">10%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 10%"></div>
                    </div>
                </div>
                <div class="mastery-bar">
                    <div class="mastery-header">
                        <span class="mastery-label">👺 Klana</span>
                        <span class="mastery-value">5%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 5%"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="panel">
                <h3 class="panel-title">📊 Aktivitas Terakhir</h3>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">🎭</div>
                        <div class="activity-info">
                            <div class="activity-title">Latihan Panji - Sembahan</div>
                            <div class="activity-time">Hari ini, 10:30</div>
                        </div>
                        <span class="activity-score">85</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">👹</div>
                        <div class="activity-info">
                            <div class="activity-title">Latihan Samba - Nindak</div>
                            <div class="activity-time">Kemarin, 14:15</div>
                        </div>
                        <span class="activity-score">72</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">🎭</div>
                        <div class="activity-info">
                            <div class="activity-title">Latihan Panji - Tanjak</div>
                            <div class="activity-time">2 hari lalu</div>
                        </div>
                        <span class="activity-score">68</span>
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div class="panel">
                <h3 class="panel-title">⚙️ Edit Profil</h3>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" name="name" value="{{ Auth::user()->name ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" name="email" value="{{ Auth::user()->email ?? '' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (opsional)</label>
                        <input type="password" class="form-input" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
        </main>
    </div>
</body>
</html>
