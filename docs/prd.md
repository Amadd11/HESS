# HESS — Product Requirements Document (PRD)

> Version: 1.1
> Architecture: Laravel 13 + Blade + Alpine.js + Tailwind CSS v4
> Target: Vibe Coding / AI Coding Assistant

## Overview

HESS adalah aplikasi survei kepuasan pegawai rumah sakit berbasis MSQ-20 dan Hospital Work Factors.
Aplikasi memiliki **2 role saja**:

- Admin (Dashboard & Analytics)
- User Anonim (Mengisi kuesioner)

Aplikasi **tidak memiliki landing page terpisah**; responden yang mengakses tautan survei akan langsung diarahkan ke form profil & kuesioner interaktif.

## Tech Stack

- Laravel 13
- Blade Template
- Alpine.js
- Tailwind CSS v4
- MySQL / PostgreSQL
- Spatie Permission

## Folder Structure

```text
app/
├── Models/
├── Services/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Survey/
│   ├── Requests/
│   │   ├── Survey/
│   │   └── Admin/
│   └── Middleware/
├── Policies/
├── Jobs/
└── Notifications/
resources/
├── views/
│   ├── admin/
│   ├── survey/
│   └── components/
```

## Roles

### Admin

- Login
- Dashboard (KPI, Charts, Word Cloud, Feedback)
- CRUD Periode Survey
- CRUD Pertanyaan
- Analytics & Laporan
- Export (Excel / PDF)

### User Anonim

- Langsung isi Profil (Kelompok tenaga, unit kerja, status kepegawaian, lama bekerja)
- Isi 44 Pertanyaan (20 MSQ + 24 Hospital Work Factors) dengan kartu interaktif 1 per 1
- Penilaian Keseluruhan (Overall Satisfaction, NPS 0–10, 2 Pertanyaan Kualitatif)
- Submit Survey & Halaman Selesai (Finish)

## Modules

### Survey (User Anonim)

- **Profile**: Form demografi anonim awal.
- **Questionnaire**: 44 pertanyaan (20 MSQ-20 + 24 Hospital Work Factors) dengan kartu interaktif berbasis Alpine.js & autosave di browser.
- **Overall Satisfaction**: Skala kepuasan global, rekomendasi tempat kerja (eNPS 0–10), dan 2 masukan kualitatif (*hal yang disukai* & *hal yang perlu diperbaiki*).
- **Finish**: Layar konfirmasi terima kasih setelah data berhasil disimpan ke database.

### Dashboard (Admin)

- KPI
- Charts
- Word Cloud
- Export

## Database

Core tables (Lihat rincian di [docs/erd.md](file:///c:/laragon/www/HESS/docs/erd.md)):

- `users`
- `demographics` (Master kelompok profesi, unit kerja, status, dan masa kerja)
- `periods`
- `categories`
- `questions`
- `responses`
- `answers`

## Service Layer

- SurveyResponseService
- SurveyScoringService
- DashboardAnalyticsService
- ExportSurveyService

## Validation

Gunakan Form Request (`App\Http\Requests\...`) untuk seluruh input survey dan admin.

## Authorization

Gunakan Laravel Policy untuk seluruh halaman dan aksi admin.

## Coding Rules (Vibe Coding)

- Gunakan Blade + Alpine.js untuk interaktivitas form survey (transisi kartu instan, zero-latency).
- Semua business logic di Service.
- Jangan query database di Blade.
- Gunakan eager loading.
- Semua migration menggunakan foreignId().
- SoftDeletes pada master data.

## Output Dashboard

- Total Responden
- Response Rate
- Intrinsic Satisfaction
- Extrinsic Satisfaction
- General Satisfaction
- Hospital Work Factors
- NPS
- Word Cloud
- Feedback Table

## Future Roadmap

- Reminder Email
- AI Sentiment Analysis
- Multi Hospital
