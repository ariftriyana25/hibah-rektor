<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Tutorial Tari Topeng</title>
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
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
        }
        /* Layout handled by sidebar partial */
        .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        .nav-links a {
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary-orange);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }
        .karakter-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        .karakter-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 1.5rem;
            background: var(--bg-card);
            border: 2px solid transparent;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 120px;
        }
        .karakter-tab:hover {
            background: var(--bg-card-hover);
        }
        .karakter-tab.active {
            border-color: var(--primary-orange);
            background: rgba(232, 90, 32, 0.1);
        }
        .karakter-tab .icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .karakter-tab .name {
            font-weight: 600;
        }
        .karakter-tab .level {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
        }
        @media (max-width: 900px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        .sidebar-panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        .sidebar-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .gerakan-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .gerakan-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .gerakan-item:hover {
            background: rgba(255,255,255,0.06);
        }
        .gerakan-item.active {
            border-color: var(--primary-orange);
            background: rgba(232, 90, 32, 0.1);
        }
        .gerakan-item .number {
            width: 28px;
            height: 28px;
            background: var(--bg-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .gerakan-item.completed .number {
            background: var(--success-green);
        }
        .gerakan-item .info h4 {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .gerakan-item .info p {
            font-size: 0.7rem;
            color: var(--text-gray);
        }
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .panel {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
        }
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .panel-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .video-container {
            position: relative;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }
        .video-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-hover) 100%);
        }
        .video-placeholder .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        .video-placeholder p {
            color: var(--text-gray);
        }
        .video-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        .video-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        .video-btn.primary {
            background: var(--primary-orange);
            color: white;
        }
        .video-btn.primary:hover {
            background: var(--primary-orange-hover);
        }
        .video-btn.secondary {
            background: var(--bg-card-hover);
            color: white;
        }
        .description-content {
            line-height: 1.8;
            color: var(--text-gray);
        }
        .description-content h3 {
            color: var(--text-white);
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }
        .description-content h3:first-child {
            margin-top: 0;
        }
        .description-content ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }
        .description-content li {
            margin-bottom: 0.25rem;
        }
        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .tip-card {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 1rem;
            border-left: 3px solid var(--primary-orange);
        }
        .tip-card h4 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .tip-card p {
            font-size: 0.8rem;
            color: var(--text-gray);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
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

        <main class="main-content" style="padding: 2rem; overflow-y: auto;">
    <div class="container">
        <div class="page-header">
            <h1>📚 Tutorial Tari Topeng</h1>
            <p>Pelajari gerakan dasar hingga mahir dari 5 karakter Tari Topeng Cirebon</p>
        </div>

        <!-- Karakter Tabs -->
        <div class="karakter-tabs">
            <div class="karakter-tab active" data-karakter="panji">
                <span class="icon">🎭</span>
                <span class="name">Panji</span>
                <span class="level">12 Gerakan</span>
            </div>
            <div class="karakter-tab" data-karakter="samba">
                <span class="icon">👹</span>
                <span class="name">Samba</span>
                <span class="level">10 Gerakan</span>
            </div>
            <div class="karakter-tab" data-karakter="rumyang">
                <span class="icon">🌸</span>
                <span class="name">Rumyang</span>
                <span class="level">8 Gerakan</span>
            </div>
            <div class="karakter-tab" data-karakter="tumenggung">
                <span class="icon">⚔️</span>
                <span class="name">Tumenggung</span>
                <span class="level">10 Gerakan</span>
            </div>
            <div class="karakter-tab" data-karakter="klana">
                <span class="icon">👺</span>
                <span class="name">Klana</span>
                <span class="level">14 Gerakan</span>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Sidebar -->
            <div class="sidebar-panel">
                <h3 class="sidebar-title">📝 Daftar Gerakan</h3>
                <div class="gerakan-list">
                    <div class="gerakan-item active" data-gerakan="sembahan">
                        <span class="number">1</span>
                        <div class="info">
                            <h4>Sembahan Awal</h4>
                            <p>Gerakan pembuka</p>
                        </div>
                    </div>
                    <div class="gerakan-item completed" data-gerakan="nindak">
                        <span class="number">✓</span>
                        <div class="info">
                            <h4>Nindak</h4>
                            <p>Langkah dasar</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="tanjak">
                        <span class="number">3</span>
                        <div class="info">
                            <h4>Tanjak</h4>
                            <p>Posisi dasar</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="ngigel">
                        <span class="number">4</span>
                        <div class="info">
                            <h4>Ngigel</h4>
                            <p>Gerakan tangan</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="nyawang">
                        <span class="number">5</span>
                        <div class="info">
                            <h4>Nyawang</h4>
                            <p>Pandangan mata</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="capang">
                        <span class="number">6</span>
                        <div class="info">
                            <h4>Capang</h4>
                            <p>Gerakan kaki</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="klepat">
                        <span class="number">7</span>
                        <div class="info">
                            <h4>Klepat</h4>
                            <p>Putaran badan</p>
                        </div>
                    </div>
                    <div class="gerakan-item" data-gerakan="sembahan_akhir">
                        <span class="number">8</span>
                        <div class="info">
                            <h4>Sembahan Akhir</h4>
                            <p>Gerakan penutup</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Video Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">🎬 Video Tutorial: Sembahan Awal</h2>
                    </div>
                    <div class="video-container">
                        <div class="video-placeholder">
                            <span class="icon">▶️</span>
                            <p>Klik untuk memutar video tutorial dari maestro</p>
                        </div>
                    </div>
                    <div class="video-controls">
                        <button class="video-btn secondary">⏮️ Sebelumnya</button>
                        <button class="video-btn primary">▶️ Putar Video</button>
                        <button class="video-btn secondary">Selanjutnya ⏭️</button>
                    </div>
                </div>

                <!-- Description Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">📖 Deskripsi Gerakan</h2>
                    </div>
                    <div class="description-content">
                        <h3>Sembahan Awal</h3>
                        <p>Sembahan adalah gerakan penghormatan yang dilakukan di awal tarian. Gerakan ini menunjukkan rasa hormat kepada penonton, sesama penari, dan Yang Maha Kuasa.</p>
                        
                        <h3>Posisi Tubuh</h3>
                        <ul>
                            <li>Duduk bersimpuh dengan kedua kaki terlipat ke belakang</li>
                            <li>Punggung tegak namun tidak kaku</li>
                            <li>Kepala sedikit menunduk sebagai tanda hormat</li>
                        </ul>
                        
                        <h3>Posisi Tangan</h3>
                        <ul>
                            <li>Kedua telapak tangan disatukan di depan dada</li>
                            <li>Jari-jari mengarah ke atas, ibu jari menyentuh dada</li>
                            <li>Siku rileks, tidak terangkat tinggi</li>
                        </ul>
                        
                        <h3>Ekspresi</h3>
                        <ul>
                            <li>Mata tertutup atau setengah tertutup</li>
                            <li>Wajah tenang dan khusyuk</li>
                            <li>Napas perlahan dan teratur</li>
                        </ul>
                    </div>
                </div>

                <!-- Tips Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">💡 Tips & Catatan</h2>
                    </div>
                    <div class="tips-grid">
                        <div class="tip-card">
                            <h4>⏱️ Durasi</h4>
                            <p>Tahan posisi sembahan selama 4-8 hitungan gamelan</p>
                        </div>
                        <div class="tip-card">
                            <h4>🎵 Irama</h4>
                            <p>Mulai bersamaan dengan bunyi gong pembuka</p>
                        </div>
                        <div class="tip-card">
                            <h4>👁️ Fokus</h4>
                            <p>Jaga konsentrasi dan kekhusyukan selama gerakan</p>
                        </div>
                        <div class="tip-card">
                            <h4>✨ Kualitas</h4>
                            <p>Gerakan halus dan mengalir, tidak patah-patah</p>
                        </div>
                    </div>
                </div>

                <!-- Action -->
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ route('practice') }}" class="btn btn-primary">🎭 Mulai Latihan Gerakan Ini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tutorial Data
        const karakterData = {
            panji: {
                name: 'Panji',
                icon: '🎭',
                description: 'Karakter halus dan lembut, melambangkan keluhuran dan kesucian',
                gerakan: [
                    { id: 'sembahan', name: 'Sembahan Awal', desc: 'Gerakan pembuka', duration: '8 hitungan', completed: false },
                    { id: 'nindak', name: 'Nindak', desc: 'Langkah dasar', duration: '4 hitungan', completed: true },
                    { id: 'tanjak', name: 'Tanjak', desc: 'Posisi dasar', duration: '4 hitungan', completed: false },
                    { id: 'ngigel', name: 'Ngigel', desc: 'Gerakan tangan', duration: '8 hitungan', completed: false },
                    { id: 'nyawang', name: 'Nyawang', desc: 'Pandangan mata', duration: '4 hitungan', completed: false },
                    { id: 'capang', name: 'Capang', desc: 'Gerakan kaki', duration: '6 hitungan', completed: false },
                    { id: 'klepat', name: 'Klepat', desc: 'Putaran badan', duration: '8 hitungan', completed: false },
                    { id: 'sembahan_akhir', name: 'Sembahan Akhir', desc: 'Gerakan penutup', duration: '8 hitungan', completed: false }
                ]
            },
            samba: {
                name: 'Samba',
                icon: '👹',
                description: 'Karakter gagah dan jenaka, melambangkan kegagahan dan humor',
                gerakan: [
                    { id: 'sembahan_samba', name: 'Sembahan Samba', desc: 'Pembukaan gagah', duration: '6 hitungan', completed: false },
                    { id: 'trecet', name: 'Trecet', desc: 'Langkah cepat', duration: '4 hitungan', completed: false },
                    { id: 'ngalaga', name: 'Ngalaga', desc: 'Pose gagah', duration: '8 hitungan', completed: false },
                    { id: 'godeg', name: 'Godeg', desc: 'Gerakan kepala', duration: '4 hitungan', completed: false },
                    { id: 'mincid', name: 'Mincid', desc: 'Loncatan ringan', duration: '6 hitungan', completed: false }
                ]
            },
            rumyang: {
                name: 'Rumyang',
                icon: '🌸',
                description: 'Karakter anggun dan dewasa, melambangkan kedewasaan wanita',
                gerakan: [
                    { id: 'sembahan_rumyang', name: 'Sembahan Rumyang', desc: 'Pembukaan anggun', duration: '8 hitungan', completed: false },
                    { id: 'keupat', name: 'Keupat', desc: 'Gerakan tangan anggun', duration: '6 hitungan', completed: false },
                    { id: 'obah_bahu', name: 'Obah Bahu', desc: 'Gerakan bahu', duration: '4 hitungan', completed: false },
                    { id: 'geol', name: 'Geol', desc: 'Gerakan pinggul', duration: '8 hitungan', completed: false }
                ]
            },
            tumenggung: {
                name: 'Tumenggung',
                icon: '⚔️',
                description: 'Karakter gagah berani, melambangkan kepahlawanan dan kewibawaan',
                gerakan: [
                    { id: 'sembahan_tum', name: 'Sembahan Tumenggung', desc: 'Pembukaan tegas', duration: '6 hitungan', completed: false },
                    { id: 'adeg', name: 'Adeg-adeg', desc: 'Kuda-kuda', duration: '4 hitungan', completed: false },
                    { id: 'jangkung', name: 'Jangkung Ilo', desc: 'Langkah gagah', duration: '8 hitungan', completed: false },
                    { id: 'ngabret', name: 'Ngabret', desc: 'Gerakan tegas', duration: '4 hitungan', completed: false },
                    { id: 'nyeredet', name: 'Nyeredet', desc: 'Langkah cepat', duration: '6 hitungan', completed: false }
                ]
            },
            klana: {
                name: 'Klana',
                icon: '👺',
                description: 'Karakter gagah dan angkara, melambangkan keangkaramurkaan',
                gerakan: [
                    { id: 'sembahan_klana', name: 'Sembahan Klana', desc: 'Pembukaan angkuh', duration: '8 hitungan', completed: false },
                    { id: 'ngabret_klana', name: 'Ngabret', desc: 'Gerakan keras', duration: '4 hitungan', completed: false },
                    { id: 'gangsingan', name: 'Gangsingan', desc: 'Putaran kuat', duration: '8 hitungan', completed: false },
                    { id: 'bantingan', name: 'Bantingan', desc: 'Gerakan menghempas', duration: '6 hitungan', completed: false },
                    { id: 'ngepret', name: 'Ngepret', desc: 'Sentakan', duration: '4 hitungan', completed: false },
                    { id: 'ngebrag', name: 'Ngebrag', desc: 'Gerakan menghentak', duration: '6 hitungan', completed: false }
                ]
            }
        };

        // Gerakan Details Data
        const gerakanDetails = {
            sembahan: {
                title: 'Sembahan Awal',
                description: `<h3>Sembahan Awal</h3>
                    <p>Sembahan adalah gerakan penghormatan yang dilakukan di awal tarian. Gerakan ini menunjukkan rasa hormat kepada penonton, sesama penari, dan Yang Maha Kuasa.</p>
                    
                    <h3>Posisi Tubuh</h3>
                    <ul>
                        <li>Duduk bersimpuh dengan kedua kaki terlipat ke belakang</li>
                        <li>Punggung tegak namun tidak kaku</li>
                        <li>Kepala sedikit menunduk sebagai tanda hormat</li>
                    </ul>
                    
                    <h3>Posisi Tangan</h3>
                    <ul>
                        <li>Kedua telapak tangan disatukan di depan dada</li>
                        <li>Jari-jari mengarah ke atas, ibu jari menyentuh dada</li>
                        <li>Siku rileks, tidak terangkat tinggi</li>
                    </ul>
                    
                    <h3>Ekspresi</h3>
                    <ul>
                        <li>Mata tertutup atau setengah tertutup</li>
                        <li>Wajah tenang dan khusyuk</li>
                        <li>Napas perlahan dan teratur</li>
                    </ul>`,
                tips: [
                    { icon: '⏱️', title: 'Durasi', text: 'Tahan posisi sembahan selama 4-8 hitungan gamelan' },
                    { icon: '🎵', title: 'Irama', text: 'Mulai bersamaan dengan bunyi gong pembuka' },
                    { icon: '👁️', title: 'Fokus', text: 'Jaga konsentrasi dan kekhusyukan selama gerakan' },
                    { icon: '✨', title: 'Kualitas', text: 'Gerakan halus dan mengalir, tidak patah-patah' }
                ]
            },
            nindak: {
                title: 'Nindak',
                description: `<h3>Nindak</h3>
                    <p>Nindak adalah gerakan langkah dasar dalam Tari Topeng yang menjadi fondasi untuk gerakan-gerakan lainnya.</p>
                    
                    <h3>Teknik Dasar</h3>
                    <ul>
                        <li>Langkahkan kaki kanan ke depan dengan tumit menyentuh lantai terlebih dahulu</li>
                        <li>Berat badan dipindahkan secara perlahan</li>
                        <li>Kaki kiri mengikuti dengan gerakan menyeret ringan</li>
                    </ul>
                    
                    <h3>Posisi Badan</h3>
                    <ul>
                        <li>Badan sedikit condong ke depan</li>
                        <li>Lutut selalu dalam posisi ditekuk ringan</li>
                        <li>Pandangan lurus ke depan</li>
                    </ul>`,
                tips: [
                    { icon: '👣', title: 'Langkah', text: 'Langkah pendek dan terkontrol' },
                    { icon: '⚖️', title: 'Keseimbangan', text: 'Jaga pusat gravitasi di tengah' },
                    { icon: '🎯', title: 'Fokus', text: 'Perhatikan timing dengan gamelan' }
                ]
            },
            tanjak: {
                title: 'Tanjak',
                description: `<h3>Tanjak</h3>
                    <p>Tanjak adalah posisi dasar berdiri dalam Tari Topeng yang menunjukkan kesiapan penari.</p>
                    
                    <h3>Posisi Kaki</h3>
                    <ul>
                        <li>Kaki dibuka selebar bahu</li>
                        <li>Lutut sedikit ditekuk</li>
                        <li>Berat badan merata di kedua kaki</li>
                    </ul>
                    
                    <h3>Posisi Tangan</h3>
                    <ul>
                        <li>Tangan di samping badan dengan siku ditekuk</li>
                        <li>Telapak tangan menghadap ke bawah</li>
                        <li>Jari-jari rileks dan teratur</li>
                    </ul>`,
                tips: [
                    { icon: '🧘', title: 'Stabilitas', text: 'Posisi harus stabil dan kokoh' },
                    { icon: '💪', title: 'Kekuatan', text: 'Gunakan otot paha untuk menahan posisi' }
                ]
            }
        };

        let currentKarakter = 'panji';
        let currentGerakan = 'sembahan';
        let currentGerakanIndex = 0;

        // DOM Elements
        const gerakanList = document.querySelector('.gerakan-list');
        const videoTitle = document.querySelector('.video-container').parentElement.querySelector('.panel-title');
        const descriptionContent = document.querySelector('.description-content');
        const tipsGrid = document.querySelector('.tips-grid');
        const videoContainer = document.querySelector('.video-container');

        // Initialize
        function init() {
            loadKarakterGerakan(currentKarakter);
            loadGerakanDetails(currentGerakan);
            setupEventListeners();
        }

        // Load gerakan list for karakter
        function loadKarakterGerakan(karakter) {
            const data = karakterData[karakter];
            if (!data) return;

            gerakanList.innerHTML = '';
            
            data.gerakan.forEach((gerakan, index) => {
                const item = document.createElement('div');
                item.className = `gerakan-item ${index === 0 ? 'active' : ''} ${gerakan.completed ? 'completed' : ''}`;
                item.dataset.gerakan = gerakan.id;
                item.dataset.index = index;
                
                item.innerHTML = `
                    <span class="number">${gerakan.completed ? '✓' : index + 1}</span>
                    <div class="info">
                        <h4>${gerakan.name}</h4>
                        <p>${gerakan.desc} • ${gerakan.duration}</p>
                    </div>
                `;
                
                item.addEventListener('click', () => selectGerakan(gerakan.id, index));
                gerakanList.appendChild(item);
            });

            // Update first gerakan as current
            if (data.gerakan.length > 0) {
                currentGerakan = data.gerakan[0].id;
                currentGerakanIndex = 0;
                loadGerakanDetails(currentGerakan);
            }
        }

        // Select a gerakan
        function selectGerakan(gerakanId, index) {
            currentGerakan = gerakanId;
            currentGerakanIndex = index;

            // Update active state
            document.querySelectorAll('.gerakan-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`.gerakan-item[data-gerakan="${gerakanId}"]`).classList.add('active');

            // Load details
            loadGerakanDetails(gerakanId);
        }

        // Load gerakan details
        function loadGerakanDetails(gerakanId) {
            const details = gerakanDetails[gerakanId];
            const karakter = karakterData[currentKarakter];
            const gerakan = karakter.gerakan.find(g => g.id === gerakanId);
            
            if (details) {
                // Update video title
                videoTitle.textContent = `🎬 Video Tutorial: ${details.title}`;
                
                // Update description
                descriptionContent.innerHTML = details.description;
                
                // Update tips
                tipsGrid.innerHTML = '';
                details.tips.forEach(tip => {
                    const tipCard = document.createElement('div');
                    tipCard.className = 'tip-card';
                    tipCard.innerHTML = `
                        <h4>${tip.icon} ${tip.title}</h4>
                        <p>${tip.text}</p>
                    `;
                    tipsGrid.appendChild(tipCard);
                });
            } else if (gerakan) {
                // Generic content for gerakan without detailed data
                videoTitle.textContent = `🎬 Video Tutorial: ${gerakan.name}`;
                
                descriptionContent.innerHTML = `
                    <h3>${gerakan.name}</h3>
                    <p>Gerakan ${gerakan.desc.toLowerCase()} dari karakter ${karakter.name}.</p>
                    <p>Durasi: ${gerakan.duration}</p>
                    
                    <h3>Petunjuk Umum</h3>
                    <ul>
                        <li>Perhatikan postur tubuh yang benar</li>
                        <li>Ikuti irama gamelan dengan tepat</li>
                        <li>Jaga keseimbangan dan kontrol gerakan</li>
                        <li>Ekspresikan karakter ${karakter.name} melalui gerakan</li>
                    </ul>
                    
                    <h3>Karakter ${karakter.name}</h3>
                    <p>${karakter.description}</p>
                `;
                
                tipsGrid.innerHTML = `
                    <div class="tip-card">
                        <h4>⏱️ Durasi</h4>
                        <p>${gerakan.duration}</p>
                    </div>
                    <div class="tip-card">
                        <h4>🎭 Karakter</h4>
                        <p>${karakter.name}</p>
                    </div>
                    <div class="tip-card">
                        <h4>📝 Deskripsi</h4>
                        <p>${gerakan.desc}</p>
                    </div>
                `;
            }

            // Update video placeholder
            updateVideoPlaceholder(gerakan || { name: gerakanId });
        }

        // Update video placeholder
        function updateVideoPlaceholder(gerakan) {
            const placeholder = videoContainer.querySelector('.video-placeholder');
            if (placeholder) {
                placeholder.innerHTML = `
                    <span class="icon">▶️</span>
                    <p>Video tutorial: ${gerakan.name}</p>
                    <p style="font-size: 0.8rem; margin-top: 0.5rem; opacity: 0.7">Klik untuk memutar video dari maestro</p>
                `;
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Karakter tab switching
            document.querySelectorAll('.karakter-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.karakter-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    
                    currentKarakter = tab.dataset.karakter;
                    loadKarakterGerakan(currentKarakter);
                });
            });

            // Video controls
            document.querySelectorAll('.video-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const text = btn.textContent.trim();
                    
                    if (text.includes('Sebelumnya')) {
                        navigateGerakan(-1);
                    } else if (text.includes('Selanjutnya')) {
                        navigateGerakan(1);
                    } else if (text.includes('Putar')) {
                        playVideo();
                    }
                });
            });

            // Video placeholder click
            videoContainer.addEventListener('click', playVideo);
        }

        // Navigate between gerakan
        function navigateGerakan(direction) {
            const karakter = karakterData[currentKarakter];
            const newIndex = currentGerakanIndex + direction;
            
            if (newIndex >= 0 && newIndex < karakter.gerakan.length) {
                const gerakan = karakter.gerakan[newIndex];
                selectGerakan(gerakan.id, newIndex);
            }
        }

        // Play video (placeholder)
        function playVideo() {
            const placeholder = videoContainer.querySelector('.video-placeholder');
            if (placeholder) {
                placeholder.innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <div style="font-size: 3rem; animation: pulse 1.5s infinite;">🎬</div>
                        <p style="margin-top: 1rem;">Video maestro akan dimuat...</p>
                        <p style="font-size: 0.8rem; opacity: 0.7; margin-top: 0.5rem;">
                            (Fitur video sedang dalam pengembangan)
                        </p>
                        <button onclick="resetVideoPlaceholder()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: var(--primary-orange); border: none; border-radius: 8px; color: white; cursor: pointer;">
                            Kembali
                        </button>
                    </div>
                `;
            }
        }

        // Reset video placeholder
        function resetVideoPlaceholder() {
            const karakter = karakterData[currentKarakter];
            const gerakan = karakter.gerakan[currentGerakanIndex];
            updateVideoPlaceholder(gerakan);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', init);
    </script>
        </main>
    </div>
</body>
</html>
