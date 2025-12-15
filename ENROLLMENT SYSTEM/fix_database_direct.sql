-- Direct SQL script to fix duplicate subjects and combine subjects with same schedule

-- First, let's see what we're working with
SELECT 'CURRENT SUBJECTS:' as info;
SELECT id, subject_code, subject_name FROM subjects ORDER BY id;

SELECT 'CURRENT SCHEDULES:' as info;
SELECT cs.id, cs.subject_id, cs.class_code, cs.time_from, cs.time_to, cs.days, cs.room, s.subject_code 
FROM class_schedules cs 
JOIN subjects s ON cs.subject_id = s.id 
ORDER BY cs.class_code, cs.days, cs.time_from;

-- Clear existing enrollments and schedules to avoid foreign key issues
DELETE FROM enrollments;
DELETE FROM class_schedules;

-- Update subjects to represent combined entities
UPDATE subjects SET subject_code = 'URD_SIA101_IT', subject_name = 'COI PRE-REQUISITE' WHERE id = 1;
UPDATE subjects SET subject_code = 'URD_SA101_IT', subject_name = 'COI PRE-REQUISITE' WHERE id = 3;
UPDATE subjects SET subject_code = 'URD_MD101_IT', subject_name = 'COI PRE-REQUISITE' WHERE id = 5;
UPDATE subjects SET subject_code = 'URD_WS101_IT', subject_name = 'COI PRE-REQUISITE' WHERE id = 9;

-- Delete duplicate subjects
DELETE FROM subjects WHERE id IN (2, 4, 6, 7, 8, 10, 11, 12);

-- Add combined schedule entries that match your format
INSERT INTO class_schedules (subject_id, class_code, unit_hours, time_from, time_to, days, room) VALUES
(1, '10114', '2.00/1.00', '11:00:00', '17:00:00', 'HM', 'AB1-207/AB1-206'),
(3, '10113', '2.00/1.00', '11:00:00', '16:00:00', 'FT', 'AB1-206/TBA AB1-206'),
(5, '10066', '2.00/1.00', '07:00:00', '15:00:00', 'HF', 'AB1-204'),
(9, '10065', '2.00/1.00', '02:00:00', '15:00:00', 'WT', 'AB1-206');

-- Re-enroll the test student in the remaining subjects
INSERT INTO enrollments (student_id, subject_id) VALUES
((SELECT id FROM users WHERE username = 'student1'), 1),
((SELECT id FROM users WHERE username = 'student1'), 3),
((SELECT id FROM users WHERE username = 'student1'), 5),
((SELECT id FROM users WHERE username = 'student1'), 9);

SELECT 'FIX COMPLETE - REMAINING SUBJECTS:' as info;
SELECT id, subject_code, subject_name FROM subjects ORDER BY id;

SELECT 'NEW SCHEDULES:' as info;
SELECT cs.id, cs.subject_id, cs.class_code, cs.time_from, cs.time_to, cs.days, cs.room, s.subject_code 
FROM class_schedules cs 
JOIN subjects s ON cs.subject_id = s.id 
ORDER BY cs.class_code, cs.days, cs.time_from;