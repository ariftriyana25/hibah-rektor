from flask_sqlalchemy import SQLAlchemy
from datetime import datetime
import json

db = SQLAlchemy()

class User(db.Model):
    __tablename__ = 'users'
    
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(120), unique=True, nullable=False)
    password = db.Column(db.String(255), nullable=False)
    avatar = db.Column(db.String(255), default='default-avatar.png')
    level = db.Column(db.String(50), default='Pemula')
    total_score = db.Column(db.Integer, default=0)
    practice_count = db.Column(db.Integer, default=0)
    progress = db.Column(db.JSON, default=list)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    sessions = db.relationship('PracticeSession', backref='user', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'name': self.name,
            'email': self.email,
            'avatar': self.avatar,
            'level': self.level,
            'total_score': self.total_score,
            'practice_count': self.practice_count,
            'progress': self.progress or [],
            'created_at': self.created_at.isoformat() if self.created_at else None
        }

class PracticeSession(db.Model):
    __tablename__ = 'practice_sessions'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    karakter = db.Column(db.String(50), nullable=False)  # panji, samba, rumyang, tumenggung, klana
    wiraga_score = db.Column(db.Float, default=0.0)  # pose accuracy
    wirama_score = db.Column(db.Float, default=0.0)  # rhythm sync
    wirasa_score = db.Column(db.Float, default=0.0)  # expression
    total_score = db.Column(db.Float, default=0.0)
    duration = db.Column(db.Integer, default=0)  # seconds
    feedback = db.Column(db.JSON, default=dict)
    pose_data = db.Column(db.JSON, default=list)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    def to_dict(self):
        return {
            'id': self.id,
            'user_id': self.user_id,
            'karakter': self.karakter,
            'wiraga_score': self.wiraga_score,
            'wirama_score': self.wirama_score,
            'wirasa_score': self.wirasa_score,
            'total_score': self.total_score,
            'duration': self.duration,
            'feedback': self.feedback or {},
            'created_at': self.created_at.isoformat() if self.created_at else None
        }

class MaestroReference(db.Model):
    __tablename__ = 'maestro_references'
    
    id = db.Column(db.Integer, primary_key=True)
    karakter = db.Column(db.String(50), nullable=False)
    gerakan_name = db.Column(db.String(100), nullable=False)
    video_path = db.Column(db.String(255))
    pose_keyframes = db.Column(db.JSON, default=list)  # Golden dataset poses
    audio_path = db.Column(db.String(255))
    beat_timestamps = db.Column(db.JSON, default=list)  # Gamelan beat timings
    description = db.Column(db.Text)
    difficulty = db.Column(db.String(20), default='mudah')
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    def to_dict(self):
        return {
            'id': self.id,
            'karakter': self.karakter,
            'gerakan_name': self.gerakan_name,
            'video_path': self.video_path,
            'audio_path': self.audio_path,
            'difficulty': self.difficulty,
            'description': self.description
        }

class Leaderboard(db.Model):
    __tablename__ = 'leaderboard'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    karakter = db.Column(db.String(50), nullable=False)
    best_score = db.Column(db.Float, default=0.0)
    rank = db.Column(db.Integer)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    user = db.relationship('User', backref='leaderboard_entries')
