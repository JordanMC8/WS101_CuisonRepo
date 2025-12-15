<?php
class Schedule
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get schedule for a specific subject
    public function getScheduleBySubjectId($subjectId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM class_schedules WHERE subject_id = ?");
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll();
    }

    // Get schedules for multiple subjects
    public function getSchedulesBySubjectIds($subjectIds)
    {
        if (empty($subjectIds)) {
            return [];
        }

        $placeholders = str_repeat('?,', count($subjectIds) - 1) . '?';
        $stmt = $this->pdo->prepare("SELECT * FROM class_schedules WHERE subject_id IN ($placeholders)");
        $stmt->execute($subjectIds);
        return $stmt->fetchAll();
    }

    // Add schedule for a subject
    public function addSchedule($subjectId, $classCode, $unitHours, $timeFrom, $timeTo, $days, $room)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO class_schedules (subject_id, class_code, unit_hours, time_from, time_to, days, room) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$subjectId, $classCode, $unitHours, $timeFrom, $timeTo, $days, $room]);
    }

    // Update schedule
    public function updateSchedule($id, $classCode, $unitHours, $timeFrom, $timeTo, $days, $room)
    {
        $stmt = $this->pdo->prepare("
            UPDATE class_schedules 
            SET class_code = ?, unit_hours = ?, time_from = ?, time_to = ?, days = ?, room = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$classCode, $unitHours, $timeFrom, $timeTo, $days, $room, $id]);
    }

    // Delete schedule
    public function deleteSchedule($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM class_schedules WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get student's schedule based on their enrollments
    public function getStudentSchedule($studentId)
    {
        $stmt = $this->pdo->prepare("
            SELECT cs.*, s.subject_code, s.subject_name, s.credits, s.description, u.first_name as instructor_first_name, u.last_name as instructor_last_name
            FROM class_schedules cs
            JOIN subjects s ON cs.subject_id = s.id
            JOIN enrollments e ON s.id = e.subject_id
            LEFT JOIN faculty_assignments fa ON s.id = fa.subject_id
            LEFT JOIN users u ON fa.faculty_id = u.id
            WHERE e.student_id = ? AND e.status != 'dropped'
            ORDER BY cs.days, cs.time_from
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
}
