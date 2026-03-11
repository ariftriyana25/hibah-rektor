<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CITRA - Platform pembelajaran Tari Topeng Cirebon berbasis AI dan Deep Learning untuk pelestarian budaya Indonesia">
    <title>CITRA - Lestarikan Tari Topeng dengan Kecerdasan Buatan</title>
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
            --text-light-gray: #CCCCCC;
            --accent-gradient: linear-gradient(135deg, #E85A20 0%, #FF8C42 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(13, 13, 13, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-light-gray);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: var(--primary-orange);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
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
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(232, 90, 32, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-white);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-outline:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }

        .btn-text {
            background: transparent;
            color: var(--primary-orange);
            padding: 0.5rem;
        }

        .btn-text:hover {
            text-decoration: underline;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 5% 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: radial-gradient(circle at 70% 30%, rgba(232, 90, 32, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .hero h1 span {
            color: var(--primary-orange);
        }

        .hero-description {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .dancer-image {
            max-width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            filter: drop-shadow(0 20px 50px rgba(232, 90, 32, 0.2));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Technology Section */
        .section {
            padding: 6rem 5%;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
        }

        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .tech-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .tech-card:hover {
            background: var(--bg-card-hover);
            transform: translateY(-5px);
            border-color: rgba(232, 90, 32, 0.3);
        }

        .tech-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: var(--bg-dark);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .tech-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .tech-card p {
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        /* How It Works Section */
        .steps-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            max-width: 1000px;
            margin: 0 auto;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 200px;
        }

        .step-icon {
            width: 100px;
            height: 100px;
            background: var(--bg-card);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 2.5rem;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .step:hover .step-icon {
            border-color: var(--primary-orange);
            background: rgba(232, 90, 32, 0.1);
        }

        .step h4 {
            font-size: 1rem;
            font-weight: 600;
        }

        .step-arrow {
            font-size: 2rem;
            color: var(--primary-orange);
        }

        /* Why Choose Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--primary-orange);
        }

        .feature-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
            background: var(--primary-orange);
            border-radius: 24px;
            padding: 4rem 2rem;
            text-align: center;
            margin: 4rem 5%;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-dark {
            background: var(--bg-dark);
            color: white;
            border: 2px solid var(--bg-dark);
        }

        .btn-dark:hover {
            background: transparent;
            color: var(--bg-dark);
            border-color: var(--bg-dark);
        }

        /* Footer */
        .footer {
            padding: 2rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-copyright {
            color: var(--text-gray);
            margin-bottom: 1rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .footer-links a {
            color: var(--primary-orange);
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 6rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-image {
                margin-top: 2rem;
            }

            .steps-container {
                flex-direction: column;
            }

            .step-arrow {
                transform: rotate(90deg);
            }
        }

        /* Mobile menu toggle */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* Placeholder for dancer image */
        .dancer-placeholder {
            width: 400px;
            height: 500px;
            background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-hover) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .dancer-placeholder::before {
            content: '🎭';
            font-size: 8rem;
            opacity: 0.3;
        }

        .dancer-placeholder::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, var(--bg-dark), transparent);
        }

        /* Animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon">C</div>
            <span>CITRA</span>
        </div>

        <ul class="nav-links">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#fitur">Fitur</a></li>
            <li><a href="#cara-kerja">Cara Kerja</a></li>
            <li><a href="#tentang">Tentang Kami</a></li>
            @if (Route::has('login'))
                @auth
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                @endauth
            @endif
        </ul>

        <div class="nav-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                    @endif
                @endauth
            @endif
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="hero-content fade-in">
            <h1>Lestarikan Tari Topeng dengan <span>Kecerdasan Buatan.</span></h1>
            <p class="hero-description">
                Belajar tari Cirebon secara otentik melalui analisis gerakan real-time berbasis Deep Learning.
            </p>
            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/practice') }}" class="btn btn-primary">Mulai Belajar</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Mulai Belajar</a>
                    @endauth
                @endif
                <a href="#cara-kerja" class="btn-text">Lihat Demo →</a>
            </div>
        </div>
        <div class="hero-image fade-in delay-2">
            <img src="{{ asset('img/Orang_nari.png') }}" alt="Penari Topeng Cirebon" class="dancer-image">
        </div>
    </section>

    <!-- Technology Section -->
    <section class="section" id="fitur">
        <h2 class="section-title">Teknologi Kami</h2>
        <div class="tech-grid">
            <div class="tech-card fade-in delay-1">
                <div class="tech-icon">🎭</div>
                <h3>Deteksi Gerak Presisi</h3>
                <p>Memastikan setiap gerakan terekam dengan tepat.</p>
            </div>
            <div class="tech-card fade-in delay-2">
                <div class="tech-icon">😊</div>
                <h3>Analisis Ekspresi</h3>
                <p>Menganalisis setiap ekspresi wajah penari.</p>
            </div>
            <div class="tech-card fade-in delay-3">
                <div class="tech-icon">🎵</div>
                <h3>Sinkronisasi Irama</h3>
                <p>Menjaga irama tarian sesuai musik.</p>
            </div>
            <div class="tech-card fade-in delay-4">
                <div class="tech-icon">📊</div>
                <h3>Evaluasi Cerdas</h3>
                <p>Memberikan evaluasi yang tepat dan mendalam.</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section" id="cara-kerja">
        <h2 class="section-title">Cara Kerja CITRA</h2>
        <div class="steps-container">
            <div class="step fade-in delay-1">
                <div class="step-icon">📹</div>
                <h4>Unggah Video</h4>
            </div>
            <span class="step-arrow">→</span>
            <div class="step fade-in delay-2">
                <div class="step-icon">🤖</div>
                <h4>AI Menganalisis Gerakan</h4>
            </div>
            <span class="step-arrow">→</span>
            <div class="step fade-in delay-3">
                <div class="step-icon">💡</div>
                <h4>Dapatkan Feedback</h4>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="section" id="tentang">
        <h2 class="section-title">Kenapa Pelajar Memilih CITRA?</h2>
        <div class="features-grid">
            <div class="feature-card fade-in delay-1">
                <h3>Akses bebas 24/7</h3>
                <p>Belajar kapan saja tanpa batasan waktu.</p>
            </div>
            <div class="feature-card fade-in delay-2">
                <h3>Belajar dari pakem maestro tari</h3>
                <p>Ikuti langkah-langkah dari para ahli.</p>
            </div>
            <div class="feature-card fade-in delay-3">
                <h3>Feedback otomatis & akurat</h3>
                <p>Dapatkan ulasan cepat dan tepat.</p>
            </div>
            <div class="feature-card fade-in delay-4">
                <h3>Platform modern yang menarik bagi generasi muda</h3>
                <p>Desain yang sesuai dengan gaya kekinian.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section fade-in">
        <h2>Siap Belajar Tari Topeng dengan Cara Baru?</h2>
        <p>Gabung bersama ratusan pelajar budaya yang telah memulai perjalanan mereka bersama CITRA.</p>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-dark">Daftar Sekarang</a>
        @endif
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p class="footer-copyright">© {{ date('Y') }} CITRA Project</p>
        <div class="footer-links">
            <a href="#">Kebijakan</a>
            <a href="#">Bantuan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

    <script>
        // Intersection Observer for fade-in animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.querySelector('.nav-links');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
                    navLinks.style.flexDirection = 'column';
                    navLinks.style.position = 'absolute';
                    navLinks.style.top = '100%';
                    navLinks.style.left = '0';
                    navLinks.style.right = '0';
                    navLinks.style.background = 'var(--bg-dark)';
                    navLinks.style.padding = '1rem';
                    navLinks.style.gap = '1rem';
                });
            }
        });
    </script>
</body>
</html>
