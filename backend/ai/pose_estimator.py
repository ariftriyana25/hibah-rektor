import cv2
import numpy as np
import mediapipe as mp
from typing import Dict, List, Tuple, Optional
import math

class PoseEstimator:
    """
    MediaPipe-based pose estimation for Tari Topeng Cirebon.
    Evaluates Wiraga (body movement accuracy).
    """
    
    # Key landmark indices for dance analysis
    BODY_LANDMARKS = {
        'nose': 0,
        'left_eye_inner': 1,
        'left_eye': 2,
        'left_eye_outer': 3,
        'right_eye_inner': 4,
        'right_eye': 5,
        'right_eye_outer': 6,
        'left_ear': 7,
        'right_ear': 8,
        'mouth_left': 9,
        'mouth_right': 10,
        'left_shoulder': 11,
        'right_shoulder': 12,
        'left_elbow': 13,
        'right_elbow': 14,
        'left_wrist': 15,
        'right_wrist': 16,
        'left_pinky': 17,
        'right_pinky': 18,
        'left_index': 19,
        'right_index': 20,
        'left_thumb': 21,
        'right_thumb': 22,
        'left_hip': 23,
        'right_hip': 24,
        'left_knee': 25,
        'right_knee': 26,
        'left_ankle': 27,
        'right_ankle': 28,
        'left_heel': 29,
        'right_heel': 30,
        'left_foot_index': 31,
        'right_foot_index': 32
    }
    
    # Joint triplets for angle calculation
    ANGLE_JOINTS = [
        ('left_shoulder', 'left_elbow', 'left_wrist'),
        ('right_shoulder', 'right_elbow', 'right_wrist'),
        ('left_hip', 'left_knee', 'left_ankle'),
        ('right_hip', 'right_knee', 'right_ankle'),
        ('left_elbow', 'left_shoulder', 'left_hip'),
        ('right_elbow', 'right_shoulder', 'right_hip'),
        ('left_shoulder', 'left_hip', 'left_knee'),
        ('right_shoulder', 'right_hip', 'right_knee'),
    ]
    
    def __init__(self):
        self.mp_pose = mp.solutions.pose
        self.mp_face_mesh = mp.solutions.face_mesh
        self.mp_hands = mp.solutions.hands
        self.mp_drawing = mp.solutions.drawing_utils
        
        self.pose = self.mp_pose.Pose(
            static_image_mode=False,
            model_complexity=2,
            enable_segmentation=True,
            min_detection_confidence=0.7,
            min_tracking_confidence=0.7
        )
        
        self.face_mesh = self.mp_face_mesh.FaceMesh(
            static_image_mode=False,
            max_num_faces=1,
            refine_landmarks=True,
            min_detection_confidence=0.7,
            min_tracking_confidence=0.7
        )
        
        self.hands = self.mp_hands.Hands(
            static_image_mode=False,
            max_num_hands=2,
            min_detection_confidence=0.7,
            min_tracking_confidence=0.7
        )
    
    def process_frame(self, frame: np.ndarray) -> Dict:
        """Process a single frame and extract all pose data."""
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        
        # Get pose landmarks
        pose_results = self.pose.process(rgb_frame)
        face_results = self.face_mesh.process(rgb_frame)
        hands_results = self.hands.process(rgb_frame)
        
        result = {
            'pose_landmarks': None,
            'face_landmarks': None,
            'hand_landmarks': [],
            'angles': {},
            'head_orientation': None,
            'body_orientation': None,
            'confidence': 0.0
        }
        
        if pose_results.pose_landmarks:
            landmarks = pose_results.pose_landmarks.landmark
            result['pose_landmarks'] = self._extract_pose_landmarks(landmarks)
            result['angles'] = self._calculate_joint_angles(landmarks)
            result['body_orientation'] = self._calculate_body_orientation(landmarks)
            result['confidence'] = self._calculate_pose_confidence(landmarks)
        
        if face_results.multi_face_landmarks:
            face_landmarks = face_results.multi_face_landmarks[0]
            result['face_landmarks'] = self._extract_face_landmarks(face_landmarks.landmark)
            result['head_orientation'] = self._calculate_head_orientation(face_landmarks.landmark)
        
        if hands_results.multi_hand_landmarks:
            for hand_landmarks in hands_results.multi_hand_landmarks:
                result['hand_landmarks'].append(
                    self._extract_hand_landmarks(hand_landmarks.landmark)
                )
        
        return result
    
    def _extract_pose_landmarks(self, landmarks) -> List[Dict]:
        """Extract pose landmarks as dictionary."""
        extracted = []
        for name, idx in self.BODY_LANDMARKS.items():
            lm = landmarks[idx]
            extracted.append({
                'name': name,
                'x': lm.x,
                'y': lm.y,
                'z': lm.z,
                'visibility': lm.visibility
            })
        return extracted
    
    def _extract_face_landmarks(self, landmarks) -> List[Dict]:
        """Extract key face landmarks for expression analysis."""
        key_indices = [1, 33, 61, 199, 263, 291, 389, 454, 10, 152]  # Key facial points
        extracted = []
        for idx in key_indices:
            lm = landmarks[idx]
            extracted.append({
                'index': idx,
                'x': lm.x,
                'y': lm.y,
                'z': lm.z
            })
        return extracted
    
    def _extract_hand_landmarks(self, landmarks) -> List[Dict]:
        """Extract hand landmarks for gesture analysis."""
        extracted = []
        for idx, lm in enumerate(landmarks):
            extracted.append({
                'index': idx,
                'x': lm.x,
                'y': lm.y,
                'z': lm.z
            })
        return extracted
    
    def _calculate_angle(self, a: Tuple, b: Tuple, c: Tuple) -> float:
        """Calculate angle between three points."""
        ba = np.array([a[0] - b[0], a[1] - b[1], a[2] - b[2]])
        bc = np.array([c[0] - b[0], c[1] - b[1], c[2] - b[2]])
        
        cosine_angle = np.dot(ba, bc) / (np.linalg.norm(ba) * np.linalg.norm(bc) + 1e-6)
        angle = np.arccos(np.clip(cosine_angle, -1.0, 1.0))
        return np.degrees(angle)
    
    def _calculate_joint_angles(self, landmarks) -> Dict[str, float]:
        """Calculate all joint angles."""
        angles = {}
        for joint1, joint2, joint3 in self.ANGLE_JOINTS:
            idx1 = self.BODY_LANDMARKS[joint1]
            idx2 = self.BODY_LANDMARKS[joint2]
            idx3 = self.BODY_LANDMARKS[joint3]
            
            p1 = (landmarks[idx1].x, landmarks[idx1].y, landmarks[idx1].z)
            p2 = (landmarks[idx2].x, landmarks[idx2].y, landmarks[idx2].z)
            p3 = (landmarks[idx3].x, landmarks[idx3].y, landmarks[idx3].z)
            
            angle_name = f"{joint1}_{joint2}_{joint3}"
            angles[angle_name] = self._calculate_angle(p1, p2, p3)
        
        return angles
    
    def _calculate_body_orientation(self, landmarks) -> Dict:
        """Calculate body orientation (facing direction)."""
        left_shoulder = landmarks[self.BODY_LANDMARKS['left_shoulder']]
        right_shoulder = landmarks[self.BODY_LANDMARKS['right_shoulder']]
        left_hip = landmarks[self.BODY_LANDMARKS['left_hip']]
        right_hip = landmarks[self.BODY_LANDMARKS['right_hip']]
        
        shoulder_vec = np.array([
            right_shoulder.x - left_shoulder.x,
            right_shoulder.y - left_shoulder.y,
            right_shoulder.z - left_shoulder.z
        ])
        
        hip_vec = np.array([
            right_hip.x - left_hip.x,
            right_hip.y - left_hip.y,
            right_hip.z - left_hip.z
        ])
        
        yaw = np.arctan2(shoulder_vec[2], shoulder_vec[0])
        
        return {
            'yaw': np.degrees(yaw),
            'shoulder_alignment': np.linalg.norm(shoulder_vec[:2]),
            'hip_alignment': np.linalg.norm(hip_vec[:2])
        }
    
    def _calculate_head_orientation(self, landmarks) -> Dict:
        """Calculate head orientation for Wirasa analysis."""
        nose = landmarks[1]
        left_eye = landmarks[33]
        right_eye = landmarks[263]
        chin = landmarks[152]
        forehead = landmarks[10]
        
        eye_vec = np.array([right_eye.x - left_eye.x, right_eye.y - left_eye.y])
        yaw = np.arctan2(eye_vec[1], eye_vec[0])
        
        face_height = np.sqrt(
            (forehead.x - chin.x)**2 + (forehead.y - chin.y)**2
        )
        pitch = np.arcsin(np.clip(nose.z * 2, -1, 1))
        
        roll = np.arctan2(right_eye.y - left_eye.y, right_eye.x - left_eye.x)
        
        return {
            'yaw': np.degrees(yaw),
            'pitch': np.degrees(pitch),
            'roll': np.degrees(roll)
        }
    
    def _calculate_pose_confidence(self, landmarks) -> float:
        """Calculate overall pose detection confidence."""
        visibilities = [lm.visibility for lm in landmarks]
        return np.mean(visibilities)
    
    def compare_poses(self, user_pose: Dict, maestro_pose: Dict, 
                      angle_threshold: float = 15.0) -> Dict:
        """Compare user pose with maestro reference."""
        if not user_pose['pose_landmarks'] or not maestro_pose.get('angles'):
            return {'score': 0, 'feedback': ['Pose tidak terdeteksi']}
        
        user_angles = user_pose['angles']
        maestro_angles = maestro_pose.get('angles', {})
        
        errors = []
        total_diff = 0
        compared_joints = 0
        
        for joint_name, maestro_angle in maestro_angles.items():
            if joint_name in user_angles:
                user_angle = user_angles[joint_name]
                diff = abs(user_angle - maestro_angle)
                total_diff += diff
                compared_joints += 1
                
                if diff > angle_threshold:
                    body_part = self._get_body_part_name(joint_name)
                    if diff > 30:
                        errors.append(f"Perbaiki posisi {body_part} (selisih besar)")
                    else:
                        errors.append(f"Sesuaikan {body_part}")
        
        if compared_joints == 0:
            return {'score': 0, 'feedback': ['Tidak ada sendi yang terdeteksi']}
        
        avg_diff = total_diff / compared_joints
        score = max(0, 100 - (avg_diff * 2))
        
        return {
            'score': round(score, 1),
            'feedback': errors[:5] if errors else ['Bagus! Gerakan sudah tepat'],
            'avg_angle_diff': round(avg_diff, 2),
            'joints_compared': compared_joints
        }
    
    def _get_body_part_name(self, joint_name: str) -> str:
        """Convert joint name to Indonesian body part name."""
        mappings = {
            'left_shoulder': 'bahu kiri',
            'right_shoulder': 'bahu kanan',
            'left_elbow': 'siku kiri',
            'right_elbow': 'siku kanan',
            'left_wrist': 'pergelangan kiri',
            'right_wrist': 'pergelangan kanan',
            'left_hip': 'pinggul kiri',
            'right_hip': 'pinggul kanan',
            'left_knee': 'lutut kiri',
            'right_knee': 'lutut kanan',
            'left_ankle': 'pergelangan kaki kiri',
            'right_ankle': 'pergelangan kaki kanan'
        }
        
        for key, value in mappings.items():
            if key in joint_name:
                return value
        return joint_name
    
    def draw_pose(self, frame: np.ndarray, pose_data: Dict, 
                  color: Tuple = (0, 255, 0)) -> np.ndarray:
        """Draw pose landmarks on frame."""
        if not pose_data['pose_landmarks']:
            return frame
        
        h, w = frame.shape[:2]
        
        # Draw connections
        connections = [
            (11, 12), (11, 13), (13, 15), (12, 14), (14, 16),
            (11, 23), (12, 24), (23, 24),
            (23, 25), (25, 27), (24, 26), (26, 28)
        ]
        
        landmarks = pose_data['pose_landmarks']
        for conn in connections:
            p1 = landmarks[conn[0]]
            p2 = landmarks[conn[1]]
            x1, y1 = int(p1['x'] * w), int(p1['y'] * h)
            x2, y2 = int(p2['x'] * w), int(p2['y'] * h)
            cv2.line(frame, (x1, y1), (x2, y2), color, 2)
        
        # Draw landmarks
        for lm in landmarks:
            x, y = int(lm['x'] * w), int(lm['y'] * h)
            cv2.circle(frame, (x, y), 5, color, -1)
        
        return frame
    
    def release(self):
        """Release resources."""
        self.pose.close()
        self.face_mesh.close()
        self.hands.close()


class ExpressionAnalyzer:
    """
    Analyze facial expressions for Wirasa evaluation.
    Detects emotion and expression intensity.
    """
    
    def __init__(self):
        self.mp_face_mesh = mp.solutions.face_mesh
        self.face_mesh = self.mp_face_mesh.FaceMesh(
            static_image_mode=False,
            max_num_faces=1,
            refine_landmarks=True,
            min_detection_confidence=0.7
        )
        
        # Facial landmark indices for expression analysis
        self.MOUTH_OUTER = [61, 185, 40, 39, 37, 0, 267, 269, 270, 409, 291]
        self.MOUTH_INNER = [78, 191, 80, 81, 82, 13, 312, 311, 310, 415, 308]
        self.LEFT_EYE = [33, 246, 161, 160, 159, 158, 157, 173, 133]
        self.RIGHT_EYE = [362, 398, 384, 385, 386, 387, 388, 466, 263]
        self.LEFT_EYEBROW = [70, 63, 105, 66, 107]
        self.RIGHT_EYEBROW = [336, 296, 334, 293, 300]
    
    def analyze(self, frame: np.ndarray) -> Dict:
        """Analyze facial expression in frame."""
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        results = self.face_mesh.process(rgb_frame)
        
        if not results.multi_face_landmarks:
            return {'detected': False, 'expression': 'unknown', 'intensity': 0}
        
        landmarks = results.multi_face_landmarks[0].landmark
        
        # Calculate expression metrics
        mouth_openness = self._calculate_mouth_openness(landmarks)
        eye_openness = self._calculate_eye_openness(landmarks)
        eyebrow_raise = self._calculate_eyebrow_position(landmarks)
        
        # Determine expression based on metrics
        expression = self._classify_expression(mouth_openness, eye_openness, eyebrow_raise)
        intensity = (mouth_openness + eye_openness + eyebrow_raise) / 3
        
        return {
            'detected': True,
            'expression': expression,
            'intensity': round(intensity, 2),
            'mouth_openness': round(mouth_openness, 2),
            'eye_openness': round(eye_openness, 2),
            'eyebrow_raise': round(eyebrow_raise, 2)
        }
    
    def _calculate_mouth_openness(self, landmarks) -> float:
        """Calculate how open the mouth is (0-1)."""
        upper_lip = landmarks[13]
        lower_lip = landmarks[14]
        mouth_corner_left = landmarks[61]
        mouth_corner_right = landmarks[291]
        
        vertical = abs(upper_lip.y - lower_lip.y)
        horizontal = abs(mouth_corner_left.x - mouth_corner_right.x)
        
        return min(1.0, vertical / (horizontal + 1e-6) * 2)
    
    def _calculate_eye_openness(self, landmarks) -> float:
        """Calculate average eye openness (0-1)."""
        left_upper = landmarks[159]
        left_lower = landmarks[145]
        right_upper = landmarks[386]
        right_lower = landmarks[374]
        
        left_openness = abs(left_upper.y - left_lower.y)
        right_openness = abs(right_upper.y - right_lower.y)
        
        return min(1.0, (left_openness + right_openness) * 10)
    
    def _calculate_eyebrow_position(self, landmarks) -> float:
        """Calculate eyebrow raise level (0-1)."""
        left_brow = landmarks[66]
        right_brow = landmarks[296]
        left_eye = landmarks[159]
        right_eye = landmarks[386]
        
        left_dist = abs(left_brow.y - left_eye.y)
        right_dist = abs(right_brow.y - right_eye.y)
        
        return min(1.0, (left_dist + right_dist) * 5)
    
    def _classify_expression(self, mouth: float, eye: float, brow: float) -> str:
        """Classify expression based on metrics."""
        if mouth > 0.3 and eye > 0.5:
            return 'surprised'
        elif mouth > 0.2 and brow > 0.3:
            return 'expressive'
        elif mouth < 0.1 and eye < 0.3:
            return 'neutral'
        elif mouth > 0.15:
            return 'smiling'
        else:
            return 'focused'
    
    def release(self):
        """Release resources."""
        self.face_mesh.close()
