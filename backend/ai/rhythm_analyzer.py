import numpy as np
import librosa
from typing import List, Dict, Tuple, Optional
import json

class RhythmAnalyzer:
    """
    Audio rhythm analyzer for Wirama evaluation.
    Detects gamelan beats and evaluates tempo synchronization.
    """
    
    # Standard Tari Topeng tempo ranges (BPM)
    TEMPO_RANGES = {
        'panji': (60, 80),      # Slow, graceful
        'samba': (80, 100),     # Moderate
        'rumyang': (70, 90),    # Flowing
        'tumenggung': (90, 110), # Strong
        'klana': (100, 130)     # Fast, dynamic
    }
    
    def __init__(self, sample_rate: int = 22050):
        self.sample_rate = sample_rate
        self.hop_length = 512
        self.current_tempo = 0
        self.beat_times = []
        self.onset_times = []
    
    def load_audio(self, audio_path: str) -> Tuple[np.ndarray, int]:
        """Load audio file and return samples."""
        y, sr = librosa.load(audio_path, sr=self.sample_rate)
        return y, sr
    
    def analyze_audio(self, y: np.ndarray) -> Dict:
        """Full audio analysis for gamelan music."""
        
        # Tempo detection
        tempo, beat_frames = librosa.beat.beat_track(
            y=y, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        # Convert beat frames to times
        beat_times = librosa.frames_to_time(
            beat_frames, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        # Onset detection for more precise timing
        onset_frames = librosa.onset.onset_detect(
            y=y, sr=self.sample_rate, hop_length=self.hop_length
        )
        onset_times = librosa.frames_to_time(
            onset_frames, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        # Spectral analysis for gamelan characteristics
        spectral_centroid = librosa.feature.spectral_centroid(
            y=y, sr=self.sample_rate
        )
        
        # RMS energy for dynamic analysis
        rms = librosa.feature.rms(y=y)
        
        self.current_tempo = float(tempo)
        self.beat_times = beat_times.tolist()
        self.onset_times = onset_times.tolist()
        
        return {
            'tempo': float(tempo),
            'beat_times': beat_times.tolist(),
            'onset_times': onset_times.tolist(),
            'duration': float(len(y) / self.sample_rate),
            'total_beats': len(beat_times),
            'avg_spectral_centroid': float(np.mean(spectral_centroid)),
            'avg_rms': float(np.mean(rms))
        }
    
    def analyze_realtime_chunk(self, audio_chunk: np.ndarray) -> Dict:
        """Analyze a short audio chunk for real-time processing."""
        if len(audio_chunk) < 2048:
            return {'beat_detected': False, 'energy': 0}
        
        # RMS energy
        rms = np.sqrt(np.mean(audio_chunk**2))
        
        # Simple onset detection
        onset_strength = librosa.onset.onset_strength(
            y=audio_chunk, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        peak_threshold = np.mean(onset_strength) + np.std(onset_strength)
        beat_detected = np.max(onset_strength) > peak_threshold
        
        return {
            'beat_detected': beat_detected,
            'energy': float(rms),
            'onset_strength': float(np.max(onset_strength))
        }
    
    def evaluate_synchronization(self, movement_times: List[float], 
                                  beat_times: List[float],
                                  tolerance: float = 0.15) -> Dict:
        """
        Evaluate how well movements are synchronized with beats.
        
        Args:
            movement_times: Timestamps of user movements (peaks)
            beat_times: Timestamps of music beats
            tolerance: Acceptable timing difference in seconds
        """
        if not movement_times or not beat_times:
            return {
                'score': 0,
                'feedback': ['Tidak ada data gerakan atau musik'],
                'synced_count': 0,
                'total_beats': 0
            }
        
        synced_count = 0
        timing_errors = []
        
        for beat_time in beat_times:
            # Find closest movement to this beat
            closest_movement = min(movement_times, 
                                   key=lambda x: abs(x - beat_time))
            error = abs(closest_movement - beat_time)
            timing_errors.append(error)
            
            if error <= tolerance:
                synced_count += 1
        
        sync_percentage = (synced_count / len(beat_times)) * 100
        avg_error = np.mean(timing_errors)
        
        # Generate feedback
        feedback = []
        if sync_percentage >= 90:
            feedback.append('Sinkronisasi dengan musik sangat baik!')
        elif sync_percentage >= 70:
            feedback.append('Sinkronisasi cukup baik, pertahankan!')
        elif sync_percentage >= 50:
            feedback.append('Perhatikan ketukan musik lebih seksama')
        else:
            feedback.append('Latih kepekaan terhadap irama gamelan')
        
        if avg_error > 0.2:
            feedback.append('Gerakan terlambat dari ketukan')
        elif avg_error > 0.1:
            feedback.append('Tingkatkan ketepatan timing')
        
        return {
            'score': round(sync_percentage, 1),
            'synced_count': synced_count,
            'total_beats': len(beat_times),
            'avg_timing_error': round(float(avg_error), 3),
            'feedback': feedback
        }
    
    def get_tempo_feedback(self, detected_tempo: float, karakter: str) -> Dict:
        """Provide feedback on tempo matching for character."""
        expected_range = self.TEMPO_RANGES.get(karakter, (70, 100))
        
        if detected_tempo < expected_range[0]:
            status = 'slow'
            message = f'Tempo terlalu lambat untuk {karakter}. Percepat gerakan.'
        elif detected_tempo > expected_range[1]:
            status = 'fast'
            message = f'Tempo terlalu cepat untuk {karakter}. Perlambat gerakan.'
        else:
            status = 'good'
            message = f'Tempo sesuai untuk karakter {karakter}!'
        
        return {
            'status': status,
            'detected_tempo': round(detected_tempo, 1),
            'expected_range': expected_range,
            'message': message
        }
    
    def detect_movement_peaks(self, pose_sequence: List[Dict], 
                               threshold: float = 0.05) -> List[float]:
        """
        Detect peaks in movement from pose sequence.
        Used to find when significant movements occur.
        """
        if len(pose_sequence) < 3:
            return []
        
        velocities = []
        for i in range(1, len(pose_sequence)):
            prev_pose = pose_sequence[i-1]
            curr_pose = pose_sequence[i]
            
            if not prev_pose.get('pose_landmarks') or not curr_pose.get('pose_landmarks'):
                velocities.append(0)
                continue
            
            total_movement = 0
            count = 0
            
            for j in range(min(len(prev_pose['pose_landmarks']), 
                               len(curr_pose['pose_landmarks']))):
                prev_lm = prev_pose['pose_landmarks'][j]
                curr_lm = curr_pose['pose_landmarks'][j]
                
                dx = curr_lm['x'] - prev_lm['x']
                dy = curr_lm['y'] - prev_lm['y']
                dz = curr_lm.get('z', 0) - prev_lm.get('z', 0)
                
                movement = np.sqrt(dx**2 + dy**2 + dz**2)
                total_movement += movement
                count += 1
            
            avg_movement = total_movement / count if count > 0 else 0
            velocities.append(avg_movement)
        
        # Find peaks
        movement_times = []
        for i in range(1, len(velocities) - 1):
            if velocities[i] > velocities[i-1] and velocities[i] > velocities[i+1]:
                if velocities[i] > threshold:
                    timestamp = pose_sequence[i].get('timestamp', i * 0.033)
                    movement_times.append(timestamp)
        
        return movement_times


class GamelanBeatDetector:
    """
    Specialized beat detector for gamelan music.
    Detects structural beats (gongan, kenong) for Tari Topeng.
    """
    
    def __init__(self, sample_rate: int = 22050):
        self.sample_rate = sample_rate
        self.hop_length = 512
    
    def detect_gong_hits(self, y: np.ndarray) -> List[float]:
        """Detect gong hits in gamelan music (low frequency peaks)."""
        # Low-pass filter for gong detection
        y_low = librosa.effects.percussive(y)
        
        # Spectral flux in low frequencies
        S = np.abs(librosa.stft(y_low))
        low_band = S[:20, :]  # Focus on low frequencies
        
        flux = np.sum(np.diff(low_band, axis=1).clip(min=0), axis=0)
        
        # Peak detection
        peaks = librosa.util.peak_pick(
            flux, pre_max=10, post_max=10, pre_avg=30, post_avg=30, 
            delta=0.5, wait=30
        )
        
        gong_times = librosa.frames_to_time(
            peaks, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        return gong_times.tolist()
    
    def detect_kenong_pattern(self, y: np.ndarray) -> List[float]:
        """Detect kenong pattern (mid frequency metallic sounds)."""
        # Harmonic-percussive separation
        y_perc = librosa.effects.percussive(y)
        
        # Mid-frequency focus
        S = np.abs(librosa.stft(y_perc))
        mid_band = S[20:100, :]
        
        flux = np.sum(np.diff(mid_band, axis=1).clip(min=0), axis=0)
        
        peaks = librosa.util.peak_pick(
            flux, pre_max=5, post_max=5, pre_avg=15, post_avg=15,
            delta=0.3, wait=15
        )
        
        kenong_times = librosa.frames_to_time(
            peaks, sr=self.sample_rate, hop_length=self.hop_length
        )
        
        return kenong_times.tolist()
    
    def get_structural_beats(self, y: np.ndarray) -> Dict:
        """Get all structural beats for complete gamelan analysis."""
        gong_hits = self.detect_gong_hits(y)
        kenong_hits = self.detect_kenong_pattern(y)
        
        # Regular beat detection
        tempo, beat_frames = librosa.beat.beat_track(
            y=y, sr=self.sample_rate
        )
        beat_times = librosa.frames_to_time(
            beat_frames, sr=self.sample_rate
        )
        
        return {
            'tempo': float(tempo),
            'regular_beats': beat_times.tolist(),
            'gong_hits': gong_hits,
            'kenong_hits': kenong_hits,
            'total_gongs': len(gong_hits),
            'total_kenongs': len(kenong_hits)
        }
