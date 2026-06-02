<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Input Pembayaran SPP</h1>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" id="pembayaranForm">
                    <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                    <input type="hidden" name="action" value="create_pembayaran">
                    
                    <div class="form-group">
                        <label for="nisn_search" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px; display: inline; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Siswa (NISN/NIS/Nama)
                        </label>
                        <input type="text" id="nisn_search" class="form-input" placeholder="Ketik untuk mencari siswa..." autocomplete="off">
                        <div id="searchResults" class="autocomplete-results"></div>
                    </div>
                    
                    <div id="siswaInfo" style="display:none;" class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem;">Informasi Siswa</h4>
                            <p style="margin: 0.25rem 0;"><strong>NISN:</strong> <span id="info_nisn"></span></p>
                            <p style="margin: 0.25rem 0;"><strong>Nama:</strong> <span id="info_nama"></span></p>
                            <p style="margin: 0.25rem 0;"><strong>Kelas:</strong> <span id="info_kelas"></span></p>
                            <p style="margin: 0.25rem 0;"><strong>Tarif SPP:</strong> <span id="info_spp"></span></p>
                        </div>
                    </div>
                    
                    <input type="hidden" id="nisn" name="nisn" required>
                    <input type="hidden" id="id_spp" name="id_spp" required>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bulan_dibayar" class="form-label">Bulan *</label>
                            <select id="bulan_dibayar" name="bulan_dibayar" class="form-select" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tahun_dibayar" class="form-label">Tahun *</label>
                            <input type="number" id="tahun_dibayar" name="tahun_dibayar" class="form-input" value="<?= date('Y') ?>" required min="2020" max="2030">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah_bayar" class="form-label">Jumlah Bayar *</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-input" placeholder="Masukkan jumlah pembayaran" required min="0" step="1000">
                    </div>
                    
                    <div class="form-group">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Proses Pembayaran
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
