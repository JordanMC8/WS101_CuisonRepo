# USER ACCOUNTS OF THE SYSTEM

- STUDENT
Username: student1
Password: password

-Adviser(LEO GABRIEL VILLANUEVA)
Username: adviser1
Password: password

-ADMIN SIDE
Username: admin
Password: password

# Thesis Management System

A comprehensive web-based platform for managing academic theses with role-based access control, submission workflows, and advanced search capabilities.

## Features

### User Roles
- **Administrator**: Manage users, departments, programs, and system settings
- **Student**: Submit theses, track status, and view approved theses
- **Adviser**: Review and approve/reject student theses

### Core Functionality
- User authentication with role-based access control
- Thesis submission with metadata (title, abstract, keywords, etc.)
- Approval workflow with comments and feedback
- Advanced search and filtering of theses
- Activity logging for audit trails
- Report generation and export capabilities
- Secure file upload with validation

### Technical Specifications
- **Backend**: PHP with PDO for database interactions
- **Database**: MySQL with comprehensive schema
- **Frontend**: HTML5, CSS3, JavaScript with responsive design
- **Security**: Password hashing, file validation, CSRF protection, rate limiting

## System Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server
- Composer (for dependencies, if any)

## Installation

1. Clone or download the repository to your web server directory
2. Create a MySQL database and import the `thesis_system.sql` file
3. Update the database configuration in `config/db.php` if needed
4. Ensure the `assets/uploads/` directory is writable
5. Access the application through your web browser

## Folder Structure
```
Thesis System/
├── admin/                 # Admin-specific pages
├── adviser/               # Adviser-specific pages
├── assets/                # Static assets (CSS, JS, images, uploads)
│   ├── css/
│   ├── images/
│   ├── js/
│   └── uploads/
├── config/                # Configuration files
├── controllers/           # Controller classes
├── includes/              # Utility classes and functions
├── models/                # Data models
├── student/               # Student-specific pages
└── views/                 # View templates
    ├── admin/
    ├── adviser/
    ├── shared/
    └── student/
```

## Database Schema

The system includes the following tables:
- `users`: Stores user information with roles
- `departments`: Academic departments
- `programs`: Academic programs linked to departments
- `theses`: Main thesis information and metadata
- `thesis_files`: Uploaded thesis files
- `approvals`: Approval workflow tracking
- `review_logs`: Review comments and feedback
- `activity_logs`: System activity audit trail

## User Credentials

Default user accounts (password for all: `password`):
- Admin: `admin` / `admin@university.edu`
- Student: `student` / `student@university.edu`
- Adviser: `faculty` / `faculty@university.edu`

## Security Features
- Password hashing with bcrypt
- File upload validation and sanitization
- CSRF protection
- Rate limiting for login attempts
- Input sanitization and output encoding
- Session management

## Reporting
- Thesis statistics and analytics
- User registration trends
- Department performance metrics
- Export reports to CSV format

## License
This project is proprietary and intended for educational purposes only.

## Support
For issues and feature requests, please contact the development team.