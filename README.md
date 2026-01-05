# 📘 Sistem Monitoring Risiko Stunting Posyandu (SMRSP) Bougenville

Repositori ini berisi dokumentasi pengembangan **Sistem Monitoring Risiko Stunting Posyandu (SMRSP) Bougenville**.  

---

## 🧭 Daftar Isi

- [Deskripsi Singkat Sistem](#deskripsi-singkat-sistem)
- [Struktur Folder Desain Sistem](#struktur-folder)
- [Desain Sistem](#diagram-uml)
  - [Use Case Diagram](#use-case-diagram)
  - [Class Diagram](#class-diagram)
  - [Dependency Injection](#dependency-injection)
  - [Factory Method](#factory-method)
  - [Builder](#builder)
  - [Singleton](#singleton)
  - [Sequence Diagram](#sequence-diagram)
  - [Activity Diagram](#activity-diagram)
  - [State Machine Diagram](#state-machine-diagram)
- [Cara Instalasi](#cara-instalasi)


---

## 📝 Deskripsi Singkat Sistem

**Sistem Monitoring Risiko Stunting Posyandu** adalah sistem yang digunakan untuk **Memprediksi dan memantau risiko stunting pada balita di Posyandu Bougenville desa Margasari Kecamatan Tigaraksa Kabupaten Tangerang**. Klik link dibawah untuk selengkapnya.

[Dokumen Laporan Penelitian](https://docs.google.com/document/d/1ZoFwWu4EAAuzPiZqyTBVVw9NBTRAz3HE/edit?usp=sharing&ouid=100629304192679567901&rtpof=true&sd=true)

Fitur utama sistem antara lain:

- ✅ Fitur 1 – *Kelola Data Balita*
- ✅ Fitur 2 – *Pengukuran Antopometri*
- ✅ Fitur 3 – *Klasifikasi dan Predisi Stunting*
- ✅ Fitur 4 – *Laporan Kegiatan*
- ✅ Fitur 5 – *Dashboard Monitoring*
- ✅ Fitur 6 – *Kelola User*


---

## 📂 Struktur Folder Desain Sistem

Folder ini berisikan diagram dan gambar desain sistem diantaranya:

```bash
.
├── README.md
├── docs/
│   ├── usecase-diagram.png
│   ├── class-diagram.png
│   ├── factory-method.png
│   ├── builder.png
│   ├── singelton.png
│   ├── dependency-injection.png
│   ├── activity-diagram.png
│   ├── state-machine-diagram.png
│   └── SMRSP.drawio
```
---

## 📊 Desain Sistem

### Use Case Diagram

<p align="center">
  <img src="docs/use-case-diagram.png" alt="Use Case Diagram" width="70%">
</p>

### Class Diagram

<p align="center">
  <img src="docs/class-diagram.jpg" alt="Use Case Diagram" width="70%">
</p>

### Factory Method

<p align="center">
  <img src="docs/factory-method.jpg" alt="Use Case Diagram" width="50%">
</p>

### Builder

<p align="center">
  <img src="docs/builder.jpg" alt="Use Case Diagram" width="70%">
</p>

### Singleton

<p align="center">
  <img src="docs/singleton.png" alt="Use Case Diagram" width="40%">
</p>

### Sequence Diagram

<p align="center">
  <img src="docs/sequence-diagram.png" alt="Use Case Diagram" width="70%">
</p>

### Dependency Injection

<p align="center">
  <img src="docs/dependency-injection.png" alt="Use Case Diagram" width="90%">
</p>

### Activity Diagram

<p align="center">
  <img src="docs/activity-diagram.png" alt="Use Case Diagram" width="50%">
</p>

### State Machine Diagram

<p align="center">
  <img src="docs/state-machine-diagram.png" alt="Use Case Diagram" width="80%">
</p>

---

## ⬇️ Cara Instalasi

```
git clone https://github.com/RamziAhmar/SMRSP-Margasari.git
```
```
cd SMRSP-Margasari
```
```
composer install
```
```
npm install
```
```
php artisan migrate
```
```
php artisan generate:key
```
```
npm run dev
```
buka terminal baru dan jalankan 
```
php artisan serve
```
Server akan berkalan di localhost:9000
