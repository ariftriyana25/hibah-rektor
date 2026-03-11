<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Leaderboard</title>
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
            --gold: #FFD700;
            --silver: #C0C0C0;
            --bronze: #CD7F32;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
        }
        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            text-decoration: none;
            color: white;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-subtitle {
            text-align: center;
            color: var(--text-gray);
            margin-bottom: 2rem;
        }
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 0.5rem 1.25rem;
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        .filter-tab:hover, .filter-tab.active {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }
        .top-3 {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: end;
        }
        .podium-item {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }
        .podium-item.first {
            padding: 2rem 1.5rem;
            border-color: var(--gold);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.2);
        }
        .podium-item.second { border-color: var(--silver); }
        .podium-item.third { border-color: var(--bronze); }
        .rank-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .rank-badge.gold { background: var(--gold); color: #000; }
        .rank-badge.silver { background: var(--silver); color: #000; }
        .rank-badge.bronze { background: var(--bronze); color: #fff; }
        .podium-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 1rem auto;
        }
        .podium-item.first .podium-avatar {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }
        .podium-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .podium-level {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        .podium-score {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-orange);
        }
        .podium-item.first .podium-score {
            font-size: 2rem;
        }
        .leaderboard-table {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
        }
        .leaderboard-row {
            display: grid;
            grid-template-columns: 60px 1fr 100px 120px;
            padding: 1rem 1.5rem;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s ease;
        }
        .leaderboard-row:hover {
            background: var(--bg-card-hover);
        }
        .leaderboard-row.header {
            font-weight: 600;
            color: var(--text-gray);
            font-size: 0.85rem;
        }
        .rank {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .user-name {
            font-weight: 500;
        }
        .user-level {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        .karakter-cell {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        .score-cell {
            font-weight: 700;
            font-size: 1.1rem;
            text-align: right;
            color: var(--primary-orange);
        }
        @media (max-width: 768px) {
            .top-3 {
                grid-template-columns: 1fr;
            }
            .podium-item.first { order: -1; }
            .leaderboard-row {
                grid-template-columns: 40px 1fr 80px;
            }
            .karakter-cell { display: none; }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <main class="main-content" style="padding: 2rem;">
    <div class="container">
        <h1 class="page-title">🏆 Leaderboard</h1>
        <p class="page-subtitle">Penari terbaik platform CITRA</p>

        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua</button>
            <button class="filter-tab" data-filter="panji">Panji</button>
            <button class="filter-tab" data-filter="samba">Samba</button>
            <button class="filter-tab" data-filter="rumyang">Rumyang</button>
            <button class="filter-tab" data-filter="tumenggung">Tumenggung</button>
            <button class="filter-tab" data-filter="klana">Klana</button>
        </div>

        <div class="top-3">
            <div class="podium-item second">
                <span class="rank-badge silver">2</span>
                <div class="podium-avatar">S</div>
                <div class="podium-name">Siti Nurhaliza</div>
                <div class="podium-level">Level: Mahir</div>
                <div class="podium-score">4,850</div>
            </div>
            <div class="podium-item first">
                <span class="rank-badge gold">1</span>
                <div class="podium-avatar">A</div>
                <div class="podium-name">Ahmad Wijaya</div>
                <div class="podium-level">Level: Master</div>
                <div class="podium-score">5,230</div>
            </div>
            <div class="podium-item third">
                <span class="rank-badge bronze">3</span>
                <div class="podium-avatar">B</div>
                <div class="podium-name">Budi Santoso</div>
                <div class="podium-level">Level: Mahir</div>
                <div class="podium-score">4,520</div>
            </div>
        </div>

        <div class="leaderboard-table">
            <div class="leaderboard-row header">
                <div>Rank</div>
                <div>Pengguna</div>
                <div class="karakter-cell">Karakter</div>
                <div style="text-align: right;">Skor</div>
            </div>
            @php
                $leaderboardData = [
                    ['rank' => 4, 'name' => 'Dewi Lestari', 'initial' => 'D', 'level' => 'Menengah', 'karakter' => 'Panji', 'score' => 4120],
                    ['rank' => 5, 'name' => 'Eko Prasetyo', 'initial' => 'E', 'level' => 'Menengah', 'karakter' => 'Samba', 'score' => 3980],
                    ['rank' => 6, 'name' => 'Fitri Handayani', 'initial' => 'F', 'level' => 'Menengah', 'karakter' => 'Rumyang', 'score' => 3750],
                    ['rank' => 7, 'name' => 'Gunawan Putra', 'initial' => 'G', 'level' => 'Dasar', 'karakter' => 'Panji', 'score' => 3520],
                    ['rank' => 8, 'name' => 'Hesti Wulandari', 'initial' => 'H', 'level' => 'Dasar', 'karakter' => 'Klana', 'score' => 3280],
                    ['rank' => 9, 'name' => 'Irfan Maulana', 'initial' => 'I', 'level' => 'Dasar', 'karakter' => 'Panji', 'score' => 3050],
                    ['rank' => 10, 'name' => 'Joko Widodo', 'initial' => 'J', 'level' => 'Pemula', 'karakter' => 'Tumenggung', 'score' => 2890],
                ];
            @endphp
            @foreach($leaderboardData as $user)
            <div class="leaderboard-row">
                <div class="rank">#{{ $user['rank'] }}</div>
                <div class="user-cell">
                    <div class="user-avatar-sm">{{ $user['initial'] }}</div>
                    <div>
                        <div class="user-name">{{ $user['name'] }}</div>
                        <div class="user-level">Level: {{ $user['level'] }}</div>
                    </div>
                </div>
                <div class="karakter-cell">{{ $user['karakter'] }}</div>
                <div class="score-cell">{{ number_format($user['score']) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                // Filter logic would go here with actual API call
            });
        });
    </script>
        </main>
    </div>
</body>
</html>