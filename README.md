# Sistem Pembayaran SPP Sekolah

Sistem manajemen pembayaran SPP berbasis web menggunakan PHP Native, MySQL, dan vanilla JavaScript.

## 🎯 Fitur Utama

### Role: Admin
- ✅ Manajemen data siswa (CRUD)
- ✅ Manajemen data petugas
- ✅ Manajemen data kelas
- ✅ Manajemen tarif SPP
- ✅ Laporan pembayaran dengan visualisasi chart
- ✅ Export laporan ke CSV
- ✅ Audit log semua aktivitas

### Role: Petugas
- ✅ Input pembayaran SPP dengan autocomplete siswa
- ✅ Duplicate payment protection (bulan + tahun)
- ✅ Cetak bukti pembayaran dengan QR code
- ✅ Riwayat pembayaran

### Role: Siswa
- ✅ Lihat profile
- ✅ Riwayat pembayaran SPP
- ✅ Total pembayaran

## 🛠️ Teknologi

- **Backend**: PHP 7.4+ (Native, PDO)
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Security**: Password hashing, CSRF protection, prepared statements, session management

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Web server (Apache/Nginx) atau PHP built-in server
- Browser modern (Chrome, Firefox, Edge)

## 🚀 Instalasi & Setup

### Metode 1: Quick Start (Recommended)

1. **Clone/Extract project**
   ```bash
   cd spp-native
   ```

2. **Konfigurasi database**
   - Copy `.env.example` ke `.env`
   - Edit `.env` sesuai konfigurasi MySQL Anda:
     ```
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=spp_sekolah
     DB_USER=root
     DB_PASS=
     ```

3. **Import database**
   ```bash
   php seed.php
   ```
   
   Jika gagal, import manual:
   - **XAMPP**: Buka phpMyAdmin → Import → Pilih `database/spp.sql`
   - **Command Line**: 
     ```bash
     mysql -u root < database/spp.sql
     ```

4. **Jalankan aplikasi**
   ```bash
   sh run-local.sh
   ```
   Atau manual:
   ```bash
   php -S localhost:8000 -t public
   ```

5. **Akses aplikasi**
   - URL: http://localhost:8000
   - Login dengan credentials di bawah

### Metode 2: XAMPP/Laragon

1. Copy folder `spp-native` ke `htdocs` (XAMPP) atau `www` (Laragon)
2. Buat database `spp_sekolah` di phpMyAdmin
3. Import file `database/spp.sql`
4. Edit `.env` sesuai konfigurasi
5. Akses: http://localhost/spp-native/public

## 🔐 Default Credentials

| Role    | Username | Password      |
|---------|----------|---------------|
| Admin   | admin    | Admin123!     |
| Petugas | petugas  | Petugas123!   |
| Siswa   | siswa1   | Siswa123!     |

**⚠️ PENTING**: Ganti password default setelah login pertama!

## 📁 Struktur Project

```
spp-native/
├── app/
│   ├── controllers/          # Business logic controllers
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── AdminController.php
│   │   ├── PembayaranController.php
│   │   ├── SiswaController.php
│   │   ├── ReportController.php
│   │   └── SearchController.php
│   ├── models/               # Database models
│   │   ├── DB.php           # Database connection (singleton)
│   │   ├── User.php
│   │   ├── Siswa.php
│   │   ├── Petugas.php
│   │   ├── Kelas.php
│   │   ├── Spp.php
│   │   ├── Pembayaran.php
│   │   └── AuditLog.php
│   └── views/                # View templates
│       ├── layouts/          # Header, footer, sidebar
│       ├── admin/            # Admin views
│       ├── petugas/          # Petugas views
│       └── siswa/            # Siswa views
├── public/                   # Web root
│   ├── index.php            # Entry point
│   ├── login.php
│   ├── logout.php
│   └── assets/
│       ├── css/style.css    # Main stylesheet
│       ├── js/app.js        # JavaScript functionality
│       └── images/
├── database/
│   └── spp.sql              # Database schema + seeds
├── wireframes/              # Design mockups
│   ├── ascii-wireframes.txt
│   ├── dashboard_mock.html
│   ├── payment_form_mock.html
│   └── svg_wireframes.svg
├── tests/
│   └── basic_tests.php      # Basic functionality tests
├── .env                     # Environment configuration
├── .env.example
├── seed.php                 # Database seeder
├── run-local.sh             # Quick start script
├── design-tokens.json       # Design system tokens
└── README.md
```

## 🔒 Fitur Keamanan

1. **Authentication & Authorization**
   - Session-based authentication
   - Role-based access control (RBAC)
   - Session timeout (default: 30 menit)
   - Password hashing dengan `password_hash()`

2. **Input Validation**
   - Client-side validation (JavaScript)
   - Server-side validation (PHP)
   - Prepared statements untuk semua query
   - CSRF token protection

3. **Database Security**
   - Stored procedures untuk transaksi kritis
   - Duplicate payment protection (UNIQUE constraint)
   - Audit log untuk tracking aktivitas
   - Foreign key constraints

4. **Session Security**
   - `session_regenerate_id()` setelah login
   - HttpOnly cookies
   - Session timeout check

## 📊 Database Schema

### Tables
- `users` - User accounts (admin, petugas, siswa)
- `siswa` - Student data
- `petugas` - Staff data
- `kelas` - Class data
- `spp` - SPP tariff data
- `pembayaran` - Payment transactions
- `audit_log` - Activity audit trail

### Stored Procedures
- `sp_tambah_pembayaran()` - Add payment with duplicate check
- `sp_get_history_siswa()` - Get student payment history

### Triggers
- `after_insert_pembayaran` - Auto-log payment insertion

## 🧪 Testing

### Manual Testing

1. **Login Flow**
   - Test dengan semua role (admin, petugas, siswa)
   - Test invalid credentials
   - Test session timeout

2. **Payment Flow (Petugas)**
   - Search siswa via autocomplete
   - Input pembayaran valid
   - Test duplicate payment (harus ditolak)
   - Cetak bukti pembayaran

3. **Admin Functions**
   - CRUD siswa
   - View laporan
   - Export CSV

4. **Siswa Functions**
   - View profile
   - View payment history

### Automated Tests

```bash
php tests/basic_tests.php
```

Test coverage:
- ✅ Database connection
- ✅ User authentication
- ✅ Duplicate payment prevention
- ✅ Stored procedure execution

## 🎨 Design System

Design tokens tersedia di `design-tokens.json`:

- **Colors**: Primary (#1E3A8A), Accent (#10B981), CTA (#F59E0B)
- **Typography**: Inter font family, responsive sizes
- **Spacing**: 4px base unit (xs, sm, md, lg, xl)
- **Border Radius**: sm (6px), md (12px), lg (20px)

Wireframes tersedia di folder `wireframes/`:
- ASCII wireframes (text-based)
- HTML mockups (interactive)
- SVG wireframes (visual)

## 🌐 Browser Support

- Chrome 90+
- Firefox 88+
- Edge 90+
- Safari 14+

## 📱 Responsive Design

- Mobile-first approach
- Breakpoints: 640px, 768px, 1024px, 1280px
- Sidebar collapses on mobile
- Touch-friendly buttons

## 🔧 Troubleshooting

### Database Connection Error
```
Solution: Check .env file, ensure MySQL is running
```

### Session Timeout Too Fast
```
Solution: Edit SESSION_TIMEOUT in .env (default: 1800 seconds)
```

### Autocomplete Not Working
```
Solution: Check browser console for errors, ensure SearchController.php is accessible
```

### Cannot Import Database
```
Solution: Use phpMyAdmin or run: mysql -u root < database/spp.sql
```

## 📝 Assumptions & Limitations

### Assumptions
1. **Default Ports**: Web server on 8000, MySQL on 3306
2. **Password Policy**: Minimum 8 characters (enforced in seed data)
3. **Payment Period**: Monthly payments, one payment per month per student
4. **Currency**: Indonesian Rupiah (IDR)
5. **Academic Year**: Follows calendar year (Jan-Dec)

### Limitations
1. **PDF Export**: Requires wkhtmltopdf or similar (fallback: printable HTML)
2. **Email Notifications**: Not implemented (can be added)
3. **Multi-language**: Indonesian only
4. **Bulk Operations**: Not implemented (add if needed)
5. **Advanced Reporting**: Basic charts only (can be enhanced)

### Future Enhancements
- Email notifications for payments
- SMS gateway integration
- Advanced analytics dashboard
- Bulk payment import
- Mobile app (PWA)
- Payment gateway integration

## 👥 User Roles & Permissions

| Feature | Admin | Petugas | Siswa |
|---------|-------|---------|-------|
| Dashboard | ✅ | ✅ | ✅ |
| Manage Siswa | ✅ | ❌ | ❌ |
| Manage Petugas | ✅ | ❌ | ❌ |
| Manage Kelas | ✅ | ❌ | ❌ |
| Manage SPP | ✅ | ❌ | ❌ |
| Input Payment | ❌ | ✅ | ❌ |
| View Reports | ✅ | ❌ | ❌ |
| Export Data | ✅ | ❌ | ❌ |
| View Profile | ✅ | ✅ | ✅ |
| View History | ✅ | ✅ | ✅ (own) |

## 📞 Support

Untuk pertanyaan atau issue:
1. Check README.md
2. Check wireframes untuk UX flow
3. Check database/spp.sql untuk schema
4. Check tests/basic_tests.php untuk examples

## 📄 License

Educational project - UKK RPL specification

## 🎓 Credits

Developed for UKK (Uji Kompetensi Keahlian) - Rekayasa Perangkat Lunak

---

**Version**: 1.0.0  
**Last Updated**: January 25, 2026  
**PHP Version**: 7.4+  
**Database**: MySQL 5.7+ / MariaDB 10.3+
