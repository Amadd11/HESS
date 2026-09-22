# Laravel HESS — Full Architecture & Code Audit Prompt (Production Ready)

Bertindaklah sebagai **Senior Laravel Developer & Tech Lead** dengan pemahaman mendalam tentang **Laravel 13, Blade, Alpine.js, Tailwind CSS v4, MySQL**, dan arsitektur yang **bersih, pragmatis, dan proporsional (anti over-engineering)**.

Lakukan audit menyeluruh terhadap project **HESS (Hospital Employee Satisfaction Survey)**. Perlu ditekankan bahwa **aplikasi ini adalah aplikasi survei sederhana dan belum masuk level enterprise**. Jangan memaksakan pola arsitektur yang berlebihan (*over-engineering* seperti abstraksi berlapis-lapis, repository pattern yang rumit, atau pemisahan folder yang terlalu dalam). Fokus audit adalah memastikan kode **rapi, terstruktur, ringan, aman, mudah dirawat oleh satu tim kecil, dan siap production** sesuai skala kebutuhan riilnya.

Audit harus dilakukan seperti proses **code review sebelum aplikasi di-deploy ke production**. Jangan hanya memberi saran umum atau teori enterprise yang tidak relevan. Identifikasi setiap masalah nyata, jelaskan penyebabnya, dampaknya, dan berikan rekomendasi perbaikan yang simpel, elegan, serta mengikuti best practice standar Laravel 13.

---

## Ruang Lingkup Audit

### 1. Architecture Audit (Laravel MVC + Service Layer)

Audit apakah project sudah mengikuti konsep MVC yang benar.

Periksa:

* Apakah Controller terlalu gemuk (Fat Controller).
* Apakah Business Logic sudah dipindahkan ke Service.
* Apakah Service hanya menangani business logic.
* Apakah Model hanya menangani relasi, scope, accessor, mutator, dan casting.
* Apakah Form Request hanya menangani validasi HTTP.
* Apakah Blade hanya berisi tampilan (tidak ada query database atau business logic).
* Apakah Policy digunakan untuk authorization admin.
* Apakah struktur folder sudah modular dan mudah dikembangkan.
* Apakah terdapat business logic yang terlalu rumit/berbelit-belit (*over-engineered*) yang sebenarnya bisa dibuat jauh lebih sederhana dan efisien sesuai kebutuhan.

Evaluasi apakah ada pelanggaran **Single Responsibility Principle (SRP)** dan prinsip **KISS (Keep It Simple, Stupid)**.

---

### 2. Folder Structure Audit

Audit struktur folder project Laravel.

Evaluasi apakah struktur berikut sudah clean:

* app/Models
* app/Services
* app/Http/Controllers/Admin
* app/Http/Controllers/Survey
* app/Http/Requests/Admin
* app/Http/Requests/Survey
* app/Policies
* app/Jobs
* resources/views/admin
* resources/views/survey
* resources/views/components

Periksa apakah ada file yang salah lokasi, terlalu besar, atau seharusnya dipisahkan menjadi partial atau component.

Berikan struktur folder yang lebih bersih jika diperlukan.

---

### 3. MVC Audit

Audit implementasi MVC secara detail.

#### Controller

Periksa apakah:

* Controller terlalu panjang.
* Controller memiliki query kompleks.
* Controller menghitung statistik.
* Controller melakukan transformasi data.
* Controller mengirim terlalu banyak data ke Blade.

Rekomendasikan apa yang dipindahkan ke Service atau ViewModel.

#### Model

Periksa:

* Relasi sudah benar.
* Scope digunakan dengan tepat.
* Accessor/Mutator digunakan bila perlu.
* Fillable/Guarded aman.
* SoftDeletes pada master data.
* Casting sudah digunakan.

#### Service

Audit apakah Service:

* Memiliki satu tanggung jawab.
* Tidak mengakses Request langsung.
* Tidak mengembalikan View.
* Tidak mengandung logika presentasi.

---

### 4. Database & Eloquent Audit

Audit seluruh query database.

Periksa secara spesifik:

* N+1 Query.
* Missing eager loading.
* Nested eager loading.
* Lazy loading yang tidak perlu.
* whereHas() vs join().
* withCount().
* exists() vs count().
* first() vs get().
* select() hanya mengambil kolom yang dibutuhkan.
* Pagination.
* Chunk/Lazy Collection untuk data besar.
* Transaction pada proses submit survey.
* Foreign Key dan Index.

Identifikasi semua potensi N+1 Query dan berikan contoh cara memperbaikinya menggunakan `with()`, `load()`, atau `loadMissing()`.

---

### 5. Blade Template Audit

Audit seluruh Blade Template.

Periksa:

* Blade terlalu panjang.
* Blade memiliki lebih dari satu responsibility.
* HTML berulang.
* Tailwind class berulang.
* Inline CSS.
* Inline JavaScript.
* @php terlalu banyak.
* Logic kondisi kompleks.
* Query database di Blade.
* Loop yang menyebabkan N+1.

Rekomendasikan:

* Blade Component.
* Partial View.
* Layout Component.
* Anonymous Component.

---

### 6. Alpine.js Audit

Audit penggunaan Alpine.js.

Periksa:

* x-data terlalu besar.
* Banyak state dalam satu Blade.
* Function terlalu panjang.
* Modal state.
* Progress state.
* Autosave state.
* Transition state.

Rekomendasikan pemisahan module Alpine ke `resources/js/alpine`.

---

### 7. Tailwind CSS Audit

Audit kualitas Tailwind CSS.

Periksa:

* Utility class terlalu panjang.
* Hardcoded warna.
* Hardcoded spacing.
* Typography tidak konsisten.
* Shadow dan radius tidak konsisten.
* Reusable UI belum dibuat.
* Responsive belum konsisten.

Rekomendasikan design system menggunakan Blade Component.

---

### 8. Validation Audit

Audit seluruh Form Request.

Periksa:

* Query database di FormRequest.
* Magic number.
* prepareForValidation().
* authorize().
* Rule Object.
* Custom Validation Rule.
* attributes().
* messages().

Pastikan FormRequest hanya bertanggung jawab pada validasi.

---

### 9. Security Audit

Audit keamanan aplikasi Laravel.

Periksa:

* CSRF Protection.
* Mass Assignment.
* Validation.
* Authorization Policy.
* XSS pada Blade.
* SQL Injection.
* Route Protection.
* Hidden Input Trust Issue.
* Rate Limiting pada submit survey.
* Token Survey anonim.

Berikan rekomendasi production security.

---

### 10. Performance Audit

Audit performa aplikasi.

Periksa:

* Duplicate Query.
* Query dalam Loop.
* Collection vs Query Builder.
* Cache yang bisa digunakan.
* Config Cache.
* Route Cache.
* View Cache.
* OPcache readiness.
* Asset loading.
* Image optimization.
* Pagination.

Berikan daftar optimisasi yang bisa langsung diterapkan.

---

### 11. Clean Code Audit

Audit seluruh project berdasarkan Clean Code.

Periksa:

* Naming Convention.
* Method terlalu panjang.
* Class terlalu panjang.
* Duplicate Code.
* Magic String.
* Magic Number.
* Reusable Helper.
* Enum atau Constant bila diperlukan.
* Comment yang tidak diperlukan.
* Dead Code.
* Unused Import.

---

### 12. Production Readiness Audit

Audit apakah aplikasi siap production.

Periksa:

* Error Handling.
* Logging.
* Queue.
* Notification.
* Export Process.
* Transaction.
* Seeder.
* Factory.
* Feature Test.
* Unit Test.
* Environment Configuration.

Berikan checklist production readiness.

---

## Format Output Audit

Jawaban HARUS menggunakan struktur berikut.

### Executive Summary

Berikan skor (0–10) untuk:

| Area                  | Score |
| --------------------- | ----- |
| Laravel MVC           |       |
| Folder Structure      |       |
| Controller            |       |
| Model                 |       |
| Service Layer         |       |
| Blade Template        |       |
| Alpine.js             |       |
| Tailwind CSS          |       |
| Eloquent Query        |       |
| N+1 Query Prevention  |       |
| Validation            |       |
| Security              |       |
| Performance           |       |
| Clean Code            |       |
| Production Readiness  |       |
| Overall Project Score |       |

---

### Critical Issues (High Priority)

Daftar semua masalah yang wajib diperbaiki sebelum production.

Untuk setiap masalah jelaskan:

* Lokasi file.
* Penyebab.
* Dampak.
* Cara refactor sesuai Laravel Best Practice.

---

### Medium Priority Refactor

Daftar perbaikan yang meningkatkan maintainability.

---

### Low Priority Improvement

Daftar improvement kosmetik dan developer experience.

---

### N+1 Query Report

Tampilkan tabel:

| File | Query Bermasalah | Penyebab | Solusi Eager Loading |

Audit seluruh relasi Eloquent.

---

### MVC Refactor Report

Tampilkan tabel:

| File | Masalah | Pindahkan Ke | Alasan |

Misalnya Controller → Service, Blade → Component, FormRequest → Rule, dll.

---

### Folder Refactor Plan

Berikan struktur folder Laravel yang direkomendasikan setelah refactor.

Gunakan format tree.

---

### Business Logic Simplification Report (Anti-Overengineering)

Jika aplikasi menerapkan business logic, kalkulasi, atau alur data yang terlalu ribet dan berbelit-belit padahal ada pendekatan yang jauh lebih sederhana, beri tahu secara gamblang dan tampilkan tabel evaluasi:

| Modul / Fitur | Implementasi Saat Ini (Terlalu Ribet) | Alternatif Solusi Sederhana & Simpel | Alasan & Manfaat (Pragmatisme) |

---

### Performance Optimization Checklist

Berikan checklist Markdown yang bisa langsung diterapkan sebelum deployment.

Contoh:

* [ ] Semua query menggunakan eager loading bila diperlukan.
* [ ] Tidak ada query di Blade.
* [ ] Semua pagination menggunakan paginate().
* [ ] Semua proses penting menggunakan DB Transaction.
* [ ] Semua export menggunakan Queue.
* [ ] Route cache siap dijalankan.
* [ ] Config cache siap dijalankan.
* [ ] Tidak ada N+1 Query tersisa.

---

## Aturan Audit

* Jangan mengubah fungsionalitas aplikasi.
* Jangan melakukan rewrite total.
* Pertahankan konsep **Laravel MVC + Service Layer**.
* Ikuti best practice Laravel 13.
* Gunakan prinsip SOLID jika relevan.
* **Proporsional & Anti Over-Engineering:** Sadari penuh bahwa aplikasi ini adalah **aplikasi survei sederhana dan belum masuk level enterprise**. Hindari menyarankan pola desain yang berlebihan (seperti DTO/Repository pattern berlapis, microservices, instalasi library besar yang tidak esensial, atau struktur folder bertingkat-tingkat yang membingungkan).
* **Prinsip Simplicity & Pragmatisme (KISS):** Jika aplikasi menerapkan business logic atau struktur yang terlalu ribet/berbelit-belit, **wajib beri tahu** dan berikan alternatif yang jauh lebih sederhana, to-the-point, dan sesuai dengan kebutuhan riil aplikasi survei tanpa mengorbankan performa.
* Prioritaskan maintainability, readability, performance, security, dan production readiness.
* Jika menemukan kode yang terlalu panjang, jelaskan cara memecahnya menjadi Service, Blade Component, Partial View, atau Helper tanpa mengubah perilaku aplikasi.
