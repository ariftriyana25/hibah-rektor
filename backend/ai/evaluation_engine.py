import numpy as np
from typing import Dict, List, Optional
from datetime import datetime, timedelta
import json

class PerformanceEvaluator:
    """
    Central evaluation engine for Tari Topeng performance.
    Combines Wiraga, Wirama, and Wirasa scores.
    """
    
    # Weight configuration for each aspect
    SCORE_WEIGHTS = {
        'wiraga': 0.45,  # Body movement accuracy (most important)
        'wirama': 0.30,  # Rhythm synchronization
        'wirasa': 0.25   # Expression and feeling
    }
    
    # Thresholds for grading
    GRADE_THRESHOLDS = {
        'A+': 95, 'A': 90, 'A-': 85,
        'B+': 80, 'B': 75, 'B-': 70,
        'C+': 65, 'C': 60, 'C-': 55,
        'D': 50, 'E': 0
    }
    
    # Character-specific evaluation adjustments
    CHARACTER_FOCUS = {
        'panji': {'wiraga': 0.4, 'wirama': 0.3, 'wirasa': 0.3},  # Balanced, expressive
        'samba': {'wiraga': 0.5, 'wirama': 0.25, 'wirasa': 0.25},  # Body focus
        'rumyang': {'wiraga': 0.35, 'wirama': 0.35, 'wirasa': 0.3},  # Flow important
        'tumenggung': {'wiraga': 0.5, 'wirama': 0.3, 'wirasa': 0.2},  # Strong posture
        'klana': {'wiraga': 0.45, 'wirama': 0.35, 'wirasa': 0.2}  # Dynamic movement
    }
    
    def __init__(self):
        self.session_data = {
            'wiraga_scores': [],
            'wirama_scores': [],
            'wirasa_scores': [],
            'feedback_history': [],
            'start_time': None,
            'frame_count': 0
        }
    
    def start_session(self, karakter: str = 'panji'):
        """Initialize a new evaluation session."""
        self.session_data = {
            'karakter': karakter,
            'wiraga_scores': [],
            'wirama_scores': [],
            'wirasa_scores': [],
            'feedback_history': [],
            'start_time': datetime.now(),
            'frame_count': 0,
            'weights': self.CHARACTER_FOCUS.get(karakter, self.SCORE_WEIGHTS)
        }
    
    def update_wiraga(self, pose_comparison: Dict):
        """Update Wiraga score from pose comparison result."""
        if pose_comparison and 'score' in pose_comparison:
            self.session_data['wiraga_scores'].append(pose_comparison['score'])
            if pose_comparison.get('feedback'):
                self.session_data['feedback_history'].extend(
                    [{'type': 'wiraga', 'message': f} for f in pose_comparison['feedback'][:2]]
                )
    
    def update_wirama(self, rhythm_result: Dict):
        """Update Wirama score from rhythm analysis."""
        if rhythm_result and 'score' in rhythm_result:
            self.session_data['wirama_scores'].append(rhythm_result['score'])
            if rhythm_result.get('feedback'):
                self.session_data['feedback_history'].extend(
                    [{'type': 'wirama', 'message': f} for f in rhythm_result['feedback'][:2]]
                )
    
    def update_wirasa(self, expression_result: Dict):
        """Update Wirasa score from expression analysis."""
        if not expression_result or not expression_result.get('detected'):
            return
        
        # Score based on expression intensity and appropriateness
        intensity = expression_result.get('intensity', 0)
        expression = expression_result.get('expression', 'neutral')
        
        # Different characters expect different expressions
        karakter = self.session_data.get('karakter', 'panji')
        expected_expressions = {
            'panji': ['focused', 'neutral', 'smiling'],
            'samba': ['expressive', 'smiling'],
            'rumyang': ['focused', 'neutral'],
            'tumenggung': ['focused', 'neutral'],
            'klana': ['expressive', 'surprised']
        }
        
        expected = expected_expressions.get(karakter, ['neutral'])
        expression_match = 1.0 if expression in expected else 0.5
        
        # Calculate Wirasa score (0-100)
        wirasa_score = min(100, (intensity * 50 + expression_match * 50))
        self.session_data['wirasa_scores'].append(wirasa_score)
    
    def get_realtime_feedback(self) -> Dict:
        """Get current feedback for real-time display."""
        weights = self.session_data.get('weights', self.SCORE_WEIGHTS)
        
        # Calculate current averages
        wiraga_avg = np.mean(self.session_data['wiraga_scores'][-30:]) if self.session_data['wiraga_scores'] else 0
        wirama_avg = np.mean(self.session_data['wirama_scores'][-30:]) if self.session_data['wirama_scores'] else 0
        wirasa_avg = np.mean(self.session_data['wirasa_scores'][-30:]) if self.session_data['wirasa_scores'] else 0
        
        # Weighted total
        total_score = (
            wiraga_avg * weights['wiraga'] +
            wirama_avg * weights['wirama'] +
            wirasa_avg * weights['wirasa']
        )
        
        # Get recent feedback
        recent_feedback = self.session_data['feedback_history'][-5:]
        
        return {
            'wiraga': round(wiraga_avg, 1),
            'wirama': round(wirama_avg, 1),
            'wirasa': round(wirasa_avg, 1),
            'total': round(total_score, 1),
            'grade': self._get_grade(total_score),
            'feedback': [f['message'] for f in recent_feedback],
            'frame_count': self.session_data['frame_count']
        }
    
    def get_session_result(self) -> Dict:
        """Get final session result."""
        if not self.session_data['start_time']:
            return {'error': 'No session started'}
        
        weights = self.session_data.get('weights', self.SCORE_WEIGHTS)
        duration = (datetime.now() - self.session_data['start_time']).total_seconds()
        
        # Calculate final averages
        wiraga_final = np.mean(self.session_data['wiraga_scores']) if self.session_data['wiraga_scores'] else 0
        wirama_final = np.mean(self.session_data['wirama_scores']) if self.session_data['wirama_scores'] else 0
        wirasa_final = np.mean(self.session_data['wirasa_scores']) if self.session_data['wirasa_scores'] else 0
        
        total_score = (
            wiraga_final * weights['wiraga'] +
            wirama_final * weights['wirama'] +
            wirasa_final * weights['wirasa']
        )
        
        # Generate comprehensive feedback
        feedback = self._generate_final_feedback(wiraga_final, wirama_final, wirasa_final)
        
        return {
            'karakter': self.session_data.get('karakter', 'panji'),
            'duration': int(duration),
            'total_frames': self.session_data['frame_count'],
            'scores': {
                'wiraga': round(wiraga_final, 1),
                'wirama': round(wirama_final, 1),
                'wirasa': round(wirasa_final, 1),
                'total': round(total_score, 1)
            },
            'grade': self._get_grade(total_score),
            'feedback': feedback,
            'improvement_areas': self._get_improvement_areas(wiraga_final, wirama_final, wirasa_final),
            'timestamp': datetime.now().isoformat()
        }
    
    def _get_grade(self, score: float) -> str:
        """Convert score to letter grade."""
        for grade, threshold in self.GRADE_THRESHOLDS.items():
            if score >= threshold:
                return grade
        return 'E'
    
    def _generate_final_feedback(self, wiraga: float, wirama: float, wirasa: float) -> List[str]:
        """Generate comprehensive final feedback."""
        feedback = []
        
        # Wiraga feedback
        if wiraga >= 85:
            feedback.append("Gerakan tubuh sangat presisi dan sesuai pakem!")
        elif wiraga >= 70:
            feedback.append("Gerakan tubuh cukup baik, beberapa posisi perlu diperbaiki.")
        elif wiraga >= 50:
            feedback.append("Perlu latihan lebih untuk memperbaiki posisi tubuh.")
        else:
            feedback.append("Fokus pada pelajari gerakan dasar terlebih dahulu.")
        
        # Wirama feedback
        if wirama >= 85:
            feedback.append("Sinkronisasi dengan irama gamelan sangat baik!")
        elif wirama >= 70:
            feedback.append("Irama cukup sesuai, tingkatkan kepekaan terhadap ketukan.")
        elif wirama >= 50:
            feedback.append("Perlu lebih memperhatikan tempo dan ketukan musik.")
        else:
            feedback.append("Latih pendengaran terhadap ritme gamelan.")
        
        # Wirasa feedback
        if wirasa >= 85:
            feedback.append("Ekspresi dan penghayatan sangat menjiwai karakter!")
        elif wirasa >= 70:
            feedback.append("Ekspresi sudah baik, tingkatkan intensitas.")
        elif wirasa >= 50:
            feedback.append("Perlu lebih menghayati karakter yang ditarikan.")
        else:
            feedback.append("Pelajari karakteristik ekspresi untuk karakter ini.")
        
        return feedback
    
    def _get_improvement_areas(self, wiraga: float, wirama: float, wirasa: float) -> List[Dict]:
        """Identify areas needing improvement."""
        areas = []
        
        scores = [
            ('wiraga', wiraga, 'Ketepatan Gerakan'),
            ('wirama', wirama, 'Sinkronisasi Irama'),
            ('wirasa', wirasa, 'Ekspresi & Penghayatan')
        ]
        
        # Sort by score (lowest first)
        scores.sort(key=lambda x: x[1])
        
        for aspect, score, name in scores:
            if score < 80:
                priority = 'high' if score < 60 else 'medium'
                areas.append({
                    'aspect': aspect,
                    'name': name,
                    'current_score': round(score, 1),
                    'target_score': 80,
                    'priority': priority
                })
        
        return areas[:2]  # Return top 2 areas to focus on
    
    def increment_frame(self):
        """Increment frame counter."""
        self.session_data['frame_count'] += 1


class ScoreCalculator:
    """
    Utility class for score calculations and statistics.
    """
    
    @staticmethod
    def calculate_progress(sessions: List[Dict]) -> Dict:
        """Calculate progress from multiple sessions."""
        if not sessions:
            return {'trend': 'none', 'improvement': 0}
        
        if len(sessions) < 2:
            return {
                'trend': 'neutral',
                'improvement': 0,
                'avg_score': sessions[0].get('total_score', 0)
            }
        
        # Sort by date
        sorted_sessions = sorted(sessions, key=lambda x: x.get('created_at', ''))
        
        # Calculate trend
        recent_scores = [s.get('total_score', 0) for s in sorted_sessions[-5:]]
        older_scores = [s.get('total_score', 0) for s in sorted_sessions[:-5]] if len(sorted_sessions) > 5 else [sorted_sessions[0].get('total_score', 0)]
        
        recent_avg = np.mean(recent_scores)
        older_avg = np.mean(older_scores)
        
        improvement = recent_avg - older_avg
        
        if improvement > 5:
            trend = 'improving'
        elif improvement < -5:
            trend = 'declining'
        else:
            trend = 'stable'
        
        return {
            'trend': trend,
            'improvement': round(improvement, 1),
            'recent_avg': round(recent_avg, 1),
            'total_sessions': len(sessions),
            'total_practice_time': sum(s.get('duration', 0) for s in sessions)
        }
    
    @staticmethod
    def calculate_level(total_score: int, practice_count: int) -> str:
        """Determine user level based on score and practice count."""
        if practice_count < 5:
            return 'Pemula'
        elif practice_count < 15 and total_score < 1000:
            return 'Dasar'
        elif practice_count < 30 and total_score < 3000:
            return 'Menengah'
        elif practice_count < 50 and total_score < 6000:
            return 'Mahir'
        else:
            return 'Master'
    
    @staticmethod
    def calculate_daily_stats(sessions: List[Dict], days: int = 7) -> List[Dict]:
        """Calculate daily statistics for the past N days."""
        stats = []
        today = datetime.now().date()
        
        for i in range(days):
            date = today - timedelta(days=i)
            day_sessions = [
                s for s in sessions
                if s.get('created_at', '').startswith(date.isoformat())
            ]
            
            if day_sessions:
                stats.append({
                    'date': date.isoformat(),
                    'session_count': len(day_sessions),
                    'avg_score': round(np.mean([s.get('total_score', 0) for s in day_sessions]), 1),
                    'total_duration': sum(s.get('duration', 0) for s in day_sessions)
                })
            else:
                stats.append({
                    'date': date.isoformat(),
                    'session_count': 0,
                    'avg_score': 0,
                    'total_duration': 0
                })
        
        return stats[::-1]  # Reverse to chronological order
    
    @staticmethod
    def get_character_mastery(sessions: List[Dict]) -> Dict[str, Dict]:
        """Calculate mastery level for each character."""
        characters = ['panji', 'samba', 'rumyang', 'tumenggung', 'klana']
        mastery = {}
        
        for char in characters:
            char_sessions = [s for s in sessions if s.get('karakter') == char]
            
            if char_sessions:
                avg_score = np.mean([s.get('total_score', 0) for s in char_sessions])
                best_score = max(s.get('total_score', 0) for s in char_sessions)
                
                if avg_score >= 85:
                    level = 'Master'
                elif avg_score >= 70:
                    level = 'Mahir'
                elif avg_score >= 50:
                    level = 'Menengah'
                else:
                    level = 'Pemula'
                
                mastery[char] = {
                    'level': level,
                    'avg_score': round(avg_score, 1),
                    'best_score': round(best_score, 1),
                    'session_count': len(char_sessions)
                }
            else:
                mastery[char] = {
                    'level': 'Belum dicoba',
                    'avg_score': 0,
                    'best_score': 0,
                    'session_count': 0
                }
        
        return mastery
