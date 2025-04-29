# artisan-forces
## Team Members
- sampad-singha (Team Leader)
- Saiful-islam-Sohan
- MdMahadiHasan

## Mentor
- shadman-ahmed-bs23

# 🎓 Department Automation System 
### One stop solution for student, teacher & staff.

A backend API built with Laravel for managing student, teacher, courses, sessions, enrollments, grading, result, publication, student applications (like leave or transcript), and real-time notifications. Designed for use in university-level academic portals with role-based access control.

---

## 🚀 Features

### 👨‍💼 Admin

- Manage users, roles, and permissions
- Import users using excel sheet
- Create and manage courses and sessions
- Assign teachers to courses
- Review and send student applications for authorization
- Crete notices and send for approval
- Auto generate publication to students and teachers using DOI
- View system-wide statistics and data exports
  
---

### 👨‍🏫 Teacher

- View assigned courses and enrolled students
- Assign and update student marks (CA + Final)
- Access and manage course materials (upload, update, delete)
- Approve or decline student applications (if assigned as authorizer)
- Generate & download ID card
- View noticeboard and administrative messages

---

-  Role & permission management
-  Course & session tracking
-  Student enrollment, grading & result
-  Generate & download ID card
-  Dynamic application templates (leave, transcript, etc.)
-  Secure file uploads/downloads for course materials
-  PDF generation with mPDF
-  Authenticated API for students, teachers, and admins

---

### 👨‍🎓 Student

- View registered courses and grades
- See and download semester results
- Submit applications (leave, transcript, etc.) using dynamic forms
- Download approved application PDFs
- View enrolled and all courses
- View uploaded course materials
- Re enroll to courses (if applicable)
- Download university ID card (PDF)

## ⚙️ Tech Stack

- **PHP 8.2**, **Laravel 11** (Backend)
- **MySQL** (Database)
- **React** (Frontend)
- **mPDF** (PDF generation)
- **Filament** (Admin Panel)

---

## 📦 Installation

```bash
git clone https://github.com/Learnathon-By-Geeky-Solutions/artisan-forces
cd artisan_forces
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

## 📦 Frontend

```bash
cd frontend
npm install
npm run dev
```

## Requirements
- PHP v8.2+ with all the extensions
- node v20 or higher (use nvm before installing the packages)
- Laravel v11 or higher
- Mail client (Mailhog or Mailtrap for local development)
- MySQL server (XAMPP or similar)

