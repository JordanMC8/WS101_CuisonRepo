<?php
class Subject
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all subjects
    public function getAllSubjects()
    {
        $stmt = $this->pdo->query("SELECT * FROM subjects ORDER BY subject_code");
        return $stmt->fetchAll();
    }

    // Get subject by ID
    public function getSubjectById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create a new subject
    public function createSubject($subjectCode, $subjectName, $credits, $description)
    {
        $stmt = $this->pdo->prepare("INSERT INTO subjects (subject_code, subject_name, credits, description) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$subjectCode, $subjectName, $credits, $description]);
    }

    // Update subject
    public function updateSubject($id, $subjectCode, $subjectName, $credits, $description)
    {
        $stmt = $this->pdo->prepare("UPDATE subjects SET subject_code = ?, subject_name = ?, credits = ?, description = ? WHERE id = ?");
        return $stmt->execute([$subjectCode, $subjectName, $credits, $description, $id]);
    }

    // Delete subject
    public function deleteSubject($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM subjects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get prerequisites for a subject
    public function getPrerequisites($subjectId)
    {
        $stmt = $this->pdo->prepare("
            SELECT s.* 
            FROM subjects s 
            JOIN prerequisites p ON s.id = p.prerequisite_subject_id 
            WHERE p.subject_id = ?
        ");
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll();
    }

    // Add prerequisite
    public function addPrerequisite($subjectId, $prerequisiteId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO prerequisites (subject_id, prerequisite_subject_id) VALUES (?, ?)");
        return $stmt->execute([$subjectId, $prerequisiteId]);
    }

    // Remove prerequisite
    public function removePrerequisite($subjectId, $prerequisiteId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM prerequisites WHERE subject_id = ? AND prerequisite_subject_id = ?");
        return $stmt->execute([$subjectId, $prerequisiteId]);
    }
}
