# Class Schedule Feature Implementation

## Overview
This document describes the implementation of the class schedule feature for the Enrollment System. The feature allows students to view their class schedules after enrolling in subjects.

## Implementation Details

### 1. Database Schema Changes
- Added a new `class_schedules` table to store schedule information:
  - `id`: Primary key
  - `subject_id`: Foreign key referencing subjects table
  - `class_code`: Class identification code
  - `unit_hours`: Unit hours information
  - `time_from`: Class start time
  - `time_to`: Class end time
  - `days`: Days of the week when class meets
  - `room`: Room where class is held

### 2. New Files Created
- `models/Schedule.php`: Model for handling schedule operations
- `schedule.php`: Page for displaying student schedules
- `populate_schedule_data.php`: Script for populating sample schedule data
- `enroll_test_student.php`: Script for enrolling test students
- `check_subjects.php`: Script for checking existing subjects

### 3. Modified Files
- `enrollment_system.sql`: Added class_schedules table definition
- `enroll.php`: Added link to schedule page after successful enrollment
- `includes/navbar.php`: Added navigation link to schedule page
- `subjects.php`: Added schedule management functionality

### 4. Key Features
- Students can view their class schedules after enrolling in subjects
- Admins can manage class schedules for subjects
- Schedule information includes class code, subject, units, unit hours, time, days, and room
- Responsive design using Bootstrap

## Usage Instructions

### For Students
1. Log in as a student
2. Navigate to the "Enroll" page
3. Enroll in subjects
4. Click the "View your class schedule" link in the success message, or navigate to "Schedule" in the navbar
5. View your class schedule with all relevant details

### For Administrators
1. Log in as an admin
2. Navigate to the "Subjects" page
3. Click the "Schedule" button next to a subject
4. Add, edit, or delete class schedules for that subject

## Testing
- Created sample schedule data for testing
- Created test student account (username: student1, password: student123)
- Enrolled test student in subjects with schedules
- Verified schedule display functionality

## Future Enhancements
- Implement AJAX loading for schedules in admin interface
- Add validation for schedule time conflicts
- Implement schedule export functionality (PDF, CSV)
- Add recurring schedule patterns
- Implement schedule notifications