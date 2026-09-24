# HESS — Sentiment Analysis Dashboard

> **Feature Specification v1.0**
>
> Laravel 13 • Blade • Alpine.js • Tailwind CSS v4
>
> Clean Architecture • MVC + Service Layer • Production Ready

---

# 1. Overview

## Tujuan Fitur

Sentiment Analysis Dashboard merupakan fitur analitik kualitatif pada aplikasi **HESS (Hospital Employee Satisfaction Survey)** yang digunakan untuk menganalisis jawaban terbuka responden (feedback positif dan masukan perbaikan) secara visual dan interaktif.

Dashboard ini membantu admin rumah sakit melihat:

* Distribusi sentimen positif, netral, dan negatif.
* Kata yang paling sering muncul pada feedback responden.
* Topik dominan yang sering dibahas pegawai.
* Tren sentimen antar periode survei.
* Sentimen berdasarkan unit kerja, profesi, status pegawai, dan masa kerja.
* Ringkasan insight otomatis sebagai bahan evaluasi manajemen rumah sakit.

Dashboard hanya dapat diakses oleh **Admin**.

---

# 2. User Role

| Role        | Permission                                              |
| ----------- | ------------------------------------------------------- |
| Admin       | View Sentiment Dashboard, Filter Analytics, Export Data |
| User Anonim | Tidak memiliki akses                                    |

Policy menggunakan `SentimentDashboardPolicy`.

---

# 3. Dashboard Information Architecture

```text
Dashboard
│
├── Overview
├── Responses
├── Analytics Kepuasan Pegawai
├── Sentiment Analysis ⭐
│   ├── Summary
│   ├── Word Cloud
│   ├── Keyword Analytics
│   ├── Trend
│   ├── Breakdown
│   ├── Feedback Explorer
│   └── AI Insight
│
└── Reports
```

---

# 4. UI / UX Design System

## Theme Color

| Element    | Color   |
| ---------- | ------- |
| Primary    | #173F78 |
| Secondary  | #6F3F7E |
| Success    | #16A34A |
| Warning    | #EAB308 |
| Danger     | #DC2626 |
| Background | #F8FAFC |
| Surface    | #FFFFFF |

## Typography

* Font : Inter
* Heading : Bold
* Body : Medium
* Caption : Regular

## Border Radius

* Card : 24px
* Button : 16px
* Badge : Full Rounded

---

# 5. Dashboard Layout

## Layout Preview

```text
┌─────────────────────────────────────────────────────────────┐
│ Header + Filters + Export                                  │
├─────────────────────────────────────────────────────────────┤
│ KPI Cards (Positive / Neutral / Negative / Total / Score)   │
├─────────────────────────────────────────────────────────────┤
│ Word Cloud (Tab Positif / Netral / Negatif)                │
├───────────────────────┬─────────────────────────────────────┤
│ Top Positive Keywords │ Top Negative Keywords              │
├─────────────────────────────────────────────────────────────┤
│ Sentiment Trend (Line Chart)                               │
├───────────────────────┬─────────────────────────────────────┤
│ Breakdown by Unit      │ Breakdown by Profession           │
├───────────────────────┴─────────────────────────────────────┤
│ Feedback Explorer Table                                    │
├─────────────────────────────────────────────────────────────┤
│ AI Insight Summary                                         │
└─────────────────────────────────────────────────────────────┘
```

---

# 6. Section Specification

## 6.1 Header Dashboard

### Components

* Page Title
* Subtitle
* Filter Panel
* Export Button

### Filters

| Filter         | Type   |
| -------------- | ------ |
| Periode Survey | Select |
| Unit Kerja     | Select |
| Profesi        | Select |
| Status Pegawai | Select |
| Masa Kerja     | Select |
| Sentimen       | Select |

Filter menggunakan GET Query String.

---

## 6.2 KPI Summary Cards

### Card 1 — Sentimen Positif

Menampilkan:

* Persentase.
* Jumlah feedback.
* Icon senyum hijau.

### Card 2 — Sentimen Netral

Menampilkan:

* Persentase.
* Jumlah feedback.
* Icon netral.

### Card 3 — Sentimen Negatif

Menampilkan:

* Persentase.
* Jumlah feedback.
* Icon sedih merah.

### Card 4 — Total Feedback

Jumlah seluruh feedback kualitatif.

### Card 5 — Average Sentiment Score

Rata-rata skor sentimen seluruh feedback.

---

## 6.3 Word Cloud

### Tujuan

Menampilkan kata yang paling sering muncul berdasarkan sentimen.

### Tab

* Positif
* Netral
* Negatif

### Interaction

Klik kata akan membuka panel Feedback Explorer dengan filter otomatis.

### Data

* kata
* frekuensi
* sentiment

---

## 6.4 Keyword Analytics

### Positive Keywords

Horizontal Bar Chart.

Top 10 kata positif.

### Negative Keywords

Horizontal Bar Chart.

Top 10 kata negatif.

### Output

| Kata      | Frekuensi |
| --------- | --------- |
| pelayanan | 48        |
| teamwork  | 36        |
| dukungan  | 32        |

---

## 6.5 Sentiment Trend

### Chart

Line Chart.

### X Axis

Periode Survey.

### Y Axis

Jumlah feedback.

Series:

* Positive
* Neutral
* Negative

---

## 6.6 Sentiment Breakdown

### By Unit Kerja

Stacked Bar Chart.

### By Profesi

Horizontal Bar Chart.

### By Status Pegawai

Pie Chart.

### By Masa Kerja

Heatmap / Stacked Bar.

---

## 6.7 Feedback Explorer

### Table Columns

| Kolom          |
| -------------- |
| Sentimen Badge |
| Feedback       |
| Unit           |
| Profesi        |
| Masa Kerja     |
| Tanggal Survey |

### Features

* Search.
* Filter.
* Pagination.
* Sort terbaru / terlama.
* Highlight keyword.

---

## 6.8 AI Insight Summary

Menampilkan ringkasan otomatis.

### Insight Card

* Topik Positif.
* Topik Negatif.
* Rekomendasi.
* Kesimpulan.

Output berupa card dengan icon AI.

---

# 7. Laravel Clean Architecture

## Controller

```text
app/Http/Controllers/Admin/
└── SentimentDashboardController.php
```

Controller hanya mengambil data dari Service.

## Services

```text
app/Services/
├── DashboardAnalyticsService.php
├── SentimentAnalysisService.php
├── KeywordExtractionService.php
├── WordCloudService.php
└── SentimentInsightService.php
```

### Responsibility

#### DashboardAnalyticsService

* KPI.
* Trend.
* Breakdown.

#### SentimentAnalysisService

* Label positif/netral/negatif.
* Feedback explorer.

#### KeywordExtractionService

* Top keywords.
* Frekuensi.

#### WordCloudService

* Data word cloud.
* Normalisasi kata.
* Stop words.

#### SentimentInsightService

* Ringkasan AI.
* Insight otomatis.

---

# 8. Blade Structure

```text
resources/views/admin/sentiment/

index.blade.php

partials/
├── header.blade.php
├── summary-cards.blade.php
├── word-cloud.blade.php
├── keyword-chart.blade.php
├── trend-chart.blade.php
├── breakdown-chart.blade.php
├── feedback-table.blade.php
└── ai-summary.blade.php

components/
├── metric-card.blade.php
├── sentiment-badge.blade.php
├── quote-card.blade.php
├── keyword-pill.blade.php
├── filter-panel.blade.php
├── chart-card.blade.php
└── empty-state.blade.php
```

---

# 9. Alpine.js Modules

```text
resources/js/alpine/

sentiment-dashboard.js
filters.js
word-cloud.js
feedback-table.js
chart-tabs.js
```

### Responsibility

#### sentiment-dashboard.js

Global state dashboard.

#### filters.js

Filter dropdown.

#### word-cloud.js

Tab switching.

#### feedback-table.js

Search dan pagination UI.

---

# 10. Routes

```php
Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/sentiment', [SentimentDashboardController::class, 'index'])
            ->name('admin.sentiment.index');

});
```

---

# 11. Database Source

Menggunakan tabel existing HESS.

## Tables

* responses
* answers
* questions
* categories
* demographics
* periods

Tidak menambah tabel baru pada MVP.

---

# 12. Performance Rules

Semua query harus memenuhi aturan berikut.

* Tidak ada query di Blade.
* Tidak ada N+1 Query.
* Gunakan eager loading.
* Gunakan withCount().
* Gunakan pagination.
* Gunakan cache analytics.
* Gunakan select() hanya kolom yang dibutuhkan.

---

# 13. Coding Rules

## Wajib

* MVC.
* Service Layer.
* Form Request.
* Policy.
* Blade Component.
* Partial View.
* Alpine Module.

## Dilarang

* Query database di Blade.
* Business logic di Controller.
* HTML panjang dalam satu Blade.
* Hardcoded warna.
* Hardcoded string sentimen.
* Duplicate Tailwind classes.

---

# 14. Production Checklist

* [ ] Controller tipis.
* [ ] Service memiliki satu tanggung jawab.
* [ ] Semua statistik berasal dari Service.
* [ ] Semua Blade menggunakan reusable component.
* [ ] Semua filter menggunakan Form Request.
* [ ] Semua chart menerima data siap render.
* [ ] Tidak ada N+1 Query.
* [ ] Responsive desktop, tablet, mobile.
* [ ] Empty State.
* [ ] Loading State.
* [ ] Error State.
* [ ] Export Excel dan PDF.
* [ ] Authorization menggunakan Policy.

```
```
