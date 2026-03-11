import os
from dotenv import load_dotenv

load_dotenv()

class Config:
    SECRET_KEY = os.getenv('SECRET_KEY', 'citra-secret-key-2024')
    JWT_SECRET_KEY = os.getenv('JWT_SECRET_KEY', 'citra-jwt-secret-2024')
    
    # Use SQLite as default for easy development, PostgreSQL for production
    SQLALCHEMY_DATABASE_URI = os.getenv(
        'DATABASE_URL', 
        'sqlite:///' + os.path.join(os.path.dirname(__file__), 'citra.db')
    )
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    
    UPLOAD_FOLDER = os.path.join(os.path.dirname(__file__), 'uploads')
    MAX_CONTENT_LENGTH = 100 * 1024 * 1024  # 100MB
    ALLOWED_EXTENSIONS = {'mp4', 'webm', 'avi', 'mov', 'mp3', 'wav', 'ogg'}
    MAESTRO_FOLDER = os.path.join(os.path.dirname(__file__), 'maestro_data')
    MODEL_FOLDER = os.path.join(os.path.dirname(__file__), 'models')
