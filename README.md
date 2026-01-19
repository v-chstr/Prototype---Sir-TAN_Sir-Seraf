# SPUP Integrated Evaluation and Feedback System

A comprehensive Laravel-based evaluation and feedback system designed for St. Paul University Philippines (SPUP) to collect, manage, and analyze evaluations and feedback from various stakeholders.

---

## 📋 System Overview

### Purpose
The SPUP Integrated Evaluation and Feedback System serves as a centralized platform for gathering evaluations and feedback from the university community. It enables students, employees, guests, and parents/guardians to evaluate various university standards and office services, providing valuable insights for continuous improvement.

### Key Objectives
1. **Standardized Evaluation Process** - Provide a uniform method for collecting feedback across all university departments and services
2. **Multi-Stakeholder Engagement** - Allow different user types to participate in the evaluation process
3. **Data-Driven Decision Making** - Generate comprehensive reports and analytics to guide administrative decisions
4. **Transparency & Accountability** - Track and monitor service quality across all university offices

### System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    SPUP Evaluation System                        │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   Student   │  │  Employee   │  │    Guest    │   Users      │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘              │
│         │                │                │                      │
│  ┌──────┴────────────────┴────────────────┴──────┐              │
│  │           Authentication Layer                 │              │
│  │         (Role-Based Access Control)           │              │
│  └──────────────────────┬────────────────────────┘              │
│                         │                                        │
│  ┌──────────────────────┴────────────────────────┐              │
│  │              Evaluation Module                 │              │
│  │  ┌─────────────────┐  ┌─────────────────┐    │              │
│  │  │   Standards     │  │    Offices      │    │              │
│  │  │  - Admin Leaders│  │  - Healthcare   │    │              │
│  │  │  - Environment  │  │  - ICT          │    │              │
│  │  │  - Facilities   │  │  - Canteen      │    │              │
│  │  │                 │  │  - Registrar    │    │              │
│  │  │                 │  │  - OSA          │    │              │
│  │  └─────────────────┘  └─────────────────┘    │              │
│  └──────────────────────┬────────────────────────┘              │
│                         │                                        │
│  ┌──────────────────────┴────────────────────────┐              │
│  │            Admin Dashboard                     │              │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐         │              │
│  │  │Analytics│ │ Reports │ │ Export  │         │              │
│  │  │ Charts  │ │Generator│ │  Excel  │         │              │
│  │  └─────────┘ └─────────┘ └─────────┘         │              │
│  └───────────────────────────────────────────────┘              │
│                         │                                        │
│  ┌──────────────────────┴────────────────────────┐              │
│  │              Database Layer                    │              │
│  │           (SQLite / MySQL)                    │              │
│  └───────────────────────────────────────────────┘              │
└─────────────────────────────────────────────────────────────────┘
```

### Data Flow

```
User Registration → Login → Select Evaluation Category → Rate Criteria → Submit
                                                                           │
                                                                           ▼
Admin Dashboard ← Analytics Processing ← Database Storage ← Evaluation Data
       │
       ▼
Reports & Excel Exports → Decision Making → Service Improvement
```

---

## ✨ Features

### 🔐 User Features
- **Multi-role Authentication**: Secure login system supporting four distinct user roles
  - Students (with Student ID)
  - Employees (with Employee ID)
  - Guests (general visitors)
  - Parents/Guardians (with contact information)
- **Standards Evaluation**: Rate university standards including Administration Leaders, Learning Environment, and Facilities
- **Offices Evaluation**: Evaluate service quality of Healthcare, ICT, Canteen, Registrar Office, and OSA
- **Contact Form**: Submit messages, inquiries, and suggestions to the administration
- **Evaluation History**: Track previously submitted evaluations

### 📊 Admin Features
- **Real-time Dashboard**: Comprehensive overview with key performance indicators
  - Total registered users
  - Total submitted evaluations
  - Active evaluation categories
  - Unread messages count
- **Visual Analytics**: Interactive charts and graphs powered by Chart.js
  - Monthly evaluation trends (line chart)
  - User distribution by role (doughnut chart)
  - Category ratings comparison (bar chart)
  - Evaluations per category (horizontal bar chart)
- **Evaluation Management**: View, filter, and analyze all submitted evaluations
- **User Management**: Monitor registered users and their evaluation activity
- **Category Management**: View and manage evaluation categories and criteria
- **Message Center**: Read, respond to, and manage contact messages
- **Report Generation**: Create detailed and summary reports with filtering options
- **Excel Export**: Export evaluation data and reports to spreadsheets

---

## 🛠 Tech Stack

| Component | Technology |
|-----------|------------|
| **Backend Framework** | Laravel 12.x (PHP 8.1+) |
| **Frontend Framework** | Bootstrap 5.3 |
| **Icons** | Bootstrap Icons |
| **Database** | SQLite (default) / MySQL |
| **Charts & Visualization** | Chart.js 4.x |
| **Excel Export** | Laravel Excel (Maatwebsite/Excel 3.1) |
| **Authentication** | Laravel Built-in Auth with Custom Middleware |
| **ORM** | Eloquent ORM |

---

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM (optional, for asset compilation)
- SQLite or MySQL database

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Final_Project-Sir Seraf-Sir Tan
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   
   For **SQLite** (default - recommended for development):
   ```bash
   # The database file is already configured
   # Located at: database/database.sqlite
   ```
   
   For **MySQL** (recommended for production):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=spup_evaluation
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - 🌐 Main Site: http://localhost:8000
   - 🔐 Login Page: http://localhost:8000/login
   - 👤 Admin Panel: http://localhost:8000/admin

---

## 🔑 Default Credentials

### Administrator Account
| Field | Value |
|-------|-------|
| **Email** | admin@spup.edu.ph |
| **Password** | admin123 |

> ⚠️ **Security Note**: Change the default admin password immediately after first login in a production environment.

---

## 👥 User Roles & Permissions

| Role | Description | Capabilities |
|------|-------------|--------------|
| **Student** | Currently enrolled students | Submit evaluations, view history, contact admin |
| **Employee** | Faculty and staff members | Submit evaluations, view history, contact admin |
| **Guest** | External visitors | Submit evaluations, contact admin |
| **Parent/Guardian** | Parents or guardians of students | Submit evaluations, contact admin |
| **Admin** | System administrators | Full access to dashboard, reports, user management |

### Role-Based Access Control

```
┌─────────────────────────────────────────────────────────────┐
│                    Access Control Matrix                     │
├──────────────────┬──────────┬──────────┬───────┬───────────┤
│ Feature          │ Student  │ Employee │ Guest │ Admin     │
├──────────────────┼──────────┼──────────┼───────┼───────────┤
│ View Home Page   │    ✓     │    ✓     │   ✓   │     ✓     │
│ Submit Evaluation│    ✓     │    ✓     │   ✓   │     ✓     │
│ Contact Form     │    ✓     │    ✓     │   ✓   │     ✓     │
│ View Dashboard   │    ✗     │    ✗     │   ✗   │     ✓     │
│ Manage Users     │    ✗     │    ✗     │   ✗   │     ✓     │
│ View Reports     │    ✗     │    ✗     │   ✗   │     ✓     │
│ Export Data      │    ✗     │    ✗     │   ✗   │     ✓     │
│ Reply Messages   │    ✗     │    ✗     │   ✗   │     ✓     │
└──────────────────┴──────────┴──────────┴───────┴───────────┘
```

---

## 📝 Evaluation Categories

### Standards Evaluation

| Category | Description | Sample Criteria |
|----------|-------------|-----------------|
| **Administration Leaders** | Evaluate leadership and management | Communication, Decision-making, Accessibility |
| **Learning Environment** | Assess academic atmosphere | Classroom conditions, Teaching quality, Resources |
| **Facilities** | Rate physical infrastructure | Cleanliness, Maintenance, Safety |

### Offices Evaluation

| Office | Description | Sample Criteria |
|--------|-------------|-----------------|
| **Healthcare Services** | University clinic and medical services | Staff courtesy, Response time, Facility cleanliness |
| **ICT Services** | Information technology support | Internet connectivity, Technical support, Equipment |
| **Canteen Services** | Food and dining facilities | Food quality, Cleanliness, Service speed |
| **Registrar Office** | Student records and enrollment | Processing time, Staff helpfulness, Accuracy |
| **Office of Student Affairs (OSA)** | Student services and activities | Program quality, Staff approachability, Support |

### Rating Scale

| Rating | Label | Description |
|--------|-------|-------------|
| ⭐ 1 | Poor | Significantly below expectations |
| ⭐⭐ 2 | Fair | Below average, needs improvement |
| ⭐⭐⭐ 3 | Good | Meets basic expectations |
| ⭐⭐⭐⭐ 4 | Very Good | Exceeds expectations |
| ⭐⭐⭐⭐⭐ 5 | Excellent | Outstanding performance |

---

## 📁 Project Structure

```
Final_Project-Sir Seraf-Sir Tan/
│
├── app/
│   ├── Exports/                    # Excel export classes
│   │   ├── EvaluationsExport.php   # Detailed evaluation export
│   │   └── SummaryReportExport.php # Summary report export
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── ReportController.php
│   │   │   ├── AuthController.php  # Authentication logic
│   │   │   ├── ContactController.php
│   │   │   ├── EvaluationController.php
│   │   │   └── HomeController.php
│   │   │
│   │   └── Middleware/
│   │       └── AdminMiddleware.php # Admin access control
│   │
│   └── Models/                     # Eloquent models
│       ├── ContactMessage.php
│       ├── Evaluation.php
│       ├── EvaluationCategory.php
│       ├── EvaluationCriteria.php
│       ├── EvaluationResponse.php
│       ├── Role.php
│       └── User.php
│
├── database/
│   ├── migrations/                 # Database schema
│   │   ├── create_roles_table.php
│   │   ├── add_role_to_users_table.php
│   │   ├── create_evaluation_categories_table.php
│   │   ├── create_evaluation_criteria_table.php
│   │   ├── create_evaluations_table.php
│   │   ├── create_evaluation_responses_table.php
│   │   └── create_contact_messages_table.php
│   │
│   ├── seeders/                    # Sample data seeders
│   │   ├── AdminSeeder.php
│   │   ├── EvaluationCategorySeeder.php
│   │   └── RoleSeeder.php
│   │
│   └── database.sqlite             # SQLite database file
│
├── resources/
│   └── views/
│       ├── admin/                  # Admin panel views
│       │   ├── dashboard.blade.php
│       │   ├── categories/
│       │   ├── evaluations/
│       │   ├── messages/
│       │   ├── reports/
│       │   └── users/
│       │
│       ├── auth/                   # Login & Register views
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       │
│       ├── evaluation/             # Evaluation forms
│       │   ├── form.blade.php
│       │   ├── select.blade.php
│       │   └── thank-you.blade.php
│       │
│       ├── layouts/                # Layout templates
│       │   ├── app.blade.php       # Main layout
│       │   └── admin.blade.php     # Admin layout
│       │
│       ├── contact.blade.php
│       ├── home.blade.php
│       └── welcome.blade.php
│
├── routes/
│   └── web.php                     # All web routes
│
├── .env                            # Environment configuration
├── composer.json                   # PHP dependencies
└── README.md                       # This file
```

---

## 🗄️ Database Schema

### Entity Relationship Diagram

```
┌─────────────┐       ┌─────────────┐       ┌────────────────────┐
│    roles    │       │    users    │       │ evaluation_        │
├─────────────┤       ├─────────────┤       │ categories         │
│ id          │◄──────│ role_id     │       ├────────────────────┤
│ name        │       │ id          │       │ id                 │
│ display_name│       │ name        │       │ name               │
│ description │       │ email       │       │ type               │
└─────────────┘       │ password    │       │ description        │
                      │ student_id  │       │ icon               │
                      │ employee_id │       │ is_active          │
                      │ phone       │       └─────────┬──────────┘
                      │ department  │                 │
                      │ is_admin    │                 │
                      └──────┬──────┘                 │
                             │                        │
                             │                        ▼
                             │         ┌────────────────────┐
                             │         │ evaluation_        │
                             │         │ criteria           │
                             │         ├────────────────────┤
                             │         │ id                 │
                             │         │ category_id        │
                             │         │ question           │
                             │         │ description        │
                             │         │ order              │
                             │         │ is_active          │
                             │         └─────────┬──────────┘
                             │                   │
                             ▼                   │
                      ┌─────────────┐           │
                      │ evaluations │           │
                      ├─────────────┤           │
                      │ id          │           │
                      │ user_id     │           │
                      │ category_id │           │
                      │ academic_yr │           │
                      │ semester    │           │
                      │ comment     │           │
                      │ status      │           │
                      └──────┬──────┘           │
                             │                   │
                             ▼                   ▼
                      ┌─────────────────────────────┐
                      │    evaluation_responses     │
                      ├─────────────────────────────┤
                      │ id                          │
                      │ evaluation_id               │
                      │ criteria_id                 │
                      │ rating                      │
                      │ comment                     │
                      └─────────────────────────────┘

┌─────────────────────┐
│  contact_messages   │
├─────────────────────┤
│ id                  │
│ user_id             │
│ name                │
│ email               │
│ subject             │
│ message             │
│ status              │
│ admin_reply         │
│ replied_at          │
└─────────────────────┘
```

---

## 📊 Admin Dashboard Features

### Statistics Cards
- **Total Users**: Count of all registered non-admin users
- **Total Evaluations**: Number of submitted evaluations
- **Active Categories**: Number of enabled evaluation categories
- **Unread Messages**: Pending contact messages

### Charts & Visualizations

1. **Monthly Evaluation Trend** (Line Chart)
   - Tracks evaluation submissions throughout the year
   - Helps identify peak evaluation periods

2. **User Distribution** (Doughnut Chart)
   - Breakdown of users by role
   - Visual representation of stakeholder participation

3. **Category Ratings** (Bar Chart)
   - Average rating per evaluation category
   - Compare performance across departments

4. **Evaluations by Category** (Horizontal Bar Chart)
   - Number of evaluations per category
   - Identify most/least evaluated areas

### Report Generation
- **Detailed Reports**: Individual evaluation records with all responses
- **Summary Reports**: Aggregated statistics by category and criteria
- **Export Options**: Download as Excel spreadsheet

---

## 🔒 Security Features

- Password hashing using bcrypt
- CSRF protection on all forms
- Role-based middleware for admin routes
- Input validation and sanitization
- SQL injection prevention via Eloquent ORM
- XSS protection through Blade templating

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is developed exclusively for **St. Paul University Philippines**.

---

## 📞 Contact & Support

For questions, issues, or support:

- **Email**: it@spup.edu.ph
- **Department**: IT Department, SPUP
- **Location**: St. Paul University Philippines

---

<p align="center">
  <b>SPUP Integrated Evaluation and Feedback System</b><br>
  <i>Empowering continuous improvement through feedback</i><br><br>
  Developed for St. Paul University Philippines © 2026
</p>
