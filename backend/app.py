from flask import Flask, request, jsonify, send_from_directory
from flask_cors import CORS
from flask_socketio import SocketIO, emit, join_room, leave_room
from flask_jwt_extended import JWTManager, create_access_token, jwt_required, get_jwt_identity
import bcrypt
import json
import os
import base64
import numpy as np
import cv2
from datetime import datetime, timedelta
from config import Config
from models import db, User, PracticeSession, MaestroReference, Leaderboard

# Initialize Flask app
app = Flask(__name__)
app.config.from_object(Config)

# Initialize extensions
CORS(app, resources={r"/*": {"origins": "*"}})
socketio = SocketIO(app, cors_allowed_origins="*", async_mode='eventlet')
jwt = JWTManager(app)

# Initialize database
db.init_app(app)

# Create upload directories
os.makedirs(Config.UPLOAD_FOLDER, exist_ok=True)
os.makedirs(Config.MAESTRO_FOLDER, exist_ok=True)
os.makedirs(Config.MODEL_FOLDER, exist_ok=True)

# Import AI modules (lazy import to handle dependencies)
pose_estimator = None
rhythm_analyzer = None
performance_evaluator = None

def init_ai_modules():
    global pose_estimator, rhythm_analyzer, performance_evaluator
    try:
        from ai.pose_estimator import PoseEstimator, ExpressionAnalyzer
        from ai.rhythm_analyzer import RhythmAnalyzer
        from ai.evaluation_engine import PerformanceEvaluator
        
        pose_estimator = PoseEstimator()
        rhythm_analyzer = RhythmAnalyzer()
        performance_evaluator = PerformanceEvaluator()
        print("AI modules initialized successfully")
    except Exception as e:
        print(f"Warning: AI modules not fully loaded: {e}")

# Store for active practice sessions
active_sessions = {}

# ==================== AUTH ROUTES ====================

@app.route('/api/register', methods=['POST'])
def register():
    """Register a new user."""
    data = request.json
    
    if not data.get('email') or not data.get('password') or not data.get('name'):
        return jsonify({'error': 'Semua field harus diisi'}), 400
    
    # Check if email exists
    if User.query.filter_by(email=data['email']).first():
        return jsonify({'error': 'Email sudah terdaftar'}), 400
    
    # Hash password
    hashed = bcrypt.hashpw(data['password'].encode('utf-8'), bcrypt.gensalt())
    
    # Create user
    user = User(
        name=data['name'],
        email=data['email'],
        password=hashed.decode('utf-8')
    )
    
    db.session.add(user)
    db.session.commit()
    
    # Create access token
    access_token = create_access_token(
        identity=user.id,
        expires_delta=timedelta(days=7)
    )
    
    return jsonify({
        'message': 'Registrasi berhasil',
        'user': user.to_dict(),
        'access_token': access_token
    }), 201

@app.route('/api/login', methods=['POST'])
def login():
    """User login."""
    data = request.json
    
    if not data.get('email') or not data.get('password'):
        return jsonify({'error': 'Email dan password harus diisi'}), 400
    
    user = User.query.filter_by(email=data['email']).first()
    
    if not user or not bcrypt.checkpw(data['password'].encode('utf-8'), user.password.encode('utf-8')):
        return jsonify({'error': 'Email atau password salah'}), 401
    
    access_token = create_access_token(
        identity=user.id,
        expires_delta=timedelta(days=7)
    )
    
    return jsonify({
        'message': 'Login berhasil',
        'user': user.to_dict(),
        'access_token': access_token
    })

@app.route('/api/user', methods=['GET'])
@jwt_required()
def get_user():
    """Get current user profile."""
    user_id = get_jwt_identity()
    user = User.query.get(user_id)
    
    if not user:
        return jsonify({'error': 'User tidak ditemukan'}), 404
    
    return jsonify({'user': user.to_dict()})

@app.route('/api/user', methods=['PUT'])
@jwt_required()
def update_user():
    """Update user profile."""
    user_id = get_jwt_identity()
    user = User.query.get(user_id)
    data = request.json
    
    if data.get('name'):
        user.name = data['name']
    if data.get('avatar'):
        user.avatar = data['avatar']
    
    db.session.commit()
    return jsonify({'user': user.to_dict()})

# ==================== PRACTICE ROUTES ====================

@app.route('/api/practice/start', methods=['POST'])
@jwt_required()
def start_practice():
    """Start a new practice session."""
    user_id = get_jwt_identity()
    data = request.json
    karakter = data.get('karakter', 'panji')
    
    # Initialize evaluator for this session
    if performance_evaluator:
        performance_evaluator.start_session(karakter)
    
    session_id = f"{user_id}_{datetime.now().timestamp()}"
    active_sessions[session_id] = {
        'user_id': user_id,
        'karakter': karakter,
        'start_time': datetime.now(),
        'frames': []
    }
    
    return jsonify({
        'session_id': session_id,
        'karakter': karakter,
        'message': 'Sesi latihan dimulai'
    })

@app.route('/api/practice/end', methods=['POST'])
@jwt_required()
def end_practice():
    """End practice session and save results."""
    user_id = get_jwt_identity()
    data = request.json
    session_id = data.get('session_id')
    
    if session_id not in active_sessions:
        return jsonify({'error': 'Sesi tidak ditemukan'}), 404
    
    session_data = active_sessions.pop(session_id)
    
    # Get final evaluation
    result = {'scores': {'wiraga': 0, 'wirama': 0, 'wirasa': 0, 'total': 0}}
    if performance_evaluator:
        result = performance_evaluator.get_session_result()
    
    # Save to database
    practice = PracticeSession(
        user_id=user_id,
        karakter=session_data['karakter'],
        wiraga_score=result['scores']['wiraga'],
        wirama_score=result['scores']['wirama'],
        wirasa_score=result['scores']['wirasa'],
        total_score=result['scores']['total'],
        duration=int((datetime.now() - session_data['start_time']).total_seconds()),
        feedback=result.get('feedback', [])
    )
    
    db.session.add(practice)
    
    # Update user stats
    user = User.query.get(user_id)
    user.practice_count += 1
    user.total_score += int(result['scores']['total'])
    
    # Update level
    from ai.evaluation_engine import ScoreCalculator
    user.level = ScoreCalculator.calculate_level(user.total_score, user.practice_count)
    
    # Update leaderboard
    update_leaderboard(user_id, session_data['karakter'], result['scores']['total'])
    
    db.session.commit()
    
    return jsonify({
        'message': 'Sesi selesai',
        'result': result,
        'practice_id': practice.id
    })

@app.route('/api/practice/history', methods=['GET'])
@jwt_required()
def get_practice_history():
    """Get user's practice history."""
    user_id = get_jwt_identity()
    limit = request.args.get('limit', 20, type=int)
    
    sessions = PracticeSession.query.filter_by(user_id=user_id)\
        .order_by(PracticeSession.created_at.desc())\
        .limit(limit).all()
    
    return jsonify({
        'sessions': [s.to_dict() for s in sessions]
    })

@app.route('/api/practice/stats', methods=['GET'])
@jwt_required()
def get_practice_stats():
    """Get practice statistics."""
    user_id = get_jwt_identity()
    
    sessions = PracticeSession.query.filter_by(user_id=user_id).all()
    sessions_data = [s.to_dict() for s in sessions]
    
    from ai.evaluation_engine import ScoreCalculator
    
    return jsonify({
        'progress': ScoreCalculator.calculate_progress(sessions_data),
        'daily_stats': ScoreCalculator.calculate_daily_stats(sessions_data),
        'character_mastery': ScoreCalculator.get_character_mastery(sessions_data)
    })

# ==================== MAESTRO ROUTES ====================

@app.route('/api/maestro', methods=['GET'])
def get_maestro_list():
    """Get list of maestro references."""
    karakter = request.args.get('karakter')
    
    query = MaestroReference.query
    if karakter:
        query = query.filter_by(karakter=karakter)
    
    references = query.all()
    return jsonify({
        'references': [r.to_dict() for r in references]
    })

@app.route('/api/maestro/<int:maestro_id>', methods=['GET'])
def get_maestro_detail(maestro_id):
    """Get maestro reference detail."""
    reference = MaestroReference.query.get_or_404(maestro_id)
    return jsonify({'reference': reference.to_dict()})

@app.route('/api/maestro/upload', methods=['POST'])
@jwt_required()
def upload_maestro():
    """Upload new maestro reference (admin only)."""
    if 'video' not in request.files:
        return jsonify({'error': 'Video file required'}), 400
    
    video = request.files['video']
    karakter = request.form.get('karakter', 'panji')
    gerakan_name = request.form.get('gerakan_name', 'Gerakan Dasar')
    
    # Save video
    filename = f"{karakter}_{datetime.now().timestamp()}.mp4"
    video_path = os.path.join(Config.MAESTRO_FOLDER, filename)
    video.save(video_path)
    
    # Process video to extract keyframes (simplified)
    pose_keyframes = extract_pose_keyframes(video_path)
    
    reference = MaestroReference(
        karakter=karakter,
        gerakan_name=gerakan_name,
        video_path=filename,
        pose_keyframes=pose_keyframes,
        description=request.form.get('description', '')
    )
    
    db.session.add(reference)
    db.session.commit()
    
    return jsonify({
        'message': 'Referensi maestro berhasil diunggah',
        'reference': reference.to_dict()
    })

# ==================== LEADERBOARD ROUTES ====================

@app.route('/api/leaderboard', methods=['GET'])
def get_leaderboard():
    """Get leaderboard."""
    karakter = request.args.get('karakter')
    limit = request.args.get('limit', 10, type=int)
    
    query = db.session.query(Leaderboard, User)\
        .join(User, Leaderboard.user_id == User.id)
    
    if karakter:
        query = query.filter(Leaderboard.karakter == karakter)
    
    results = query.order_by(Leaderboard.best_score.desc()).limit(limit).all()
    
    leaderboard = []
    for idx, (entry, user) in enumerate(results):
        leaderboard.append({
            'rank': idx + 1,
            'user_id': user.id,
            'name': user.name,
            'avatar': user.avatar,
            'level': user.level,
            'karakter': entry.karakter,
            'best_score': entry.best_score
        })
    
    return jsonify({'leaderboard': leaderboard})

def update_leaderboard(user_id: int, karakter: str, score: float):
    """Update leaderboard entry."""
    entry = Leaderboard.query.filter_by(user_id=user_id, karakter=karakter).first()
    
    if entry:
        if score > entry.best_score:
            entry.best_score = score
    else:
        entry = Leaderboard(
            user_id=user_id,
            karakter=karakter,
            best_score=score
        )
        db.session.add(entry)

# ==================== AI PROCESSING ROUTES ====================

@app.route('/api/analyze/pose', methods=['POST'])
def analyze_pose():
    """Analyze pose from image."""
    if not pose_estimator:
        return jsonify({'error': 'AI module not available'}), 503
    
    data = request.json
    if not data.get('image'):
        return jsonify({'error': 'Image data required'}), 400
    
    # Decode base64 image
    image_data = base64.b64decode(data['image'].split(',')[1] if ',' in data['image'] else data['image'])
    nparr = np.frombuffer(image_data, np.uint8)
    frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    
    if frame is None:
        return jsonify({'error': 'Invalid image'}), 400
    
    # Process pose
    pose_data = pose_estimator.process_frame(frame)
    
    # Compare with maestro if provided
    comparison = None
    if data.get('maestro_pose'):
        comparison = pose_estimator.compare_poses(pose_data, data['maestro_pose'])
    
    return jsonify({
        'pose': pose_data,
        'comparison': comparison
    })

@app.route('/api/analyze/audio', methods=['POST'])
def analyze_audio():
    """Analyze audio for rhythm."""
    if not rhythm_analyzer:
        return jsonify({'error': 'AI module not available'}), 503
    
    if 'audio' not in request.files:
        return jsonify({'error': 'Audio file required'}), 400
    
    audio_file = request.files['audio']
    temp_path = os.path.join(Config.UPLOAD_FOLDER, f"temp_{datetime.now().timestamp()}.wav")
    audio_file.save(temp_path)
    
    try:
        y, sr = rhythm_analyzer.load_audio(temp_path)
        analysis = rhythm_analyzer.analyze_audio(y)
        return jsonify({'analysis': analysis})
    finally:
        if os.path.exists(temp_path):
            os.remove(temp_path)

# ==================== WEBSOCKET EVENTS ====================

@socketio.on('connect')
def handle_connect():
    """Handle client connection."""
    print(f"Client connected: {request.sid}")
    emit('connected', {'status': 'connected', 'sid': request.sid})

@socketio.on('disconnect')
def handle_disconnect():
    """Handle client disconnection."""
    print(f"Client disconnected: {request.sid}")

@socketio.on('join_session')
def handle_join_session(data):
    """Join a practice session room."""
    session_id = data.get('session_id')
    if session_id:
        join_room(session_id)
        emit('session_joined', {'session_id': session_id})

@socketio.on('leave_session')
def handle_leave_session(data):
    """Leave a practice session room."""
    session_id = data.get('session_id')
    if session_id:
        leave_room(session_id)
        emit('session_left', {'session_id': session_id})

@socketio.on('pose_frame')
def handle_pose_frame(data):
    """Process real-time pose frame from client."""
    if not pose_estimator:
        emit('pose_result', {'error': 'AI not available'})
        return
    
    try:
        # Decode image
        image_data = data.get('image', '')
        if ',' in image_data:
            image_data = image_data.split(',')[1]
        
        img_bytes = base64.b64decode(image_data)
        nparr = np.frombuffer(img_bytes, np.uint8)
        frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        
        if frame is None:
            emit('pose_result', {'error': 'Invalid frame'})
            return
        
        # Process pose
        pose_data = pose_estimator.process_frame(frame)
        
        # Get maestro reference for comparison
        maestro_pose = data.get('maestro_pose')
        comparison = None
        if maestro_pose:
            comparison = pose_estimator.compare_poses(pose_data, maestro_pose)
            
            # Update evaluator
            if performance_evaluator:
                performance_evaluator.update_wiraga(comparison)
                performance_evaluator.increment_frame()
        
        # Get real-time feedback
        feedback = {}
        if performance_evaluator:
            feedback = performance_evaluator.get_realtime_feedback()
        
        emit('pose_result', {
            'pose': {
                'landmarks': pose_data['pose_landmarks'],
                'angles': pose_data['angles'],
                'confidence': pose_data['confidence']
            },
            'comparison': comparison,
            'feedback': feedback,
            'timestamp': datetime.now().isoformat()
        })
        
    except Exception as e:
        emit('pose_result', {'error': str(e)})

@socketio.on('audio_chunk')
def handle_audio_chunk(data):
    """Process real-time audio chunk."""
    if not rhythm_analyzer:
        emit('audio_result', {'error': 'AI not available'})
        return
    
    try:
        audio_data = np.array(data.get('samples', []), dtype=np.float32)
        result = rhythm_analyzer.analyze_realtime_chunk(audio_data)
        
        emit('audio_result', {
            'beat_detected': result['beat_detected'],
            'energy': result['energy'],
            'timestamp': datetime.now().isoformat()
        })
    except Exception as e:
        emit('audio_result', {'error': str(e)})

# ==================== HELPER FUNCTIONS ====================

def extract_pose_keyframes(video_path: str) -> list:
    """Extract pose keyframes from maestro video."""
    if not pose_estimator:
        return []
    
    keyframes = []
    cap = cv2.VideoCapture(video_path)
    fps = cap.get(cv2.CAP_PROP_FPS)
    frame_interval = int(fps / 2)  # 2 keyframes per second
    
    frame_count = 0
    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            break
        
        if frame_count % frame_interval == 0:
            pose_data = pose_estimator.process_frame(frame)
            if pose_data['pose_landmarks']:
                keyframes.append({
                    'timestamp': frame_count / fps,
                    'angles': pose_data['angles'],
                    'landmarks': pose_data['pose_landmarks']
                })
        
        frame_count += 1
    
    cap.release()
    return keyframes

# ==================== DATABASE INITIALIZATION ====================

def init_db():
    """Initialize database tables."""
    with app.app_context():
        db.create_all()
        
        # Add sample maestro data if empty
        if MaestroReference.query.count() == 0:
            sample_data = [
                MaestroReference(
                    karakter='panji',
                    gerakan_name='Sembahan Awal',
                    description='Gerakan penghormatan di awal tarian Panji',
                    difficulty='mudah'
                ),
                MaestroReference(
                    karakter='panji',
                    gerakan_name='Nindak',
                    description='Gerakan berjalan khas karakter Panji',
                    difficulty='menengah'
                ),
                MaestroReference(
                    karakter='samba',
                    gerakan_name='Sembahan Samba',
                    description='Gerakan penghormatan karakter Samba',
                    difficulty='mudah'
                ),
                MaestroReference(
                    karakter='klana',
                    gerakan_name='Tanjak Klana',
                    description='Posisi dasar karakter Klana yang gagah',
                    difficulty='menengah'
                )
            ]
            db.session.add_all(sample_data)
            db.session.commit()
        
        print("Database initialized")

# ==================== MAIN ====================

if __name__ == '__main__':
    init_db()
    init_ai_modules()
    socketio.run(app, host='0.0.0.0', port=5000, debug=True)