# CLAUDE.MD — AI Development Guide
## SPUP Integrated Evaluation and Feedback System

---

## Project Identity
- **Project**: SPUP Integrated Evaluation and Feedback System
- **Institution**: St. Paul University Philippines
- **Stack**: Laravel 10.x · PHP 8.1+ · SQLite/MySQL · Bootstrap 5 · Chart.js · Laravel Excel
- **Architecture**: MVC with Blade templating, Eloquent ORM

---

## Design Philosophy

### Microsoft Forms Design Language
All UI changes must adhere to these principles:

1. **Flat & Clean** — No heavy shadows. Use `box-shadow: 0 1px 4px rgba(0,0,0,0.08)` max.
2. **Minimal Border Radius** — `border-radius: 4px` for cards/containers. `2px` for badges/buttons. Never `15px+`.
3. **Icon Restraint** — Icons are used ONLY for navigation sidebar items and functional buttons (e.g., export, eye/view). Section headers use **text only**, no decorative icons.
4. **Compact Stat Cards** — Plain flat card with thin `#e8e8e8` border. Text label above + large number below. No icon blobs, no colored left-border accents.
5. **Typography Hierarchy** — `fw-semibold` for numbers, `text-muted` + small for labels. Clean separation.
6. **Neutral Backgrounds** — `#f4f6f9` body, `#fff` cards. No gradients on content areas.
7. **Professional Color Palette** — Primary: `#198754` (SPUP green), Accent: `#FFD700`, Dark: `#0d5c36`. Use semantic colors sparingly (success/warning/danger for ratings only).

### When to Use Icons
- Sidebar navigation: YES (one icon per nav item)
- Action buttons (export, view, back): YES
- Section/card headers: **NO**
- Empty state illustrations: Acceptable but minimal
- Inline text decoration: **NO**

---

## Data Confidentiality Rules

### Frontend Anonymization (CRITICAL)
- **All user names and emails are anonymized on the frontend.** The admin cannot see real names.
- **Database stores real names in plaintext** — this is by design for database standards.
- Anonymization is handled by `App\Helpers\AnonymizeHelper` using HMAC-SHA256 hashing.
- The same user always produces the same anonymous identifier (deterministic).
- Format: `Respondent-A7F3C2`, `User-B91E0D`, `Sender-4C8A12`
- Emails display as: `a7f3••••@••••.•••`

### AnonymizeHelper Methods
| Method | Input | Output Example |
|---|---|---|
| `anonymizeUser($userId, $prefix)` | User ID + prefix | `Respondent-A7F3C2` |
| `anonymizeEmail($userId)` | User ID | `a7f3••••@••••.•••` |
| `anonymizeInitial($userId)` | User ID | `K` (single letter) |

### Where Anonymization is Applied
- `admin/dashboard.blade.php` — Recent evaluations table
- `admin/evaluations/index.blade.php` — Evaluations list
- `admin/evaluations/show.blade.php` — Evaluator info panel
- `admin/reports/result.blade.php` — Detailed evaluations table
- `admin/users/index.blade.php` — Users management table
- `admin/messages/index.blade.php` — Contact messages list
- `admin/messages/show.blade.php` — Message detail view
- `layouts/admin.blade.php` — Top navbar shows "Administrator" (not real name)

### What is NOT Anonymized
- The admin's own session label (shows "Administrator")
- Category names, criteria questions, ratings, dates
- Role display names (Student, Employee, etc.)
- Excel exports (`EvaluationsExport.php`) — **NOTE: exports still contain real names. Future task: anonymize exports.**

---

## File Structure Reference

### Key Directories
```
app/Helpers/AnonymizeHelper.php     — Frontend anonymization logic
app/Http/Controllers/Admin/         — All admin controllers
app/Exports/                        — Excel export classes
resources/views/admin/              — Admin panel blade views
resources/views/layouts/admin.blade.php — Admin layout + CSS
```

### Admin Views
| View | Purpose |
|---|---|
| `admin/dashboard.blade.php` | Main dashboard with stats + charts |
| `admin/evaluations/index.blade.php` | Evaluations listing with filters |
| `admin/evaluations/show.blade.php` | Single evaluation detail |
| `admin/users/index.blade.php` | User management table |
| `/messages/index.blade.php` | Contact messages list |
| `admin/messages/show.blade.php` | Message detail + reply |
| `admin/reports/index.blade.php` | Report generator form |
| `admin/reports/result.blade.php` | Generated report view |
| `admin/reports/summary.blade.php` | Summary report view |
| `admin/categories/index.blade.php` | Category management |

---

## Development Rules

1. **Never display real user names on frontend** — always use `AnonymizeHelper`
2. **Never store encrypted names in database** — anonymization is frontend-only
3. **Follow Microsoft Forms design** — flat, compact, minimal icons, 4px radius
4. **Use Eloquent ORM** — no raw SQL unless performance-critical aggregation
5. **Validate all inputs** — use Laravel Form Requests or inline validation
6. **Middleware for auth** — admin routes use `auth` + `admin` middleware
7. **Chart.js bar radius** — use `borderRadius: 2`, never rounded bars

---

## Decision Support Feature (How It Works)

The system's decision support capability is implemented through the admin dashboard and reporting modules. It converts raw evaluation responses into actionable indicators for institutional planning and service improvement.

### 1) Data Inputs
- Respondents submit evaluations per category (standards/offices).
- Each evaluation stores category-level metadata in `evaluations` and per-question ratings/comments in `evaluation_responses`.
- Only records with `status = submitted` are included in admin analytics.

### 2) Processing and Aggregation
- **Volume metrics**: total users, total submitted evaluations, total active categories, unread messages.
- **Trend metrics**: monthly submission counts for the current year.
- **Performance metrics**: average ratings per category and per criterion.
- **Distribution metrics**: respondent counts by role (student, employee, guest, parent/guardian).

Core calculations are currently unweighted means and counts:
- Category average rating = average of all criterion ratings linked to submitted evaluations in that category.
- Overall report average = total of all ratings divided by total number of responses.

### 3) Admin Decision Outputs
- **Dashboard Overview** (`admin/dashboard`):
	- KPI/stat cards for high-level monitoring
	- Charts for monthly trends, category ratings, user distribution, and category evaluation counts
	- Recent evaluations table for quick operational review
- **Evaluation Management** (`admin/evaluations`):
	- Filterable records (category, role, date range; plus student filters)
	- Drill-down to individual evaluation details
- **Reports** (`admin/reports`, `admin/summary`):
	- Detailed, summary, and category-specific views
	- Export options (screen view, CSV/Excel-compatible output, PDF)

### 4) Decision-Making Use Cases
- Identify low-performing categories/criteria (e.g., low average ratings) and prioritize interventions.
- Detect seasonal participation and response trends via monthly counts.
- Compare service quality across standards vs offices for resource allocation.
- Use filtered reports for targeted actions (department/role/date-specific reviews).

### 5) Privacy and Governance in Decision Support
- Admin-facing respondent identity is anonymized in frontend tables/panels via `AnonymizeHelper`.
- Decision support focuses on aggregated ratings, counts, and trends rather than personally identifying display data.

### 6) Current Scope and Improvement Path
- Current analytics are descriptive/diagnostic (what happened, where performance is high/low).
- Future extensions can include threshold-based alerts, weighted scoring, benchmarking by period, and recommendation rules for automatic action prompts.

---

## Academic Year & Semester Cycle Feature (Planned)

### Overview
Users are allowed to evaluate **once per category per semester per academic year**. When a new semester or academic year begins, users can evaluate again — their previous evaluations are preserved and their "already evaluated" lock resets for the new period. The admin controls when each period ends and when the next one begins.

---

### Feature Requirements

#### 1) Academic Year & Semester Settings (Admin-Controlled)
- A `settings` table (or dedicated `academic_periods` table) stores the **currently active academic year and semester**.
- Fields: `academic_year` (e.g., `2025-2026`), `semester` (`First Semester` | `Second Semester` | `Summer`), `is_active` (boolean), `started_at`, `ended_at`.
- Only **one period is active at a time**.
- Admin can view and edit the active period from a settings page (`admin/settings`).
- Admin can **close the current period** (sets `ended_at`, marks inactive) and **open the next one** (creates a new active record with the new semester/academic year).

#### 2) Evaluation Lock Scope
- The one-evaluation-per-category limit is scoped to `(user_id, category_id, academic_year, semester)` — NOT lifetime.
- When a new period is opened, all users can evaluate again across all categories.
- Old evaluations remain intact in the database for historical reporting.

#### 3) Transition Rules
| Current Semester | Next Semester | Academic Year |
|---|---|---|
| First Semester | Second Semester | Same year |
| Second Semester | Summer | Same year |
| Summer | First Semester | Incremented by 1 |

#### 4) Frontend Behavior
- The currently active academic year and semester is displayed on the Standards and Offices listing pages (e.g., *"A.Y. 2025–2026 · First Semester"*).
- If **no active period** exists (admin has not opened one), evaluation buttons are disabled with the label *"Evaluation period not yet open"*.
- If the user has already evaluated a category for the **current active period**, the button shows *"Already Evaluated"* (disabled).
- If a new period opens, the button reverts to *"Evaluate Now"* for all categories.

#### 5) Admin Settings UI (`admin/settings`)
- Shows the current active period (academic year + semester).
- Button: **"Close Period & Open Next"** — closes the active period and automatically calculates and creates the next one.
- Confirmation modal required before transitioning (irreversible action).
- History table showing all past periods with date ranges and total evaluation counts.

---

### Implementation Notes

#### Files to Create / Modify
| File | Action | Purpose |
|---|---|---|
| `database/migrations/xxxx_create_academic_periods_table.php` | Create | Stores period records |
| `app/Models/AcademicPeriod.php` | Create | Eloquent model with `active()` scope |
| `app/Http/Controllers/Admin/SettingsController.php` | Create | Manage active period transitions |
| `resources/views/admin/settings/index.blade.php` | Create | Admin settings UI |
| `app/Http/Controllers/EvaluationController.php` | Modify | Scope lock to active period |
| `app/Http/Controllers/HomeController.php` | Modify | Pass active period to listing views |
| `resources/views/standards/index.blade.php` | Modify | Show period label; gate buttons |
| `resources/views/offices/index.blade.php` | Modify | Show period label; gate buttons |
| `routes/web.php` | Modify | Add admin settings routes |

#### AcademicPeriod Model — Key Methods
```php
// Get the currently active period
AcademicPeriod::active()->first();

// Compute the next semester/year from the current period
public function nextPeriod(): array {
    // First Semester → Second Semester (same year)
    // Second Semester → Summer (same year)
    // Summer → First Semester (year + 1)
}
```

#### Evaluation Lock Check (updated)
```php
$activePeriod = AcademicPeriod::active()->first();

$alreadySubmitted = Evaluation::where('user_id', Auth::id())
    ->where('category_id', $category->id)
    ->where('academic_year', $activePeriod->academic_year)
    ->where('semester', $activePeriod->semester)
    ->exists();
```

#### Security & Edge Cases
- If no active period exists, `EvaluationController@show` and `@store` must abort with a user-friendly message — not a 500 error.
- Admin transition action must be guarded by `auth` + `admin` middleware and require CSRF.
- `ended_at` must be recorded on close to preserve period history.
- The `academic_year` and `semester` columns on the `evaluations` table must be populated from the active period at submission time (not derived at read time).

---

## Session Reporting
After each task session, a session report is provided in the chat (not as a file).
Contains: Changes made, files modified, design decisions, open items.
