# CITRA Platform
## Pengembangan Platform CITRA Berbasis Deep Learning dan Motion Analysis untuk Pelestarian Tari Topeng Cirebon

Platform pembelajaran tari tradisional Indonesia dengan teknologi AI untuk analisis gerakan real-time.

---

## FITUR UTAMA

### 1. Motion Capture AI
- **Wiraga (Gerakan)**: Analisis pose menggunakan MediaPipe untuk mendeteksi 33 titik sendi tubuh
- **Wirama (Irama)**: Sinkronisasi dengan gamelan menggunakan audio beat detection  
- **Wirasa (Ekspresi)**: Deteksi orientasi kepala dan ekspresi wajah

### 2. Real-time Evaluation
- Skor akurasi gerakan dengan feedback langsung
- Perbandingan dengan referensi maestro
- Grafik progres harian/mingguan

### 3. Karakter Tari Topeng
- Panji, Samba, Rumyang, Tumenggung, Klana

---

## STRUKTUR PROJECT

```
citra-platform/
├── backend/                    # Flask Python Backend
│   ├── ai/
│   │   ├── __init__.py
│   │   ├── pose_estimator.py   # MediaPipe pose detection
│   │   ├── rhythm_analyzer.py  # Gamelan beat detection
│   │   └── evaluation_engine.py # Wiraga/Wirama/Wirasa scoring
│   ├── app.py                  # Main Flask app with WebSocket
│   ├── models.py               # SQLAlchemy models
│   ├── config.py
│   ├── requirements.txt
│   └── .env
│
└── frontend/                   # Laravel 10+ Frontend
    ├── app/
    │   ├── Http/Controllers/
    │   └── Models/
    ├── resources/views/
    │   ├── welcome.blade.php   # Landing page
    │   ├── practice.blade.php  # Real-time practice UI
    │   ├── dashboard.blade.php
    │   ├── leaderboard.blade.php
    │   └── auth/
    ├── database/migrations/
    └── routes/web.php
```

---

## INSTALASI

### Prasyarat
- Python 3.10+
- PHP 8.1+
- Node.js 18+
- PostgreSQL 14+
- Composer
- Git

### 1. Clone Repository
```bash
git clone <repository-url>
cd citra-platform
```

### 2. Setup Backend (Flask)

```bash
cd backend

# Buat virtual environment
python -m venv venv

# Aktivasi (Windows)
venv\Scripts\activate

# Aktivasi (Linux/Mac)
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt

# Setup environment
copy .env.example .env
# Edit .env dengan konfigurasi database

# Jalankan server
python app.py
```

Backend berjalan di: `http://localhost:5000`

### 3. Setup Database

```sql
-- PostgreSQL
CREATE DATABASE citra_db;
CREATE USER citra_user WITH PASSWORD 'cirebon123';
GRANT ALL PRIVILEGES ON DATABASE citra_db TO citra_user;
```

### 4. Setup Frontend (Laravel)

```bash
cd frontend

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=citra_db
# DB_USERNAME=citra_user
# DB_PASSWORD=cirebon123

# Jalankan migrasi
php artisan migrate

# Build assets
npm run dev

# Jalankan server
php artisan serve
```

Frontend berjalan di: `http://localhost:8000`

---

## KONFIGURASI

### Backend (.env)
```
SECRET_KEY=your-secret-key
JWT_SECRET_KEY=your-jwt-secret
DATABASE_URL=postgresql://citra_user:cirebon123@127.0.0.1:5432/citra_db
FLASK_ENV=development
```

### Frontend (.env)
```
APP_NAME=CITRA
APP_URL=http://localhost:8000
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_DATABASE=citra_db
AI_BACKEND_URL=http://localhost:5000
```

---

## PENGGUNAAN

1. **Register/Login** - Buat akun untuk menyimpan progres
2. **Pilih Karakter** - Panji, Samba, Rumyang, Tumenggung, atau Klana
3. **Mulai Latihan** - Aktifkan kamera dan ikuti gerakan maestro
4. **Lihat Skor** - Dapatkan feedback real-time Wiraga, Wirama, Wirasa
5. **Pantau Progres** - Lihat grafik perkembangan di dashboard

---

## DEPLOYMENT (Production)

### Backend
```bash
pip install gunicorn eventlet
gunicorn --worker-class eventlet -w 1 app:app -b 0.0.0.0:5000
```

### Frontend
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Nginx Config
```nginx
server {
    listen 80;
    server_name citra.example.com;
    
    location / {
        proxy_pass http://127.0.0.1:8000;
    }
    
    location /api {
        proxy_pass http://127.0.0.1:5000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

---

## API ENDPOINTS

### Authentication
- `POST /api/register` - Registrasi user
- `POST /api/login` - Login user
- `GET /api/user` - Get profile (JWT required)

### Practice
- `POST /api/practice/start` - Mulai sesi latihan
- `POST /api/practice/end` - Akhiri sesi
- `GET /api/practice/history` - Riwayat latihan
- `GET /api/practice/stats` - Statistik

### Leaderboard
- `GET /api/leaderboard` - Get rankings

### WebSocket Events
- `pose_frame` - Kirim frame untuk analisis
- `pose_result` - Terima hasil analisis
- `audio_chunk` - Kirim audio untuk beat detection

---

## LICENSE

MIT License - CITRA Project 2024
# hibah-rektor
