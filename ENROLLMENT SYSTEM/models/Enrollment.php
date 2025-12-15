<?php
class Enrollment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Enroll student in a subject
    public function enrollStudent($studentId, $subjectId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
        return $stmt->execute([$studentId, $subjectId]);
    }

    // Get student enrollments
    public function getStudentEnrollments($studentId)
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*, s.subject_code, s.subject_name, s.credits
            FROM enrollments e
            JOIN subjects s ON e.subject_id = s.id
            WHERE e.student_id = ?
            ORDER BY e.enrollment_date DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    // Get students enrolled in a subject
    public function getStudentsInSubject($subjectId)
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*, u.first_name, u.last_name, u.profile_picture, u.signature
            FROM enrollments e
            JOIN users u ON e.student_id = u.id
            WHERE e.subject_id = ?
            ORDER BY u.last_name, u.first_name
        ");
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll();
    }

    // Check if student has completed a prerequisite subject
    public function hasCompletedPrerequisite($studentId, $prerequisiteId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM enrollments 
            WHERE student_id = ? AND subject_id = ? AND status = 'completed'
        ");
        $stmt->execute([$studentId, $prerequisiteId]);
        return $stmt->fetch() !== false;
    }

    // Submit grade for student
    public function submitGrade($enrollmentId, $grade)
    {
        $stmt = $this->pdo->prepare("UPDATE enrollments SET grade = ?, status = 'completed' WHERE id = ?");
        return $stmt->execute([$grade, $enrollmentId]);
    }

    // Drop enrollment
    public function dropEnrollment($enrollmentId)
    {
        $stmt = $this->pdo->prepare("UPDATE enrollments SET status = 'dropped' WHERE id = ?");
        return $stmt->execute([$enrollmentId]);
    }
}
