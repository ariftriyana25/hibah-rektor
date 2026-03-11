<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Pengaturan</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .settings-section {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .setting-item:last-child {
            border-bottom: none;
        }
        .setting-info h4 {
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        .setting-info p {
            font-size: 0.8rem;
            color: var(--text-gray);
        }
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255,255,255,0.1);
            border-radius: 26px;
            transition: 0.4s;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
        }
        input:checked + .toggle-slider {
            background-color: var(--primary-orange);
        }
        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        .select-input {
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
            min-width: 150px;
        }
        .slider-input {
            width: 150px;
            accent-color: var(--primary-orange);
        }
        .slider-value {
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 0.5rem;
            min-width: 40px;
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
        .btn-danger {
            background: var(--error-red);
            color: white;
        }
        .btn-secondary {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .danger-zone {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error-red);
        }
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid var(--success-green);
            color: var(--success-green);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: none;
        }
        .slider-container {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <main class="main-content" style="padding: 0; overflow-y: auto;">
    <div class="container">
        <h1 class="page-title">⚙️ Pengaturan</h1>

        <div class="success-message" id="saveSuccess">
            ✓ Pengaturan berhasil disimpan!
        </div>

        <!-- Camera & Display Settings -->
        <div class="settings-section">
            <h3 class="section-title">📹 Kamera & Tampilan</h3>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Kamera Default</h4>
                    <p>Pilih kamera yang akan digunakan untuk latihan</p>
                </div>
                <select class="select-input" id="cameraSelect">
                    <option value="default">Kamera Utama</option>
                    <option value="external">Kamera Eksternal</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Kualitas Video</h4>
                    <p>Kualitas lebih tinggi membutuhkan koneksi lebih cepat</p>
                </div>
                <select class="select-input" id="videoQuality">
                    <option value="low">480p (Hemat Data)</option>
                    <option value="medium" selected>720p (Standar)</option>
                    <option value="high">1080p (Tinggi)</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Tampilkan Skeleton</h4>
                    <p>Menampilkan visualisasi pose skeleton saat latihan</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Mirror Mode</h4>
                    <p>Cerminkan tampilan kamera seperti cermin</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Audio Settings -->
        <div class="settings-section">
            <h3 class="section-title">🔊 Audio</h3>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Volume Musik</h4>
                    <p>Volume gamelan saat latihan</p>
                </div>
                <div class="slider-container">
                    <input type="range" class="slider-input" min="0" max="100" value="70" id="musicVolume">
                    <span class="slider-value" id="musicVolumeValue">70%</span>
                </div>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Volume Feedback</h4>
                    <p>Volume suara notifikasi feedback</p>
                </div>
                <div class="slider-container">
                    <input type="range" class="slider-input" min="0" max="100" value="50" id="feedbackVolume">
                    <span class="slider-value" id="feedbackVolumeValue">50%</span>
                </div>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Suara Feedback</h4>
                    <p>Aktifkan suara saat menerima feedback</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Practice Settings -->
        <div class="settings-section">
            <h3 class="section-title">🎭 Latihan</h3>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Tingkat Kesulitan Default</h4>
                    <p>Tingkat kesulitan saat memulai latihan baru</p>
                </div>
                <select class="select-input">
                    <option value="easy">Mudah</option>
                    <option value="medium" selected>Menengah</option>
                    <option value="hard">Sulit</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Hitung Mundur Sebelum Mulai</h4>
                    <p>Waktu persiapan sebelum latihan dimulai</p>
                </div>
                <select class="select-input">
                    <option value="0">Tanpa Hitung Mundur</option>
                    <option value="3" selected>3 Detik</option>
                    <option value="5">5 Detik</option>
                    <option value="10">10 Detik</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Tampilkan Referensi Maestro</h4>
                    <p>Tampilkan video maestro sebagai panduan</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Auto-save Sesi</h4>
                    <p>Simpan sesi latihan secara otomatis</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="settings-section">
            <h3 class="section-title">🔔 Notifikasi</h3>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Pengingat Latihan</h4>
                    <p>Ingatkan untuk latihan setiap hari</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Update Leaderboard</h4>
                    <p>Notifikasi saat peringkat berubah</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Achievement</h4>
                    <p>Notifikasi saat mendapat achievement baru</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="settings-section danger-zone">
            <h3 class="section-title">⚠️ Zona Bahaya</h3>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Reset Progress</h4>
                    <p>Hapus semua data latihan dan mulai dari awal. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <button class="btn btn-danger" onclick="confirmReset()">Reset Semua</button>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>Hapus Akun</h4>
                    <p>Hapus akun dan semua data secara permanen.</p>
                </div>
                <button class="btn btn-danger" onclick="confirmDelete()">Hapus Akun</button>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button class="btn btn-primary" onclick="saveSettings()">💾 Simpan Pengaturan</button>
        </div>
    </div>

    <script>
        // Volume sliders
        document.getElementById('musicVolume').addEventListener('input', function() {
            document.getElementById('musicVolumeValue').textContent = this.value + '%';
        });
        document.getElementById('feedbackVolume').addEventListener('input', function() {
            document.getElementById('feedbackVolumeValue').textContent = this.value + '%';
        });

        function saveSettings() {
            // Save to localStorage or server
            const settings = {
                camera: document.getElementById('cameraSelect').value,
                videoQuality: document.getElementById('videoQuality').value,
                musicVolume: document.getElementById('musicVolume').value,
                feedbackVolume: document.getElementById('feedbackVolume').value,
            };
            localStorage.setItem('citraSettings', JSON.stringify(settings));
            
            document.getElementById('saveSuccess').style.display = 'block';
            setTimeout(() => {
                document.getElementById('saveSuccess').style.display = 'none';
            }, 3000);
        }

        function confirmReset() {
            if (confirm('Apakah Anda yakin ingin mereset semua progress? Tindakan ini tidak dapat dibatalkan.')) {
                // Reset action
                alert('Progress telah direset.');
            }
        }

        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus akun? Semua data akan hilang secara permanen.')) {
                // Delete account action
                window.location.href = '/delete-account';
            }
        }

        // Load saved settings
        const savedSettings = localStorage.getItem('citraSettings');
        if (savedSettings) {
            const settings = JSON.parse(savedSettings);
            if (settings.camera) document.getElementById('cameraSelect').value = settings.camera;
            if (settings.videoQuality) document.getElementById('videoQuality').value = settings.videoQuality;
            if (settings.musicVolume) {
                document.getElementById('musicVolume').value = settings.musicVolume;
                document.getElementById('musicVolumeValue').textContent = settings.musicVolume + '%';
            }
            if (settings.feedbackVolume) {
                document.getElementById('feedbackVolume').value = settings.feedbackVolume;
                document.getElementById('feedbackVolumeValue').textContent = settings.feedbackVolume + '%';
            }
        }
    </script>
        </main>
    </div>
</body>
</html>
