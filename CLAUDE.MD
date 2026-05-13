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
| `admin/messages/index.blade.php` | Contact messages list |
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

## Session Reporting
After each task session, a session report is provided in the chat (not as a file).
Contains: Changes made, files modified, design decisions, open items.
