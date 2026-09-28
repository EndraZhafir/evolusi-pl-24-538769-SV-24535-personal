```markdown
# AGENT TASK SPECIFICATION: CI/CD FRONTEND VUE.JS & LARAVEL INTEGRATION

## 1. System & Environment Context
* **Proyek**: Tugas Praktikum KEPL Pertemuan 04 - CI/CD untuk Vue.js[cite: 1].
* **Mahasiswa**: Endra Zhafir (NIM: 24/538769/SV/24535).
* **Workspace Lokal**: `D:\College\5th Semester\KEPL\Pertemuan 02\App\evolusi-pl-24-538769-SV-24535`.
* **Arsitektur Remote Git (Dual Remote)**:
  * `origin`: `https://github.com/KEPL2026/evolusi-pl-24-538769-SV-24535` (Organization, kuota CI habis).
  * `personal`: `https://github.com/EndraZhafir/evolusi-pl-24-538769-SV-24535-personal` (Repo personal aktif untuk GitHub Actions).
* **Aturan Eksekusi Git**: Seluruh pengujian pipeline, Pull Request, dan commit uji coba dijalankan pada remote `personal`. Setelah pipeline tervalidasi dan di-merge ke branch `main`, hasil akhir di-push ke remote `origin`.

---

## 2. Definisi Kebutuhan & Deliverables
1. **Backend Laravel**: Sediakan endpoint JSON `GET /api/tugas` dan izinkan CORS dari frontend `http://localhost:5173`[cite: 1].
2. **Frontend Vue 3**:
   * Direktori: `frontend/`[cite: 1].
   * Minimal dua halaman ber-router (`HomeView.vue` dan `TugasView.vue`)[cite: 1].
   * URL backend diambil secara dinamis via `import.meta.env.VITE_API_URL`[cite: 1].
   * Menyediakan fallback UI / error handling jika backend offline[cite: 1].
3. **Pengujian Unit (Vitest)**:
   * Minimal 1 unit test logika aplikasi[cite: 1].
   * Test harus 100% mandiri dan lolos di runner CI tanpa server backend Laravel aktif[cite: 1].
4. **Pipeline CI/CD GitHub Actions (`.github/workflows/frontend-ci.yml`)**:
   * Rangkaian 4 job berantai menggunakan `needs:`: `lint` -> `test` -> `build` -> `deploy`[cite: 1].
   * Menggunakan `npm ci` dan `cache: 'npm'` (dengan `cache-dependency-path: frontend/package-lock.json`)[cite: 1].
   * Job `build` mengunggah artefak folder `dist/`[cite: 1].
   * Job `deploy` mengunduh artefak `dist/` dan menampilkan isinya ke log (`ls -la dist/`) tanpa build ulang[cite: 1].
   * Job `deploy` hanya berjalan jika target adalah branch `main` (`if: github.ref == 'refs/heads/main'`)[cite: 1]. Pada Pull Request, job deploy berstatus *skipped*[cite: 1].
5. **Bukti Validasi**:
   * PR dengan pipeline hijau namun job deploy *skipped*[cite: 1].
   * Simulasi unit test gagal yang menyebabkan pipeline merah, diikuti perbaikan[cite: 1].
   * Log job deploy yang menampilkan file di `dist/`[cite: 1].
   * Screenshot data Laravel tampil di browser[cite: 1].
6. **Laporan Akhir**: Dokumen PDF `P4_NIM_Nama.pdf`[cite: 1].

---

## 3. Rencana Eksekusi Kode (File by File)

### Fase A: Konfigurasi Backend Laravel
- **File**: `routes/api.php`
  * Buat endpoint `GET /api/tugas` yang mengembalikan response JSON[cite: 1]:
    ```json
    {
      "success": true,
      "message": "Daftar tugas berhasil diambil",
      "data": [
        {"id": 1, "judul": "Setup CI/CD Pipeline", "status": "Selesai"},
        {"id": 2, "judul": "Integrasi Vue 3 ke Laravel", "status": "Proses"},
        {"id": 3, "judul": "Menulis Unit Test Vitest", "status": "Tertunda"}
      ]
    }
    ```
- **File**: `config/cors.php`
  * Izinkan `http://localhost:5173` dan `http://127.0.0.1:5173` pada konfigurasi `allowed_origins`.

---

### Fase B: Setup Frontend Vue 3
- **Lokasi**: Folder `frontend/`[cite: 1].
- **File**: `frontend/.env`
  ```ini
  VITE_API_URL=[http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)

```

* **File**: `frontend/.env.example`
```ini
VITE_API_URL=[http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)

```


* **File**: `frontend/src/router/index.js`
* Konfigurasi dua route: `/` (`HomeView.vue`) dan `/tugas` (`TugasView.vue`).




* **File**: `frontend/src/views/HomeView.vue`
* Tampilan beranda dan tombol navigasi router menuju `/tugas`.


* **File**: `frontend/src/views/TugasView.vue`
* Menggunakan `fetch(`${import.meta.env.VITE_API_URL}/tugas`)`.


* Menyertakan state reaktif: `daftarTugas`, `loading`, dan `error` penanganan koneksi Laravel mati.




* **File**: `.gitignore` (Root dan/atau `frontend/.gitignore`)
* Pastikan mengabaikan `node_modules/`, `dist/`, dan `.env`.


* Pastikan `frontend/package-lock.json` tetap ter-track oleh Git.





---

### Fase C: Unit Testing Vitest

* **File**: `frontend/src/utils/taskHelper.js`
```javascript
export function hitungTugasSelesai(tasks) {
  if (!Array.isArray(tasks)) return 0
  return tasks.filter(task => task.status === 'Selesai').length
}

export function formatJudul(judul) {
  if (!judul) return '-'
  return judul.trim()
}

```


* **File**: `frontend/src/utils/__tests__/taskHelper.spec.js`
```javascript
import { describe, it, expect } from 'vitest'
import { hitungTugasSelesai, formatJudul } from '../taskHelper'

describe('Unit Test Logika Aplikasi Tugas', () => {
  it('berhasil menghitung jumlah tugas dengan status Selesai', () => {
    const dummyTasks = [
      { id: 1, judul: 'Task 1', status: 'Selesai' },
      { id: 2, judul: 'Task 2', status: 'Proses' },
      { id: 3, judul: 'Task 3', status: 'Selesai' }
    ]
    expect(hitungTugasSelesai(dummyTasks)).toBe(2)
  })

  it('mengembalikan 0 jika array tugas kosong', () => {
    expect(hitungTugasSelesai([])).toBe(0)
  })

  it('membersihkan spasi pada judul tugas', () => {
    expect(formatJudul('  Tugas Praktikum  ')).toBe('Tugas Praktikum')
  })
})

```



---

### Fase D: Workflow GitHub Actions

* **File**: `.github/workflows/frontend-ci.yml`

```yaml
name: Frontend CI/CD

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

defaults:
  run:
    working-directory: frontend

jobs:
  lint:
    name: Gerbang 1 - Lint
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
          cache-dependency-path: frontend/package-lock.json
      - run: npm ci
      - run: npm run lint

  test:
    name: Gerbang 2 - Test
    needs: lint
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
          cache-dependency-path: frontend/package-lock.json
      - run: npm ci
      - run: npm run test:unit

  build:
    name: Gerbang 3 - Build
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
          cache-dependency-path: frontend/package-lock.json
      - run: npm ci
      - run: npm run build
      - uses: actions/upload-artifact@v4
        with:
          name: frontend-dist
          path: frontend/dist/

  deploy:
    name: Gerbang 4 - Deploy
    needs: build
    if: github.ref == 'refs/heads/main'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
        with:
          name: frontend-dist
          path: frontend/dist
      - name: Tampilkan Isi dist ke Log
        run: |
          echo "Daftar artefak dist hasil build:"
          ls -la dist/
          echo "Deploy selesai tanpa build ulang."

```

---

## 4. Runbook Eksekusi Terminal & Alur Kerja Git

### Langkah 1: Pengaturan Remote Git

```bash
cd "D:\College\5th Semester\KEPL\Pertemuan 02\App\evolusi-pl-24-538769-SV-24535"
git remote add personal [https://github.com/EndraZhafir/evolusi-pl-24-538769-SV-24535-personal.git](https://github.com/EndraZhafir/evolusi-pl-24-538769-SV-24535-personal.git)
git fetch origin
git fetch personal
git checkout main

```

### Langkah 2: Buat Branch Fitur

```bash
git checkout -b feature/frontend-cicd

```

### Langkah 3: Skenario 1 - Pembuktian Pipeline Merah (Test Failed)

1. Modifikasi file `frontend/src/utils/__tests__/taskHelper.spec.js` pada baris asersi menjadi sengaja salah (`expect(...).toBe(999)`).
2. Commit dan push ke remote `personal`:
```bash
git add .
git commit -m "test: simulasi kegagalan pengujian unit pada CI"
git push -u personal feature/frontend-cicd

```


3. Buka GitHub personal, buat Pull Request ke branch `main`.
4. **Dokumentasikan**: Ambil screenshot pipeline GitHub Actions yang berhenti merah pada job `Gerbang 2 - Test`.



### Langkah 4: Skenario 2 - Pembuktian Deploy Skipped pada PR

1. Kembalikan asersi pada `taskHelper.spec.js` ke nilai valid (`toBe(2)`).
2. Commit dan push perbaikan:
```bash
git add frontend/src/utils/__tests__/taskHelper.spec.js
git commit -m "fix: perbaiki unit test agar valid dan lolos CI"
git push personal feature/frontend-cicd

```


3. Tunggu pipeline PR selesai:
* `lint` -> Success.


* `test` -> Success.


* `build` -> Success.


* `deploy` -> Skipped.




4. **Dokumentasikan**: Ambil screenshot daftar check PR yang menunjukkan status ketiga job hijau dan deploy *skipped*.



### Langkah 5: Skenario 3 - Merge ke Main & Pembuktian Deploy Log

1. Di halaman GitHub PR, klik **Merge pull request**.
2. Masuk ke menu **Actions** di repo personal pada commit merge `main`.
3. Pastikan 4 job (`lint`, `test`, `build`, `deploy`) berstatus hijau.


4. Buka detail log job `deploy` pada step `Tampilkan Isi dist ke Log`.
5. **Dokumentasikan**:
* Ambil screenshot grafik 4 job hijau.


* Ambil screenshot log yang memuat direktori `dist/` (`index.html`, folder `assets/`, dll.).





### Langkah 6: Skenario 4 - Verifikasi Antarmuka Browser

1. Jalankan backend: `php artisan serve` (Terminal 1).
2. Jalankan frontend: `npm run dev` di folder `frontend` (Terminal 2).


3. Akses `http://localhost:5173/tugas` di browser.
4. **Dokumentasikan**:
* Screenshot data dari Laravel tampil di Vue.


* Screenshot pesan peringatan error saat backend dihentikan.





### Langkah 7: Sinkronisasi ke Organization Repo

```bash
git checkout main
git pull personal main
git push origin main

```

---

## 5. Struktur Panduan Laporan (`P4_24538769SV24535_EndraZhafir.pdf`)

* **Cover**: Judul praktikum, Identitas (Endra Zhafir - 24/538769/SV/24535), URL kedua repository.


* **Bagian 1 - Endpoint Backend & CORS (20%)**: Potongan kode route Laravel, konfigurasi `config/cors.php`, dan screenshot data tampil di browser.


* **Bagian 2 - Arsitektur Frontend Vue 3 (20%)**: Penjelasan struktur 2 router view, konsumsi `VITE_API_URL`, serta fallback UI saat offline.


* **Bagian 3 - Unit Testing Vitest (15%)**: Penjelasan isolasi tes mandiri tanpa dependensi backend di runner CI.


* **Bagian 4 - Pipeline CI/CD 4 Gerbang (35%)**:
* Penjelasan ketergantungan `needs:` dan cache npm.


* Mekanisme passing artefak `upload-artifact` ke `download-artifact` tanpa build ulang.


* Kondisi pengaman deploy `if: github.ref == 'refs/heads/main'`.


* Bukti visual: Screenshot pipeline merah, screenshot deploy skipped di PR, screenshot 4 job sukses di main, dan log isi `dist/`.




* **Bagian 5 - Analisis Kendala & Solusi**: Strategi mitigasi limitasi kuota GitHub Actions organisasi via personal remote mirror.



```

```