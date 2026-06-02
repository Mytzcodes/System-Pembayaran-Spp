<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px; display: inline; vertical-align: middle; margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile Siswa
            </h1>
        </div>
        
        <?php if ($currentSiswa): ?>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Informasi Pribadi</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>NISN</label>
                        <p><?= htmlspecialchars($currentSiswa['nisn']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>NIS</label>
                        <p><?= htmlspecialchars($currentSiswa['nis']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Nama Lengkap</label>
                        <p><?= htmlspecialchars($currentSiswa['nama']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Kelas</label>
                        <p><?= htmlspecialchars($currentSiswa['nama_kelas']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Kompetensi Keahlian</label>
                        <p><?= htmlspecialchars($currentSiswa['kompetensi_keahlian']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>No. Telepon</label>
                        <p><?= htmlspecialchars($currentSiswa['no_telp']) ?></p>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <label>Alamat</label>
                        <p><?= htmlspecialchars($currentSiswa['alamat']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Tarif SPP</label>
                        <p style="font-size: 1.25rem; font-weight: 600; color: var(--primary);">Rp <?= number_format($currentSiswa['nominal']) ?> / bulan</p>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Data siswa tidak ditemukan. Silakan hubungi administrator.</span>
        </div>
        <?php endif; ?>
    </div>
</main>

<style>
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
}

.info-item label {
    display: block;
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-xs);
}

.info-item p {
    font-size: 1rem;
    color: var(--text);
}
</style>
