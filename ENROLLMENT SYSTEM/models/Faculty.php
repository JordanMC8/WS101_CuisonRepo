<?php
class Faculty
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get subjects assigned to faculty
    public function getAssignedSubjects($facultyId)
    {
        $stmt = $this->pdo->prepare("
            SELECT s.* 
            FROM subjects s 
            JOIN faculty_assignments fa ON s.id = fa.subject_id 
            WHERE fa.faculty_id = ?
        ");
        $stmt->execute([$facultyId]);
        return $stmt->fetchAll();
    }

    // Assign faculty to subject
    public function assignToSubject($facultyId, $subjectId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO faculty_assignments (faculty_id, subject_id) VALUES (?, ?)");
        return $stmt->execute([$facultyId, $subjectId]);
    }

    // Remove faculty assignment
    public function removeFromSubject($facultyId, $subjectId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM faculty_assignments WHERE faculty_id = ? AND subject_id = ?");
        return $stmt->execute([$facultyId, $subjectId]);
    }
}
