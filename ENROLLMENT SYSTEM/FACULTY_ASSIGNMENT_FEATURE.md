# Faculty Assignment Feature

## Overview
This document describes the faculty assignment feature that has been implemented in the Enrollment System. This feature allows administrators to assign faculty members to teach specific subjects.

## Implementation Details

### Files Modified

1. **models/User.php**
   - Added `getUsersByRole($role)` method to retrieve users by their role (faculty, student, admin)

2. **subjects.php**
   - Added faculty assignment functionality to the subjects management page
   - Integrated faculty assignment with existing subject management features

### Key Features

1. **Faculty Assignment Interface**
   - Added a "Faculty" button to each subject in the subjects list
   - Created a modal interface for assigning faculty members to subjects
   - Dropdown selection of available faculty members
   - Form submission for faculty assignment

2. **Backend Logic**
   - Faculty assignment validation to prevent duplicate assignments
   - Proper error handling and user feedback
   - Integration with existing faculty assignment database table

3. **User Experience**
   - Consistent styling with the rest of the admin interface
   - Clear success/error messages
   - Intuitive workflow for assigning faculty to subjects

## Usage Instructions

### For Administrators

1. Log in to the system with administrator credentials
2. Navigate to the "Subjects" page in the admin portal
3. Find the subject you want to assign faculty to
4. Click the "Faculty" button in the Actions column for that subject
5. In the modal that appears:
   - Select a faculty member from the dropdown list
   - Click "Assign Faculty" to complete the assignment
6. The system will display a success message upon successful assignment

### Test Credentials

- **Test Faculty Member**: 
  - Username: `faculty1`
  - Password: `faculty123`

## Technical Implementation

### Database Integration
The feature uses the existing `faculty_assignments` table with the following structure:
- `id`: Primary key
- `faculty_id`: Foreign key referencing the users table
- `subject_id`: Foreign key referencing the subjects table
- `assignment_date`: Timestamp of when the assignment was made

### Code Structure
The implementation follows the existing MVC pattern of the application:
- **Model**: Uses the Faculty and User models for data operations
- **View**: Adds UI elements to the existing subjects management page
- **Controller**: Adds POST request handlers for faculty assignment operations

## Future Enhancements

Possible improvements for future versions:
1. Display current faculty assignments in the modal
2. Add ability to remove faculty assignments
3. Implement bulk faculty assignment functionality
4. Add search/filter capabilities for faculty members
5. Include faculty assignment information in subject listings