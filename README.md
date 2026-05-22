<h1 align="center">🗳️ BD Election Watch — Backend</h1>

<p align="center">
  <strong>বাংলাদেশ নির্বাচন পর্যবেক্ষণ প্ল্যাটফর্ম | Bangladesh Election Monitoring Platform</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Filament-4.x-FDAE4B?style=for-the-badge" alt="Filament 4">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

---

## Overview

**BD Election Watch** is a Laravel 12-based backend platform for collecting, managing, and verifying citizen-submitted reports of election-related violations and incidents in Bangladesh. It features:

- **Public API** — Citizens submit reports with multimedia evidence
- **Admin Panel** — Admins review, verify, or reject reports via a Filament dashboard
- **Verified Reports API** — Exposes approved reports for public consumption

---

## Features

- Submit election violation reports (with image, video, and URL evidence)
- Admin dashboard with real-time stats and charts (Filament 4 + Livewire)
- Report verification workflow: `Pending → Verified / Rejected`
- API token authentication for secure report submission
- Rate limiting (5 submissions per 10 minutes per IP)
- Bot/spam protection via honeypot field
- IP address and user-agent tracking
- CSV/Excel export of report data
- Bengali-friendly form labels and placeholders
- Database notifications support

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Admin Panel | Filament 4 |
| API Auth | Laravel Sanctum |
| Frontend | Vite + Tailwind CSS 4 |
| Realtime Components | Livewire |
| Database | MySQL 8 |
| Queue / Cache | Database driver |
| Dev Environment | Laravel Sail (Docker) |

---

## Project Structure

```
app/
├── Filament/
│   ├── Exports/          # ReportExporter (CSV/Excel)
│   ├── Resources/
│   │   └── Reports/      # ReportResource, form, table, pages
│   └── Widgets/          # StatsOverview, ReportsChart
├── Http/
│   ├── Controllers/      # ReportController (API)
│   └── Middleware/       # VerifyApiToken
├── Livewire/             # ReportsChart component
├── Models/               # User, Report
└── Providers/            # AdminPanelProvider

routes/
├── api.php               # Public + authenticated API routes
└── web.php

database/migrations/      # All schema migrations
```

---

## API Endpoints

### Submit a Report
```
POST /api/create-report
```

**Headers:**
```
X-API-TOKEN: your_api_token_here
Content-Type: multipart/form-data
```

**Body (form-data):**

| Field | Type | Required | Description |
|---|---|---|---|
| `name` | string | No | Reporter's name |
| `contact` | string | No | Phone / email |
| `category` | string | Yes | Violation category |
| `location` | string | Yes | Location of incident |
| `constituency` | string | Yes | Electoral constituency |
| `description` | string | Yes | Description (max 2000 chars) |
| `event_date` | date | No | Date of incident |
| `time` | string | No | Time of incident |
| `image` | file | No | Evidence image (max 5MB) |
| `video` | string | No | Video URL |
| `url` | string | No | Reference URL |
| `website` | string | — | Leave empty (honeypot) |

**Rate Limit:** 5 requests per 10 minutes per IP.

---

### Get Verified Reports
```
GET /api/get-reports?date=YYYY-MM-DD
```

Returns paginated verified reports (10 per page). The `date` parameter filters by `event_date`.

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "category": "ভোট কেন্দ্র দখল",
      "location": "ঢাকা",
      "constituency": "ঢাকা-১",
      "description": "...",
      "status": "verified",
      "image": "http://your-domain.com/storage/reports/images/example.jpg",
      "event_date": "2026-01-15",
      "created_at": "2026-01-15T10:30:00.000000Z"
    }
  ],
  "last_page": 5,
  "total": 48
}
```

---

## Admin Panel

Access the admin panel at `/admin`.

### Dashboard Widgets
- **Stats Overview** — Total, Pending, Verified, and Rejected report counts
- **Reports Chart** — Hourly submission chart for today

### Report Management
- List all reports with filters (status: Pending / Verified / Rejected)
- Create, view, edit, and delete reports
- Bulk delete
- Export to CSV/Excel

---

## Installation

### Requirements
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL 8.0+
- (Optional) Docker for Laravel Sail

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/mrjisan12/bdelectionwatch-backend-.git
cd bdelectionwatch-backend
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials and API token:
```env
DB_DATABASE=bdelectionwatch_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

API_TOKEN=your_secure_api_token_here
```

**4. Run migrations**
```bash
php artisan migrate
```

**5. Create an admin user**
```bash
php artisan make:filament-user
```

**6. Build frontend assets**
```bash
npm run build
```

**7. Start the development server**
```bash
php artisan serve
```

Or use the all-in-one dev command (runs server, queue, logs, and Vite):
```bash
composer run dev
```

### Using Laravel Sail (Docker)
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm run dev
```

---

## Environment Variables

| Variable | Description | Example |
|---|---|---|
| `APP_URL` | Application URL | `http://localhost` |
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_DATABASE` | Database name | `bdelectionwatch_db` |
| `API_TOKEN` | API authentication token | `@##bdelectionwatch2026_##@` |
| `FILESYSTEM_DISK` | Storage disk | `public` |
| `QUEUE_CONNECTION` | Queue driver | `database` |

---

## Security

- All report submission endpoints require a valid `X-API-TOKEN` header
- Rate limiting prevents abuse (5 requests / 10 min per IP)
- Honeypot field (`website`) blocks automated bot submissions
- IP address and user-agent are logged per submission
- Admin panel protected by Filament authentication

---

## Database Schema

### `reports` table

| Column | Type | Description |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string, nullable | Reporter name |
| `contact` | string, nullable | Contact info |
| `category` | string | Violation category |
| `location` | string | Location of incident |
| `constituency` | string | Electoral constituency |
| `description` | text | Detailed description |
| `event_date` | date, nullable | Date of incident |
| `time` | string, nullable | Time of incident |
| `image` | string, nullable | Stored image path |
| `video` | string, nullable | Video URL |
| `url` | string, nullable | Reference URL |
| `status` | enum | `pending`, `verified`, `rejected` |
| `evidence` | text, nullable | Admin notes / feedback |
| `ip_address` | string, nullable | Submitter IP |
| `user_agent` | string, nullable | Submitter browser info |
| `created_at` | timestamp | Submission time |
| `updated_at` | timestamp | Last update time |

---

## Testing

```bash
composer run test
# or
php artisan test
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Author

**Jisan** — [@mrjisan12](https://github.com/mrjisan12)
