<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Tentang Kami</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary-orange: #E85A20;
            --bg-dark: #0D0D0D;
            --bg-card: #1A1A1A;
            --text-white: #FFFFFF;
            --text-gray: #A0A0A0;
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
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        .hero-section {
            text-align: center;
            margin-bottom: 4rem;
        }
        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .hero-section h1 span {
            color: var(--primary-orange);
        }
        .hero-section p {
            font-size: 1.1rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.8;
        }
        .section {
            margin-bottom: 4rem;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .content {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 2rem;
            line-height: 1.8;
            color: var(--text-gray);
        }
        .content h3 {
            color: var(--text-white);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .content h3:first-child {
            margin-top: 0;
        }
        .content ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }
        .content li {
            margin-bottom: 0.5rem;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .team-member {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
        }
        .team-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-orange), #FF8C42);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        .team-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .team-role {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .tech-item {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }
        .tech-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        .tech-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .contact-item {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .contact-icon {
            width: 50px;
            height: 50px;
            background: rgba(232, 90, 32, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .contact-info h4 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        .contact-info p {
            font-size: 0.85rem;
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
        <nav style="display: flex; gap: 1rem;">
            <a href="{{ url('/') }}" style="color: var(--text-gray); text-decoration: none;">Beranda</a>
            <a href="{{ route('about') }}" style="color: var(--primary-orange); text-decoration: none;">Tentang</a>
        </nav>
    </header>

    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1>Tentang <span>CITRA</span></h1>
            <p>Platform pembelajaran Tari Topeng Cirebon berbasis kecerdasan buatan untuk melestarikan warisan budaya Indonesia.</p>
        </div>

        <!-- Visi & Misi -->
        <div class="section">
            <h2 class="section-title">🎯 Visi & Misi</h2>
            <div class="content">
                <h3>Visi</h3>
                <p>Menjadi platform edukasi tari tradisional terdepan di Indonesia yang mengintegrasikan teknologi AI untuk pelestarian dan pengembangan budaya.</p>
                
                <h3>Misi</h3>
                <ul>
                    <li>Melestarikan Tari Topeng Cirebon melalui teknologi digital</li>
                    <li>Memudahkan akses pembelajaran tari tradisional bagi semua kalangan</li>
                    <li>Menerapkan kecerdasan buatan untuk evaluasi gerakan yang akurat</li>
                    <li>Mendokumentasikan gerakan maestro sebagai referensi golden standard</li>
                    <li>Membangun komunitas pecinta tari tradisional Indonesia</li>
                </ul>
            </div>
        </div>

        <!-- Latar Belakang -->
        <div class="section">
            <h2 class="section-title">📜 Latar Belakang</h2>
            <div class="content">
                <p>Tari Topeng Cirebon adalah salah satu warisan budaya tak benda Indonesia yang perlu dilestarikan. Namun, pembelajaran tari tradisional sering terkendala oleh keterbatasan akses ke maestro dan kurangnya metode evaluasi yang objektif.</p>
                
                <p>CITRA (Cirebon Traditional Art) hadir sebagai solusi dengan memanfaatkan teknologi Deep Learning dan Motion Analysis untuk:</p>
                <ul>
                    <li><strong>Wiraga (Gerak)</strong> - Menganalisis ketepatan pose tubuh menggunakan MediaPipe</li>
                    <li><strong>Wirama (Irama)</strong> - Mengevaluasi sinkronisasi dengan musik gamelan</li>
                    <li><strong>Wirasa (Ekspresi)</strong> - Mendeteksi ekspresi dan orientasi kepala</li>
                </ul>
            </div>
        </div>

        <!-- Tim -->
        <div class="section">
            <h2 class="section-title">👥 Tim Pengembang</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="team-avatar">👨‍🎓</div>
                    <div class="team-name">Dr. Pembimbing</div>
                    <div class="team-role">Dosen Pembimbing</div>
                </div>
                <div class="team-member">
                    <div class="team-avatar">👨‍💻</div>
                    <div class="team-name">Tim Mahasiswa</div>
                    <div class="team-role">Pengembang Utama</div>
                </div>
                <div class="team-member">
                    <div class="team-avatar">🎭</div>
                    <div class="team-name">Maestro Tari</div>
                    <div class="team-role">Konsultan Budaya</div>
                </div>
                <div class="team-member">
                    <div class="team-avatar">🎵</div>
                    <div class="team-name">Seniman Gamelan</div>
                    <div class="team-role">Konsultan Musik</div>
                </div>
            </div>
        </div>

        <!-- Teknologi -->
        <div class="section">
            <h2 class="section-title">⚡ Teknologi</h2>
            <div class="tech-grid">
                <div class="tech-item">
                    <div class="tech-icon">🐍</div>
                    <div class="tech-name">Python</div>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">🔥</div>
                    <div class="tech-name">Flask</div>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">⚔️</div>
                    <div class="tech-name">Laravel</div>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">🤖</div>
                    <div class="tech-name">MediaPipe</div>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">🧠</div>
                    <div class="tech-name">TensorFlow</div>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">🔌</div>
                    <div class="tech-name">WebSocket</div>
                </div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="section">
            <h2 class="section-title">📞 Kontak</h2>
            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">📧</div>
                    <div class="contact-info">
                        <h4>Email</h4>
                        <p>citra@university.ac.id</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🏫</div>
                    <div class="contact-info">
                        <h4>Universitas</h4>
                        <p>Program Studi Informatika</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-info">
                        <h4>Lokasi</h4>
                        <p>Cirebon, Jawa Barat</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--text-gray);">© 2024 CITRA - Hibah Rektor Project</p>
        </div>
    </div>
</body>
</html>
