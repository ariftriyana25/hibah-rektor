<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CITRA - Mode Latihan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/holistic/holistic.js" crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"></script>
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
            overflow-x: hidden;
        }

        /* Layout handled by sidebar partial */

        .practice-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: rgba(26, 26, 26, 0.95);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .header-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.25rem;
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
        }

        .btn-primary {
            background: var(--primary-orange);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-orange-hover);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-secondary:hover {
            background: var(--bg-card-hover);
        }

        .btn-success {
            background: var(--success-green);
            color: white;
        }

        .btn-danger {
            background: var(--error-red);
            color: white;
        }

        /* Main Layout */
        .practice-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 1.5rem;
            padding: 1.5rem;
            height: calc(100vh - 70px);
        }

        /* Video Section */
        .video-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .video-container {
            position: relative;
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            flex: 1;
            min-height: 400px;
        }

        #videoElement {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
        }

        #canvasElement {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: scaleX(-1);
        }

        .video-overlay {
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            display: flex;
            justify-content: space-between;
            z-index: 10;
        }

        .status-badge {
            background: rgba(0,0,0,0.7);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-indicator.active {
            background: var(--success-green);
        }

        .status-indicator.inactive {
            background: var(--error-red);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .timer-display {
            background: rgba(0,0,0,0.7);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 1.25rem;
            font-weight: 600;
            font-family: monospace;
        }

        /* Control Bar */
        .control-bar {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-card);
            border-radius: 12px;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .control-btn.record {
            background: var(--error-red);
            color: white;
        }

        .control-btn.record.recording {
            animation: recording 1s infinite;
        }

        @keyframes recording {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .control-btn.music {
            background: var(--bg-card-hover);
            color: white;
        }

        .control-btn.settings {
            background: var(--bg-card-hover);
            color: white;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            overflow-y: auto;
        }

        .panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .panel-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Score Display */
        .score-display {
            text-align: center;
        }

        .main-score {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .score-label {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .grade-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--primary-orange);
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Score Breakdown */
        .score-breakdown {
            margin-top: 1rem;
        }

        .score-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .score-item-label {
            font-size: 0.85rem;
            color: var(--text-gray);
        }

        .score-item-value {
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.1);
            border-radius: 3px;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        .progress-fill.wiraga {
            background: linear-gradient(90deg, #22C55E, #4ADE80);
        }

        .progress-fill.wirama {
            background: linear-gradient(90deg, #3B82F6, #60A5FA);
        }

        .progress-fill.wirasa {
            background: linear-gradient(90deg, #A855F7, #C084FC);
        }

        /* Feedback Panel */
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
        }

        .feedback-item {
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 8px;
            font-size: 0.85rem;
            border-left: 3px solid var(--primary-orange);
        }

        .feedback-item.success {
            border-left-color: var(--success-green);
            background: rgba(34, 197, 94, 0.1);
        }

        .feedback-item.warning {
            border-left-color: var(--warning-yellow);
            background: rgba(234, 179, 8, 0.1);
        }

        .feedback-item.error {
            border-left-color: var(--error-red);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Character Selection */
        .character-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .character-btn {
            padding: 0.75rem 0.5rem;
            background: rgba(255,255,255,0.05);
            border: 2px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            color: var(--text-white);
        }

        .character-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .character-btn.active {
            border-color: var(--primary-orange);
            background: rgba(232, 90, 32, 0.1);
        }

        .character-icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        /* Audio Controls */
        .audio-controls {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .audio-control-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .audio-label {
            font-size: 0.8rem;
            color: var(--text-gray);
            min-width: 60px;
        }

        .slider {
            flex: 1;
            -webkit-appearance: none;
            height: 6px;
            border-radius: 3px;
            background: rgba(255,255,255,0.1);
            outline: none;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-orange);
            cursor: pointer;
        }

        /* Maestro Reference */
        .maestro-preview {
            position: relative;
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }

        .maestro-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            font-size: 0.85rem;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .loading-overlay.hidden {
            display: none;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.1);
            border-top-color: var(--primary-orange);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 1rem;
            color: var(--text-gray);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .practice-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .panel {
                flex: 1;
                min-width: 280px;
            }
        }

        @media (max-width: 640px) {
            .practice-header {
                padding: 1rem;
            }

            .practice-container {
                padding: 1rem;
            }

            .sidebar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Memuat AI Motion Capture...</p>
    </div>

    <div class="app-layout">
        @include('partials.sidebar')

        <main style="overflow-y: auto; height: 100vh;">
            <!-- Practice Top Bar -->
            <div class="practice-topbar">
                <span style="color: var(--text-gray); font-size: 0.9rem;">Selamat berlatih, {{ Auth::user()->name ?? 'Pengguna' }}!</span>
                <div class="header-controls">
                    <button class="btn btn-primary" id="startSessionBtn">Mulai Sesi</button>
                </div>
            </div>

    <!-- Main Content -->
    <main class="practice-container">
        <!-- Video Section -->
        <div class="video-section">
            <div class="video-container" id="videoContainer">
                <video id="videoElement" autoplay playsinline></video>
                <canvas id="canvasElement"></canvas>
                
                <div class="video-overlay">
                    <div class="status-badge">
                        <span class="status-indicator inactive" id="cameraStatus"></span>
                        <span id="cameraStatusText">Kamera Mati</span>
                    </div>
                    <div class="timer-display" id="timerDisplay">00:00</div>
                </div>
            </div>

            <div class="control-bar">
                <button class="control-btn record" id="recordBtn" title="Rekam">⏺</button>
                <button class="control-btn music" id="musicBtn" title="Musik">🎵</button>
                <button class="control-btn settings" id="cameraBtn" title="Kamera">📷</button>
                <button class="control-btn settings" id="settingsBtn" title="Pengaturan">⚙️</button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Score Panel -->
            <div class="panel score-display">
                <div class="main-score" id="totalScore">0</div>
                <div class="score-label">Skor Total</div>
                <span class="grade-badge" id="gradeBadge">-</span>

                <div class="score-breakdown">
                    <div class="score-item">
                        <span class="score-item-label">Wiraga (Gerakan)</span>
                        <span class="score-item-value" id="wiragaScore">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill wiraga" id="wiragaProgress" style="width: 0%"></div>
                    </div>

                    <div class="score-item" style="margin-top: 0.75rem;">
                        <span class="score-item-label">Wirama (Irama)</span>
                        <span class="score-item-value" id="wiramaScore">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill wirama" id="wiramaProgress" style="width: 0%"></div>
                    </div>

                    <div class="score-item" style="margin-top: 0.75rem;">
                        <span class="score-item-label">Wirasa (Ekspresi)</span>
                        <span class="score-item-value" id="wirasaScore">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill wirasa" id="wirasaProgress" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Performance Graph -->
            <div class="panel">
                <h3 class="panel-title">📈 Grafik Performa</h3>
                <div style="height: 100px; display: flex; align-items: flex-end; gap: 4px; padding: 0.5rem 0;">
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 30%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 45%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 55%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 40%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 65%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar" style="flex: 1; background: linear-gradient(to top, var(--primary-orange) 0%, transparent 100%); height: 70%; border-radius: 4px 4px 0 0;"></div>
                    <div class="perf-bar current" style="flex: 1; background: linear-gradient(to top, var(--success-green) 0%, transparent 100%); height: 0%; border-radius: 4px 4px 0 0;" id="currentPerfBar"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; color: var(--text-gray);">
                    <span>6 sesi lalu</span>
                    <span>Sekarang</span>
                </div>
            </div>

            <!-- Feedback Panel -->
            <div class="panel">
                <h3 class="panel-title">💡 Feedback Real-time</h3>
                <div class="feedback-list" id="feedbackList">
                    <div class="feedback-item">Mulai sesi untuk mendapatkan feedback.</div>
                </div>
            </div>

            <!-- Character Selection -->
            <div class="panel">
                <h3 class="panel-title">🎭 Pilih Karakter</h3>
                <div class="character-grid">
                    <button class="character-btn active" data-karakter="panji">
                        <div class="character-icon">🎭</div>
                        Panji
                    </button>
                    <button class="character-btn" data-karakter="samba">
                        <div class="character-icon">👹</div>
                        Samba
                    </button>
                    <button class="character-btn" data-karakter="rumyang">
                        <div class="character-icon">🌸</div>
                        Rumyang
                    </button>
                    <button class="character-btn" data-karakter="tumenggung">
                        <div class="character-icon">⚔️</div>
                        Tumenggung
                    </button>
                    <button class="character-btn" data-karakter="klana">
                        <div class="character-icon">👺</div>
                        Klana
                    </button>
                </div>
                
                <!-- Gerakan Selection -->
                <select id="gerakanPracticeSelect" style="width: 100%; padding: 0.5rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; margin-top: 0.75rem;">
                    <option value="">Semua Gerakan</option>
                    <option value="sembahan">Sembahan Awal</option>
                    <option value="nindak">Nindak</option>
                    <option value="tanjak">Tanjak</option>
                    <option value="ngigel">Ngigel</option>
                    <option value="klepat">Klepat</option>
                </select>
            </div>

            <!-- Display Settings -->
            <div class="panel">
                <h3 class="panel-title">🎨 Pengaturan Tampilan</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" id="showSkeleton" checked style="width: 18px; height: 18px; accent-color: var(--primary-orange);">
                        <span style="font-size: 0.85rem;">Tampilkan Skeleton</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" id="showLandmarks" checked style="width: 18px; height: 18px; accent-color: var(--primary-orange);">
                        <span style="font-size: 0.85rem;">Tampilkan Titik Sendi</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" id="mirrorMode" checked style="width: 18px; height: 18px; accent-color: var(--primary-orange);">
                        <span style="font-size: 0.85rem;">Mode Mirror</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" id="countdownMode" style="width: 18px; height: 18px; accent-color: var(--primary-orange);">
                        <span style="font-size: 0.85rem;">Countdown 3-2-1</span>
                    </label>
                </div>
            </div>

            <!-- Audio Controls -->
            <div class="panel">
                <h3 class="panel-title">🎵 Audio Gamelan</h3>
                <div class="audio-controls">
                    <div class="audio-control-row">
                        <span class="audio-label">Volume</span>
                        <input type="range" class="slider" id="volumeSlider" min="0" max="100" value="70">
                    </div>
                    <div class="audio-control-row">
                        <span class="audio-label">Tempo</span>
                        <input type="range" class="slider" id="tempoSlider" min="50" max="150" value="100">
                        <span style="font-size: 0.75rem; color: var(--text-gray);" id="tempoDisplay">100%</span>
                    </div>
                    <select id="musicSelect" style="width: 100%; padding: 0.5rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; margin-top: 0.5rem;">
                        <option value="">Pilih Musik Gamelan</option>
                        <option value="panji_basic">Gamelan Panji - Dasar</option>
                        <option value="panji_advanced">Gamelan Panji - Mahir</option>
                        <option value="samba_basic">Gamelan Samba - Dasar</option>
                        <option value="rumyang_basic">Gamelan Rumyang - Dasar</option>
                        <option value="tumenggung_basic">Gamelan Tumenggung - Dasar</option>
                        <option value="klana_dynamic">Gamelan Klana - Dinamis</option>
                    </select>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                        <button id="playMusicBtn" style="flex: 1; padding: 0.5rem; background: var(--primary-orange); border: none; border-radius: 6px; color: white; cursor: pointer; font-size: 0.85rem;">▶ Putar</button>
                        <button id="stopMusicBtn" style="flex: 1; padding: 0.5rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; cursor: pointer; font-size: 0.85rem;">⏹ Stop</button>
                    </div>
                </div>
            </div>

            <!-- Maestro Reference -->
            <div class="panel">
                <h3 class="panel-title">👨‍🏫 Referensi Maestro</h3>
                <div class="maestro-preview">
                    <div class="maestro-placeholder" id="maestroPlaceholder">
                        Pilih gerakan untuk melihat referensi
                    </div>
                    <video id="maestroVideo" style="width: 100%; height: 100%; object-fit: cover; display: none;"></video>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                    <button id="compareModeBtn" style="flex: 1; padding: 0.5rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; cursor: pointer; font-size: 0.8rem;">📊 Mode Banding</button>
                    <button id="pipModeBtn" style="flex: 1; padding: 0.5rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; cursor: pointer; font-size: 0.8rem;">🖼 PiP Mode</button>
                </div>
            </div>

            <!-- Recording Panel -->
            <div class="panel">
                <h3 class="panel-title">🎬 Rekaman</h3>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <button id="startRecordingBtn" style="padding: 0.6rem; background: var(--error-red); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span style="width: 10px; height: 10px; background: white; border-radius: 50%;"></span>
                        Mulai Rekam
                    </button>
                    <button id="screenshotBtn" style="padding: 0.6rem; background: var(--bg-card-hover); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; cursor: pointer; font-size: 0.85rem;">
                        📸 Screenshot Pose
                    </button>
                    <div style="font-size: 0.75rem; color: var(--text-gray); text-align: center; margin-top: 0.5rem;">
                        Rekaman: <span id="recordingStatus">Tidak aktif</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="panel" style="background: linear-gradient(135deg, rgba(232, 90, 32, 0.1), transparent);">
                <h3 class="panel-title">📊 Statistik Sesi Ini</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div style="text-align: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                        <div style="font-size: 1.25rem; font-weight: 700;" id="poseCount">0</div>
                        <div style="font-size: 0.7rem; color: var(--text-gray);">Pose Terdeteksi</div>
                    </div>
                    <div style="text-align: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                        <div style="font-size: 1.25rem; font-weight: 700;" id="correctPoseCount">0</div>
                        <div style="font-size: 0.7rem; color: var(--text-gray);">Pose Benar</div>
                    </div>
                    <div style="text-align: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                        <div style="font-size: 1.25rem; font-weight: 700;" id="avgAccuracy">0%</div>
                        <div style="font-size: 0.7rem; color: var(--text-gray);">Akurasi</div>
                    </div>
                    <div style="text-align: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px;">
                        <div style="font-size: 1.25rem; font-weight: 700;" id="bestStreak">0</div>
                        <div style="font-size: 0.7rem; color: var(--text-gray);">Best Streak</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <audio id="gamelanAudio" preload="auto"></audio>

    <script>
        // Configuration
        const API_BASE_URL = 'http://localhost:5000';
        const WS_URL = 'http://localhost:5000';

        // State
        let socket = null;
        let holistic = null;
        let camera = null;
        let cameraStream = null;
        let isSessionActive = false;
        let sessionId = null;
        let selectedKarakter = 'panji';
        let timerInterval = null;
        let sessionStartTime = null;
        let isRecording = false;
        let isHolisticReady = false;
        let isCameraReady = false;
        let frameProcessingActive = false;
        let retryCount = 0;
        const MAX_RETRIES = 3;

        // DOM Elements
        const videoElement = document.getElementById('videoElement');
        const canvasElement = document.getElementById('canvasElement');
        const ctx = canvasElement.getContext('2d');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const startSessionBtn = document.getElementById('startSessionBtn');
        const recordBtn = document.getElementById('recordBtn');
        const cameraBtn = document.getElementById('cameraBtn');
        const musicBtn = document.getElementById('musicBtn');
        const timerDisplay = document.getElementById('timerDisplay');
        const cameraStatus = document.getElementById('cameraStatus');
        const cameraStatusText = document.getElementById('cameraStatusText');
        const feedbackList = document.getElementById('feedbackList');
        const totalScoreEl = document.getElementById('totalScore');
        const gradeBadge = document.getElementById('gradeBadge');
        const wiragaScoreEl = document.getElementById('wiragaScore');
        const wiramaScoreEl = document.getElementById('wiramaScore');
        const wirasaScoreEl = document.getElementById('wirasaScore');
        const wiragaProgress = document.getElementById('wiragaProgress');
        const wiramaProgress = document.getElementById('wiramaProgress');
        const wirasaProgress = document.getElementById('wirasaProgress');
        const gamelanAudio = document.getElementById('gamelanAudio');
        const volumeSlider = document.getElementById('volumeSlider');

        // Update loading text
        function updateLoadingText(text) {
            const loadingText = loadingOverlay.querySelector('.loading-text');
            if (loadingText) loadingText.textContent = text;
        }

        // Initialize MediaPipe Holistic with retry
        async function initHolistic() {
            updateLoadingText('Memuat AI Holistic Detection...');
            
            return new Promise((resolve, reject) => {
                try {
                    holistic = new Holistic({
                        locateFile: (file) => {
                            return `https://cdn.jsdelivr.net/npm/@mediapipe/holistic/${file}`;
                        }
                    });

                    holistic.setOptions({
                        modelComplexity: 1,
                        smoothLandmarks: true,
                        refineFaceLandmarks: true,
                        minDetectionConfidence: 0.5,
                        minTrackingConfidence: 0.5
                    });

                    holistic.onResults(onHolisticResults);

                    holistic.initialize().then(() => {
                        console.log('MediaPipe Holistic initialized');
                        isHolisticReady = true;
                        resolve();
                    }).catch(err => {
                        console.error('Holistic init error:', err);
                        reject(err);
                    });

                } catch (err) {
                    console.error('Holistic creation error:', err);
                    reject(err);
                }
            });
        }

        // Initialize Camera with robust error handling
        async function initCamera() {
            updateLoadingText('Mengakses kamera...');
            
            // Stop existing stream first
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }

            const constraints = [
                // Try HD first
                { video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' }, audio: false },
                // Fallback to SD
                { video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }, audio: false },
                // Last resort - any camera
                { video: true, audio: false }
            ];

            let stream = null;
            
            for (const constraint of constraints) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraint);
                    console.log('Camera acquired with:', constraint);
                    break;
                } catch (err) {
                    console.warn('Camera constraint failed:', constraint, err);
                }
            }

            if (!stream) {
                throw new Error('Tidak dapat mengakses kamera. Pastikan kamera tidak digunakan aplikasi lain.');
            }

            cameraStream = stream;
            videoElement.srcObject = stream;

            // Wait for video to be ready
            return new Promise((resolve, reject) => {
                const timeout = setTimeout(() => {
                    reject(new Error('Kamera timeout - tidak merespon'));
                }, 10000);

                videoElement.onloadedmetadata = () => {
                    clearTimeout(timeout);
                    
                    // Set canvas size to match video
                    const videoWidth = videoElement.videoWidth || 640;
                    const videoHeight = videoElement.videoHeight || 480;
                    
                    canvasElement.width = videoWidth;
                    canvasElement.height = videoHeight;
                    
                    console.log(`Video dimensions: ${videoWidth}x${videoHeight}`);
                    
                    // Force video to play
                    videoElement.play().then(() => {
                        isCameraReady = true;
                        cameraStatus.classList.remove('inactive');
                        cameraStatus.classList.add('active');
                        cameraStatusText.textContent = 'Kamera Aktif';
                        
                        resolve();
                    }).catch(err => {
                        console.error('Video play error:', err);
                        reject(err);
                    });
                };

                videoElement.onerror = (err) => {
                    clearTimeout(timeout);
                    reject(new Error('Video element error'));
                };
            });
        }

        // Start frame processing with safety checks
        function startFrameProcessing() {
            if (frameProcessingActive) {
                console.log('Frame processing already active');
                return;
            }
            
            frameProcessingActive = true;
            let lastFrameTime = 0;
            const targetFPS = 15; // Limit to 15 FPS for stability
            const frameInterval = 1000 / targetFPS;
            
            async function processFrame(timestamp) {
                if (!frameProcessingActive) return;
                
                // Throttle frame rate
                if (timestamp - lastFrameTime < frameInterval) {
                    requestAnimationFrame(processFrame);
                    return;
                }
                lastFrameTime = timestamp;

                try {
                    // Check if video is ready
                    if (videoElement.readyState >= 2 && isHolisticReady && isCameraReady) {
                        // Draw video to canvas first (important for visibility)
                        ctx.save();
                        ctx.translate(canvasElement.width, 0);
                        ctx.scale(-1, 1);
                        ctx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                        ctx.restore();
                        
                        // Send to holistic detection
                        await holistic.send({ image: videoElement });
                    } else {
                        // Draw video anyway even if pose not ready
                        if (videoElement.readyState >= 2) {
                            ctx.save();
                            ctx.translate(canvasElement.width, 0);
                            ctx.scale(-1, 1);
                            ctx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                            ctx.restore();
                        }
                    }
                } catch (err) {
                    console.error('Frame processing error:', err);
                }
                
                requestAnimationFrame(processFrame);
            }
            
            requestAnimationFrame(processFrame);
            console.log('Frame processing started');
        }

        // Stop frame processing
        function stopFrameProcessing() {
            frameProcessingActive = false;
        }

        // Process holistic results (pose + face + hands)
        function onHolisticResults(results) {
            // Don't clear canvas - video is already drawn
            
            // Draw all detected landmarks overlay on top of video
            ctx.save();
            ctx.translate(canvasElement.width, 0);
            ctx.scale(-1, 1);

            // 1. Draw Face Mesh (subtle, semi-transparent)
            if (results.faceLandmarks) {
                drawConnectors(ctx, results.faceLandmarks, FACEMESH_TESSELATION, {
                    color: 'rgba(120, 110, 10, 0.3)',
                    lineWidth: 1
                });
                drawLandmarks(ctx, results.faceLandmarks, {
                    color: 'rgba(120, 256, 121, 0.5)',
                    lineWidth: 0.5,
                    radius: 1
                });
            }

            // 2. Draw Right Hand Landmarks (red tones)
            if (results.rightHandLandmarks) {
                drawConnectors(ctx, results.rightHandLandmarks, HAND_CONNECTIONS, {
                    color: 'rgba(80, 22, 10, 0.8)',
                    lineWidth: 2
                });
                drawLandmarks(ctx, results.rightHandLandmarks, {
                    color: '#FF4444',
                    lineWidth: 1,
                    radius: 3
                });
            }

            // 3. Draw Left Hand Landmarks (green tones)
            if (results.leftHandLandmarks) {
                drawConnectors(ctx, results.leftHandLandmarks, HAND_CONNECTIONS, {
                    color: 'rgba(121, 22, 76, 0.8)',
                    lineWidth: 2
                });
                drawLandmarks(ctx, results.leftHandLandmarks, {
                    color: '#44FF44',
                    lineWidth: 1,
                    radius: 3
                });
            }

            // 4. Draw Pose Landmarks (body skeleton - orange)
            if (results.poseLandmarks) {
                drawConnectors(ctx, results.poseLandmarks, POSE_CONNECTIONS, {
                    color: 'rgba(232, 90, 32, 0.8)',
                    lineWidth: 3
                });
                drawLandmarks(ctx, results.poseLandmarks, {
                    color: '#FFFFFF',
                    lineWidth: 1,
                    radius: 5
                });
            }

            ctx.restore();

            // Send all landmark data to backend if session is active
            if (isSessionActive && socket && socket.connected) {
                sendHolisticData(results);
            }
        }

        // Send holistic data to backend (pose + face + hands)
        function sendHolisticData(results) {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = videoElement.videoWidth || 640;
                canvas.height = videoElement.videoHeight || 480;
                const tempCtx = canvas.getContext('2d');
                tempCtx.drawImage(videoElement, 0, 0);
                
                const imageData = canvas.toDataURL('image/jpeg', 0.5);

                socket.emit('pose_frame', {
                    image: imageData,
                    landmarks: results.poseLandmarks || [],
                    faceLandmarks: results.faceLandmarks || null,
                    leftHandLandmarks: results.leftHandLandmarks || null,
                    rightHandLandmarks: results.rightHandLandmarks || null,
                    karakter: selectedKarakter,
                    timestamp: Date.now()
                });
            } catch (err) {
                console.error('Send holistic data error:', err);
            }
        }

        // Initialize WebSocket connection
        function initSocket() {
            try {
                socket = io(WS_URL, {
                    transports: ['websocket', 'polling'],
                    reconnection: true,
                    reconnectionAttempts: 5,
                    reconnectionDelay: 1000
                });

                socket.on('connect', () => {
                    console.log('WebSocket connected');
                    addFeedback('Terhubung ke server AI', 'success');
                });

                socket.on('disconnect', () => {
                    console.log('WebSocket disconnected');
                    addFeedback('Terputus dari server', 'warning');
                });

                socket.on('connect_error', (err) => {
                    console.log('WebSocket connection error:', err.message);
                    // Don't spam feedback - server might not be running
                });

                socket.on('pose_result', (data) => {
                    if (data.error) {
                        console.error('Pose error:', data.error);
                        return;
                    }

                    updateScores(data.feedback);
                    
                    if (data.comparison && data.comparison.feedback) {
                        data.comparison.feedback.forEach(msg => {
                            addFeedback(msg, msg.includes('Bagus') ? 'success' : 'warning');
                        });
                    }
                });

                socket.on('audio_result', (data) => {
                    if (data.beat_detected) {
                        document.body.style.boxShadow = 'inset 0 0 100px rgba(232, 90, 32, 0.3)';
                        setTimeout(() => {
                            document.body.style.boxShadow = 'none';
                        }, 100);
                    }
                });
            } catch (err) {
                console.error('Socket init error:', err);
                addFeedback('Server AI tidak tersedia (mode offline)', 'warning');
            }
        }

        // Update score displays
        function updateScores(feedback) {
            if (!feedback) return;

            const wiraga = feedback.wiraga || 0;
            const wirama = feedback.wirama || 0;
            const wirasa = feedback.wirasa || 0;
            const total = feedback.total || 0;
            const grade = feedback.grade || '-';

            animateValue(totalScoreEl, parseInt(totalScoreEl.textContent) || 0, Math.round(total), 300);
            
            wiragaScoreEl.textContent = `${Math.round(wiraga)}%`;
            wiramaScoreEl.textContent = `${Math.round(wirama)}%`;
            wirasaScoreEl.textContent = `${Math.round(wirasa)}%`;

            wiragaProgress.style.width = `${wiraga}%`;
            wiramaProgress.style.width = `${wirama}%`;
            wirasaProgress.style.width = `${wirasa}%`;

            gradeBadge.textContent = grade;

            if (total >= 85) {
                gradeBadge.style.background = 'var(--success-green)';
            } else if (total >= 60) {
                gradeBadge.style.background = 'var(--warning-yellow)';
            } else {
                gradeBadge.style.background = 'var(--error-red)';
            }
        }

        // Animate value change
        function animateValue(element, start, end, duration) {
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.round(start + (end - start) * progress);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Add feedback message
        function addFeedback(message, type = 'info') {
            const item = document.createElement('div');
            item.className = `feedback-item ${type}`;
            item.textContent = message;
            
            feedbackList.insertBefore(item, feedbackList.firstChild);
            
            while (feedbackList.children.length > 10) {
                feedbackList.removeChild(feedbackList.lastChild);
            }
        }

        // Start practice session
        async function startSession() {
            if (isSessionActive) {
                await endSession();
                return;
            }

            // Generate local session ID if server not available
            sessionId = `local_${Date.now()}`;
            isSessionActive = true;
            sessionStartTime = Date.now();
            
            startSessionBtn.textContent = 'Akhiri Sesi';
            startSessionBtn.classList.remove('btn-primary');
            startSessionBtn.classList.add('btn-danger');
            
            timerInterval = setInterval(updateTimer, 1000);
            
            if (socket && socket.connected) {
                socket.emit('join_session', { session_id: sessionId });
            }
            
            addFeedback(`Sesi dimulai untuk karakter ${selectedKarakter}`, 'success');
        }

        // End practice session
        async function endSession() {
            isSessionActive = false;
            clearInterval(timerInterval);
            
            startSessionBtn.textContent = 'Mulai Sesi';
            startSessionBtn.classList.remove('btn-danger');
            startSessionBtn.classList.add('btn-primary');
            
            if (socket && socket.connected) {
                socket.emit('leave_session', { session_id: sessionId });
            }
            
            addFeedback('Sesi berakhir!', 'success');
        }

        // Update timer display
        function updateTimer() {
            if (!sessionStartTime) return;
            
            const elapsed = Math.floor((Date.now() - sessionStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            timerDisplay.textContent = `${minutes}:${seconds}`;
        }

        // Character selection
        document.querySelectorAll('.character-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.character-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedKarakter = btn.dataset.karakter;
                addFeedback(`Karakter dipilih: ${selectedKarakter}`, 'info');
            });
        });

        // Music control
        musicBtn.addEventListener('click', () => {
            if (gamelanAudio.paused) {
                gamelanAudio.play();
                musicBtn.textContent = '⏸';
            } else {
                gamelanAudio.pause();
                musicBtn.textContent = '🎵';
            }
        });

        // Volume control
        volumeSlider.addEventListener('input', (e) => {
            gamelanAudio.volume = e.target.value / 100;
        });

        // Camera toggle
        cameraBtn.addEventListener('click', async () => {
            if (cameraStream) {
                // Stop camera
                stopFrameProcessing();
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
                videoElement.srcObject = null;
                isCameraReady = false;
                
                // Clear canvas
                ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
                ctx.fillStyle = '#1A1A1A';
                ctx.fillRect(0, 0, canvasElement.width, canvasElement.height);
                ctx.fillStyle = '#A0A0A0';
                ctx.font = '16px Poppins';
                ctx.textAlign = 'center';
                ctx.fillText('Kamera dimatikan', canvasElement.width/2, canvasElement.height/2);
                
                cameraStatus.classList.remove('active');
                cameraStatus.classList.add('inactive');
                cameraStatusText.textContent = 'Kamera Mati';
                addFeedback('Kamera dimatikan', 'info');
            } else {
                // Restart camera
                try {
                    await initCamera();
                    startFrameProcessing();
                    addFeedback('Kamera diaktifkan', 'success');
                } catch (err) {
                    console.error('Camera restart error:', err);
                    addFeedback('Gagal mengaktifkan kamera', 'error');
                }
            }
        });

        // Session button
        startSessionBtn.addEventListener('click', startSession);

        // ============ NEW FEATURE HANDLERS ============

        // Display Settings State
        let showSkeleton = true;
        let showLandmarks = true;
        let mirrorMode = true;
        let countdownEnabled = false;

        // Session Statistics
        let poseCount = 0;
        let correctPoseCount = 0;
        let currentStreak = 0;
        let bestStreak = 0;

        // Display Settings Handlers
        document.getElementById('showSkeleton')?.addEventListener('change', (e) => {
            showSkeleton = e.target.checked;
            addFeedback(`Skeleton ${showSkeleton ? 'ditampilkan' : 'disembunyikan'}`, 'info');
        });

        document.getElementById('showLandmarks')?.addEventListener('change', (e) => {
            showLandmarks = e.target.checked;
            addFeedback(`Titik sendi ${showLandmarks ? 'ditampilkan' : 'disembunyikan'}`, 'info');
        });

        document.getElementById('mirrorMode')?.addEventListener('change', (e) => {
            mirrorMode = e.target.checked;
            videoElement.style.transform = mirrorMode ? 'scaleX(-1)' : 'none';
            addFeedback(`Mode mirror ${mirrorMode ? 'aktif' : 'nonaktif'}`, 'info');
        });

        document.getElementById('countdownMode')?.addEventListener('change', (e) => {
            countdownEnabled = e.target.checked;
            addFeedback(`Countdown ${countdownEnabled ? 'aktif' : 'nonaktif'}`, 'info');
        });

        // Tempo Display
        document.getElementById('tempoSlider')?.addEventListener('input', (e) => {
            const tempo = e.target.value;
            const tempoDisplay = document.getElementById('tempoDisplay');
            if (tempoDisplay) tempoDisplay.textContent = `${tempo}%`;
            
            // Adjust audio playback rate
            if (gamelanAudio) {
                gamelanAudio.playbackRate = tempo / 100;
            }
        });

        // Music Play/Stop Buttons
        document.getElementById('playMusicBtn')?.addEventListener('click', () => {
            const musicSelect = document.getElementById('musicSelect');
            if (musicSelect.value) {
                addFeedback(`Memutar musik: ${musicSelect.options[musicSelect.selectedIndex].text}`, 'success');
                // Placeholder - would load actual audio file
            } else {
                addFeedback('Pilih musik gamelan terlebih dahulu', 'warning');
            }
        });

        document.getElementById('stopMusicBtn')?.addEventListener('click', () => {
            if (gamelanAudio) {
                gamelanAudio.pause();
                gamelanAudio.currentTime = 0;
            }
            addFeedback('Musik dihentikan', 'info');
        });

        // Recording Functions
        let mediaRecorder = null;
        let recordedChunks = [];

        document.getElementById('startRecordingBtn')?.addEventListener('click', async function() {
            const recordingStatusEl = document.getElementById('recordingStatus');
            
            if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                // Start recording
                try {
                    const stream = canvasElement.captureStream(30);
                    mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm' });
                    recordedChunks = [];
                    
                    mediaRecorder.ondataavailable = (e) => {
                        if (e.data.size > 0) recordedChunks.push(e.data);
                    };
                    
                    mediaRecorder.onstop = () => {
                        const blob = new Blob(recordedChunks, { type: 'video/webm' });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `latihan_${selectedKarakter}_${Date.now()}.webm`;
                        a.click();
                        addFeedback('Rekaman disimpan!', 'success');
                    };
                    
                    mediaRecorder.start();
                    this.innerHTML = '<span style="width: 10px; height: 10px; background: white; border-radius: 50%; animation: pulse 1s infinite;"></span> Stop Rekam';
                    this.style.background = '#22C55E';
                    if (recordingStatusEl) recordingStatusEl.textContent = 'Merekam...';
                    addFeedback('Rekaman dimulai', 'success');
                    
                } catch (err) {
                    console.error('Recording error:', err);
                    addFeedback('Gagal memulai rekaman', 'error');
                }
            } else {
                // Stop recording
                mediaRecorder.stop();
                this.innerHTML = '<span style="width: 10px; height: 10px; background: white; border-radius: 50%;"></span> Mulai Rekam';
                this.style.background = 'var(--error-red)';
                if (recordingStatusEl) recordingStatusEl.textContent = 'Tidak aktif';
            }
        });

        // Screenshot Function
        document.getElementById('screenshotBtn')?.addEventListener('click', () => {
            try {
                const link = document.createElement('a');
                link.download = `pose_${selectedKarakter}_${Date.now()}.png`;
                link.href = canvasElement.toDataURL('image/png');
                link.click();
                addFeedback('Screenshot pose disimpan!', 'success');
            } catch (err) {
                console.error('Screenshot error:', err);
                addFeedback('Gagal mengambil screenshot', 'error');
            }
        });

        // PiP Mode (Picture-in-Picture)
        document.getElementById('pipModeBtn')?.addEventListener('click', async () => {
            try {
                if (document.pictureInPictureElement) {
                    await document.exitPictureInPicture();
                    addFeedback('Mode PiP dinonaktifkan', 'info');
                } else if (videoElement.requestPictureInPicture) {
                    await videoElement.requestPictureInPicture();
                    addFeedback('Mode PiP aktif - video dipindah keluar jendela', 'success');
                } else {
                    addFeedback('Browser tidak mendukung PiP', 'warning');
                }
            } catch (err) {
                console.error('PiP error:', err);
                addFeedback('Gagal mengaktifkan PiP', 'error');
            }
        });

        // Compare Mode (Side-by-side with maestro)
        let compareModeActive = false;
        document.getElementById('compareModeBtn')?.addEventListener('click', function() {
            const videoContainer = document.getElementById('videoContainer');
            compareModeActive = !compareModeActive;
            
            if (compareModeActive) {
                // Activate compare mode - split view
                this.style.background = 'var(--primary-orange)';
                this.textContent = '📊 Mode Normal';
                addFeedback('Mode Banding aktif - perbandingan dengan maestro', 'success');
                // Would add maestro reference video side by side
            } else {
                this.style.background = 'var(--bg-card-hover)';
                this.textContent = '📊 Mode Banding';
                addFeedback('Mode Normal aktif', 'info');
            }
        });

        // Update Session Statistics (called from pose results)
        function updateSessionStats(isCorrect = false) {
            poseCount++;
            
            if (isCorrect) {
                correctPoseCount++;
                currentStreak++;
                if (currentStreak > bestStreak) {
                    bestStreak = currentStreak;
                }
            } else {
                currentStreak = 0;
            }
            
            // Update DOM
            const poseCountEl = document.getElementById('poseCount');
            const correctPoseCountEl = document.getElementById('correctPoseCount');
            const avgAccuracyEl = document.getElementById('avgAccuracy');
            const bestStreakEl = document.getElementById('bestStreak');
            
            if (poseCountEl) poseCountEl.textContent = poseCount;
            if (correctPoseCountEl) correctPoseCountEl.textContent = correctPoseCount;
            if (avgAccuracyEl) avgAccuracyEl.textContent = Math.round((correctPoseCount / poseCount) * 100) + '%';
            if (bestStreakEl) bestStreakEl.textContent = bestStreak;
            
            // Update performance graph bar
            const currentPerfBar = document.getElementById('currentPerfBar');
            if (currentPerfBar && poseCount > 0) {
                const accuracy = (correctPoseCount / poseCount) * 100;
                currentPerfBar.style.height = accuracy + '%';
            }
        }

        // Gerakan Practice Select
        document.getElementById('gerakanPracticeSelect')?.addEventListener('change', (e) => {
            const gerakan = e.target.value;
            if (gerakan) {
                addFeedback(`Fokus gerakan: ${e.target.options[e.target.selectedIndex].text}`, 'info');
            } else {
                addFeedback('Latihan semua gerakan', 'info');
            }
        });

        // Initialize application with retry
        async function init() {
            try {
                // Step 1: Initialize Holistic
                await initHolistic();
                updateLoadingText('AI Holistic siap!');
                
                // Step 2: Initialize Socket (non-blocking)
                initSocket();
                
                // Step 3: Initialize Camera
                await initCamera();
                updateLoadingText('Kamera siap!');
                
                // Step 4: Start processing
                startFrameProcessing();
                
                // Hide loading overlay
                loadingOverlay.classList.add('hidden');
                addFeedback('Sistem siap! Pilih karakter dan mulai berlatih.', 'success');
                
            } catch (err) {
                console.error('Initialization error:', err);
                retryCount++;
                
                if (retryCount < MAX_RETRIES) {
                    updateLoadingText(`Gagal memuat. Mencoba lagi (${retryCount}/${MAX_RETRIES})...`);
                    setTimeout(init, 2000);
                } else {
                    updateLoadingText('Gagal memuat. Periksa izin kamera dan refresh halaman.');
                    loadingOverlay.querySelector('.loading-spinner').style.borderTopColor = '#EF4444';
                }
            }
        }

        // Start initialization when page loads
        window.addEventListener('DOMContentLoaded', init);
    </script>
        </main>
    </div>
</body>
</html>